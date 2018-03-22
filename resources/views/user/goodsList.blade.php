@extends('user.layouts')
<script src="https://js.stripe.com/v3/"></script>
@section('css')
    <link href="/assets/pages/css/pricing.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/fancybox/source/jquery.fancybox.css" rel="stylesheet" type="text/css" />
    <style>
        .fancybox > img {
            width: 75px;
            height: 75px;
        }
    </style>
@endsection
@section('title', trans('home.panel'))
@section('content')
    <!-- BEGIN CONTENT BODY -->
    <div class="page-content" style="padding-top:0;">
        @if (Session::has('successMsg'))
            <div class="alert alert-success">
                <button class="close" data-close="alert"></button>
                {{Session::get('successMsg')}}
            </div>
        @endif
        @if (Session::has('errorMsg'))
             <div class="alert alert-danger">
                  <button class="close" data-close="alert"></button>
                         <strong>错误：</strong> {{Session::get('errorMsg')}}
             </div>
       @endif
        <!-- BEGIN PAGE BASE CONTENT -->
        <div class="row">
           <div class="col-md-12" style="height:40px;margin-top:30px;">
                <form style="padding-left:20px" class="buyPayment">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <span style="margin-right:25px;font-size:16px;">
                      <label for="zfb">
                        <input type="radio" id="zfb" name="payment" value ="Alipay" checked="checked" />&nbsp;&nbsp;支付宝
                    </lable>                   
 </span>
                    <span style="font-size:16px;">
<label for="xyk">
                        <input type="radio" id="xyk" name="payment" value ="Card" />&nbsp;&nbsp;信用卡支付
                 </label>   </span>
                </form>
            </div>

            <div class="col-md-12  col-lg-12 pacType" style="margin-bottom:50px;">
                <input type="hidden" name="user_level" value="{{$user['level']}}" />
                <input type="hidden" name="user_type" value="{{$user['user_type']}}" />
                <input type="hidden" name="specify" value="{{$specify}}" />
                @if (isset($order_goods))<input type="hidden" name="order_goods" value="{{$order_goods}}" /> @endif
                @foreach($goodsList as $key => $goods)
                    <div class="col-md-4 col-sm-4 col-lg-4">
                        <div class="{{$goods->name=='专业套餐'?'pacAct':''}}">
                            <input type="hidden" name="goods_level" value="{{$goods->level}}">
                              <h4>{{$goods->name}}</h4>
                              <form>
                                  <p>
<label for="{{$goods->id}}+mon">
                                      <input type="radio" name="{{$goods->name}}" id="{{$goods->id}}+mon"  value ="1" />月付
                               </label>       <span>&yen;<i> {{$goods->mon_price}}</i>/月</span>
                                  </p>
                                  <p>
<label for="{{$goods->id}}+year">
                                      <input type="radio" name="{{$goods->name}}" id="{{$goods->id}}+year" value ="2" checked="checked" />年付</label>
                                      <span>&yen;<i> {{$goods->year_price}}</i>/月</span>
                                  </p>
                                  @if ($specify)
                                  <input type="button" value="续费" class="pacBtn" onclick="buy('{{$goods->id}}')" target="_blank" />
                                  @else
                                  <input type="button" value="购买" class="pacBtn" onclick="buy('{{$goods->id}}')" target="_blank" />
                                  @endif
                              </form>
                        </div>
                     </div>
                @endforeach
