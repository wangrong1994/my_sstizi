@extends('user.layouts')
<link href="/assets/fontStyle/style.css" />
@section('css')
    <style type="text/css">
        .ticker {
            background-color: #fff;
            margin-bottom: 20px;
            border: 1px solid #e7ecf1!important;
            border-radius: 4px;
            -webkit-border-radius: 4px;
        }
        .ticker ul {
            padding: 0;
        }
        .ticker li {
            list-style: none;
            padding: 15px;
        }

    </style>

    <style type="text/css">
        #lottery{width:574px;height:584px;margin:20px auto;background:url(/assets/images/bg.jpg) no-repeat;padding:50px 55px;}
        #lottery table td{width:142px;height:142px;text-align:center;vertical-align:middle;font-size:24px;color:#333;font-index:-999}
        #lottery table td a{width:284px;height:284px;line-height:150px;display:block;text-decoration:none;}
        #lottery table td.active{background-color:#ea0000;}

    </style>
@endsection
@section('title', trans('home.panel'))
@section('content')
    <!-- BEGIN CONTENT BODY -->
    <div class="page-content" style="padding-top:0;">
        <!-- BEGIN PAGE BASE CONTENT -->
        @if (Session::has('successMsg'))
            <div class="alert alert-success">
                <button class="close" data-close="alert"></button>
                {{Session::get('successMsg')}}
            </div>
        @endif
    <!-- END PAGE BASE CONTENT -->
        <div class="row widget-row">
             <div class="col-md-12  col-lg-12" style="background:white;padding-top:30px;padding-bottom:30px;min-height:100%;">
                 <input type="hidden" name="_token" value="{{csrf_token()}}">
                 <input type="hidden" name="level" value="{{$info['level']}}">
                 @if(!$nodeList->isEmpty())
                     <ul class="nodeList clearfix">
                         @foreach ($nodeList as $item)
                         
                                 @if ($item->group_id == 5 && $info['level'] >1)
                                  
                              @else

                             <li>{{$item -> country_name}}</li>
                        @endif 
                        @endforeach
                     </ul>
                     <div class="col-md-12  col-lg-12 accDetial">
                         <div class="nodeDetail col-md-8 col-sm-8 col-lg-8">
                         
                             @foreach ($nodeList as $item)
                                 @if ($item->group_id == 5 && $info['level'] >1)
                                  
                              @else
 
                                 <div>
                                      <p>地址：<span>{{$item -> server}}</span></p>
                                      <p>端口：<span>{{$item ->port}}</span></p>
                                      <p>密码：<span>{{$item ->passwd}}</span></p>
                                      <p>加密方式：<span>{{$item ->method}}</span></p>
                                      <p>流量：<span>{{$info['usedTransfer']}} / {{$info['totalTransfer']}}</span></p>
                                      <p>周期：<span>{{empty($info['created_at']) ? '从未购买' : $info['created_at'].'/'. $info['expire_time']}}</span></p>
                                      <p>到期时间：<span>{{$info['expire_time']}}</span></p>
                                      <p>最近连接：<span>{{empty($info['laster_use']) ? '从未使用' : date('Y-m-d H:i:s', $info['laster_use'])}}</span>
                                      <p>备注<span></span></p>
                                  </div>
                              @endif
                             @endforeach
                         </div>
                         <div class="nodeCode col-md-4 col-sm-4 col-lg-4">
@foreach ($nodeList as $item)
 @if ($item->group_id==5 && $info['level'] >1)
 @else
<div>
                             
                             <h4 style="margin-top:30px;">ss连接二维码</h4>
                             
                                  <div id="qrcode_ss_img_{{$item->id}}"></div>
                             <h4>ssr连接二维码</h4>

                                  <div id="qrcode_ssr_img_{{$item->id}}"></div>

</div>
@endif
                             @endforeach
</div>
                         
                         <div class="col-md-12 col-sm-12 col-lg-12">
                             <a onclick.prevent="openTip()" data-toggle="modal" data-target="#editPass">修改密码</a>
                             <a href="{{url('user/goodsList')}}">续费</a>
                         </div>
                     </div>
                 @else 
                     暂无可用节点，请前往<a href='{{url('user/goodsList')}}'>购买</a>
                 @endif
             </div>
        </div>
        <div class="modal fade pay-modal" id="editPass" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body row">
                         <h4 style="height:35px;line-height:35px;text-align:left;">请输入新密码</h4>
                         <input type="text" placeholder="请输入新密码" id="newPass"/>
                         <div>
                             <h4>注意</h4>
                             <p>此处修改的密码为Shadowsocks的密码</p>
                         </div>
                    </div>
                    <div class="modal-footer">
                         <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                         <button type="button" class="btn btn-primary editPsw">确定</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END CONTENT BODY -->
