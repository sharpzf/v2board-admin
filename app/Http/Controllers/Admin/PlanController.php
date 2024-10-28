<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\PlanSave;
use App\Http\Requests\Admin\PlanSort;
use App\Http\Requests\Admin\PlanUpdate;
use App\Services\PlanService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Order;
use App\Models\V2user as User;
use Illuminate\Support\Facades\DB;
use App\Models\ServerGroup;


class PlanController extends Controller
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
            if(isset($arr['renew'])){
                $arr=[
                    'renew'=>$arr['renew'],
                    'updated_at'=>time()
                ];
            }

            Plan::find($id)->update($arr);
            return response()->json(['code'=>0,'msg'=>'操作成功']);

        }
        return view('admin.plan.index');

    }

    public function data(Request $request)
    {
        $counts = PlanService::countActiveUsers();
//        $plans = Plan::orderBy('sort', 'ASC')->get();
        $plans = Plan::orderBy('sort', 'ASC')
            ->with(['group'])
            ->paginate($request->get('limit',30))->toArray();


        foreach ($plans['data'] as $k => &$v) {
//            $plans['data'][$k]->count = 0;
//            $plans['data'][$k]['count'] = 0;
            $v['count'] = 0;
            $v['transfer_enable'] .= ' GB';
            $v['month_price'] = sprintf("%.2f", $v['month_price']/100);
            $v['quarter_price'] = sprintf("%.2f", $v['quarter_price']/100);
            $v['half_year_price'] = sprintf("%.2f", $v['half_year_price']/100);
            $v['year_price'] = sprintf("%.2f", $v['year_price']/100);
            $v['two_year_price'] = sprintf("%.2f", $v['two_year_price']/100);
            $v['three_year_price'] = sprintf("%.2f", $v['three_year_price']/100);
            $v['onetime_price'] = sprintf("%.2f", $v['onetime_price']/100);
            $v['reset_price'] = sprintf("%.2f", $v['reset_price']/100);
            foreach ($counts as $kk => $vv) {
//                if ($plans[$k]->id === $counts[$kk]->plan_id) $plans[$k]->count = $counts[$kk]->count;
//                if ($plans['data'][$k]['id'] === $counts[$kk]->plan_id) $plans['data'][$k]['count'] = $counts[$kk]->count;
                if ($v['id'] === $counts[$kk]->plan_id) $v['count'] = $counts[$kk]->count;
            }
        }
//        return response([
//            'data' => $plans
//        ]);
//        echo '<pre>';
//        print_r($plans);exit;
        $data = [
            'code' => 0,
            'msg'   => '正在请求中...',
            'count' => $plans['total'],
            'data'  => $plans['data']
        ];
        return response()->json($data);
    }

    public function create()
    {
        $serverGroups = ServerGroup::get()->toArray();
        $reset_traffic_method=[
            NULL=>'跟随系统设置',
            0=>'每月1号',
            1=>'按月重置',
            2=>'不重置',
            3=>'每年1月1日',
            4=>'按年重置',
        ];
//        $plan=[];
        return view('admin.plan.create',compact('serverGroups','reset_traffic_method'));
    }

    public function store(Request $request)
    {
//        $params = $request->validated();
        $params=$request->all();

        $arr=$params;
        unset($arr['_token']);
        if(isset($arr['force_update'])){
            unset($arr['force_update']);
        }
        $keys_arr=['month_price','quarter_price','half_year_price','year_price','two_year_price','three_year_price','onetime_price','reset_price'];

        foreach($arr as $kk=>$val){
            if(in_array($kk,$keys_arr) && $val>0){
                $arr[$kk]*=100;
            }
        }


        if (!Plan::create($arr)) {
//            abort(500, '创建失败');
            return redirect(route('admin.plan.create'))->withErrors(['status'=>'创建失败']);
        }
        return redirect(route('admin.plan'))->with(['status'=>'添加成功']);

    }

    public function edit($id)
    {
        $plan = Plan::find($id);
        if (!$plan){
            return redirect(route('admin.plan'))->withErrors(['status'=>'订阅不存在']);
        }
        $serverGroups = ServerGroup::get()->toArray();
        $reset_traffic_method=[
            NULL=>'跟随系统设置',
            0=>'每月1号',
            1=>'按月重置',
            2=>'不重置',
            3=>'每年1月1日',
            4=>'按年重置',
        ];
        return view('admin.plan.edit',compact('plan','serverGroups','reset_traffic_method'));

    }


    public function update(Request $request)
    {
//        $params = $request->validated();
        $params=$request->all();

        $arr=$params;
        $id=$arr['id'];
        if ($id>0) {
            $plan = Plan::find($id);
            if (!$plan) {
//                abort(500, '该订阅不存在');
                return redirect(route('admin.plan'))->withErrors(['status'=>'订阅不存在']);
            }
            DB::beginTransaction();
            // update user group id and transfer
            try {
//                if ($request->input('force_update')) {
                if (isset($arr['force_update'])) {
                    User::where('plan_id', $plan->id)->update([
                        'group_id' => $params['group_id'],
                        'transfer_enable' => $params['transfer_enable'] * 1073741824,
                        'speed_limit' => $params['speed_limit']
                    ]);
                }

                unset($arr['_token']);
                if(isset($arr['force_update'])){
                    unset($arr['force_update']);
                }

                $keys_arr=['month_price','quarter_price','half_year_price','year_price','two_year_price','three_year_price','onetime_price','reset_price'];

                foreach($arr as $kk=>$val){
                    if(in_array($kk,$keys_arr) && $val>0){
                        $arr[$kk]*=100;
                    }
                }
                $plan->update($arr);
            } catch (\Exception $e) {
                DB::rollBack();
//                abort(500, '保存失败');
                return redirect(route('admin.plan'))->withErrors(['status'=>'保存失败']);
            }
            DB::commit();
            return redirect(route('admin.plan'))->with(['status'=>'编辑成功']);
        }
//        if (!Plan::create($params)) {
//            abort(500, '创建失败');
//        }
//        return response([
//            'data' => true
//        ]);
    }



    public function destroy(Request $request)
    {
        $ids = $request->get('ids');
        $id=$ids[0];

        if (empty($ids)){
            return response()->json(['code'=>1,'msg'=>'请选择删除项']);
        }

        if (Order::where('plan_id', $id)->first()) {
//            abort(500, '该订阅下存在订单无法删除');
            return response()->json(['code'=>1,'msg'=>'该订阅下存在订单无法删除']);
        }
        if (User::where('plan_id', $id)->first()) {
//            abort(500, '该订阅下存在用户无法删除');
            return response()->json(['code'=>1,'msg'=>'该订阅下存在用户无法删除']);
        }
        if ($id) {
            $plan = Plan::find($id);
            if (!$plan) {
//                abort(500, '该订阅ID不存在');
                return response()->json(['code'=>1,'msg'=>'该订阅ID不存在']);
            }
        }




        if($plan->delete()){
            return response()->json(['code'=>0,'msg'=>'删除成功']);
        }
        return response()->json(['code'=>1,'msg'=>'删除失败']);

    }
    public function drop(Request $request)
    {
        if (Order::where('plan_id', $request->input('id'))->first()) {
            abort(500, '该订阅下存在订单无法删除');
        }
        if (User::where('plan_id', $request->input('id'))->first()) {
            abort(500, '该订阅下存在用户无法删除');
        }
        if ($request->input('id')) {
            $plan = Plan::find($request->input('id'));
            if (!$plan) {
                abort(500, '该订阅ID不存在');
            }
        }
        return response([
            'data' => $plan->delete()
        ]);
    }

    public function update111(PlanUpdate $request)
    {
        $updateData = $request->only([
            'show',
            'renew'
        ]);

        $plan = Plan::find($request->input('id'));
        if (!$plan) {
            abort(500, '该订阅不存在');
        }

        try {
            $plan->update($updateData);
        } catch (\Exception $e) {
            abort(500, '保存失败');
        }

        return response([
            'data' => true
        ]);
    }

    public function sort(PlanSort $request)
    {
        DB::beginTransaction();
        foreach ($request->input('plan_ids') as $k => $v) {
            if (!Plan::find($v)->update(['sort' => $k + 1])) {
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
