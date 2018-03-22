<!DOCTYPE html>
<!--[if IE 8]> <html lang="{{app()->getLocale()}}" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="{{app()->getLocale()}}" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="{{app()->getLocale()}}">
<!--<![endif]-->
<link href="/assets/fontStyle/style.css" rel="stylesheet" type="text/css" />
<head>
    <meta charset="utf-8" />
    <title>{{trans('login.title')}}</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <meta content="" name="description" />
    <meta content="" name="author" />
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN THEME GLOBAL STYLES -->
    <link href="/assets/global/css/components-rounded.min.css" rel="stylesheet" id="style_components" type="text/css" />
    <!-- END THEME GLOBAL STYLES -->
    <!-- BEGIN PAGE LEVEL STYLES -->
    <link href="/assets/pages/css/login-2.min.css" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL STYLES -->
    <!-- BEGIN THEME LAYOUT STYLES -->
    <!-- END THEME LAYOUT STYLES -->
    <link rel="shortcut icon" href="favicon.ico" />
    <style>
        html,body{
            width:100%;
            height:100%;
        }
        .loginIn{
            width:100%;
            height:auto;
            min-height:100%;
            background:#28376e;
        }
        .ssHeader h2{
            color:#7aabf7;
            font-weight:bolder;
            width:70%;
            display:inline-block;
            margin-top:0;
        }
        .ssHeader span{
            display:inline-block;
           float:right;
        }
        .ssHeader span a{
            display:inline-block;
            background:none;
            border:1px solid #7aabf7;
            color:#7aabf7;
            padding:5px 15px;
            font-size:14px;
            border-radius:3px;
        }
        .ssHeader span a:hover{
            text-decoration:none;
            background:#6c9ffb;
            color:#ffffff;
        }
        .ssHeader{
            position:relative;
            padding-top:30px;
            margin-bottom:60px;
            width:100%;
        }
        .sstcont{
            /*border-bottom:1px solid #4766a6;*/
            padding:60px 0;
          
         /* background:url("/assets/images/sstzbj.png") no-repeat left top/100% 100%;*/
        }
