<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\OrderAssign;
use App\Http\Requests\Admin\OrderUpdate;
use App\Http\Requests\Admin\OrderFetch;
use App\Models\CommissionLog;
use App\Services\OrderService;
use App\Services\UserService;
use App\Utils\Helper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\V2user as User;
use App\Models\Plan;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;

class OrderController extends Controller
{
    private function filter(Request $request, &$builder)
    {
        if ($request->input('filter')) {
            foreach ($request->input('filter') as $filter) {
                if ($filter['key'] === 'email') {
                    $user = User::where('email', "%{$filter['value']}%")->first();
                    if (!$user) continue;
                    $builder->where('user_id', $user->id);
                    continue;
                }
                if ($filter['condition'] === '模糊') {
                    $filter['condition'] = 'like';
                    $filter['value'] = "%{$filter['value']}%";
                }
                $builder->where($filter['key'], $filter['condition'], $filter['value']);
            }
        }
    }


    public function index(Request $request)
    {

        $user_id=$request->input('user_id',0);
        return view('admin.order.index',compact('user_id'));

    }

    public function data(Request $request)
    {

        $limit_num=$request->get('limit',30);
        $orderModel = Order::orderBy('created_at', 'DESC');
        $param=$request->all();

        if(isset($param['field_name'])&&!empty($param['field_name'])){
//            $where[$param['field_name']]=['like','%'.$param['field_val'].'%'];
            $orderModel=$orderModel->where($param['field_name'],'like','%'.$param['field_val'].'%');
        }

        if(isset($param['user_id'])&&!empty($param['user_id'])){
            $orderModel=$orderModel->where('user_id',$param['user_id']);
        }

        $res = $orderModel->paginate($limit_num)->toArray();

        $period_arr=[
            'month_price'=>'月付',
            'quarter_price'=>'季付',
            'half_year_price'=>'半年付',
            'year_price'=>'年付',
            'two_year_price'=>'两年付',
            'three_year_price'=>'三年付',
            'onetime_price'=>'一次性',
            'reset_price'=>'流量重置包'
        ];

        $type_arr=[
            0=>'新购',
            1=>'新购',
            2=>'续费',
            3=>'变更',
            4=>'流量包',
        ];
        $order_status=[
            '待支付',
            '开通中',
            '已取消',
            '已完成',
            '已折抵'
        ];

        $commission_status=[
            '待确认',
            '发放中',
            '已发放',
            '已驳回'
        ];

        $plan = Plan::get();
//        for ($i = 0; $i < count($res); $i++) {
        for ($i = 0; $i < count($res['data']); $i++) {
            for ($k = 0; $k < count($plan); $k++) {
                if ($plan[$k]['id'] == $res['data'][$i]['plan_id']) {
                    $res['data'][$i]['plan_name'] = $plan[$k]['name'];
                }
                $res['data'][$i]['trade_no_url']=route('admin.order.detail',['id'=>$res['data'][$i]['id']]);
                $res['data'][$i]['cancel_url']=route('admin.order.cancel',['id'=>$res['data'][$i]['id']]);
                $res['data'][$i]['paid_url']=route('admin.order.paid',['id'=>$res['data'][$i]['id']]);
                $res['data'][$i]['set_invalid']=route('admin.order.set',['id'=>$res['data'][$i]['id'],'commission_status'=>3]);
                $res['data'][$i]['set_effective']=route('admin.order.set',['id'=>$res['data'][$i]['id'],'commission_status'=>2]);
                $res['data'][$i]['set_confirmed']=route('admin.order.set',['id'=>$res['data'][$i]['id'],'commission_status'=>0]);


                $res['data'][$i]['trade_no_val']=$this->maskString($res['data'][$i]['trade_no'],3,26);
                $res['data'][$i]['type_val']=$type_arr[$res['data'][$i]['type']];

                $res['data'][$i]['status_val']=$order_status[$res['data'][$i]['status']];
                $res['data'][$i]['period_val']=$period_arr[$res['data'][$i]['period']];
                $res['data'][$i]['total_amount_val']=sprintf("%.2f", $res['data'][$i]['total_amount']/100);
                $res['data'][$i]['commission_balance_val']=($res['data'][$i]['status']==0 ||$res['data'][$i]['status']==2)?'-':sprintf("%.2f", $res['data'][$i]['commission_balance']/100);
                $res['data'][$i]['commission_status_val']=($res['data'][$i]['status']==0 ||$res['data'][$i]['status']==2)?'-':$commission_status[$res['data'][$i]['commission_status']];
                $res['data'][$i]['created_at_val']=date('Y-m-d H:i:s',$res['data'][$i]['created_at']);
//                echo $res['data'][$i]['created_at'];exit;
            }
        }

        $data = [
            'code' => 0,
            'msg'   => '正在请求中...',
            'count' => $res['total'],
            'data'  => $res['data']
        ];
        return response()->json($data);
    }


