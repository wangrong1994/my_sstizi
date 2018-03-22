<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;
/**
 * 注册控制器
 * Class LoginController
 * @package App\Http\Controllers
 */
class ShadowsocksController extends Controller {

  //安卓教程页面
  public function android (Request $request) {
    return Response::view('shadowsocks/android');
  }

  //IOS教程页面
  public function ios (Request $request) {
    return Response::view('shadowsocks/ios');
  }

  //MAC教程页面
  public function mac (Request $request) {
    return Response::view('shadowsocks/mac');
  }

  //教程页面
  public function windows (Request $request) {
    return Response::view('shadowsocks/windows');
  }


  //需要登录的，没有登录按钮
  //安卓教程页面
  public function user_android (Request $request) {
    return Response::view('shadowsocks/user_android');
  }

  //IOS教程页面
  public function user_ios (Request $request) {
    return Response::view('shadowsocks/user_ios');
  }

  //MAC教程页面
  public function user_mac (Request $request) {
    return Response::view('shadowsocks/user_mac');
  }

  //教程页面
  public function user_windows (Request $request) {
    return Response::view('shadowsocks/user_windows');
}
}