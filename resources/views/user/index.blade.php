@extends('user.layouts')

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
        <div class="row">
            <div class="col-md-8">
                 <div class="portlet box grey-salt">
                      <div class="portlet-title" style="background:#d7dbe4">
                           <div class="caption" style="font-size:14px;font-weight:500;"><i class="ssr-icon-placard" style="font-size:16px;position:relative;top:-2px;margin-right:8px;"></i>公告</div>
                          
                      </div>
                      <div class="portlet-body Noticle">
                          <ul class="sstNotic">
                              @if($notice->isEmpty())
                                  <li>暂无公告</li>
                              @else
                                  @foreach ($notice as $k => $item)
                                      <li>
                                          <a href="{{url('user/article?id=' . $item->id)}}" target="_blank">{{$item->title}} </a >
                                      </li>
                                  @endforeach
                              @endif
                          </ul>

                      </div>
                 </div>
                 <div class="portlet box grey-salt">
                      <div class="portlet-title" style="background:#d7dbe4">
                           <div class="caption" style="font-size:14px;font-weight:500;"><i class="ssr-icon-tutorial" style="font-size:16px;position:relative;top:-2px;margin-right:8px;"></i>使用教程</div>
                      </div>
                      <div class="portlet-body">
                          <ul class="row sstTutorial sstckli">
                              <li class="col-md-6">
                                  <i class="ssr-icon-apple" style="font-size:25px;"></i> iOS版使用教程
                                  <a href="{{url('shadowsocks/user_ios')}}" target="_blank" class="sstckbtn sstbtn">查看</a>
                              </li>
                              <li class="col-md-6">
                                  <i class="ssr-icon-android" style="font-size:25px;"></i> Android版使用教程
                                  <a href="{{url('shadowsocks/user_android')}}" target="_blank" class="sstckbtn sstbtn">查看</a>
                              </li>
                              <li class="col-md-6">
                                  <i class="ssr-icon-windows" style="font-size:25px;"></i> Windows版使用教程
                                  <a href="{{url('shadowsocks/user_windows')}}" target="_blank" class="sstckbtn sstbtn">查看</a>
                              </li>
                              <li class="col-md-6">
                                  <i class="ssr-icon-finder" style="font-size:25px;"></i> Mac OS版使用教程
                                  <a href="{{url('shadowsocks/user_mac')}}" target="_blank" class="sstckbtn sstbtn">查看</a>
                              </li>
                          </ul>
                      </div>
                 </div>
            </div>
            <div class="col-md-4">
                <div class="portlet box grey-salt">
                    <div class="portlet-title" style="background:#d7dbe4">
                        <div class="caption" style="font-size:14px;font-weight:500;"><i class="ssr-icon-people" style="font-size:16px;position:relative;top:-2px;margin-right:8px;"></i>{{trans('home.account_info')}}</div>
                    </div>
                    <div class="portlet-body">
                        <p class="text-muted"> {{trans('home.account_level')}}：{{$info['levelName']}} </p>
                        @if ($info['expire_time'] == '2099-01-01 00:00:00') 
                        <p class="text-muted"> {{trans('home.account_expire')}}：未购买 </p>
                        @else
                        <p class="text-muted"> {{trans('home.account_expire')}}：{{$info['now'] > $info['expire_time'] ? '已过期' : $info['expire_time']}} </p>
                       @endif
                        <p class="text-muted"> {{trans('home.account_last_usage')}}：{{empty($info['t']) ? '从未使用' : date('Y-m-d H:i:s', $info['t'])}} </p>
                        <p class="text-muted"> {{trans('home.account_last_login')}}：{{empty($info['last_login']) ? '未登录' : date('Y-m-d H:i:s', $info['last_login'])}} </p>
                        <p class="text-muted">
                            {{trans('home.account_bandwidth_usage')}}：{{$info['usedTransfer']}} （{{$info['totalTransfer']}}）@if($info['traffic_reset_day']) &ensp;&ensp;每月{{$info['traffic_reset_day']}}日自动重置流量 @endif
                            <div class="progress progress-striped active" style="margin-bottom:0;" title="{{trans('home.account_total_traffic')}} {{$info['totalTransfer']}}，{{trans('home.account_usage_traffic')}} {{$info['usedTransfer']}}">
                                <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="{{$info['usedPercent'] * 100}}" aria-valuemin="0" aria-valuemax="100" style="width: {{$info['usedPercent'] * 100}}%">
                                    <span class="sr-only"> {{$info['usedTransfer']}} / {{$info['totalTransfer']}} </span>
                                </div>
                            </div>
                        </p>
                    </div>
                </div>
                <div class="portlet box grey-salt">
                    <div class="portlet-title" style="background:#d7dbe4">
                        <div class="caption" style="font-size:14px;font-weight:500;"><i class="ssr-icon-download" style="font-size:16px;position:relative;top:-2px;margin-right:8px;"></i>下载</div>
                    </div>
                    <div class="portlet-body">
                          <ul class="row sstTutorial sstDown">
                              <li class="col-md-6 col-sm-3">
                                  <a href="https://itunes.apple.com/tw/app/ssrconnectpro/id1272045249?l=zh&mt=8" class="sstxzbtn sstbtn"><i class="ssr-icon-apple"></i>iOS版</a>
                              </li>
                              <li class="col-md-6 col-sm-3">
                                  <a href="https://github.com/shadowsocks/shadowsocks-android/releases/download/v4.3.3/shadowsocks-nightly-4.3.3.apk" class="sstxzbtn sstbtn"><i class="ssr-icon-android"></i>&nbsp;Android版</a>
                              </li>
                              <li class="col-md-6 col-sm-3">
                                  <a href="https://github.com/shadowsocks/shadowsocks-windows/releases/download/4.0.7/Shadowsocks-4.0.7.zip" class="sstxzbtn sstbtn"><i class="ssr-icon-windows"></i>&nbsp;Windows版</a>
                              </li>
                              <li class="col-md-6 col-sm-3">
                                  <a href="https://github.com/shadowsocks/ShadowsocksX-NG/releases/download/v1.7.0/ShadowsocksX-NG.1.7.0.zip" class="sstxzbtn sstbtn"><i class="ssr-icon-finder"></i>&nbsp;Mac OS版</a>
                              </li>
                          </ul>
                    </div>
                </div>
            </div>
        </div>
        <div id="charge_modal" class="modal fade" tabindex="-1" data-focus-on="input:first" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                        <h4 class="modal-title">余额充值</h4>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger" style="display: none;" id="charge_msg"></div>
                        <form action="#" method="post" class="form-horizontal">
                            <div class="form-body">
                                <div class="form-group">
                                    <label for="charge_type" class="col-md-4 control-label">充值方式</label>
                                    <div class="col-md-6">
                                        <select class="form-control" name="charge_type" id="charge_type">
                                            <option value="1" selected>卡券</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="charge_coupon" class="col-md-4 control-label"> 券码 </label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="charge_coupon" id="charge_coupon" placeholder="请输入券码">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn dark btn-outline">关闭</button>
                        <button type="button" class="btn red btn-outline" onclick="return charge();">充值</button>
                    </div>
                </div>
            </div>
        </div>
        <div id="exchange_modal" class="modal fade" tabindex="-1" data-focus-on="input:first" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                        <h4 class="modal-title"> 兑换流量 </h4>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info" id="msg">您有 {{$info['score']}} 积分，共计可兑换 {{$info['score']}}M 免费流量。</div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn dark btn-outline">关闭</button>
                        <button type="button" class="btn red btn-outline" onclick="return exchange();">立即兑换</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- END PAGE BASE CONTENT -->
    </div>
    <!-- END CONTENT BODY -->
