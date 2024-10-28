{{csrf_field()}}
<div class="layui-form-item">
    <label for="" class="layui-form-label">显示名称</label>
    <div class="layui-input-block">
        <input type="text" name="name" value="{{isset($payment['name'])?$payment['name']:''}}" lay-verify="required" placeholder="用于前端显示使用" class="layui-input" >
    </div>
</div>


<div class="layui-form-item">
    <label for="" class="layui-form-label">图标URL(选填)</label>
    <div class="layui-input-block">
        <input type="text" name="icon" value="{{isset($payment['icon'])?$payment['icon']:''}}"  placeholder="用于前端显示使用(https://x.com/icon.svg)" class="layui-input" >
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">自定义通知域名(选填)</label>
    <div class="layui-input-block">
        <input type="text" name="notify_domain" value="{{isset($payment['notify_domain'])?$payment['notify_domain']:''}}"  placeholder="网关的通知将会发送到该域名(https://x.com)" class="layui-input" >
    </div>
</div>



<div class="layui-form-item">
    <div class="layui-inline">
        <label class="layui-form-label">百分比手续费(选填)</label>
        <div class="layui-input-inline" style="width: 200px;">
            <input type="number" name="handling_fee_percent" value="{{isset($payment['handling_fee_percent'])?$payment['handling_fee_percent']:''}}" placeholder="在订单金额基础上附加手续费" autocomplete="off" title="" step="0.001" class="layui-input" >
        </div>
    </div>

    <div class="layui-inline">
        <label class="layui-form-label">固定手续费(选填)</label>
        <div class="layui-input-inline" style="width: 200px;">
{{--            <input type="number" name="handling_fee_fixed" value="{{isset($payment)?sprintf("%.2f", $payment->handling_fee_fixed/100):''}}" autocomplete="off" class="layui-input" placeholder="在订单金额基础上附加手续费" title="" step="0.001">--}}
            <input type="number" name="handling_fee_fixed" value="{{isset($payment['handling_fee_fixed'])?sprintf("%.2f", $payment['handling_fee_fixed']/100):''}}" autocomplete="off" class="layui-input" placeholder="在订单金额基础上附加手续费" title="" step="0.001">
        </div>
    </div>

</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">接口文件</label>
    <div class="layui-input-block">
        <select name="payment" lay-search  lay-filter="payment" lay-verify="required">
            @foreach($pay_method as $first)
                <option value="{{ $first }}" @if(isset($payment)&&$payment['payment']==$first) selected @endif>{{ $first }}</option>
            @endforeach
        </select>
    </div>
</div>


<div class="show_box AlipayF2F @if((isset($payment)&&$payment['payment']=='AlipayF2F') || !isset($payment)) active @endif">

    <div class="layui-form-item">
        <label for="" class="layui-form-label">支付宝APPID</label>
        <div class="layui-input-block">
            <input type="text" name="AlipayF2F[]" value="{{isset($payment['config']['app_id'])&&!empty($payment['config']['app_id'])?$payment['config']['app_id']:''  }}"  class="layui-input" >
        </div>
    </div>


    <div class="layui-form-item">
        <label for="" class="layui-form-label">支付宝私钥</label>
        <div class="layui-input-block">
            <input type="text" name="AlipayF2F[]" value="{{isset($payment['config']['private_key'])&&!empty($payment['config']['private_key'])?$payment['config']['private_key']:''  }}"  class="layui-input" >
        </div>
    </div>

    <div class="layui-form-item">
        <label for="" class="layui-form-label">支付宝公钥</label>
        <div class="layui-input-block">
            <input type="text" name="AlipayF2F[]" value="{{isset($payment['config']['public_key'])&&!empty($payment['config']['public_key'])?$payment['config']['public_key']:''  }}"  class="layui-input" >
        </div>
    </div>


    <div class="layui-form-item">
        <label for="" class="layui-form-label">自定义商品名称</label>
        <div class="layui-input-block">
            <input type="text" name="AlipayF2F[]" placeholder="将会体现在支付宝账单中" value="{{isset($payment['config']['product_name'])&&!empty($payment['config']['product_name'])?$payment['config']['product_name']:''  }}"  class="layui-input" >
        </div>
    </div>