</div>
             <div style="text-align:center;margin-bottom:70px;margin-top:20px;color:red;min-width:300px;padding:5px 10px">请谨慎选择所需套餐，购买完成后，在套餐使用期限内暂时无法变更为其他套餐。</div>
           
            <div class="col-md-12">
                <table class="goodTable">
                    <thead>
                        <tr>
                            <th>类别</th>
                            <th>普通套餐</th>
                            <th>专业套餐</th>
                            <th>尊享套餐</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>用途</td>
                            <td>网页/视频/办公</td>
                            <td>极速网页/高清视频/游戏</td>
                            <td>极速网页/高清视频/游戏</td>
                        </tr>
                        <tr>
                            <td>线路数量</td>
                            <td>日本/香港</td>
                            <td>日本/香港/美国/英国/韩国</td>
                            <td>全球服务器</td>
                        </tr>
                        <tr>
                            <td>线路质量</td>
                            <td>普通</td>
                            <td>高速</td>
                            <td>高速</td>
                        </tr>
                        <tr>
                            <td>每月流量</td>
                            <td>50GB</td>
                            <td>80GB</td>
                            <td>200GB</td>
                        </tr>
                        <tr>
                            <td>接入模式</td>
                            <td>服务器直连</td>
                            <td>服务器直连</td>
                            <td>服务器直连</td>
                        </tr>
                        <tr>
                            <td>终端数量</td>
                            <td>无限制</td>
                            <td>无限制</td>
                            <td>无限制</td>
                        </tr>
                        <tr>
                            <td>同时在线</td>
                            <td>1台</td>
                            <td>5台</td>
                            <td>无限制</td>
                        </tr>
                        <tr>
                            <td>服务支持</td>
                            <td>客服支持</td>
                            <td>技术支持</td>
                            <td>工程师支持</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal fade pay-modal" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">信用卡支付</h4>
                      </div>
                      <div class="modal-body">
                           <form id="payment-form">
                                <div class="form-row">
                                  <div id="card-element">
                                    <!-- a Stripe Element will be inserted here. -->
                                  </div>

                                  <!-- Used to display form errors -->
                                  <div id="card-errors" role="alert"></div>
                                </div>
                                <button id="doPayment" class="btn payment-btn">支付</button>
                           </form>
                      </div>
                    </div>
                  </div>
            </div>
        </div>
        <!-- END PAGE BASE CONTENT -->
    </div>
    <!-- END CONTENT BODY -->
@endsection
<style>
    #paymentModal .modal-dialog{
        width:45%;
        max-width:650px;
        min-width:320px;
        position:absolute;
        top:50%;
        left:50%;
        transform:translate(-50%,-50%);
    }
    #paymentModal .modal-dialog .modal-content{
        padding:5px 20px;
        text-align:center;
    }
    #paymentModal .modal-header{
        border:none;
        text-align:center;
        font-weight:700;
        fonf-size:18px;
    }
    #doPayment{
        width:30%;
        text-align:center;
        border:none;
        outline:none;
        margin-top:20px;
        background:#6c9ef8;
        color:white;
        border-radius:3px;
        height:35px;
    }
    #doPayment:hover{
        background:#749ce4;
    }
    .goodTable{
        width:100%;
    }
     .goodTable{
        width:100%;
       border-collapse:collapse;
    }
    .goodTable th,.goodTable td{
        text-align:center;
        color:#515463;
        font-weight:500;
        padding:15px 0;
    }
    .goodTable th:first-child,.goodTable td:first-child{
        background:#28376e;
        color:white;
    }
    .goodTable tr{
        border-top:1px solid #d3d4d9;
        border-bottom:1px solid #d3d4d9;
    }
   .goodTable th,.goodTable td{
        border-right:1px solid #d3d4d9;
    }
   .goodTable th:last-child, .goodTable td:last-child{
       border-right:none;
    }

    .goodTable>tbody>tr{
        border-top:1px solid #d3d4d9;
    }
    .pacType{
        margin:80px 0 20px;
        height:350px;
    }
    .pacType>div>div{
        box-shadow:1px 1px 2px 2px #ddd;
        background:#ffffff;
        padding:15px 30px;
        border-radius:5px;
max-width:400px;
    }
    .pacType>div>div>h4{
        text-align:center;
        font-weight:bolder;
        margin-bottom:25px;
    }
    .pacType>div>div p>span{
        float:right;
    }
    .pacType>div>div .pacBtn{
        margin-left:20%;
        background:#6c9ffb;
        color:white;
        text-align:center;
        border-radius:5px;
        border:none;
        outline:none;
        width:60%;
        height:40px;
        line-height:40px;
        margin-top:35px;
    }
    .pacType>div>div .pacBtn:hover{
        background:#6689c8;
    }
    .pacType>div>div form p{
        line-height:35px;
        padding:15px;
       /* margin:0 -10px;*/
    }
    .pacType>div>div form p:first-child{
        border-bottom:1px solid #e1e1e1;
    }
    .pacType>div>div form p span,.pacType>div>div form p input{
        color:#6c9ffb
    }
    .pacType>div>div form p input{
        margin-right:8px;
    }
    .pacType>div>div form p span i{
        font-style:normal;
        font-size:24px;
        font-weight:bolder;
    }
    .pacType>div>.pacAct{
        background:#28376e;
       /* margin:0 -10px;*/
        position:relative;
        top:-43px;
    }
    .pacType>div>.pacAct>h4,.pacType>div>.pacAct form p{
        color:white;
    }
    .pacType>div>.pacAct>h4{
        font-size:22px;
    }
    .pacType>div>.pacAct form p{
        font-size:16px;
        line-height:40px;
        padding:20px 10px;
    }
    .pacType>div>.pacAct .pacBtn{
        height:45px;
        line-height:45px;
        font-size:16px;
    }
