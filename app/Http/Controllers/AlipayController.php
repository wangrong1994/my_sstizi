<?php
namespace App\Http\Controllers;
//ini_set('display_errors',1);
//error_reporting(E_ALL);

use App\Http\Models\Goods;
use App\Http\Models\User;
use App\Http\Models\Level;
use Illuminate\Http\Request;
use App\Http\Models\Order;
use Response;
use Redirect;
use Cache;
use Log;
use DB;

class AlipayController extends Controller {
  /** URL：支付通讯 */
  const URL_PAY     = 'https://mapi.alipay.com/gateway.do?';
 // const URL_PAY     = ' https://openapi.alipaydev.com/gateway.do?';
   /** URL: 通知返回 */
  const URL_NOTIFY  = '/alipay/notify';
  /** URL: 通知返回 */
  const URL_RETURN = '/user/orderList';
  /** 对方给我们的合作伙伴编号 */
  const PARTNER_ID   = '';

  const ALIPAY_PUBLICKEY_PATH = '';

  const SELFPUBLICKEY = '';

  const SELF_PRIVATEKEY_PATH = '';

  /** 美元汇率 */
  const ALIPAY_USD_EXRATE = 6.9;

  /** 错误：回调给出的参数不正确 */
  const ERR_PARAMS_FAILED        = 201;

  protected static $config;

  /**
   * 构造函数
   */
  function __construct () {
     self::$config = $this->systemConfig();
  }

  protected  static function filter_sign_params (array &$params) {
    // 要过滤掉的字段列表
    $filter_keys = ['sign', 'sign_type'];
    foreach ($filter_keys as $target) {
      if (array_key_exists($target, $params)) {
        unset ($params[$target]);
      }
    }
    return true;
  }

  public function generate_sign_string (array &$params) {
    $args = "";
    foreach ($params as $key => $value) {
      // 神经病支付宝两种签名的格式不一样！
      $args .= $key.'='.$value.'&';
    }
    $args = substr ($args, 0, -1);
    return $args;
  }

  protected function _rsa_sign (array $params){
    $sign = '';
    $args = $this->generate_sign_string($params);

    $priv_key = openssl_pkey_get_private(file_get_contents(self::SELF_PRIVATEKEY_PATH));
    if (openssl_sign ($args, $sign, $priv_key)) {
      $sign = urlencode (base64_encode ($sign));
    }
    openssl_free_key ($priv_key);
    return $sign;
  }

  protected function add_rsa_sign (array &$params) {
    // 先过滤掉数组的相关键
    self::filter_sign_params ($params);
    $params['sign'] = $this->_rsa_sign($params);
    // 附加上签名类型
    $params['sign_type'] = 'RSA';
    return true;
  }