</div>




<div class="show_box BTCPay @if(isset($payment)&&$payment['payment']=='BTCPay') active @endif">

    <div class="layui-form-item">
        <label for="" class="layui-form-label">API接口所在网址(包含最后的斜杠)</label>
        <div class="layui-input-block">
            <input type="text" name="BTCPay[]" value="{{isset($payment['config']['btcpay_url'])&&!empty($payment['config']['btcpay_url'])?$payment['config']['btcpay_url']:''  }}"  class="layui-input" >
        </div>
    </div>


    <div class="layui-form-item">
        <label for="" class="layui-form-label">storeId</label>
        <div class="layui-input-block">
            <input type="text" name="BTCPay[]" value="{{isset($payment['config']['btcpay_storeId'])&&!empty($payment['config']['btcpay_storeId'])?$payment['config']['btcpay_storeId']:''  }}"  class="layui-input" >
        </div>
    </div>

    <div class="layui-form-item">
        <label for="" class="layui-form-label">API KEY</label>
        <div class="layui-input-block">
            <input type="text" name="BTCPay[]" placeholder="个人设置中的API KEY(非商店设置中的)" value="{{isset($payment['config']['btcpay_api_key'])&&!empty($payment['config']['btcpay_api_key'])?$payment['config']['btcpay_api_key']:''  }}"  class="layui-input" >
        </div>
    </div>


    <div class="layui-form-item">
        <label for="" class="layui-form-label">WEBHOOK KEY</label>
        <div class="layui-input-block">
            <input type="text" name="BTCPay[]"  value="{{isset($payment['config']['btcpay_webhook_key'])&&!empty($payment['config']['btcpay_webhook_key'])?$payment['config']['btcpay_webhook_key']:''  }}"  class="layui-input" >
        </div>
    </div>

</div>




<div class="show_box CoinPayments @if(isset($payment)&&$payment['payment']=='CoinPayments') active @endif">

    <div class="layui-form-item">
        <label for="" class="layui-form-label">Merchant ID</label>
        <div class="layui-input-block">
            <input type="text" name="CoinPayments[]" placeholder="商户 ID，填写您在 Account Settings 中得到的 ID" value="{{isset($payment['config']['coinpayments_merchant_id'])&&!empty($payment['config']['coinpayments_merchant_id'])?$payment['config']['coinpayments_merchant_id']:''  }}"  class="layui-input" >
        </div>
    </div>


    <div class="layui-form-item">
        <label for="" class="layui-form-label">IPN Secret</label>
        <div class="layui-input-block">
            <input type="text" name="CoinPayments[]" placeholder="通知密钥，填写您在 Merchant Settings 中自行设置的值" value="{{isset($payment['config']['coinpayments_ipn_secret'])&&!empty($payment['config']['coinpayments_ipn_secret'])?$payment['config']['coinpayments_ipn_secret']:''  }}"  class="layui-input" >
        </div>
    </div>

    <div class="layui-form-item">
        <label for="" class="layui-form-label">货币代码</label>
        <div class="layui-input-block">
            <input type="text" name="CoinPayments[]" placeholder="填写您的货币代码（大写），建议与 Merchant Settings 中的值相同" value="{{isset($payment['config']['coinpayments_currency'])&&!empty($payment['config']['coinpayments_currency'])?$payment['config']['coinpayments_currency']:''  }}"  class="layui-input" >
        </div>
    </div>


</div>





