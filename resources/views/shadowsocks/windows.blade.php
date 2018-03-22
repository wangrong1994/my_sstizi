<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>shadowsocks使用教程---Windows</title>
    <link href="/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <script src="/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <link rel="stylesheet" href="/assets/global/css/shadowsocks.css"/>
    <script src="/js/layer/layer.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/jquery-validation/js/localization/messages_zh.min.js" type="text/javascript"></script>
    <script src="/assets/pages/scripts/login.js" type="text/javascript"></script>
</head>
<body>
    <div class="ssHeader">
        <div class="cont">
             <h2>SS梯子</h2>
             <span>
                 <a href="{{url('/register')}}">注册</a>
                 <a type="button" data-toggle="modal" data-target="#sstazLogin">登录</a>
             </span>
        </div>
    </div>
            @if (Session::get('errorMsg'))
                <div class="alert alert-danger" style="background:white;position:absolute;left:50%;top:50%;transform:translate(-50%,-50%);width:30%;height:150px;z-index:999999;line-height:120px;text-align:center;font-size:18px;">
                     <button class="close" data-close="alert">&times;</button>
                     <span> {!! Session::get('errorMsg') !!} </span>
                 </div>
            @endif
            @if (Session::get('regSuccessMsg'))
                 <div class="alert alert-danger" style="background:white;position:absolute;left:50%;top:50%;transform:translate(-50%,-50%);width:30%;height:150px;z-index:999999;line-height:120px;text-align:center;font-size:18px;">
                       <button class="close" data-close="alert">&times;</button>
                       <span> {{Session::get('regSuccessMsg')}} </span>
                 </div>
            @endif
    <div class="cont">
        <h1>shadowsocks使用教程---Windows</h1>
        <section style="word-wrap:break-word;">
            <h3>第一步：下载客户端</h3>
            <p>shadowsocks客户端Windows版安装包下载地址：
                <a href="https://github.com/shadowsocks/shadowsocks-windows/releases/download/4.0.7/Shadowsocks-4.0.7.zip" style="color:#19feeb;">https://github.com/shadowsocks/shadowsocks-windows/releases/download/4.0.7/Shadowsocks-4.0.7.zip</a>
                下载成功之后安装即可
            </p>
        </section>
        <section>
            <h3>第二步：登录ss梯子个人中心</h3>
            <p>
                使用浏览器登录到<a href="https://sstizi.me" style="color:#19feeb;">https://sstizi.me</a>，选择左侧【节点账号】页面，就会出现所有线路的配置信息和对应的配置二维码。
            </p>
            <img src="/assets/images/win/win01.png" style="width: 80%;height: auto" />
        </section>
        <section>
            <h3>第三步：打开客户端配置线路</h3>
            <p>
                打开刚才安装到客户端，这时它会出现在顶部任务栏中，右键点击后如图选择，扫码成功后选择线路，然后点击"打开shadowsocks"即可连接
            </p>
            <img src="/assets/images/win/win02.png" style="width: 45%;height: auto" />
            <img src="/assets/images/win/win03.png" style="width: 45%;height: auto" />
            <p style="text-align: center">
                到这里你就连接成功了。当然，如果你想配置多条线路，可以重复扫码，将sstizi所有线路都配置好。
            </p>
            <p style="text-align: center">
                您也可以在这里对每条线路的名字进行备注。
            </p>
            <img src="/assets/images/win/win04.png" style="width: 45%;height: auto" />
            <img src="/assets/images/win/win05.png" style="width: 45%;height: auto" />
        </section>
        <p style="text-align: center;font-weight: 200;">感谢您的使用</p>
    </div>
  <div class="modal fade" id="sstazLogin" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
         <div class="modal-dialog" role="document" style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);">
             <div class="modal-content">
                  <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  </div>
                  <div class="modal-body" style="padding:15px 25px;">
                   <form class="login-form" action="{{url('login')}}" method="post">
                    <div class="form-group">
                        <div>
                            <p class="col-ms-2 col-md-2 col-lg-2">用户名</p>
                            <p class="col-ms-10 col-md-10 col-lg-10">
                                <input class="form-control form-control-solid placeholder-no-fix" type="text" autocomplete="off" placeholder="{{trans('login.username')}}" name="username" value="{{Request::old('username')}}" />
                            </p>
                        </div>

                    </div>
                    <div class="form-group">
                        <div>
                            <p class="col-ms-2 col-md-2 col-lg-2">密码</p>
                            <p class="col-ms-10 col-md-10 col-lg-10">
                                <input class="form-control form-control-solid placeholder-no-fix" type="password" autocomplete="off" placeholder="{{trans('login.password')}}" name="password" value="{{Request::old('password')}}" />
                            </p>
                        </div>
                        <input type="hidden" name="_token" value="{{csrf_token()}}" />
                    </div>

                    <div class="form-actions clearfix">
                        <div class="pull-left">
                            <label class="rememberme mt-checkbox mt-checkbox-outline">
                                <input type="checkbox" name="remember" value="1"> {{trans('login.remember')}}
                                <span></span>
                            </label>
                        </div>
                        <div class="pull-right forget-password-block">
                            <a href="{{url('resetPassword')}}" class="forget-password">{{trans('login.forget_password')}}</a>
                        </div>
                    </div>
                    <div class="form-actions" style="width:30%;margin:0 auto;">
                        <button type="submit" class="btn btn-block uppercase btn-primary" style="margin-bottom:20px;height:35px;">{{trans('login.login')}}</button>
                    </div>
                 </form>
                  </div>
             </div>
         </div>
       </div>
    <script>
          $(window).scroll(function(){
             if($(window).scrollTop()>=80){
               $(".ssHeader").addClass('fixTop');
             }else(
               $(".ssHeader").removeClass('fixTop')
             )
           })
           $(".close").click(function(){
              $(this).parent().css("display","none");
           })

    </script>
</body>
</html>