.layerclass{
  top:80px !important;
background:rgba(0,0,0,0.6) !important;
color:white !important;
margin-left:90px!important;
 }
    /**
     * The CSS shown here will not be introduced in the Quickstart guide, but shows
     * how you can use CSS to style your Element's container.
     */
    .StripeElement {
      background-color: white;
      height: 40px;
      padding: 10px 12px;
      border-radius: 4px;
      border: 1px solid transparent;
      box-shadow: 0 1px 3px 0 #e6ebf1;
      -webkit-transition: box-shadow 150ms ease;
      transition: box-shadow 150ms ease;
    }

    .StripeElement--focus {
      box-shadow: 0 1px 3px 0 #cfd7df;
    }

    .StripeElement--invalid {
      border-color: #fa755a;
    }

    .StripeElement--webkit-autofill {
      background-color: #fefde5 !important;
    }
    @media screen and (max-width: 767px) {
                .pacType>div>.pacAct{
            top:0;
        }
        .pacType>div{
            min-width:300px;
}
        .pacType>div>div{
            margin-bottom:15px;
            margin-left:auto;
            margin-right:auto;
        }
        .pacType{
            margin:40px 0;
            height:1100px;
            padding-left:0;
            padding-right:0;
        }

    }
 
</style>
@section('script')
    <script src="/assets/global/plugins/fancybox/source/jquery.fancybox.js" type="text/javascript"></script>
    <script src="/js/layer/layer.js" type="text/javascript"></script>

    <script type="text/javascript">
        // Create a Stripe client
    
        var stripe = Stripe('stripe publish key');
        // Create an instance of Elements
        var elements = stripe.elements();

        // Custom styling can be passed to options when creating an Element.
        // (Note that this demo uses a wider set of styles than the guide below.)
        var style = {
          base: {
            color: '#32325d',
            lineHeight: '18px',
            fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
            fontSmoothing: 'antialiased',
            fontSize: '16px',
            '::placeholder': {
              color: '#aab7c4'
            }
          },
          invalid: {
            color: '#fa755a',
            iconColor: '#fa755a'
          }
        };

        // Create an instance of the card Element
        var card = elements.create('card', {style: style});

        // Add an instance of the card Element into the `card-element` <div>
        card.mount('#card-element');
        card.addEventListener('change', function(event) {
            var displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
         });


        // 编辑商品
        function exchange(id) {
            //
        }

        // 查看商品图片
        $(document).ready(function () {
            $('.fancybox').fancybox({
                openEffect: 'elastic',
                closeEffect: 'elastic'
            })
        })
        function buy(n){
           layer.config({
  skin: 'layerclass'//自定义样式layerclass
})
            var userType= $("input[name='user_type']").val();
            var buyPayment= $(".buyPayment input:checked").val();
            var buy_Type=$(".pacBtn").eq(n-1).parent().find("input:checked").val();
            var specify= $("input[name='specify']").val();
              if ($("input[name='specify']").val() == 1) {
                 if ($("input[name='user_level']").val() != -1 && $("input[name='order_goods']").val() != n) {
                   layer.msg('失败：当前的套餐使用时间内无法变更为其它套餐，您可继续续费当前套餐，或到期后重新选择其他套餐，如有问题可邮件联系客服处理');
    //            }else if ($("input[name='user_type']").val() > $(".pacBtn").eq(n-1).parent().find("input:checked").val()) {
                //               layer.msg('您已属于包年用户，不可购买包月套餐！')
                  return false;
                }
          
                if ($("input[name='user_type']").val() != $(".pacBtn").eq(n-1).parent().find("input:checked").val() ) {
                   if (userType == 1) {
                      layer.msg('失败：包月用户不可购买包年套餐');
                      return false;
                }
                else if (userType == 2) {
                  layer.msg('失败：包年用户不可购买包月套餐');
                  return false;
                }
            }
//            }else if ($("input[name='user_type']").val() > $(".pacBtn").eq(n-1).parent().find("input:checked").val()) {
            //               layer.msg('您已属于包年用户，不可购买包月套餐！')
           }
            if(buyPayment=="Alipay"){
                $.ajax({
                    type: 'POST',
                    url: '../alipay/create',
                    data: {
                        goods_id:n,
                        goods_method:buy_Type,
                        _token: '{{csrf_token()}}'
                    },
                    dataType:"JSON",
                    success: function(data){
                       if (data.status == 0) {
                           window.location.href=data.url;
                       }else{
                           layer.msg(data.message);
                       }
                    }
                });
            }
            else if (buyPayment == 'Card') {
                $('#paymentModal').modal('show');
                // Create a token or display an error when the form is submitted.
                var form = document.getElementById('payment-form');
                form.addEventListener('submit', function(event) {
                    event.preventDefault();
                    $('#doPayment').attr('disabled', true);
                    stripe.createToken(card).then(function(result) {
                        if (result.error) {
                        // Inform the customer that there was an error
                            var errorElement = document.getElementById('card-errors');
                            errorElement.textContent = result.error.message;
                            $('#doPayment').attr('disabled', false);
                        } else {
                            $('#payment-form').find('input[name="stripeToken"]').remove();
                            var payBtn = $('#doPayment');
                            payBtn.attr('disabled', true);
                            // Insert the token ID into the form so it gets submitted to the server
                            var form = document.getElementById('payment-form');
                            var hiddenInput = document.createElement('input');
                            hiddenInput.setAttribute('type', 'hidden');
                            hiddenInput.setAttribute('name', 'stripeToken');
                            hiddenInput.setAttribute('value', result.token.id);
                            form.appendChild(hiddenInput);
                            // Submit the form
                            // form.submit();
                            var dataToken = $('#payment-form').serialize();
                            $.ajax({
                                type: 'POST',
                                url: '../stripe/create',
                                data: {
                                    goods_id:n,
                                    goods_method:buy_Type,
                                    _token: '{{csrf_token()}}',
                                    token:dataToken
                                },
                                dataType:"JSON",
                                 success: function(data){
                                    if (data.status == 'success') {
                                      layer.msg(data.message);
                                        setTimeout(function () {
                                            window.location.href='../user/orderList';
                                        },3000)
                                    }
                                    else if (data.status == 'fail') {
                                      layer.msg(data.message);
                                      setTimeout(function () {
                                        window.location.reload();
                                      },3000)
                                    }
                                }
                            });
                        }
                    });
                });
            }
        }
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
    </script>
@endsection
