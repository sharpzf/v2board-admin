<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\CouponSave;
use App\Http\Requests\Admin\CouponGenerate;
use App\Models\Plan;
use App\Models\V2user as User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Utils\Helper;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;

class CouponController extends Controller
{

    public function index(Request $request)
    {

        if($request->get('id')){
            $arr=$request->all();
            $id=$arr['id'];
            if(isset($arr['show'])){
                $arr=[
                    'show'=>$arr['show'],
                    'updated_at'=>time()
                ];
            }

            Coupon::find($id)->update($arr);
            return response()->json(['code'=>0,'msg'=>'操作成功']);

        }
        return view('admin.coupon.index');

    }

    public function data(Request $request)
    {

        $coupons = Coupon::orderBy('id', 'DESC')
            ->paginate($request->get('limit',30))->toArray();

        $type_arr=[
            2=>'比例',
            1=>'金额'
        ];
        foreach ($coupons['data'] as $k => &$v) {
            $v['type'] = $type_arr[$v['type']];
            $v['limit_use'] = $v['limit_use']?$v['limit_use']:'无限';
            $v['period_of_validity'] = date('Y-m-d H:i:s',$v['started_at']).' ~ '.date('Y-m-d H:i:s',$v['ended_at']);
        }

        $data = [
            'code' => 0,
            'msg'   => '正在请求中...',
            'count' => $coupons['total'],
            'data'  => $coupons['data']
        ];
        return response()->json($data);
    }

    public function create()
    {
        $limit_period=[
            'month_price'=>'月付',
            'quarter_price'=>'季付',
            'half_year_price'=>'半年付',
            'year_price'=>'年付',
            'two_year_price'=>'两年付',
            'three_year_price'=>'三年付',
            'onetime_price'=>'一次性',
            'reset_price'=>'流量重置包'
        ];
        $plan = Plan::pluck('name','id')->toArray();
        return view('admin.coupon.create',compact('plan','limit_period'));
    }

    public function store(Request $request)
    {
        $params=$request->all();

        if (empty($params['name'])) {
            return redirect(route('admin.coupon.create'))->withErrors(['status'=>'名称不能为空']);
        }
        if (empty($params['value'])) {
            return redirect(route('admin.coupon.create'))->withErrors(['status'=>'金额或比例不能为空']);
        }
        if (empty($params['started_at']) || empty($params['ended_at'])) {
            return redirect(route('admin.coupon.create'))->withErrors(['status'=>'优惠券有效期不能为空']);
        }

        $params['limit_plan_ids']=$params['limit_plan_ids']?json_encode(explode(',',$params['limit_plan_ids'])):null;
        $params['limit_period']=$params['limit_period']?json_encode(explode(',',$params['limit_period'])):null;

        $params['started_at']=strtotime($params['started_at']);
        $params['ended_at']=strtotime($params['ended_at']);

        if (empty($params['code'])) {
            $params['code'] = Helper::randomChar(8);
        }

        if ($params['type']==1) {
            $params['value'] = $params['value']*100;
        }
        unset($params['_token']);
        if (!Coupon::create($params)){
            return redirect(route('admin.coupon.create'))->withErrors(['status'=>'添加失败']);
        }

        return redirect(route('admin.coupon'))->with(['status'=>'添加成功']);
    }

    public function edit($id)
    {
        $coupon = Coupon::find($id)->toArray();
        if (!$coupon){
            return redirect(route('admin.coupon'))->withErrors(['status'=>'优惠卷不存在']);
        }

        $limit_period=[
            'month_price'=>'月付',
            'quarter_price'=>'季付',
            'half_year_price'=>'半年付',
            'year_price'=>'年付',
            'two_year_price'=>'两年付',
            'three_year_price'=>'三年付',
            'onetime_price'=>'一次性',
            'reset_price'=>'流量重置包'
        ];
        $plan = Plan::pluck('name','id')->toArray();

        $coupon['limit_plan_ids']=$coupon['limit_plan_ids']?json_decode($coupon['limit_plan_ids'],true):[];
        $coupon['limit_period']=$coupon['limit_period']?json_decode($coupon['limit_period'],true):[];
        $coupon['started_at']=date('Y-m-d H:i:s',$coupon['started_at']);
        $coupon['ended_at']=date('Y-m-d H:i:s',$coupon['ended_at']);
        $coupon['value']=$coupon['type']==1?sprintf("%.2f", $coupon['value']/100):$coupon['value'];

        return view('admin.coupon.edit',compact('coupon','limit_period','plan'));

    }

    public function update(Request $request, $id)
    {
        $params=$request->all();
        if (empty($params['name'])) {
            return redirect(route('admin.coupon.edit',['id'=>$id]))->withErrors(['status'=>'名称不能为空']);
        }
        if (empty($params['value'])) {
            return redirect(route('admin.coupon.edit',['id'=>$id]))->withErrors(['status'=>'金额或比例不能为空']);
        }
        if (empty($params['started_at']) || empty($params['ended_at'])) {
            return redirect(route('admin.coupon.edit',['id'=>$id]))->withErrors(['status'=>'优惠券有效期不能为空']);
        }

        $params['limit_plan_ids']=$params['limit_plan_ids']?json_encode(explode(',',$params['limit_plan_ids'])):null;
        $params['limit_period']=$params['limit_period']?json_encode(explode(',',$params['limit_period'])):null;

        $params['started_at']=strtotime($params['started_at']);
        $params['ended_at']=strtotime($params['ended_at']);


        if ($params['type']==1) {
            $params['value'] = $params['value']*100;
        }


        unset($params['_method']);
        unset($params['_token']);
        try {
            $coupon = Coupon::find($id);
            if (empty($params['code'])) {
                unset($params['code']);
            }

            $coupon->update($params);
            return redirect(route('admin.coupon'))->with(['status'=>'更新成功']);
        } catch (\Exception $e) {
            return redirect(route('admin.coupon.edit',['id'=>$id]))->withErrors(['status'=>'更新失败']);
        }

    }