  //生成订单及支付url
  public function createOrder (Request $request) {
    $goods_id = intval($request->get('goods_id'));
    $user = $request->session()->get('user');
    $goods_method = $request->get('goods_method');//1月付/2年付

    if (($goods_method != 1 && $goods_method != 2)) {
      return Response::json(['status' => 'fail', 'data' => '', 'message' => '购买失败,请稍后再试']);
    }
    $user = User::query()->where('id', $user['id'])->first();
    // 商品信息
    $goods = Goods::query()->where('id', $goods_id)->where('status', 1)->first();

    if (!$goods) {
      //TODO:购买商品页需要做判断，出现异常时挂掉
      return Response::json(['status' => 'fail', 'data' => '', 'message' => '购买失败：服务不存在']);
    }

    //获取当前有效订单
    $order = Order::query()->where('user_id', $user->id)->where('is_expire', 0)->where('status', 2)->orderBy('oid', 'desc')->first();

    if (!empty($order)) {
      //获取有效订单的商品等级
      $order_goods = Goods::query()->where('id', $order->goods_id)->first();
      if ($order_goods->level < $goods->level) {
        return Response::json(['status' => 'fail', 'data' => '', 'message' => '购买失败：您当前购买的套餐还未到期，不可购买其它套餐！']);
      }
    }

    /*$user->levelName = Level::query()->where('level', $user['level'])->first()['level_name'];
    if ($user->level > $goods->level) {
      return Response::json(['status' => 'fail', 'data' => '', 'message' => '购买失败：您当前等级为'.$user->levelName.',不可购买低于该的等级的套餐！']);
    }

    if ($user->user_type > $goods_method) {
      return Response::json(['status' => 'fail', 'data' => '', 'message' => '购买失败：您已属于包年用户，不可购买包月套餐！']);
    }*/

    $rmb_fee = 0;
    if ($goods_method == 1) {
      $subject = sprintf("SSTIZI Network Service %d %s", 1, 'month');
      $rmb_fee = round(($goods->mon_price/100),2);
      $expire_at = date("Y-m-d H:i:s", strtotime("+" . $goods->days . " days"));
    }
    else if ($goods_method == 2) {
      $subject = sprintf("SSTIZI Network Service %d %s", 1, 'year');
      $rmb_fee = round(($goods->year_price)/100*12, 2);
      $expire_at = date("Y-m-d H:i:s", strtotime("+" . $goods->days*12 . " days"));
    }

    if ($user->id==1) {
     $rmb_fee = 0.04;
   }

    DB::beginTransaction();
    try {
      // 生成订单
      $order = new Order();
      $order->orderId = date('ymdHis') . mt_rand(100000, 999999).$user->id;
      $order->user_id = $user->id;
      $order->goods_id = $goods->id;
      $order->totalPrice = $rmb_fee*100;
      $order->expire_at = $expire_at;
      $order->is_expire = 0;
      $order->pay_way = 1; // 支付方式
      $order->status = 0;
      $order->goods_method = $goods_method;
      $order->created_at = date('Y-m-d H:i:s');
      $order->save();

      DB::commit();
      //生成请求参数
      $params = [
        'service' => 'create_forex_trade',
        'partner' => self::PARTNER_ID,
        '_input_charset' => 'UTF-8',
        'out_trade_no' => $order->orderId,
        'subject' => $subject,
        'seller_id' => self::PARTNER_ID,
        'rmb_fee' => $rmb_fee,
        'currency' => 'USD',
        'notify_url' => self::$config['alipay_notify_url'].self::URL_NOTIFY,
        'return_url' => self::$config['website_url'].self::URL_RETURN,
        'product_code' => 'NEW_OVERSEAS_SELLER',
        'supplier' => 'sstizi'
      ];

      $url = "";
      ksort ($params);
      reset ($params);
      foreach ($params as $key => $value) {
        $url .= $key.'='.urlencode($value).'&';
      }
      // 加密的 pre_sign 不应该包含多余字符
      $sign = $this->_rsa_sign($params);
      $url .= 'sign='.$sign.'&sign_type=RSA';
      $url = self::URL_PAY.$url;
    }
    catch (\Exception $e) {
      DB::rollBack();

      Log::error('购买失败：' . $e->getMessage());

      return Response::json(['status' => 'fail', 'data' => '', 'message' => '支付失败：' . $e->getMessage()]);
    }
    //echo $url;die;
    $array = array('status'=>0,'url'=>$url);
    return json_encode($array);
    //return Response::view('use/addOrder', $url);
   // return redirect()->to($url); //直接重定向到支付
  }

  /**
   * @param $notify_id
   * @return bool
   */
  protected function check_notify_id ($notify_id) {
    $params = [
      'service'   => 'notify_verify',
      'partner'   => self::PARTNER_ID,
      'notify_id' => $notify_id,
    ];

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($curl, CURLOPT_URL, static::URL_PAY);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
    $result = curl_exec($curl);

    if (curl_getinfo($curl, CURLINFO_HTTP_CODE) != 200) {
      Log::error("Http code:" . curl_getinfo($curl, CURLINFO_HTTP_CODE) . " details:" . $result . "\r\n");
      return false;
    }
    // 如果返回的不是true 那么就是认证失败
    return true;
  }

