<?php

namespace App\Http\Controllers\Admin\Server;

use App\Http\Requests\Admin\ServerShadowsocksSave;
use App\Http\Requests\Admin\ServerShadowsocksUpdate;
use App\Models\ServerRoute;
use App\Models\ServerShadowsocks;
use App\Services\ServerService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RouteController extends Controller
{

    public function index(Request $request)
    {
        return view('admin.server.route.index');
    }

    public function data(Request $request)
    {
//        $serverGroups = ServerGroup::get();
        $routes = ServerRoute::query()
            ->paginate($request->get('limit',30))->toArray();

        $action_arr=[
            'block'=>'禁止访问',
            'dns'=>'指定DNS服务器进行解析',
        ];
        foreach ($routes['data'] as $k => &$route) {
            $array = json_decode($route['match'], true);
//            if (is_array($array)) $route['match'] = $array;
            $route['action']=$action_arr[$route['action']];
            $route['match']='匹配 '.count($array).' 条规则';
//            $route['action']=$action_arr[$route['action']];
        }

        $data = [
            'code' => 0,
            'msg'   => '正在请求中...',
            'count' => $routes['total'],
            'data'  => $routes['data']
        ];
        return response()->json($data);
    }


    public function create()
    {
        return view('admin.server.route.create');
    }

    public function store(Request $request)
    {
        $params=$request->all();


        if (empty($params['remarks'])) {
//            abort(500, '组名不能为空');
            return redirect(route('admin.route.create'))->withErrors(['status'=>'备注不能为空']);
        }

        if (empty($params['match'])) {
//            abort(500, '组名不能为空');
            return redirect(route('admin.route.create'))->withErrors(['status'=>'匹配值不能为空']);
        }


        $params['match']=array_values(explode("\n",$params['match']));
        $params['match']=json_encode($params['match']);
//        $match='[';
//        foreach($params['match'] as  $kk=>$val){
//            if($kk==0){
//                $match.='"'.trim($val).'"';
//            }else{
//                $match.=',"'.trim($val).'"';
//            }
//
//        }
//        $match.=']';
//        $params['match']=$match;

        if (!ServerRoute::create($params)){
            return redirect(route('admin.route.create'))->withErrors(['status'=>'添加失败']);
        }

        return redirect(route('admin.route'))->with(['status'=>'添加成功']);
    }

    public function edit($id)
    {
//        $group = ServerGroup::find($id);

        $route = ServerRoute::find($id)->toArray();
        if (!$route){
            return redirect(route('admin.route'))->withErrors(['status'=>'路由不存在']);
        }
//        if($route['match']){
//            $route['match']=str_replace('"','',$route['match']);
//            $route['match']=rtrim($route['match'],']');
//            $route['match']=ltrim($route['match'],'[');
//        }
//
//        $route['match']=explode(',',$route['match']);
        $route['match']=json_decode($route['match'],true);

        $match_str="";
        foreach($route['match'] as $val){
            $match_str.=$val."\n";
        }
        $route['match']=$match_str;

        return view('admin.server.route.edit',compact('route'));

    }


    public function update(Request $request, $id)
    {

        $params=$request->all();
        if (empty($params['remarks'])) {
//            abort(500, '组名不能为空');
            return redirect(route('admin.route.edit',['id'=>$id]))->withErrors(['status'=>'备注不能为空']);
        }

        if (empty($params['match'])) {
//            abort(500, '组名不能为空');
            return redirect(route('admin.route.edit',['id'=>$id]))->withErrors(['status'=>'匹配值不能为空']);
        }


//        $params['match']=explode("\n",$params['match']);
//        $match='[';
//        foreach($params['match'] as  $kk=>$val){
//            if($kk==0){
//                $match.='"'.trim($val).'"';
//            }else{
//                $match.=',"'.trim($val).'"';
//            }
//
//        }
//        $match.=']';
//        $params['match']=$match;

        $params['match']=array_values(explode("\n",$params['match']));
        $params['match']=json_encode($params['match']);
        unset($params['_method']);
        unset($params['_token']);


        try {
            $route = ServerRoute::find($id);
            $route->update($params);
            return redirect(route('admin.route'))->with(['status'=>'更新成功']);
        } catch (\Exception $e) {
            return redirect(route('admin.route.edit',['id'=>$id]))->withErrors(['status'=>'更新失败']);
        }

    }


    public function destroy(Request $request)
    {
        $ids = $request->get('ids');
        $id=$ids[0];
        if (empty($ids)){
            return response()->json(['code'=>1,'msg'=>'请选择删除项']);
        }


        if ($id) {
//            $serverGroup = ServerGroup::find($id);
            $route = ServerRoute::find($id);
            if (!$route) {
//                abort(500, '组不存在');
                return response()->json(['code'=>1,'msg'=>'路由不存在']);
            }
        }



        if($route->delete()){
            return response()->json(['code'=>0,'msg'=>'删除成功']);
        }
        return response()->json(['code'=>1,'msg'=>'删除失败']);

    }


    public function fetch(Request $request)
    {
        $routes = ServerRoute::get();
        // TODO: remove on 1.8.0
        foreach ($routes as $k => $route) {
            $array = json_decode($route->match, true);
            if (is_array($array)) $routes[$k]['match'] = $array;
        }
        // TODO: remove on 1.8.0
        return [
            'data' => $routes
        ];
    }



    public function save(Request $request)
    {
        $params = $request->validate([
            'remarks' => 'required',
            'match' => 'required|array',
            'action' => 'required|in:block,dns',
            'action_value' => 'nullable'
        ], [
            'remarks.required' => '备注不能为空',
            'match.required' => '匹配值不能为空',
            'action.required' => '动作类型不能为空',
            'action.in' => '动作类型参数有误'
        ]);
        $params['match'] = array_filter($params['match']);
        // TODO: remove on 1.8.0
        $params['match'] = json_encode($params['match']);
        // TODO: remove on 1.8.0
        if ($request->input('id')) {
            try {
                $route = ServerRoute::find($request->input('id'));
                $route->update($params);
                return [
                    'data' => true
                ];
            } catch (\Exception $e) {
                abort(500, '保存失败');
            }
        }
        if (!ServerRoute::create($params)) abort(500, '创建失败');
        return [
            'data' => true
        ];
    }

    public function drop(Request $request)
    {
        $route = ServerRoute::find($request->input('id'));
        if (!$route) abort(500, '路由不存在');
        if (!$route->delete()) abort(500, '删除失败');
        return [
            'data' => true
        ];
    }
}
