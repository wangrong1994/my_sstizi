<?php
  //$mysql = mysqli_connect('sstizi.com','ssrpanel','SSR-00-Manager','ssrpanel') or die('数据库连接失败');
use App\Http\Models\User;
use App\Http\Models\Order;

// 创建连接
/*$conn = new mysqli('sstizi.com','ssrpanel','SSR-00-Manager','ssrpanel');
// 检测连接
if ($conn->connect_error) {
  die("连接失败: " . $conn->connect_error);
}*/
//查找出订单表有效的订单
$res = Order::query()->where('is_expire','=',0)->where('stauts','=', 2) ->get();
foreach($res as $row) {
  if ($row->goods_id == 1) {
    $res = User::query()->where('id', $row->user_id)->update(['enable'=>1,'transfer_enable'=>53687091200]);
  }
  else {
    $res = User::query()->where('id', $row->user_id)->update(['enable'=>1,'transfer_enable'=>85899345920]);
  }

  $res1 = User::query()->where('id', $row->user_id)->update(['expire_time'=>$row->expire_time]);

  if ($res && $res1) {
    echo '成功更新用户'.$row->user_id;
  }else {
    echo "Error: ";
  }
}
/*$sql = "select user_id, goods_id, expire_at from `order` where is_expire=0 and status=2";
$res = $conn->query($sql);
while($row=mysqli_fetch_assoc($res)){
//var_dump($row);
  if ($row['goods_id'] == 1) {
    $sql = "update user set enable=1 , transfer_enable=53687091200 where id=".$row['user_id'];
  }
  else {
    $sql = "update user set enable=1 , transfer_enable=85899345920 where id=".$row['user_id'];
  }
  $res = $conn->query($sql);
  if ($res == true) {
    echo '成功更新用户流量'.$row['user_id'];
  }else {
    echo "Error: " . $sql . "<br>" . $conn->error;
    die;
  }
  $sql = "update user set expire_time='".$row['expire_at']."' where id=".$row['user_id'];
  $res = $conn->query($sql);
  if ($res == true) {
    echo '成功更新用户过期时间'.$row['user_id'];
  }else {
    echo "Error: " . $sql . "<br>" . $conn->error;
    die;
  }

  /*foreach($row as $v){
    var_dump($v);die;
    //foreach ($res->fetch_assoc() as $v) {


    }*/
  //}