    public function destroy(Request $request)
    {
        $ids = $request->get('ids');
        $id=$ids[0];
        if (empty($ids)){
            return response()->json(['code'=>1,'msg'=>'请选择删除项']);
        }

        $coupon = Coupon::find($id);
        if (!$coupon) {
            return response()->json(['code'=>1,'msg'=>'优惠券不存在']);
        }
        if($coupon->delete()){
            return response()->json(['code'=>0,'msg'=>'删除成功']);
        }
        return response()->json(['code'=>1,'msg'=>'删除失败']);

    }

    public function fetch(Request $request)
    {
        $current = $request->input('current') ? $request->input('current') : 1;
        $pageSize = $request->input('pageSize') >= 10 ? $request->input('pageSize') : 10;
        $sortType = in_array($request->input('sort_type'), ['ASC', 'DESC']) ? $request->input('sort_type') : 'DESC';
        $sort = $request->input('sort') ? $request->input('sort') : 'id';
        $builder = Coupon::orderBy($sort, $sortType);
        $total = $builder->count();
        $coupons = $builder->forPage($current, $pageSize)
            ->get();
        return response([
            'data' => $coupons,
            'total' => $total
        ]);
    }

    public function show(Request $request)
    {
        if (empty($request->input('id'))) {
            abort(500, '参数有误');
        }
        $coupon = Coupon::find($request->input('id'));
        if (!$coupon) {
            abort(500, '优惠券不存在');
        }
        $coupon->show = $coupon->show ? 0 : 1;
        if (!$coupon->save()) {
            abort(500, '保存失败');
        }

        return response([
            'data' => true
        ]);
    }

    public function generate(CouponGenerate $request)
    {
        if ($request->input('generate_count')) {
            $this->multiGenerate($request);
            return;
        }

        $params = $request->validated();
        if (!$request->input('id')) {
            if (!isset($params['code'])) {
                $params['code'] = Helper::randomChar(8);
            }
            if (!Coupon::create($params)) {
                abort(500, '创建失败');
            }
        } else {
            try {
                Coupon::find($request->input('id'))->update($params);
            } catch (\Exception $e) {
                abort(500, '保存失败');
            }
        }

        return response([
            'data' => true
        ]);
    }

    private function multiGenerate(CouponGenerate $request)
    {
        $coupons = [];
        $coupon = $request->validated();
        $coupon['created_at'] = $coupon['updated_at'] = time();
        $coupon['show'] = 1;
        unset($coupon['generate_count']);
        for ($i = 0;$i < $request->input('generate_count');$i++) {
            $coupon['code'] = Helper::randomChar(8);
            array_push($coupons, $coupon);
        }
        DB::beginTransaction();
        if (!Coupon::insert(array_map(function ($item) use ($coupon) {
            // format data
            if (isset($item['limit_plan_ids']) && is_array($item['limit_plan_ids'])) {
                $item['limit_plan_ids'] = json_encode($coupon['limit_plan_ids']);
            }
            if (isset($item['limit_period']) && is_array($item['limit_period'])) {
                $item['limit_period'] = json_encode($coupon['limit_period']);
            }
            return $item;
        }, $coupons))) {
            DB::rollBack();
            abort(500, '生成失败');
        }
        DB::commit();
        $data = "名称,类型,金额或比例,开始时间,结束时间,可用次数,可用于订阅,券码,生成时间\r\n";
        foreach($coupons as $coupon) {
            $type = ['', '金额', '比例'][$coupon['type']];
            $value = ['', ($coupon['value'] / 100),$coupon['value']][$coupon['type']];
            $startTime = date('Y-m-d H:i:s', $coupon['started_at']);
            $endTime = date('Y-m-d H:i:s', $coupon['ended_at']);
            $limitUse = $coupon['limit_use'] ?? '不限制';
            $createTime = date('Y-m-d H:i:s', $coupon['created_at']);
            $limitPlanIds = isset($coupon['limit_plan_ids']) ? implode("/", $coupon['limit_plan_ids']) : '不限制';
            $data .= "{$coupon['name']},{$type},{$value},{$startTime},{$endTime},{$limitUse},{$limitPlanIds},{$coupon['code']},{$createTime}\r\n";
        }
        echo $data;
    }

    public function drop(Request $request)
    {
        if (empty($request->input('id'))) {
            abort(500, '参数有误');
        }
        $coupon = Coupon::find($request->input('id'));
        if (!$coupon) {
            abort(500, '优惠券不存在');
        }
        if (!$coupon->delete()) {
            abort(500, '删除失败');
        }

        return response([
            'data' => true
        ]);
    }
}
