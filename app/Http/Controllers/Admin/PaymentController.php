<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\PaymentSave;
use App\Services\PaymentService;
use App\Utils\Helper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function getPaymentMethods()
    {
        $methods = [];
        foreach (glob(base_path('app//Payments') . '/*.php') as $file) {
            array_push($methods, pathinfo($file)['filename']);
        }

        return $methods;
        return response([
            'data' => $methods
        ]);
    }

    public function fetch()
    {
        $payments = Payment::orderBy('sort', 'ASC')->get();
        foreach ($payments as $k => $v) {
            $notifyUrl = url("/api/v1/guest/payment/notify/{$v->payment}/{$v->uuid}");
            if ($v->notify_domain) {
                $parseUrl = parse_url($notifyUrl);
                $notifyUrl = $v->notify_domain . $parseUrl['path'];
            }
            $payments[$k]['notify_url'] = $notifyUrl;
        }
        return response([
            'data' => $payments
        ]);
    }


    public function index(Request $request)
    {

        if($request->get('id')){
            $arr=$request->all();
            $id=$arr['id'];
            if(isset($arr['enable'])){
                $arr=[
                    'enable'=>$arr['enable'],
                    'updated_at'=>time()
                ];
            }

            Payment::find($id)->update($arr);
            return response()->json(['code'=>0,'msg'=>'操作成功']);

        }
        return view('admin.payment.index');

    }

    public function data(Request $request)
    {
        $payments = Payment::orderBy('sort', 'ASC')
            ->paginate($request->get('limit',30))->toArray();
//echo '<pre>';
////print_r($payments);exit;

        foreach ($payments['data'] as $k => &$v) {
            $notifyUrl = url("/api/v1/guest/payment/notify/{$v['payment']}/{$v['uuid']}");
            if ($v['notify_domain']) {
                $parseUrl = parse_url($notifyUrl);
//                $notifyUrl = $v->notify_domain . $parseUrl['path'];
                $notifyUrl = $v['notify_domain'] . $parseUrl['path'];
            }
//            $payments[$k]['notify_url'] = $notifyUrl;
            $v['notify_url'] = $notifyUrl;
        }

//        echo '<pre>';
//print_r($payments);exit;
        $data = [
            'code' => 0,
            'msg'   => '正在请求中...',
            'count' => $payments['total'],
            'data'  => $payments['data']
        ];
        return response()->json($data);
    }


    public function create()
    {
        $pay_method = $this->getPaymentMethods();

        return view('admin.payment.create',compact('pay_method'));
    }

    public function store(Request $request)
    {
        $params=$request->all();
        $arr=[
            'name'=>$params['name'],
            'icon'=>$params['icon'],
            'notify_domain'=>$params['notify_domain'],
            'handling_fee_percent'=>$params['handling_fee_percent'],
            'handling_fee_fixed'=>$params['handling_fee_fixed']*100,
            'payment'=>$params['payment'],
        ];

        if($params['handling_fee_percent']>100 || $params['handling_fee_percent']<0.1){
            return redirect(route('admin.payment.create'))->withErrors(['status'=>'百分比手续费范围须在0.1-100之间']);
        }

        $arr_keys=[
            'AlipayF2F'=>['app_id','private_key','public_key','product_name'],
            'BTCPay'=>['btcpay_url','btcpay_storeId','btcpay_api_key','btcpay_webhook_key'],
            'CoinPayments'=>['coinpayments_merchant_id','coinpayments_ipn_secret','coinpayments_currency'],
            'Coinbase'=>['coinbase_url','coinbase_api_key','coinbase_webhook_key'],
            'EPay'=>['url','pid','key'],
            'MGate'=>['mgate_url','mgate_app_id','mgate_app_secret','mgate_source_currency'],
            'StripeAlipay'=>['currency','stripe_sk_live','stripe_webhook_key'],
            'StripeCheckout'=>['currency','stripe_sk_live','stripe_pk_live','stripe_webhook_key','stripe_custom_field_name'],
            'StripeCredit'=>['currency','stripe_sk_live','stripe_pk_live','stripe_webhook_key'],
            'StripeWepay'=>['currency','stripe_sk_live','stripe_webhook_key'],
            'WechatPayNative'=>['app_id','mch_id','api_key']
        ];
        $config_arr=array_combine($arr_keys[$params['payment']],$params[$params['payment']]);
        $arr['config']=json_encode($config_arr);
        $arr['uuid']=Helper::randomChar(8);
        
        if (!Payment::create($arr)) {
//            abort(500, '创建失败');
            return redirect(route('admin.payment.create'))->withErrors(['status'=>'创建失败']);
        }
        return redirect(route('admin.payment'))->with(['status'=>'添加成功']);

    }

    public function edit($id)
    {
        $payment = Payment::find($id);
        if (!$payment){
            return redirect(route('admin.payment'))->withErrors(['status'=>'支付方式不存在']);
        }
        $payment=$payment->toArray();
        $payment['config']=json_decode($payment['config'],true);
        $pay_method = $this->getPaymentMethods();

        return view('admin.payment.edit',compact('payment','pay_method'));
    }


    public function update(Request $request)
    {
//        $params = $request->validated();
        $params=$request->all();

        $id=$params['id'];
        if ($id>0) {
            $payment = Payment::find($id);
            if (!$payment) {
//                abort(500, '该订阅不存在');
                return redirect(route('admin.payment'))->withErrors(['status'=>'支付方式不存在']);
            }

            if($params['handling_fee_percent']>100 || $params['handling_fee_percent']<0.1){

                return redirect(route('admin.payment'))->withErrors(['status'=>'百分比手续费范围须在0.1-100之间']);
            }

            $arr=[
                'name'=>$params['name'],
                'icon'=>$params['icon'],
                'notify_domain'=>$params['notify_domain'],
                'handling_fee_percent'=>$params['handling_fee_percent'],
                'handling_fee_fixed'=>$params['handling_fee_fixed']*100,
                'payment'=>$params['payment'],
            ];
            $arr_keys=[
                'AlipayF2F'=>['app_id','private_key','public_key','product_name'],
                'BTCPay'=>['btcpay_url','btcpay_storeId','btcpay_api_key','btcpay_webhook_key'],
                'CoinPayments'=>['coinpayments_merchant_id','coinpayments_ipn_secret','coinpayments_currency'],
                'Coinbase'=>['coinbase_url','coinbase_api_key','coinbase_webhook_key'],
                'EPay'=>['url','pid','key'],
                'MGate'=>['mgate_url','mgate_app_id','mgate_app_secret','mgate_source_currency'],
                'StripeAlipay'=>['currency','stripe_sk_live','stripe_webhook_key'],
                'StripeCheckout'=>['currency','stripe_sk_live','stripe_pk_live','stripe_webhook_key','stripe_custom_field_name'],
                'StripeCredit'=>['currency','stripe_sk_live','stripe_pk_live','stripe_webhook_key'],
                'StripeWepay'=>['currency','stripe_sk_live','stripe_webhook_key'],
                'WechatPayNative'=>['app_id','mch_id','api_key']
            ];
            $config_arr=array_combine($arr_keys[$params['payment']],$params[$params['payment']]);
            $arr['config']=json_encode($config_arr);
            $arr['id']=$id;

            $payment->update($arr);

            return redirect(route('admin.payment'))->with(['status'=>'编辑成功']);
        }

    }



    public function destroy(Request $request)
    {
        $ids = $request->get('ids');
        $id=$ids[0];

        $payment = Payment::find($id);
        if(!$payment){
            return response()->json(['code'=>0,'msg'=>'支付方式不存在']);
        }

        if($payment->delete()){
            return response()->json(['code'=>0,'msg'=>'删除成功']);
        }
        return response()->json(['code'=>1,'msg'=>'删除失败']);

    }


    public function getPaymentForm(Request $request)
    {
        $paymentService = new PaymentService($request->input('payment'), $request->input('id'));
        return response([
            'data' => $paymentService->form()
        ]);
    }

    public function show(Request $request)
    {
        $payment = Payment::find($request->input('id'));
        if (!$payment) abort(500, '支付方式不存在');
        $payment->enable = !$payment->enable;
        if (!$payment->save()) abort(500, '保存失败');
        return response([
            'data' => true
        ]);
    }

    public function save(Request $request)
    {
        if (!config('v2board.app_url')) {
            abort(500, '请在站点配置中配置站点地址');
        }
        $params = $request->validate([
            'name' => 'required',
            'icon' => 'nullable',
            'payment' => 'required',
            'config' => 'required',
            'notify_domain' => 'nullable|url',
            'handling_fee_fixed' => 'nullable|integer',
            'handling_fee_percent' => 'nullable|numeric|between:0.1,100'
        ], [
            'name.required' => '显示名称不能为空',
            'payment.required' => '网关参数不能为空',
            'config.required' => '配置参数不能为空',
            'notify_domain.url' => '自定义通知域名格式有误',
            'handling_fee_fixed.integer' => '固定手续费格式有误',
            'handling_fee_percent.between' => '百分比手续费范围须在0.1-100之间'
        ]);
        if ($request->input('id')) {
            $payment = Payment::find($request->input('id'));
            if (!$payment) abort(500, '支付方式不存在');
            try {
                $payment->update($params);
            } catch (\Exception $e) {
                abort(500, $e->getMessage());
            }
            return response([
                'data' => true
            ]);
        }
        $params['uuid'] = Helper::randomChar(8);
        if (!Payment::create($params)) {
            abort(500, '保存失败');
        }
        return response([
            'data' => true
        ]);
    }

    public function drop(Request $request)
    {
        $payment = Payment::find($request->input('id'));
        if (!$payment) abort(500, '支付方式不存在');
        return response([
            'data' => $payment->delete()
        ]);
    }


    public function sort(Request $request)
    {
        $request->validate([
            'ids' => 'required|array'
        ], [
            'ids.required' => '参数有误',
            'ids.array' => '参数有误'
        ]);
        DB::beginTransaction();
        foreach ($request->input('ids') as $k => $v) {
            if (!Payment::find($v)->update(['sort' => $k + 1])) {
                DB::rollBack();
                abort(500, '保存失败');
            }
        }
        DB::commit();
        return response([
            'data' => true
        ]);
    }
}
