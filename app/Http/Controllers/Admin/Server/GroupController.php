<?php

namespace App\Http\Controllers\Admin\Server;

use App\Models\Plan;
use App\Models\ServerShadowsocks;
use App\Models\ServerTrojan;
use App\Models\ServerVmess;
use App\Models\ServerGroup;
use App\Models\V2user as User;
use App\Services\ServerService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GroupController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.server.group.index');
    }


    public function data(Request $request)
    {
        if ($request->input('group_id')) {
            return response([
                'data' => [ServerGroup::find($request->input('group_id'))]
            ]);
        }
//        $serverGroups = ServerGroup::get();
        $serverGroups = ServerGroup::query()->orderBy('created_at','desc')
                        ->paginate($request->get('limit',30))->toArray();
        $serverService = new ServerService();
        $servers = $serverService->getAllServers();
//        echo '<pre>';
//        print_r($serverGroups['data']);
//        var_dump(json_decode($servers[0]['group_id'],true));
//        exit;
        foreach ($serverGroups['data'] as $k => &$v) {
            $v['user_count'] = User::where('group_id', $v['id'])->count();
            $v['server_count'] = 0;
            foreach ($servers as $server) {
//                $server_group_id=json_decode($servers[0]['group_id'],true);
                $server_group_id=$servers[0]['group_id'];
//                if (in_array($v['id'], $server['group_id'])) {
                if (in_array($v['id'], $server_group_id)) {
                    $v['server_count'] = $v['server_count']+1;
                }
            }
        }

        $data = [
            'code' => 0,
            'msg'   => '正在请求中...',
            'count' => $serverGroups['total'],
            'data'  => $serverGroups['data']
        ];
        return response()->json($data);
    }


    public function create()
    {
        return view('admin.server.group.create');
    }

    public function store(Request $request)
    {
//        $data = $request->only(['category_id','title','keywords','description','content','thumb','click']);
//        $article = Article::create($data);
//        if ($article && !empty($request->get('tags')) ){
//            $article->tags()->sync($request->get('tags'));
//        }

        if (empty($request->input('name'))) {
//            abort(500, '组名不能为空');
            return redirect(route('admin.group.create'))->withErrors(['status'=>'组名不能为空']);
        }

        if ($request->input('id')) {
            $serverGroup = ServerGroup::find($request->input('id'));
        } else {
            $serverGroup = new ServerGroup();
        }

        $serverGroup->name = $request->input('name');
        $serverGroup->save();
        return redirect(route('admin.group'))->with(['status'=>'添加成功']);
    }


    public function edit($id)
    {
        $group = ServerGroup::find($id);
        if (!$group){
            return redirect(route('admin.group'))->withErrors(['status'=>'组名不存在']);
        }
        return view('admin.server.group.edit',compact('group'));

    }

    public function update(Request $request, $id)
    {

        if (empty($request->input('name'))) {
            abort(500, '组名不能为空');
        }

        if ($id) {
            $serverGroup = ServerGroup::find($id);
        } else {
            $serverGroup = new ServerGroup();
        }

        $serverGroup->name = $request->input('name');
        if($serverGroup->save()){
            return redirect(route('admin.group'))->with(['status'=>'更新成功']);
        }
        return redirect(route('admin.group'))->withErrors(['status'=>'系统错误']);
    }


    public function destroy(Request $request)
    {
        $ids = $request->get('ids');
        $id=$ids[0];
        if (empty($ids)){
            return response()->json(['code'=>1,'msg'=>'请选择删除项']);
        }


        if ($id) {
            $serverGroup = ServerGroup::find($id);
            if (!$serverGroup) {
//                abort(500, '组不存在');
                return response()->json(['code'=>1,'msg'=>'组不存在']);
            }
        }

        $servers = ServerVmess::all();
        foreach ($servers as $server) {
            if (in_array($id, $server->group_id)) {
                return response()->json(['code'=>1,'msg'=>'该组已被节点所使用，无法删除']);
            }
        }

        if (Plan::where('group_id', $id)->first()) {
            return response()->json(['code'=>1,'msg'=>'该组已被订阅所使用，无法删除']);
        }
        if (User::where('group_id', $id)->first()) {
            return response()->json(['code'=>1,'msg'=>'该组已被用户所使用，无法删除']);
        }

        if($serverGroup->delete()){
            return response()->json(['code'=>0,'msg'=>'删除成功']);
        }
        return response()->json(['code'=>1,'msg'=>'删除失败']);

    }


    public function save(Request $request)
    {
        if (empty($request->input('name'))) {
            abort(500, '组名不能为空');
        }

        if ($request->input('id')) {
            $serverGroup = ServerGroup::find($request->input('id'));
        } else {
            $serverGroup = new ServerGroup();
        }

        $serverGroup->name = $request->input('name');
        return response([
            'data' => $serverGroup->save()
        ]);
    }

    public function drop(Request $request)
    {
        if ($request->input('id')) {
            $serverGroup = ServerGroup::find($request->input('id'));
            if (!$serverGroup) {
                abort(500, '组不存在');
            }
        }

        $servers = ServerVmess::all();
        foreach ($servers as $server) {
            if (in_array($request->input('id'), $server->group_id)) {
                abort(500, '该组已被节点所使用，无法删除');
            }
        }

        if (Plan::where('group_id', $request->input('id'))->first()) {
            abort(500, '该组已被订阅所使用，无法删除');
        }
        if (User::where('group_id', $request->input('id'))->first()) {
            abort(500, '该组已被用户所使用，无法删除');
        }
        return response([
            'data' => $serverGroup->delete()
        ]);
    }
}