    protected function maskString($string, $start = 0, $length = null) {
        $mask = str_repeat("*", $length ?: strlen($string));
        return substr_replace($string, $mask, $start, $length);
    }

    public function create($id=0)
    {
        $user=[];
      if($id){
          $user = User::where('id',$id)->select(['email','id'])->first()->toArray();
      }
        $plan = Plan::pluck('name','id')->toArray();
        $period_arr=[
            'month_price'=>'月付',
            'quarter_price'=>'季付',
            'half_year_price'=>'半年付',
            'year_price'=>'年付',
            'two_year_price'=>'两年付',
            'three_year_price'=>'三年付',
            'onetime_price'=>'一次性',
            'reset_price'=>'流量重置包'
        ];

        return view('admin.order.create',compact('plan','period_arr','user'));
    }

    public function store(Request $request)
    {
//        $params = $request->validated();
//        $params=$request->all();
        $plan = Plan::find($request->input('plan_id'));
        $user = User::where('email', $request->input('email'))->first();

        if (!$user) {
//            abort(500, '该用户不存在');
            return redirect(route('admin.order.create',['id'=>$request->input('user_id')]))->withErrors(['status'=>'该用户不存在']);
        }

        if (!$plan) {
//            abort(500, '该订阅不存在');
            return redirect(route('admin.order.create',['id'=>$request->input('user_id')]))->withErrors(['status'=>'该订阅不存在']);
        }

        $userService = new UserService();
        if ($userService->isNotCompleteOrderByUserId($user->id)) {
//            abort(500, '该用户还有待支付的订单，无法分配');
            return redirect(route('admin.order.create',['id'=>$request->input('user_id')]))->withErrors(['status'=>'该用户还有待支付的订单，无法分配']);
        }

        DB::beginTransaction();
        $order = new Order();
        $orderService = new OrderService($order);
        $order->user_id = $user->id;
        $order->plan_id = $plan->id;
        $order->period = $request->input('period');
        $order->trade_no = Helper::guid();
        $order->total_amount = $request->input('total_amount')*100;

        if ($order->period === 'reset_price') {
            $order->type = 4;
        } else if ($user->plan_id !== NULL && $order->plan_id !== $user->plan_id) {
            $order->type = 3;
        } else if ($user->expired_at > time() && $order->plan_id == $user->plan_id) {
            $order->type = 2;
        } else {
            $order->type = 1;
        }

        $orderService->setInvite($user);

        if (!$order->save()) {
            DB::rollback();
//            abort(500, '订单创建失败');
            return redirect(route('admin.order.create',['id'=>$request->input('user_id')]))->withErrors(['status'=>'订单创建失败']);
        }

        DB::commit();
        return redirect(route('admin.order'))->with(['status'=>'添加成功']);

    }



    public function detail(Request $request)
    {
        $order = Order::with(['user'=>function($query){
            $query->select('id','email');
        },'plan'=>function($query){
            $query->select('id','name');
        }])->find($request->input('id'));
//        $order = Order::find($id);
        if (!$order) return redirect(route('admin.order'))->withErrors(['status'=>'订单不存在']);
        $order['commission_log'] = CommissionLog::where('trade_no', $order->trade_no)->get()->toArray();
        if ($order->surplus_order_ids) {
            $order['surplus_orders'] = Order::whereIn('id', $order->surplus_order_ids)->get();
        }
        $order=$order->toArray();

        $period_arr=[
            'month_price'=>'月付',
            'quarter_price'=>'季付',
            'half_year_price'=>'半年付',
            'year_price'=>'年付',
            'two_year_price'=>'两年付',
            'three_year_price'=>'三年付',
            'onetime_price'=>'一次性',
            'reset_price'=>'流量重置包'
        ];
        $order_status=[
           '待支付',
           '开通中',
           '已取消',
           '已完成',
           '已折抵'
        ];

        return view('admin.order.detail',compact('order','period_arr','order_status'));
    }

    public function fetch(OrderFetch $request)
    {
        $current = $request->input('current') ? $request->input('current') : 1;
        $pageSize = $request->input('pageSize') >= 10 ? $request->input('pageSize') : 10;
        $orderModel = Order::orderBy('created_at', 'DESC');
        if ($request->input('is_commission')) {
            $orderModel->where('invite_user_id', '!=', NULL);
            $orderModel->whereNotIn('status', [0, 2]);
            $orderModel->where('commission_balance', '>', 0);
        }
        $this->filter($request, $orderModel);
        $total = $orderModel->count();
        $res = $orderModel->forPage($current, $pageSize)
            ->get();
        $plan = Plan::get();
        for ($i = 0; $i < count($res); $i++) {
            for ($k = 0; $k < count($plan); $k++) {
                if ($plan[$k]['id'] == $res[$i]['plan_id']) {
                    $res[$i]['plan_name'] = $plan[$k]['name'];
                }
            }
        }
        return response([
            'data' => $res,
            'total' => $total
        ]);
    }