<div class="show_box Coinbase @if(isset($payment)&&$payment['payment']=='Coinbase') active @endif">

    <div class="layui-form-item">
        <label for="" class="layui-form-label">接口地址</label>
        <div class="layui-input-block">
            <input type="text" name="Coinbase[]"  value="{{isset($payment['config']['coinbase_url'])&&!empty($payment['config']['coinbase_url'])?$payment['config']['coinbase_url']:''  }}"  class="layui-input" >
        </div>
    </div>


    <div class="layui-form-item">
        <label for="" class="layui-form-label">API KEY</label>
        <div class="layui-input-block">
            <input type="text" name="Coinbase[]"  value="{{isset($payment['config']['coinbase_api_key'])&&!empty($payment['config']['coinbase_api_key'])?$payment['config']['coinbase_api_key']:''  }}"  class="layui-input" >
        </div>
    </div>

    <div class="layui-form-item">
        <label for="" class="layui-form-label">WEBHOOK KEY</label>
        <div class="layui-input-block">
            <input type="text" name="Coinbase[]"  value="{{isset($payment['config']['coinbase_webhook_key'])&&!empty($payment['config']['coinbase_webhook_key'])?$payment['config']['coinbase_webhook_key']:''  }}"  class="layui-input" >
        </div>
    </div>


</div>



<div class="show_box EPay @if(isset($payment)&&$payment['payment']=='EPay') active @endif">

    <div class="layui-form-item">
        <label for="" class="layui-form-label">URL</label>
        <div class="layui-input-block">
            <input type="text" name="EPay[]"  value="{{isset($payment['config']['url'])&&!empty($payment['config']['url'])?$payment['config']['url']:''  }}"  class="layui-input" >
        </div>
    </div>


    <div class="layui-form-item">
        <label for="" class="layui-form-label">PID</label>
        <div class="layui-input-block">
            <input type="text" name="EPay[]"  value="{{isset($payment['config']['pid'])&&!empty($payment['config']['pid'])?$payment['config']['pid']:''  }}"  class="layui-input" >
        </div>
    </div>

    <div class="layui-form-item">
        <label for="" class="layui-form-label">KEY</label>
        <div class="layui-input-block">
            <input type="text" name="EPay[]"  value="{{isset($payment['config']['key'])&&!empty($payment['config']['key'])?$payment['config']['key']:''  }}"  class="layui-input" >
        </div>
    </div>


</div>



<div class="show_box MGate @if(isset($payment)&&$payment['payment']=='MGate') active @endif">

    <div class="layui-form-item">
        <label for="" class="layui-form-label">API地址</label>
        <div class="layui-input-block">
            <input type="text" name="MGate[]"  value="{{isset($payment['config']['mgate_url'])&&!empty($payment['config']['mgate_url'])?$payment['config']['mgate_url']:''  }}"  class="layui-input" >
        </div>
    </div>


    <div class="layui-form-item">
        <label for="" class="layui-form-label">APPID</label>
        <div class="layui-input-block">
            <input type="text" name="MGate[]"  value="{{isset($payment['config']['mgate_app_id'])&&!empty($payment['config']['mgate_app_id'])?$payment['config']['mgate_app_id']:''  }}"  class="layui-input" >
        </div>
    </div>

    <div class="layui-form-item">
        <label for="" class="layui-form-label">AppSecret</label>
        <div class="layui-input-block">
            <input type="text" name="MGate[]"  value="{{isset($payment['config']['mgate_app_secret'])&&!empty($payment['config']['mgate_app_secret'])?$payment['config']['mgate_app_secret']:''  }}"  class="layui-input" >
        </div>
    </div>

    <div class="layui-form-item">
        <label for="" class="layui-form-label">源货币</label>
        <div class="layui-input-block">
            <input type="text" name="MGate[]" placeholder="默认CNY"  value="{{isset($payment['config']['mgate_source_currency'])&&!empty($payment['config']['mgate_source_currency'])?$payment['config']['mgate_source_currency']:''  }}"  class="layui-input" >
        </div>
    </div>


</div>