  //异步回调
  public function notify()
  {
    // 判断请求是否为空
    if (isset ($_REQUEST['out_trade_no'])) {

      $billid = $_REQUEST['out_trade_no'];
    }else {
      echo 'fail';
      exit;
    }
    $bill = Order::query()->where('orderId', $billid)->first(); 
    if (!$bill) {
      Log::error  ('The bill is null: '.$billid);
      echo 'fail';
      exit;
    }
    if ($bill->status == 2) {
       Log::error  ('The bill is already confirm: '.$billid);
       echo 'fail';
       exit;
    }
    $params = $_REQUEST;
    Log::info('alipay notify prams:'.json_encode($params));
    if (!isset($params['sign_type'])
      || $params['sign_type'] !== 'RSA'
      || !isset($params['sign'])
    ) {
      //缺少签名参数，或者签名参数不对直接返回false;
      Log::error ('Miss params[sign_type] or [sign] or sign type is not RSA');
      //将订单设置为已支付待确认
      Order::query()->where('oid', $bill->oid)->update(['status' => 1]);      
      echo 'fail';
      exit;
    }
    $sign = $params['sign'];
    $this->filter_sign_params($params);
    ksort ($params);
    reset ($params);
    $args = $this->generate_sign_string($params, true);
    $pub_key = openssl_pkey_get_public(file_get_contents(self::ALIPAY_PUBLICKEY_PATH));
    $result = openssl_verify ($args, base64_decode ($sign), $pub_key) > 0 ? true : false;
    openssl_free_key ($pub_key);
    if (!$result) {
      Log::error ('Verify sign error');
      echo 'fail';
      exit;
    }
    if (!isset($params['notify_id'])) {
      Log::error ('notify_id is unset');
      echo 'fail';
      exit;
    }
    if (!$this->check_notify_id($params['notify_id'])) {
      Log::error ('Check notify_id Fail');
      echo 'fail';
      exit;
    }

    //确认订单
    if ((strcasecmp ($params['trade_status'], 'TRADE_FINISHED') == 0)
      || (strcasecmp ($params['trade_status'], 'TRADE_SUCCESS') == 0)) {
       // 检查货币是否是USD
      if (strcasecmp ($params['currency'], 'USD') !== 0) {
        Log::error ('Payment Currency wrong,[USD] need, given:'.$params['currency']);
        echo 'fail';
        exit;
      }
      // 因为我们没有办法确定美元到人民币的准确汇率，所以只能算一个合理的区间来估算对账，美元汇率通过外部配置设定
      if ($bill->user_id != 1) { 
      $max = $params['total_fee']*100 * self::ALIPAY_USD_EXRATE *1.2;
      $min= $params['total_fee']*100 * self::ALIPAY_USD_EXRATE *0.8;
      if ($bill->totalPrice > $max || $bill->totalPrice < $min) {
        Log::error ('payment USD(ExRate:'.self::ALIPAY_USD_EXRATE.'), change to RMB value ['.$min.','.$max.'] not cover the service value :'.$bill->totalPrice);
        echo 'fail';
        exit;
      }
}
      // 金额落在合理区间内，则直接用我们数据库中的价格进行账单确认

      DB::beginTransaction();
      try {
        $goods = Goods::query()->where('id', $bill->goods_id)->first();

        // 取出用户最后购买的有效套餐
        $order = Order::query()->where('user_id', $bill->user_id)->where('is_expire', 0)->where('status', 2)->orderBy('oid', 'desc')->first();
        if (!empty($order)) {
          Log::info('续费开始');
          //说明是续费,过期时间定为上个商品的过期日期加对应服务包日期，且不更新重置日
          if ($bill->goods_method == 1) {
            $expire_at = date("Y-m-d H:i:s", strtotime($order->expire_at ." + 1 month"));
          }
          else if ($bill->goods_method == 2) {
            $expire_at = date("Y-m-d H:i:s", strtotime($order->expire_at ." + 12 month"));
          }
          //将之前订单设置为已过期
          Order::query()->where('oid', $order->oid)->update(['is_expire' => 1]);
          //将之前订单的信息存储到当前订单上
          Order::query()->where('oid', $bill->oid)->update(['expire_at'=> $expire_at ,'order_type' => 1]);
          //更新用户续费到期时间
          User::query()->where('id', $bill->user_id)->update(['expire_time' => $expire_at]);
        }
        else {
          Log::info('新的购买处理开始');
          //新的购买
          // 如果买的是套餐，则先将之前购买的所有套餐置都无效，并扣掉之前所有套餐的流量
          if ($goods->type == 2) {
            Log::info('扣掉之前套餐流量');
            $existOrderList = Order::query()->with('goods')->whereHas('goods', function ($q) {
              $q->where('type', 2);
            })->where('user_id', $bill->user_id)->where('oid', '<>', $bill->oid)->where('is_expire', 0)->get();
            foreach ($existOrderList as $vo) {
              Order::query()->where('oid', $vo->oid)->update(['is_expire' => 1]);
              User::query()->where('id', $vo->user_id)->update(['transfer_enable' => 0]);
            }
          }

          // 把商品的流量加到账号上
          User::query()->where('id', $bill->user_id)->increment('transfer_enable', $goods->traffic * 1048576);

          // 套餐就改流量重置日，加油包不改
          if ($goods->type == 2) {
            Log::info('确认订单开始');
            // 给用户分配端口
            //修改为获取相邻ID用户的端口差>1的一个ID最小用户，然后在他的ID上+1即新的分配端口
            $user = User::query()->where('id',$bill->user_id)->first();
            // 给过期用户分配端口
            if ($user->level == -1) {
              Log::info('给过期用户分配端口');
              //增加查找用户已存在的端口是否被占用（需要修改定时任务AutoDisableExpireUserJob.php，此时port和passwd不再update为0）
              $take_up_user = User::query()->where('port', $user->port)->where('enable', 1)->first();
              //若被占用则重新获取，获取最小端口差>1的一个ID最小用户，然后在他的ID上+1即新的分配端口
              if ($take_up_user) {
                Log::info('原端口被占用则重新获取');
                $last_user = DB::select('select a.id,a.port from (select @rowno:=@rowno+1 rowno,r.* from user r,(select @rowno:=0) t order by r.port) a,(select @rowno_1:=@rowno_1+1 rowno,r.* from user r,(select @rowno_1:=0) t order by r.port) b where a.rowno=b.rowno-1 and b.port-a.port>1 and a.port !=0 and a.enable=1 limit 1
                         ');
                if ($last_user) {
                  foreach ($last_user as $port_user)  {
                    $port = $port_user->port + 1;
                    Log::info('新的端口：'.$port);
                  }
                }
                else {
                  // 最后一个可用端口
                  $last_user2 = User::query()->orderBy('id', 'desc')->first();
                  $port = self::$config['is_rand_port'] ? $this->getRandPort() : $last_user2->port + 1;
                }
                $passwd = $this->makeRandStr();
                $res = User::query()->where('id', $user->id)
                    ->update(['port' => $port, 'passwd' => $passwd]);
                Log::info('重新获取端口结果：'.($res? 'true':'false'));
              }
            }
            // 将商品的有效期和流量自动重置日期加到账号上
            //$traffic_reset_day = in_array(date('d'), [29, 30, 31]) ? 28 : abs(date('d'));
            $traffic_reset_day = abs(date("d",strtotime($bill->created_at)));
            $res = User::query()->where('id', $bill->user_id)
              ->update(['traffic_reset_day' => $traffic_reset_day,  'enable' => 1, 'level' => $goods->level, 'user_type' => $bill->goods_method]);
            Log::info('将商品的有效期和流量自动重置日期加到账号上'.($res? 'true':'false'));
          }
          else {
            // 加油包只改变可连接状态，当月可用，不增加过期时间，不修改重置日
            User::query()->where('id', $bill->user_id)->update(['enable' => 1]);
          }
          if ($bill->goods_method == 1) {
            $expire_at = date("Y-m-d H:i:s", strtotime("+" . $goods->days . " days"));
          }
          else if ($bill->goods_method == 2) {
            $expire_at = date("Y-m-d H:i:s", strtotime("+" . $goods->days*12 . " days"));
          }
          User::query()->where('id', $bill->user_id)->update(['expire_time'=>$expire_at]);
        }

         //将订单设置为已支付
        Order::query()->where('oid', $bill->oid)->update(['status' => 2]);

        DB::commit();
        Log::info('Alipay notify post data verification success');
        echo 'success';
        exit;
      }
      catch (\Exception $e) {
        DB::rollBack();
        Log::error('Alipay notify post data verification fail：' . $e->getMessage());
        echo 'fail';
        exit;
      }    
    }
     Log::error ('ERROR');
    return 'fail';
    }

    /**
     * 显示支付信息,暂时不写
     */
    public function complete () {
      Log::info  ('callback-params: %s', json_encode($_POST));
      // 判断请求是否为空
      if (isset ($_REQUEST['out_trade_no'])) {
        $billid = $_REQUEST['out_trade_no'];
      }
      else {
        return Response::json(['status' => 'fail', 'data' => '', 'message' => '购买失败：未找到您的支付订单，请联系客服处理!']);
      }

      $bill = Order::query()->where('oid', $billid);
      if (is_null($bill)) {
        Log::error  ('The bill(%s) is null. ', $billid);
        return Response::json(['status' => 'fail', 'data' => '', 'message' => '购买失败：未找到您的购买订单，请联系客服处理!']);
      }
      $success_result = [
        'status' => 0,
        'msg' => '支付成功, 您的账号信息已变更，请使用shadowsocks客户端重新配置账号。'
      ];
      return json_encode($success_result);
    }
}