.ssttop{
background:url("/assets/images/sstzbj.png") no-repeat left top/100% 101%;
}
        .sstcont h2{
            color:#77acfa;
            font-size:38px;
font-weight:bold;
            margin:0;
            margin-top:20px;
        }
        .sstcont h2 span{
            color:#28e6fc;
        }
        .sstcont p{
            color:#ffffff;
            line-height:30px;
            letter-spacing:3px;
font-size:16px;
        }
        .superiority{
            padding:35px 0;
margin-bottom:35px;
        }
        .superiority h3{
            text-align:center;
            color:#78abfa;
            margin-bottom:70px;
            font-weight:600;
            font-size:30px;
        }
        .superiority>div{
            text-align:center;
            padding:0 15px;
        }
        .superiority>div>div{
            padding:30px 25px;
            height:300px;
            border:1px solid #4f6ac1;
            border-radius:3px;
        }
        .superiority h4{
            font-weight:500;
            color:white;
            margin:20px 0;
            font-size:18px;
        }
        .superiority p{
            color:white;
            font-weight:150;
            font-size:14px;
            letter-spacing:2px;
            word-wrap:break-word;
        }
        .user>div>div{
            background:#2d3d79;
            height:180px;
            padding:0;
            padding-top:10px;
        }
        .user>div>div h4{
            color:#79adf9;
            font-size:20px;
        }
        .user>div>div h4 i{
            display:inline-block;
            margin-right:8px;
            font-size:30px;
            vertical-align:center;
        }
        .user>div>div .sstuser{
            display:inline-block;
            width:60%;
            margin:25px auto;
            background:#6c9ffb;
            color:white;
            text-align:center;
            height:40px;
            line-height:40px;
            font-size:14px;
            font-weight:200;
            border-radius:5px;
        }
        .user>div>div .sstuser:hover{
            text-decoration:none;
        }
        .sstfooter{
            background:#354580;
            width:100%;
            padding-bottom:5px;
        }
        .sstfooter .ssHeader{
            margin-bottom:0;
        }
        .fixTop h2{
             width:100%;
            font-size:28px;
        }
        .sstfooter h5{
            font-weight:bold;
            font-size:16px;
        }
          .fixTop>div{
           position:relative;
        }
        .fixTop span{
           position:absolute;
           right:0;
           top:0;        
        }
        .fixTop span a{
            display:inline-block;
            background:#6c9ffb;
            color:#ffffff;
            padding:10px 45px;
            border:none;
        }
        .fixTop span a:hover{
            background:#3c74d9;
        }
        .bordBot{
            margin-bottom:30px;
            margin-top:0px;
            background: -webkit-linear-gradient(left, rgba(70,98,161,0.2) , rgba(70,98,161,1) ,rgba(70,98,161,0.2) ); /* Safari 5.1 - 6.0 */
            background: -o-linear-gradient(right, rgba(70,98,161,0.2) , #4662a1,rgba(70,98,161,1),rgba(70,98,161,0.2) ); /* Opera 11.1 - 12.0 */
            background: -moz-linear-gradient(right, rgba(70,98,161,0.2) , #4662a1,rgba(70,98,161,1),rgba(70,98,161,0.2) ); /* Firefox 3.6 - 15 */
            background: linear-gradient(to right, rgba(70,98,161,0.2)  , #4662a1,rgba(70,98,161,1),rgba(70,98,161,0.2) ); /* 标准的语法 */
        }
        .fixTop{
           width:100%;
           position:fixed;
           bottom:0;
           background:#354580;
           z-index:1000;
        }
       #sstLogin.in .modal-dialog{
         height:auto;
          margin:35px auto;
          transform:none;
       }
        @media screen and (min-width:769px) and (max-width:992px){
          .oursuper>div{
            padding:0 5px; 
          }
          .oursuper>div>div{
            padding:10px;
         }
         .fixTop span a{
           padding:10px 25px;
          }
        }

         @media screen and (min-width:992px) and (max-width:1200px){
          .oursuper>div{
            padding:0 5px;
          }
         
        }
        @media screen and (max-width:768px){
        .superiority{padding:0;}
        .ssHeader{
          margin-bottom:20px;
         }
          .ssHeader h2{
            font-size:20px;
           width:50%
         }
        .sstcont p{
          font-size:14px;
          line-height:25px;
          }
        .sstcont img{
           margin-bottom:20px;
        }
         .sstcont{
            padding:20px 0;
         }
        .sstcont h2{
           font-size:22px;
          }
         .superiority>div>div{
            height:200px;
            padding:15px;
           margin-bottom:20px;
         }
           .superiority>div>div img{
            margin-top:20px;
          }
         .superiority>div>div .ours{
           text-align:left;
           padding-left:20px; 
         }
          .user>div>div{
              height:150px;
              padding:0;
             padding-top:10px;
           }
           .user>div>div h4{
               font-size:16px;
            }
          .user>div>div h4 i{
              font-size:24px;
              margin-right:5px;
           }
           .user>div{
             padding:0 5px;
             margin-bottom:20px;
            }
           .user>div>div .sstuser{
             width:75%;
             margin:10px auto;
           }
          .superiority h3{
            margin-bottom:40px;
           }
.oursuper>div>div:first-child{
   padding-left:0;
}
        .fixxs h2{
          color: #7aabf7;
          font-size:14px;
          font-weight:bold; 
          margin-top:0;
        }
        
        .fixxs h5{
           color:#a2abca;
           margin:0;
         }
         .fixxs p{
            margin:10px 0;
          }
        .fixxs{
           background:#354580;
          width:100%;
           position:fixed;
           bottom:0;
           padding-top:10px;
         }
        .fixxs span a{
            display:inline-block;
            background:#6c9ffb;
            color:#ffffff;
            padding:5px 20px;
            border:none;
            border-radius:3px;
        }
        .fixxs span a:hover{
            background:#3c74d9;
        }
        .fixxs>div>div{
          padding-left:0;
          padding-right:0; 
        }
        }
    </style>
</head>

<body class=" loginIn">
<!-- BEGIN LOGIN -->
    <!-- BEGIN LOGIN FORM -->
<div class="ssttop">
        <div class="ssHeader">
            <div class="container" stye="max-width:1200px;">
                 <h2>SS梯子</h2>
                 <span>
                     <a href="{{url('/register')}}">注册</a>
                     <a type="button" class="sstIndexdl">登录</a>
                 </span>
            </div>
        </div>
    <div class="container" style="height:100%;">
        <div class="alert alert-danger display-hide" style="min-width:300px;position:absolute;left:50%;top:50%;transform:translate(-50%,-50%);background:white;width:30%;height:150px;z-index:999999;line-height:120px;text-align:center;font-size:18px;">
             <button class="close" data-close="alert"></button>
             <span> {{trans('login.tips')}} </span>
        </div>
        @if (Session::get('errorMsg'))
            <div class="alert alert-danger" style="min-width:300px;position:absolute;left:50%;top:50%;transform:translate(-50%,-50%);background:white;width:30%;height:150px;z-index:999999;line-height:120px;text-align:center;font-size:18px;">
                 <button class="close" data-close="alert"></button>
                 <span> {!! Session::get('errorMsg') !!} </span>
             </div>
        @endif
        @if (Session::get('regSuccessMsg'))
             <div class="alert alert-danger" style="min-width:300px;background:white;position:absolute;left:50%;top:50%;transform:translate(-50%,-50%);width:30%;height:150px;z-index:999999;line-height:120px;text-align:center;font-size:18px;">
                   <button class="close" data-close="alert"></button>
                   <span> {{Session::get('regSuccessMsg')}} </span>
             </div>
        @endif
        <div class="row sstcont">
            <div class="col-md-6 col-lg-6 col-sm-6">
                <h2>比VPN更好用的<span>科学上网</span>工具</h2>
                <p>相对于传统的VPN，SS梯子的抗干扰性更强，从而保证了它不容易被"和谐"，同时又非常简单易用，只要下载客户端后傻瓜式配置即可使用</p>
            </div>
            <div class="col-md-1 cl-lg-1 col-sm-1">
            </div>
            <div class="col-md-5 col-lg-5 col-sm-5">
                <img src="/assets/images/login/login01.png" style="width:100%;height:auto;" />
            </div>
        </div>
    </div>
</div>
    <div style="width:100%;height:2px;" class="bordBot"></div>
    <div class="container" style="height:100%;">
        <div class="row superiority oursuper">
            <h3>我们的优势</h3>
            <div class="col-md-3 col-lg-3 col-sm-3">
                <div>
                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-4">
                    <img src="/assets/images/login/login02.png" style="width:auto;height:80px;" />
                   </div><div class="col-md-12 col-lg-12 col-sm-12 col-xs-8 ours"> <h4>多条高速线路</h4>
                    <p>我们现拥有日本、美国、香港等多条高速线路，可流畅观看2K的YouTube视频，后续还将增设更多国家线路</p></div>
                </div>
            </div>
            <div class="col-md-3 col-lg-3 col-sm-3">
                <div><div class="col-md-12 col-lg-12 col-sm-12 col-xs-4">
                    <img src="/assets/images/login/login03.png" style="width:auto;height:80px;" />
                   </div><div class="col-md-12 col-lg-12 col-sm-12 col-xs-8 ours"> <h4>灵活的套餐</h4>
                    <p>您最低只需支付12RMB/月，就能享受高速线路</p></div>
                </div>
            </div>
            <div class="col-md-3 col-lg-3 col-sm-3">
                <div><div class="col-md-12 col-lg-12 col-sm-12 col-xs-4">
                    <img src="/assets/images/login/login04.png" style="width:auto;height:80px;" />
                   </div><div class="col-md-12 col-lg-12 col-sm-12 col-xs-8 ours"> <h4>全平台支持</h4>
                    <p>SS梯子支持Win/Mac/IOS/Android/Linux 等所有主流平台</p></div>
                </div>
            </div>
            <div class="col-md-3 col-lg-3 col-sm-3">
                <div><div class="col-md-12 col-lg-12 col-sm-12 col-xs-4">
                    <img src="/assets/images/login/login05.png" style="width:auto;height:80px;" />
                 </div>  <div class="col-md-12 col-lg-12 col-sm-12 col-xs-8 ours"> <h4>真人售后支持</h4>
                    <p>如您有任何问题可以通过邮件联系<a href="mailto:sstizi.au@gmail.com" style="color:#19feeb;">sstizi.au@gmail.com</a>我们获取帮助</p></div>
                </div>
            </div>
        </div>
    </div>
    <div style="width:100%;height:2px;" class="bordBot"></div>
    <div class="container" style="height:100%;">
        <div class="row superiority user">
            <h3>使用教程</h3>
            <div class="col-md-3 col-lg-3 col-sm-3 col-xs-6">
                <div>
                    <h4><i class="ssr-icon-apple"></i>iOS版</h4>
                    <a href="{{url('shadowsocks/ios')}}" target="_blank" class="sstuser">查看教程</a>
                </div>
            </div>
            <div class="col-md-3 col-lg-3 col-sm-3 col-xs-6">
                <div>
                    <h4><i class="ssr-icon-android"></i>Android版</h4>
                    <a href="{{url('shadowsocks/android')}}" target="_blank" class="sstuser">查看教程</a>
                </div>
            </div>
            <div class="col-md-3 col-lg-3 col-sm-3 col-xs-6">
                <div>
                    <h4><i class="ssr-icon-windows"></i>Windows版</h4>
                    <a href="{{url('shadowsocks/windows')}}" target="_blank" class="sstuser">查看教程</a>
                </div>
            </div>
            <div class="col-md-3 col-lg-3 col-sm-3 col-xs-6">
                <div>
                    <h4><i class="ssr-icon-finder"></i>Mac OS版</h4>
                    <a href="{{url('shadowsocks/mac')}}" target="_blank" class="sstuser">查看教程</a>
                </div>
            </div>
        </div>
    </div>
    <div style="width:100%;height:2px;" class="bordBot"></div>
    <div class="sstfooter hidden-xs">
        <div class="container">
            <div class="col-md-3 col-lg-3 col-sm-3">
                <div style="color:#a2abca;">
                    <h5>联系我们</h5>
                    <p style="font-size:14px;"><a href="mailto:sstizi.au@gmail.com" style="color:#a2abca;">sstizi.au@gmail.com</a></p>
                </div>
            </div>
</div>
</div>
<div class="ssHeader fixTop hidden-xs" style="margin-bottom:0;padding-bottom:20px;">
            
                <div class="container" style="max-width:1200px;text-align:center">
                    <h2>现在注册去享受外面的世界吧！</h2>
                    <span>
                        <a href="{{url('/register')}}" style="display:inline-block;margin-right:8px;">注册</a>
                        <!--<a type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#sstRegister">注册</a>-->
                        <a type="button" data-toggle="modal" data-target="#sstLogin">登录</a>
                    </span>
                </div>
            
        
    </div>

<div class="fixxs">
<div class="container visible-xs">
  <div class="col-xs-4">
  <h5>联系我们</h5>
                    <p style="font-size:14px;"><a href="mailto:sstizi.au@gmail.com" style="color:#a2abca;">sstizi.au@gmail.com</a></p>
 </div>
  <div class="col-xs-8" style="text-align:center;">
   <h2>现在注册去享受外面的世界吧！</h2>
                    <span>
                        <a href="{{url('/register')}}" style="display:inline-block;margin-right:8px;">注册</a>
                        <!--<a type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#sstRegister">注册</a>-->
                        <a type="button" class="sstIndexdl">登录</a>
                    </span>
 </div>
</div>
</div>

    <div class="modal fade" id="sstLogin" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog" role="document" style="width:80%;min-width:300px;max-width:550px;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          </div>
          <div class="modal-body" style="padding:15px 25px;">
              <form class="login-form" action="{{url('login')}}" method="post">
                 <div class="form-group">
                     <div>
                         
                        <span>用户名</span>
                             <input class="form-control form-control-solid placeholder-no-fix" type="text" autocomplete="off" placeholder="{{trans('login.username')}}" name="username" value="{{Request::old('username')}}" />
                       
                     </div>

                 </div>
                 <div class="form-group">
                     <div>
                       
                        <span>密码</span>
                             <input class="form-control form-control-solid placeholder-no-fix" type="password" autocomplete="off" placeholder="{{trans('login.password')}}" name="password" value="{{Request::old('password')}}" />
                       
                     </div>
                     <input type="hidden" name="_token" value="{{csrf_token()}}" />
                 </div>
                 @if($is_captcha)
                     <div class="form-group" style="margin-bottom:65px;">
                         <label class="control-label visible-ie8 visible-ie9">{{trans('login.captcha')}}</label>
                         <input class="form-control form-control-solid placeholder-no-fix" style="width:60%;float:left;" type="text" autocomplete="off" placeholder="{{trans('login.captcha')}}" name="captcha" value="" />
                         <img src="{{captcha_src()}}" onclick="this.src='/captcha/default?'+Math.random()" alt="{{trans('login.captcha')}}" style="float:right;" />
                     </div>
                 @endif
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
    <!-- 注册弹窗功能因涉及修改的地方比较杂乱暂未实现，之后有时间进行研究
    <div class="modal fade" id="sstRegister" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document" style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);">
            <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body" style="padding:15px 25px;">
                    <form class="register-form" action="{{url('register')}}" method="post" style="display: block;" id="ssRegDate">
        @if($is_register)
            @if(Session::get('errorMsg'))
                <div class="alert alert-danger">
                    <button class="close" data-close="alert"></button>
                    <span> {{Session::get('errorMsg')}} </span>
                </div>
            @endif
            <div class="form-group">
                <label class="control-label visible-ie8 visible-ie9">{{trans('register.username')}}</label>
                <p>请输入用户名</p>
                <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="{{trans('register.username_placeholder')}}" name="username" value="{{Request::old('username')}}" required />
                <input type="hidden" name="register_token" value="{{Session::get('register_token')}}" />
                <input type="hidden" name="_token" value="{{csrf_token()}}" />
                <input type="hidden" name="aff" value="{{Request::get('aff')}}" />
            </div>
            <div class="form-group">
                <label class="control-label visible-ie8 visible-ie9">{{trans('register.password')}}</label>
                <p>请输入密码</p>
                <input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="{{trans('register.password')}}" name="password" value="{{Request::old('password')}}" required />
            </div>
            <div class="form-group">
                <label class="control-label visible-ie8 visible-ie9">{{trans('register.retype_password')}}</label>
                <p>请再次输入密码</p>
                <input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="{{trans('register.retype_password')}}" name="repassword" value="{{Request::old('repassword')}}" required />
            </div>
            @if($is_invite_register)
                <div class="form-group">
                    <label class="control-label visible-ie8 visible-ie9">{{trans('register.code')}}</label>
                    <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="{{trans('register.code')}}" name="code" value="{{Request::old('code') ? Request::old('code') : Request::get('code')}}" required />
                </div>
            @endif
            @if($is_captcha)
            <div class="form-group" style="margin-bottom:75px;">
                <label class="control-label visible-ie8 visible-ie9">{{trans('register.captcha')}}</label>
                <input class="form-control placeholder-no-fix" style="width:60%;float:left;" type="text" autocomplete="off" placeholder="{{trans('register.captcha')}}" name="captcha" value="" required />
                <img src="{{captcha_src()}}" onclick="this.src='/captcha/default?'+Math.random()" alt="{{trans('register.captcha')}}" style="float:right;" />
            </div>
            @endif
            <div class="form-group margin-top-20 margin-bottom-20">
                <label class="mt-checkbox mt-checkbox-outline">
                    <input type="checkbox" name="tnc" checked disabled /> {{trans('register.tnc_button')}}
                        <a href="javascript:showTnc();"> {{trans('register.tnc_link')}} </a>
                        <span></span>
                    </label>
            </div>
        @else
            <div class="alert alert-danger">
                <span> {{trans('register.register_alter')}} </span>
            </div>
        @endif
        <div class="form-actions" style="height:100%;text-align:center;">
            @if($is_register)
                <button type="submit" class="btn red uppercase">{{trans('register.submit')}}</button>
            @endif
        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> -->
</div>

<!--  END LOGIN -->
<!--[if lt IE 9]>
<script src="/assets/global/plugins/respond.min.js"></script>
<script src="/assets/global/plugins/excanvas.min.js"></script>
<script src="/assets/global/plugins/ie8.fix.min.js"></script>
<![endif]-->
<!-- BEGIN CORE PLUGINS -->
<script src="/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="/js/layer/layer.js" type="text/javascript"></script>
<script src="/assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/jquery-validation/js/localization/messages_zh.min.js" type="text/javascript"></script>
<script type="text/javascript">
   $(function(){
    $(".sstIndexdl").click(function(){
       $('#sstLogin').modal('show');
   })
   
       $(window).scroll(function(){
if($(window).scrollTop() == $(document).height() - $(window).height()){
              $(".fixTop").css("background","none");
            }else(
              $(".fixTop").css("background","#354580")
            )
          })
          
       $(window).scroll(function(){
if($(window).scrollTop() == $(document).height() - $(window).height()-200) {
              $(".fixxs").css("position","static");
            }else(
              $(".fixxs").css("position","fixed")
            )
          })
       })

       //$("#ssRegDate").find("input[name='repassword']").blur(function(){
           //var psw=$("#ssRegDate").find("input[name='password']").val();
           //var rpsw=$(this).val()
           //if(psw!=rpsw){

           //}
       //})
       //$("#ssRegDate .tj").click(function(){

       //})
   
    function showTnc() {
        layer.open({
            type: 1
            ,title: false //不显示标题栏
            ,closeBtn: false
            ,area: '500px;'
            ,shade: 0.8
            ,id: 'tnc' //设定一个id，防止重复弹出
            ,resize: false
            ,btn: ['{{trans('register.tnc_title')}}']
            ,btnAlign: 'c'
            ,moveType: 1 //拖拽模式，0或者1
            ,content: '<div style="padding: 20px; line-height: 22px; background-color: #393D49; color: #fff; font-weight: 300;">不得通过本站提供的服务发布、转载、传送含有下列内容之一的信息：<br>1.违反宪法确定的基本原则的；<br>2.危害国家安全，泄漏国家机密，颠覆国家政权，破坏国家统一的；<br>3.损害国家荣誉和利益的；<br>4.煽动民族仇恨、民族歧视，破坏民族团结的；<br>5.破坏国家宗教政策，宣扬邪教和封建迷信的； <br>6.散布谣言，扰乱社会秩序，破坏社会稳定的；<br>7.散布淫秽、色情、赌博、暴力、恐怖或者教唆犯罪的；<br>8.侮辱或者诽谤他人，侵害他人合法权益的；<br>9.煽动非法集会、结社、游行、示威、聚众扰乱社会秩序的；<br>10.以非法民间组织名义活动的；<br>11.含有法律、行政法规禁止的其他内容的。</div>'
            ,success: function(layero){
                layer.close();
            }
        });
    }
</script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN THEME GLOBAL SCRIPTS -->
<script src="/assets/global/scripts/app.min.js" type="text/javascript"></script>
<!-- END THEME GLOBAL SCRIPTS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="/assets/pages/scripts/login.js" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<!-- BEGIN THEME LAYOUT SCRIPTS -->
<!-- END THEME LAYOUT SCRIPTS -->
</body>

</html>