<div class="show_box StripeAlipay @if(isset($payment)&&$payment['payment']=='StripeAlipay') active @endif">

    <div class="layui-form-item">
        <label for="" class="layui-form-label">货币单位</label>
        <div class="layui-input-block">
            <input type="text" name="StripeAlipay[]"  value="{{isset($payment['config']['currency'])&&!empty($payment['config']['currency'])?$payment['config']['currency']:''  }}"  class="layui-input" >
        </div>
    </div>


    <div class="layui-form-item">
        <label for="" class="layui-form-label">SK_LIVE</label>
        <div class="layui-input-block">
            <input type="text" name="StripeAlipay[]"  value="{{isset($payment['config']['stripe_sk_live'])&&!empty($payment['config']['stripe_sk_live'])?$payment['config']['stripe_sk_live']:''  }}"  class="layui-input" >
        </div>
    </div>

    <div class="layui-form-item">
        <label for="" class="layui-form-label">WebHook密钥签名</label>
        <div class="layui-input-block">
            <input type="text" name="StripeAlipay[]"  value="{{isset($payment['config']['stripe_webhook_key'])&&!empty($payment['config']['stripe_webhook_key'])?$payment['config']['stripe_webhook_key']:''  }}"  class="layui-input" >
        </div>
    </div>


</div>




<div class="show_box StripeCheckout @if(isset($payment)&&$payment['payment']=='StripeCheckout') active @endif">

    <div class="layui-form-item">
        <label for="" class="layui-form-label">货币单位</label>
        <div class="layui-input-block">
            <input type="text" name="StripeCheckout[]"  value="{{isset($payment['config']['currency'])&&!empty($payment['config']['currency'])?$payment['config']['currency']:''  }}"  class="layui-input" >
        </div>
    </div>


    <div class="layui-form-item">
        <label for="" class="layui-form-label">SK_LIVE</label>
        <div class="layui-input-block">
            <input type="text" name="StripeCheckout[]" placeholder="API 密钥"  value="{{isset($payment['config']['stripe_sk_live'])&&!empty($payment['config']['stripe_sk_live'])?$payment['config']['stripe_sk_live']:''  }}"  class="layui-input" >
        </div>
    </div>

    <div class="layui-form-item">
        <label for="" class="layui-form-label">PK_LIVE</label>
        <div class="layui-input-block">
            <input type="text" name="StripeCheckout[]" placeholder="API 公钥"  value="{{isset($payment['config']['stripe_pk_live'])&&!empty($payment['config']['stripe_pk_live'])?$payment['config']['stripe_pk_live']:''  }}"  class="layui-input" >
        </div>
    </div>

    <div class="layui-form-item">
        <label for="" class="layui-form-label">WebHook 密钥签名</label>
        <div class="layui-input-block">
            <input type="text" name="StripeCheckout[]"  value="{{isset($payment['config']['stripe_webhook_key'])&&!empty($payment['config']['stripe_webhook_key'])?$payment['config']['stripe_webhook_key']:''  }}"  class="layui-input" >
        </div>
    </div>


    <div class="layui-form-item">
        <label for="" class="layui-form-label">自定义字段名称</label>
        <div class="layui-input-block">
            <input type="text" name="StripeCheckout[]" placeholder="例如可设置为“联系方式”，以便及时与客户取得联系"  value="{{isset($payment['config']['stripe_custom_field_name'])&&!empty($payment['config']['stripe_custom_field_name'])?$payment['config']['stripe_custom_field_name']:''  }}"  class="layui-input" >
        </div>
    </div>


</div>