@endsection
<style>
    li{
        list-style:none;
    }
    .nodeList{
        border-bottom:1px solid #e1e1e1;
        min-height:35px;
        padding-left:0
    }
    .nodeList li{
        float:left;
        height:35px;
        line-height:35px;
        padding: 0 15px !important;
        margin-right:25px;
        cursor:pointer;
    }
    .nodeList li:last-child{
        margin-right:0;
    }
    .nodeDetail{
        margin-bottom:25px;
    }
    .nodeDetail>div{
        display:none;
    }
    .nodeDetail>div p{
        padding:15px 10px;
        border-bottom:1px solid #e1e1e1;
        margin-top:0;
        margin-bottom:0;
        margin-left:-10px;
        margin-right:-10px;
    }
    .nodeDetail>div p span{
        float:right;
    }
    .nodeDetail>div:first-child{
        display:block;
    }
    .nodeCode>div{
        display:none;
        margin:10% auto;
        text-align:center;
    }
    .nodeCode>div canvas{
        width:50%;
        height:auto;
    }
    .nodeCode>div:first-child{
        display:block;
    }
    .accDetial a{
        color:#4054b3;
        display:inline-block;
        margin-right:25px;
    }
    .accDetial a:hover{
        text-decoration:none;
    }
    .pswTip{
        font-size:12px;
        color:red;
        display:none;
    }
    #editPass .modal-dialog{
        width:35%;
        max-width:600px;
        min-width:320px;
        position:absolute;
        top:50%;
        left:50%;
        transform:translate(-50%,-50%);
    }
    #editPass .modal-dialog .modal-content{
        padding:5px 35px;
    }
    #editPass .modal-header{
        border:none;
        text-align:center;
        font-weight:700;
        fonf-size:18px;
    }
    #editPass .modal-footer{
        border:none;
        text-align:center;
    }
    #editPass .modal-dialog .modal-content input{
        width:100%;
        height:35px;
        border-radius:5px;
        outline:none;
        padding-left:10px;
        margin-bottom:20px
    }
    #editPass .modal-dialog .modal-content h4{
        font-weight:bold;
        font-size:16px;
    }
    #editPass .modal-dialog .modal-content p{
        font-size:14px;
        margin-bottom:0;
    }
</style>
@section('script')
    <script src="/assets/global/plugins/jquery-qrcode/jquery.qrcode.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
    <script src="/js/layer/layer.js" type="text/javascript"></script>

    <script type="text/javascript">
        // 充值
        function charge() {
            var _token = '{{csrf_token()}}';
            var charge_type = $("#charge_type").val();
            var charge_coupon = $("#charge_coupon").val();

            if (charge_type == '1' && (charge_coupon == '' || charge_coupon == undefined)) {
                $("#charge_msg").show().html("券码不能为空");
                $("#charge_coupon").focus();
                return false;
            }

            $.ajax({
                url:'{{url('user/charge')}}',
                type:"POST",
                data:{
                    _token:_token,
                    coupon_sn:charge_coupon
                },
                beforeSend:function(){
                    $("#charge_msg").show().html("充值中...");
                },
                success:function(ret){
                    if (ret.status == 'fail') {
                        $("#charge_msg").show().html(ret.message);
                        return false;
                    }

                    $("#charge_modal").modal("hide");
                    window.location.reload();
                },
                error:function(){
                    $("#charge_msg").show().html("请求错误，请重试");
                },
                complete:function(){}
            });
        }

      // 积分兑换流量
      function exchange() {
        $.ajax({
          type: "POST",
          url: "{{url('user/exchange')}}",
          async: false,
          data: {_token:'{{csrf_token()}}'},
          dataType: 'json',
          success: function (ret) {
            layer.msg(ret.message, {time:1000}, function() {
              if (ret.status == 'success') {
                  window.location.reload();
              }
            });
          }
        });

        return false;
      }
    </script>

  <script type="text/javascript">
       $(function(){
           $(".nodeList li").eq(0).css("borderBottom","2px solid red");
       })

       $(".editPsw").click(function(){
           var newPass=$("#newPass").val();
           newPass=newPass.replace(/\s+/g,"");
           if(newPass==""){
               layer.msg("密码不能为空");
               return false;
           }else{
               $.ajax({
                   type: 'POST',
                   url: '../user/modify_passwd',
                   data: {
                     _token: '{{csrf_token()}}',
                     new_passwd:newPass,
                   },
                   dataType:"JSON",
                 success: function (ret) {
                   layer.msg(ret.message, {time:1000}, function() {
                     if (ret.status == 'success') {
                       window.location.reload();
                     }
                   });
                 }
                });
           }
       })
       $(".nodeList li").click(function(){
           var num=$(this).index();
           $(".nodeDetail>div").eq(num).show().siblings().hide();
           $(".nodeCode>div").eq(num).show().siblings().hide();
           $(this).css("borderBottom","2px solid red").siblings().css("borderBottom","none")
        })

        var UIModals = function () {
            var n = function () {
                @foreach($nodeList as $node)
                    $("#txt_{{$node->id}}").draggable({handle: ".modal-header"});
                    $("#qrcode_{{$node->id}}").draggable({handle: ".modal-header"});
                @endforeach
            };

            return {
                init: function () {
                    n()
                }
            }
        }();

        jQuery(document).ready(function () {
            UIModals.init()
        });
          
        var level =$("input[name='level']").val();
        // 循环输出节点scheme用于生成二维码
        @foreach ($nodeList as $node)
           // $('#qrcode_ssr_img_{{$node->id}}').qrcode("{{$node->ssr_scheme}}");
           // $('#qrcode_ss_img_{{$node->id}}').qrcode("{{$node->ss_scheme}}");
           @if($node->group_id==5 && $info['level'] > 1)
              
           @else
              $('#qrcode_ss_img_{{$node->id}}').qrcode("{{$node->ss_scheme}}");
              $('#qrcode_ssr_img_{{$node->id}}').qrcode("{{$node->ssr_scheme}}");
             // $('#qrcode_ss_img_{{$node->id}}').qrcode("{{$node->ss_scheme}}");
          @endif
        @endforeach

        // 节点订阅
        function subscribe() {
            window.location.href = '{{url('/user/subscribe')}}';
        }

        // 显示加密、混淆、协议
        function show(txt) {
            layer.msg(txt);
        }
       $.ajaxSetup({
         headers: {
           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         }
       });
    </script>
@endsection
