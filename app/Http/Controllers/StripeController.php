<?php
namespace App\Http\Controllers;

use App\Http\Models\Goods;
use App\Http\Models\Order;
use Illuminate\Http\Request;
use App\Http\Models\User;
use App\Http\Models\Level;
use Response;
use Redirect;
use Cache;
use Log;
use DB;

/**
 * Stripe 信用卡支付
 * Class StripeController
 * @package App\Http\Controllers
 */
class StripeController extends Controller {

  /** PUBLISH_KEY */
  const PUBLISH_KEY = '';
 
  /** APIKey */
  const APIKEY = '';
  
  /** 货币类型*/
  const PAY_CURRENCY = 'CNY';

  /** 支付请求URL*/
  const CHARGE_URL = 'https://api.stripe.com/v1/charges';
  
   protected static $config;
  
  /**
   * 构造函数
   */
  function __construct () {
    self::$config = $this->systemConfig();
  }

  /**
   * 支付请求
   * @param Request $request
   * @return \Illuminate\Http\RedirectResponse|mixed
   */
  public function create (Request $request) {
    //设置请求参数
    $goods_id = $request->get('goods_id');
    $token = $request->get('token');
    $goods_method = $request->get('goods_method'); //1月付/2年付

    if (empty($token) || ($goods_method != 1 && $goods_method != 2)) {
      return Response::json(['status' => 'fail', 'data' => '', 'message' => '购买失败,请稍后再试']);
    }

    $token = trim(str_replace('stripeToken=','',$token));

    $user = $request->session()->get('user');

    $user = User::query()->where('id', $user['id'])->first();
    // 商品信息
    $goods = Goods::query()->where('id', $goods_id)->where('status', 1)->first();
    if (!$goods) {
      //TODO:购买商品页需要做判断，出现异常时挂掉
      return Response::json(['status' => 'fail', 'data' => '', 'message' => '购买失败：服务不存在']);
    }

    $order = Order::query()->where('user_id', $user->id)->where('is_expire', 0)->where('status', 2)->orderBy('oid', 'desc')->first();

    if (!empty($order)) {
      //获取有效订单的商品等级
      $order_goods = Goods::query()->where('id', $order->goods_id)->select('level');
      //获取当前购买的商品等级
     // $goods = Goods::query()->where('id', $goods_id)->select('level');
      if ($order_goods < $goods->level) {
        return Response::json(['status' => 'fail', 'data' => '', 'message' => '购买失败：您当前购买的套餐还未到期，不可购买其它套餐！']);
      }
    }
//var_dump($goods);die;
    $rmb_fee = 0;
    if ($goods_method == 1) {
      $rmb_fee = round(($goods->mon_price),2);
      $expire_at = date("Y-m-d H:i:s", strtotime("+" . $goods->days . " days"));
    }
    else if ($goods_method == 2) {
     $rmb_fee = round(($goods->year_price)*12, 2);
      $expire_at = date("Y-m-d H:i:s", strtotime("+" . $goods->days*12 . " days"));
    }

    DB::beginTransaction();
    try {

      // 生成订单
    $order = new Order();
      $order->orderId = date('ymdHis') . mt_rand(100000, 999999).$user->id;
      $order->user_id = $user->id;
      $order->goods_id = $goods->id;
      $order->totalPrice = $rmb_fee;
      $order->expire_at = $expire_at;
      $order->is_expire = 0;
      $order->pay_way = 2; // 支付方式
      $order->status = 0;
      $order->goods_method = $goods_method;
      $order->created_at = date('Y-m-d H:i:s');
      $order->save();

      if (!$order) {
        return Response::json(['status' => 'fail', 'data' => '', 'message' => '支付失败,请稍后再试']);
      }      

      //TODO:请求支付
      $charge_params = [
        'amount' => floatval($rmb_fee),
        'currency' => self::PAY_CURRENCY,
        'source' => $token,
        'description' => 'sstizi network service '.floatval($rmb_fee)
      ];

      $headers = [
        "Content-type: application/x-www-form-urlencoded",
        "Authorization: Bearer " . self::APIKEY,
      ];
      $curl = curl_init();
      curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($charge_params));
      curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
      curl_setopt($curl, CURLOPT_URL, static::CHARGE_URL);
      curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
      $response = curl_exec($curl);

      if (curl_getinfo($curl, CURLINFO_HTTP_CODE) != 200) {
        Log::error("Http code:" . curl_getinfo($curl, CURLINFO_HTTP_CODE) . " details:" . $response . "\r\n");
        return Response::json(['status' => 'fail', 'data' => '', 'message' => '信用卡支付失败,请稍后再试']);
      }
      $response_info = json_decode($response, true);
      if (!is_array($response_info) || !isset($response_info['status']) || strcasecmp($response_info['status'], 'succeeded') != 0) {
        Log::error("API return Unexcept result, details:" . $response);
        return Response::json(['status' => 'fail', 'data' => '', 'message' => '信用卡支付失败,请稍后再试']);
      }

      $last_order = Order::query()->where('user_id', $order->user_id)->where('is_expire', 0)->where('status', 2)->orderBy('oid', 'desc')->first();
      if (!empty($last_order)) {
        Log::info('续费开始');
        //说明是续费,过期时间定为上个商品的过期日期加对应服务包日期，且不更新重置日
        if ($order->goods_method == 1) {
          $expire_at = date("Y-m-d H:i:s", strtotime($last_order ->expire_at ." + 1 month"));
        }
        else if ($order->goods_method == 2) {
          $expire_at = date("Y-m-d H:i:s", strtotime($last_order ->expire_at ." + 12 month"));
        }
        //将之前订单设置为已过期
        Order::query()->where('oid', $last_order ->oid)->update(['is_expire' => 1]);
        //将之前订单的信息存储到当前订单上
        Order::query()->where('oid', $order->oid)->update(['expire_at'=> $expire_at ,'order_type' => 1]);
        //将用户过期时间修改
        User::query()->where('id', $user->id)->update(['expire_time' => $expire_at]);
      }
      else {
        Log::info('新的购买处理开始');
       // 如果买的是套餐，则先将之前购买的所有套餐置都无效，并扣掉之前所有套餐的流量
        if ($goods->type == 2) {
          Log::info('扣掉之前套餐流量');
          $existOrderList = Order::query()->with('goods')->whereHas('goods', function ($q) {
            $q->where('type', 2);
          })->where('user_id', $user->id)->where('oid', '<>', $order->oid)->where('is_expire', 0)->get();
          foreach ($existOrderList as $vo) {
            Order::query()->where('oid', $vo->oid)->update(['is_expire' => 1]);
            User::query()->where('id', $vo->user_id)->update(['transfer_enable' => 0]);
          }
         }

        // 把商品的流量加到账号上
        $res = User::query()->where('id', $user->id)->increment('transfer_enable', $goods->traffic * 1048576);
        Log::info('把商品的流量加到账号上'.$res ? 'true' : 'false');
        // 套餐就改流量重置日，加油包不改
        if ($goods->type == 2) {
          Log::info('确认订单开始');
           // 给用户分配端口
          //修改为获取相邻ID用户的端口差>1的一个ID最小用户，然后在他的ID上+1即新的分配端口
           // 给过期用户分配端口
          if ($user->level == -1) {
            Log::info('给过期用户分配端口');
            //增加查找用户已存在的端口是否被占用（需要修改定时任务AutoDisableExpireUserJob.php，此时port和passwd不再update为0）
            $take_up_user = User::query()->where('port', $user->port)->where('enable', 1)->first();
            //若被占用则重新获取，获取最小端口差>1的一个ID最小用户，然后在他的ID上+1即新的分配端口
            if ($take_up_user) {
              Log::info('原端口被占用则重新获取');
              $last_user = DB::select('select a.id,a.port from (select @rowno:=@rowno+1 rowno,r.* from user r,(select @rowno:=0) t order by r.port) a,(select @rowno_1:=@rowno_1+1 rowno,r.* from user r,(select @rowno_1:=0) t order by r.port) b where a.rowno=b.rowno-1 and b.port-a.port>1 and a.port!=0 and a.enable=1 limit 1
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
              $res = User::query()->where('id', $order->user_id)
                  ->update(['port' => $port, 'passwd' => $passwd]);
              Log::info('重新获取端口结果：'.($res? 'true':'false'));
            }
          }           
          // 将商品的有效期和流量自动重置日期加到账号上
          $traffic_reset_day = abs(date("d",strtotime($order->created_at)));
          $res = User::query()->where('id', $user->id)
            ->update(['traffic_reset_day' => $traffic_reset_day, 'expire_time' => $order->expire_at, 'enable' => 1, 'level' => $goods->level, 'user_type' => $order->goods_method]);
          Log::info('将商品的有效期和流量自动重置日期加到账号上'.($res? 'true':'false'));
          //var_dump($res);die;
        } else {
          // 加油包只改变可连接状态，当月可用，不增加过期时间，不修改重置日
          User::query()->where('id', $user->id)->update(['enable' => 1]);
        }
      }

      //将订单设置为已支付
      Order::query()->where('oid', $order->oid)->update(['status' => 2]);

      curl_close($curl);
      DB::commit();
      Log::info('支付成功,用户'.$user->id);
      return Response::json(['status' => 'success', 'data' => '', 'message' => '支付成功, 您的账号信息已变更，请使用shadowsocks客户端重新配置账号。']);

    } catch (\Exception $e) {
      DB::rollBack();
      Log::error('支付订单失败：' . $e->getMessage());
      Log::info('支付订单失败,用户'.$user->id);
      //将订单设置为已支付待确认
      Order::query()->where('oid', $order->oid)->update(['status' => 1]);
      return Response::json(['status' => 'fail', 'data' => '', 'message' => '支付失败：' . $e->getMessage()]);
    }
  }
}