<div class="show_box StripeCredit @if(isset($payment)&&$payment['payment']=='StripeCredit') active @endif">

    <div class="layui-form-item">
        <label for="" class="layui-form-label">货币单位</label>
        <div class="layui-input-block">
            <input type="text" name="StripeCredit[]"  value="{{isset($payment['config']['currency'])&&!empty($payment['config']['currency'])?$payment['config']['currency']:''  }}"  class="layui-input" >
        </div>
    </div>


    <div class="layui-form-item">
        <label for="" class="layui-form-label">SK_LIVE</label>
        <div class="layui-input-block">
            <input type="text" name="StripeCredit[]"   value="{{isset($payment['config']['stripe_sk_live'])&&!empty($payment['config']['stripe_sk_live'])?$payment['config']['stripe_sk_live']:''  }}"  class="layui-input" >
        </div>
    </div>

    <div class="layui-form-item">
        <label for="" class="layui-form-label">PK_LIVE</label>
        <div class="layui-input-block">
            <input type="text" name="StripeCredit[]"   value="{{isset($payment['config']['stripe_pk_live'])&&!empty($payment['config']['stripe_pk_live'])?$payment['config']['stripe_pk_live']:''  }}"  class="layui-input" >
        </div>
    </div>

    <div class="layui-form-item">
        <label for="" class="layui-form-label">WebHook密钥签名</label>
        <div class="layui-input-block">
            <input type="text" name="StripeCredit[]"  value="{{isset($payment['config']['stripe_webhook_key'])&&!empty($payment['config']['stripe_webhook_key'])?$payment['config']['stripe_webhook_key']:''  }}"  class="layui-input" >
        </div>
    </div>

</div>



<div class="show_box StripeWepay @if(isset($payment)&&$payment['payment']=='StripeWepay') active @endif">

    <div class="layui-form-item">
        <label for="" class="layui-form-label">货币单位</label>
        <div class="layui-input-block">
            <input type="text" name="StripeWepay[]"  value="{{isset($payment['config']['currency'])&&!empty($payment['config']['currency'])?$payment['config']['currency']:''  }}"  class="layui-input" >
        </div>
    </div>


    <div class="layui-form-item">
        <label for="" class="layui-form-label">SK_LIVE</label>
        <div class="layui-input-block">
            <input type="text" name="StripeWepay[]"   value="{{isset($payment['config']['stripe_sk_live'])&&!empty($payment['config']['stripe_sk_live'])?$payment['config']['stripe_sk_live']:''  }}"  class="layui-input" >
        </div>
    </div>

    <div class="layui-form-item">
        <label for="" class="layui-form-label">WebHook密钥签名</label>
        <div class="layui-input-block">
            <input type="text" name="StripeCredit[]"  value="{{isset($payment['config']['stripe_webhook_key'])&&!empty($payment['config']['stripe_webhook_key'])?$payment['config']['stripe_webhook_key']:''  }}"  class="layui-input" >
        </div>
    </div>

</div>




<div class="show_box WechatPayNative @if(isset($payment)&&$payment['payment']=='WechatPayNative') active @endif">

    <div class="layui-form-item">
        <label for="" class="layui-form-label">APPID</label>
        <div class="layui-input-block">
            <input type="text" name="WechatPayNative[]" placeholder="绑定微信支付商户的APPID" value="{{isset($payment['config']['app_id'])&&!empty($payment['config']['app_id'])?$payment['config']['app_id']:''  }}"  class="layui-input" >
        </div>
    </div>


    <div class="layui-form-item">
        <label for="" class="layui-form-label">商户号</label>
        <div class="layui-input-block">
            <input type="text" name="WechatPayNative[]" placeholder="微信支付商户号"   value="{{isset($payment['config']['mch_id'])&&!empty($payment['config']['mch_id'])?$payment['config']['mch_id']:''  }}"  class="layui-input" >
        </div>
    </div>

    <div class="layui-form-item">
        <label for="" class="layui-form-label">APIKEY(v1)</label>
        <div class="layui-input-block">
            <input type="text" name="WechatPayNative[]"  value="{{isset($payment['config']['api_key'])&&!empty($payment['config']['api_key'])?$payment['config']['api_key']:''  }}"  class="layui-input" >
        </div>
    </div>

</div>




@if(isset($payment))
<input type="hidden" value="{{$payment['id']}}" name="id">

@endif

<div class="layui-form-item">
    <div class="layui-input-block">
        <button type="submit" class="layui-btn" lay-submit="" lay-filter="formDemo">确 认</button>
        <a  class="layui-btn" href="{{route('admin.payment')}}" >返 回</a>
    </div>
</div>

