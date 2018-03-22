<?php

namespace App\Console\Commands;

use App\Http\Models\Order;
use App\Http\Models\Goods;
use Illuminate\Console\Command;
use App\Http\Models\Config;
use App\Http\Models\User;
use Log;
use DB;

class AutoAddTrafficYearUserJob extends Command
{
    protected $signature = 'command:autoAddTrafficYearUserJob';
    protected $description = '自动给包年用户增加每月流量';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
      //获取已不可用包年用户
      $userList = User::query()->where('status', '>=', 0)->where('enable', 0)->where('user_type', 2)->get();

      foreach ($userList as $user) {
        // 取出用户最后购买的有效套餐
        $order = Order::query()->join('goods', function ($query) {
          $query->where('goods.type', 2);
          $query->on('goods.id', '=', 'order.goods_id');
        })->where('order.user_id', $user->id)->where('order.is_expire', 0)->where('order.status', 2)->where('order.goods_method', 2)->orderBy('order.oid', 'desc')
          ->select('order.oid', 'order.updated_at', 'order.goods_id', 'order.user_id', 'goods.traffic')->get();

        $res = $order->toArray();
        Log::info('res:'.json_encode($res));
        if (count($res)==0) {
          continue;
        }

        if (strtotime(date('Y-m-d H:i:s')) == strtotime($res[0]['updated_at'])) {
          continue;
        }

        $now_date = strtotime(date('Y-m-d'));
        $update_date = strtotime(date('Y-m-d', strtotime($res[0]['updated_at'])));

        //30天倍数
        $case = ((($now_date-$update_date)/86400)%30) != 0 ? true : false;
        //31的倍数
        $case1 = ((($now_date-$update_date)/86400)%31) != 0 ? true : false;
        //28的倍数
        $case2 = ((($now_date-$update_date)/86400)%28) != 0 ? true : false;
        //29的倍数
        $case3 = ((($now_date-$update_date)/86400)%29) != 0 ? true : false;

        if ($case && $case1 && $case2 && $case3) {
          continue;
        }
        Log::info('自动给包年用户增加每月流量开始');
        DB::beginTransaction();
        $goods = Goods::query()->where('id', $res[0]['goods_id'])->first();

        //更新order表的update_at
        $res4 = Order::query()->where('oid', $res[0]['oid'])->update(['updated_at' => date('Y-m-d H:i:s')]);

        $traffic_reset_day = in_array(date('d'), [29, 30, 31]) ? 28 : abs(date('d'));

        //清空之前流量,SS状态设置为可用, 将流量自动重置日期加到账号上并
        $res1 = User::query()->where('id', $user->id)->update(['transfer_enable' => 0, 'enable' => 1, 'traffic_reset_day' => $traffic_reset_day]);

        // 把商品的流量加到账号上
        $res2 = User::query()->where('id', $user->id)->increment('transfer_enable', $goods->traffic * 1048576);

        if (!$res1 || !$res2 || !$res4) {
          DB::rollBack();
          Log::info('自动给包年用户增加每月流量失败:'.$user->id);
        }
        else {
          DB::commit();
         Log::info('自动给包年用户增加每月流量完成:'.$user->id);
        }
      }
        Log::info('定时任务：' . $this->description);
    }
}