@endsection
<style>
    .sstTutorial{
        padding:5px 0;
    }
    .sstTutorial>li{
        list-style:none;
        padding-top:15px;
        padding-bottom:15px;
        padding-left:20px;
        color:#4f5263;
        font-size:16px;
        font-family:"微软雅黑"
    }
    .sstTutorial>li i{
        display:inline-block;
        margin-right:3px;
        color:#505362;
        font-size:20px;
       vertical-align:middle;
    }
    .sstTutorial>li>.sstbtn{
        display:inline-block;
        background:#6c9ffb;
        color:white;
        border:none;
        border-radius:5px;
    }
    .sstTutorial>li>.sstckbtn{
        padding:7px 20px;
        float:right;
        position:relative;
        top:-5px;
right:10%;
        font-weight:100;
        font-size:14px;
    }
    .sstTutorial>li>.sstxzbtn{
        width:100%;
        border-radius:5px;
        font-size:14px;
        text-align:center;
        position:relative;
        padding:15px 0;
       padding-left:20px;
    }
    .sstTutorial>li>.sstbtn:hover{
        text-decoration:none;
    }
    .sstTutorial>li>a>i{
        color:white;
    }
    .sstDown>li{
        padding-left:5%;
        padding-right:5%;
    }
.sstckli li{
           padding-left:5%;
        }
.sstDown>li i{
       margin-right:0;
position:absolute;
top:25%;
left:13%;
    }

    .sscoll>a{
        color:#797c83;
        display:inline-block;
        width:100%;
    }
    .sscoll>a:hover,.sstNotic li a:hover{
        text-decoration:none !important;
    }
    .sstNotic{
        padding:0;
        margin:-15px;
    }
    .sstNotic li{
        list-style:none;
        border-bottom:1px solid #e0e3ec;
        padding:11px 25px;
    }
    .sstNotic li:last-child{
        border-bottom:none;
    }
    .sstNotic li a{
        color:#4e5462;
        font-weight:500;
        font-size:16px;
    }
    @media screen and (max-width:1290px) {
.sstckli li{
           padding-left:5px;
        }
.sstTutorial>li>.sstckbtn{
         right:10px;
}
    }
     @media screen and (max-width: 992px){
       .sstckli li{
            padding-left:15px;
        }    
}
    @media screen and (min-width: 992px) and (max-width: 1170px) {
        .sstTutorial>li>.sstxzbtn{
            font-size:12px;
            width:108%;
        }
        .sstTutorial>li>.sstckbtn{
            font-size:12px;
            padding:5px;
        }
        .sstckli li{
            font-size:13px;
        }
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
                data:{_token:_token, coupon_sn:charge_coupon},
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
@endsection

