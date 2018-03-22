<?php

namespace App\Console\Commands;

use App\Http\Models\Order;
use App\Http\Models\Goods;
use Illuminate\Console\Command;
use App\Http\Models\Config;
use App\Http\Models\User;
use Log;
use DB;

class AutoAddTrafficUserJob extends Command
{
    protected $signature = 'command:autoAddTrafficUserJob';
    protected $description = '自动给续费用户增加每月流量';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
      //获取已不可用续费用户
      $now = date('Y-m-d H:i:s');
      $userList = User::query()->where('status', '>=', 0)->where('enable', 0)->where('expire_time' ,'>', $now)->where('expire_time','!=','2099-01-01 00:00:00')->get();

      foreach ($userList as $user) {
        $y = date('Y');
        $m = date('m');
        $today = strtotime(date('Y-m-d',time()));
        if(count($user->traffic_reset_day) == 1) {
          $reset_day = $y.'-'.$m.'-'.'0'.$user->traffic_reset_day;
        }
        else {
          $reset_day = $y.'-'.$m.'-'.$user->traffic_reset_day;
        }
        $reset_day = strtotime($reset_day);
        if (intval(($today-$reset_day)/84600) < 0) { //说明还没
          continue;
        }

        // 取出用户最后购买的有效套餐
        $order = Order::query()->join('goods', function ($query) {
          $query->on('goods.id', '=', 'order.goods_id');
        })->where('order.user_id', $user->id)->where('order.is_expire', 0)->where('order.status', 2)->orderBy('order.oid', 'desc')
          ->select('order.oid', 'order.updated_at', 'order.goods_id', 'order.user_id', 'goods.traffic')->get();

        $res = $order->toArray();
        if (count($res)==0) {
          continue;
        }
        Log::info('操作用户：'.$user->id);
        Log::info('自动给续费用户增加每月流量开始');
        DB::beginTransaction();
        $goods = Goods::query()->where('id', $res[0]['goods_id'])->first();

        //更新order表的update_at
        $res1 = Order::query()->where('oid', $res[0]['oid'])->update(['updated_at' => date('Y-m-d H:i:s')]);
        Log::info('更新order表的update_at:'. ($res1) ? 'true':'false');

        //清空之前流量,SS状态设置为可用, 将流量自动重置日期加到账号上并
        $res2 = User::query()->where('id', $user->id)->update(['transfer_enable' => 0, 'enable' => 1, 'u' => 0, 'd' => 0]);
        Log::info('清空之前流量'. ($res2) ? 'true':'false');

        // 把商品的流量加到账号上
        $res3 = User::query()->where('id', $user->id)->increment('transfer_enable', $goods->traffic * 1048576);
        Log::info('加商品的流量'. ($res2) ? 'true':'false');

        if (!$res1 || !$res2 || !$res3) {
          DB::rollBack();
          Log::info('自动给续费用户增加每月流量失败:'.$user->id);
        }
        else {
          DB::commit();
         Log::info('自动给续费用户增加每月流量完成:'.$user->id);
        }
      }
        Log::info('定时任务：' . $this->description);
    }
}