    public function paid(Request $request)
    {
        $order = Order::where('id', $request->input('id'))
            ->first();
        if (!$order) {
            return redirect(route('admin.order'))->withErrors(['status'=>'订单不存在']);
//            return response()->json(['code'=>1,'msg'=>'订单不存在']);
        }
//        if ($order->status !== 0) abort(500, '只能对待支付的订单进行操作');
        if ($order->status !== 0)return redirect(route('admin.order'))->withErrors(['status'=>'只能对待支付的订单操作']);

        $orderService = new OrderService($order);
        if (!$orderService->paid('manual_operation')) {
//            abort(500, '更新失败');
            return redirect(route('admin.order'))->withErrors(['status'=>'更新失败']);
        }

        return redirect(route('admin.order'))->with(['status'=>'更新成功']);
//        return response([
//            'data' => true
//        ]);
    }

    public function cancel(Request $request)
    {
//        $order = Order::where('trade_no', $request->input('trade_no'))
        $order = Order::where('id', $request->input('id'))
            ->first();
        if (!$order) {
//            abort(500, '订单不存在');
            return redirect(route('admin.order'))->withErrors(['status'=>'订单不存在']);
//            return response()->json(['code'=>1,'msg'=>'订单不存在']);
        }
        if ($order->status !== 0) return redirect(route('admin.order'))->withErrors(['status'=>'只能对待支付的订单操作']);
//        if ($order->status !== 0)  return response()->json(['code'=>1,'msg'=>'只能对待支付的订单操作']);

        $orderService = new OrderService($order);
        if (!$orderService->cancel()) {
//            abort(500, '更新失败');
//            return response()->json(['code'=>1,'msg'=>'更新失败']);
            return redirect(route('admin.order'))->withErrors(['status'=>'更新失败']);
        }
        return redirect(route('admin.order'))->with(['status'=>'更新成功']);
//        return response()->json(['code'=>0,'msg'=>'更新成功']);
//        return response([
//            'data' => true
//        ]);
    }

//    public function set(OrderUpdate $request)
    public function update(Request $request)
    {
//        $params = $request->only([
//            'commission_status'
//        ]);
        $params=$request->all();


        $order = Order::where('id', $request->input('id'))
            ->first();
        if (!$order) {
//            abort(500, '订单不存在');
            return redirect(route('admin.order'))->withErrors(['status'=>'订单不存在']);
//            return response()->json(['code'=>1,'msg'=>'订单不存在']);
        }

        try {
            $order->update($params);
        } catch (\Exception $e) {
//            abort(500, '更新失败');
            return redirect(route('admin.order'))->withErrors(['status'=>'更新失败']);
//            return response()->json(['code'=>1,'msg'=>'更新失败']);
        }
        return redirect(route('admin.order'))->with(['status'=>'更新成功']);

//        return response([
//            'data' => true
//        ]);
    }

    public function assign(OrderAssign $request)
    {
        $plan = Plan::find($request->input('plan_id'));
        $user = User::where('email', $request->input('email'))->first();

        if (!$user) {
            abort(500, '该用户不存在');
        }

        if (!$plan) {
            abort(500, '该订阅不存在');
        }

        $userService = new UserService();
        if ($userService->isNotCompleteOrderByUserId($user->id)) {
            abort(500, '该用户还有待支付的订单，无法分配');
        }

        DB::beginTransaction();
        $order = new Order();
        $orderService = new OrderService($order);
        $order->user_id = $user->id;
        $order->plan_id = $plan->id;
        $order->period = $request->input('period');
        $order->trade_no = Helper::guid();
        $order->total_amount = $request->input('total_amount');

        if ($order->period === 'reset_price') {
            $order->type = 4;
        } else if ($user->plan_id !== NULL && $order->plan_id !== $user->plan_id) {
            $order->type = 3;
        } else if ($user->expired_at > time() && $order->plan_id == $user->plan_id) {
            $order->type = 2;
        } else {
            $order->type = 1;
        }

        $orderService->setInvite($user);

        if (!$order->save()) {
            DB::rollback();
            abort(500, '订单创建失败');
        }

        DB::commit();

        return response([
            'data' => $order->trade_no
        ]);
    }
}
