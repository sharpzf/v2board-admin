<?php

namespace App\Http\Controllers\Admin\Server;

use App\Http\Requests\Admin\ServerShadowsocksSave;
use App\Http\Requests\Admin\ServerShadowsocksUpdate;
use App\Models\ServerShadowsocks;
use App\Models\ServerHysteria;
use App\Models\ServerTrojan;
use App\Models\ServerVmess;
use App\Models\Node;
use App\Services\ServerService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Utils\CacheKey;
use App\Models\ServerGroup;
use App\Models\ServerRoute;

class NodeController extends Controller
{


    public function index(Request $request)
    {
        if($request->input('id')){
            $arr=$request->all();
//            $id=$request->input('id');
            if(isset($arr['show'])){
                $arr=[
                    'show'=>$arr['show'],
                    'updated_at'=>time()
                ];
            }

            DB::beginTransaction();
            try {
                $info = Node::find($request->input('id'));



                if($info->type==0){
                    $server1 = ServerShadowsocks::where('server_id',$info->id)->first();
                    $server1->update($arr);
                }elseif($info->type==1){
                    $server1 = ServerVmess::where('server_id',$info->id)->first();
                    $server1->update($arr);
//                    ServerVmess::find($info->server_id)->update($arr);
                }elseif($info->type==2){
                    $server1 = ServerTrojan::where('server_id',$info->id)->first();
                    $server1->update($arr);
//                    ServerTrojan::find($info->server_id)->update($arr);
                }elseif($info->type==3){
                    $server1 = ServerHysteria::where('server_id',$info->id)->first();
                    $server1->update($arr);
//                    ServerHysteria::find($info->server_id)->update($arr);
                }

                $info->update($arr);
            } catch (\Exception $e) {
                DB::rollBack();
//                abort(500, '保存失败');
                return response()->json(['code'=>1,'msg'=>'操作失败']);
            }
            DB::commit();
            return response()->json(['code'=>0,'msg'=>'操作成功']);

        }
        $node_arr=[
            'shadowsocks','vmess','trojan','hysteria'
        ];
        return view('admin.server.node.index',compact('node_arr'));
    }

    public function data(Request $request)
    {

        $model = Node::with([
            'shadowsocks'=>function($query){
                $query->select(['id','parent_id','server_id']);
             },'vmess'=>function($query){
                $query->select(['id','parent_id','server_id']);
                },'trojan'=>function($query){
                $query->select(['id','parent_id','server_id']);
            },'hysteria'=>function($query){
                $query->select(['id','parent_id','server_id']);
            }
        ])->orderBy('created_at', 'DESC');
        $params=$request->all();
        if (isset($params['type']) && $params['type']>-1) {
            $model->where('type', $params['type']);
        }
        if (isset($params['name']) && $params['name']) {
            $model->where('name','like','%'.$params['name'].'%');
        }

        $nodes=$model->paginate($request->get('limit',30))->toArray();

        $node_arr=[
            'shadowsocks','vmess','trojan','hysteria'
        ];
        $server_group=ServerGroup::pluck('name','id')->toArray();


        foreach ($nodes['data'] as $k => &$v) {
            $type_name=$node_arr[$v['type']];
//            $v['id']=$v['parent_id']>0?$v['id'].' => '.$v['parent_id']:$v['id'];
            $v['id_show']=$v['parent_id']>0?$v[$type_name]['id'].' => '.$v[$type_name]['parent_id']:$v[$type_name]['id'];
            $v['address']=$v['host'].':'.$v['port'];
            $v['rate']=$v['rate'].' x';
            $serverType=strtoupper($type_name);

//            $v['online']=Cache::get(CacheKey::get("SERVER_{$serverType}_ONLINE_USER", $v['parent_id'] ?? $v['id']));
            $v['online']=Cache::get(CacheKey::get("SERVER_{$serverType}_ONLINE_USER", $v[$type_name]['parent_id'] ?? $v[$type_name]['id']));

            $v['last_check_at'] = Cache::get(CacheKey::get("SERVER_{$serverType}_LAST_CHECK_AT", $v[$type_name]['parent_id'] ?? $v[$type_name]['id']));
            $v['last_push_at'] = Cache::get(CacheKey::get("SERVER_{$serverType}_LAST_PUSH_AT", $v[$type_name]['parent_id'] ?? $v[$type_name]['id']));
            if ((time() - 300) >= $v['last_check_at']) {
                $v['available_status'] = 0;
            } else if ((time() - 300) >= $v['last_push_at']) {
                $v['available_status'] = 1;
            } else {
                $v['available_status'] = 2;
            }


//            $group_arr=json_decode($v['group_id'],true);
            $group_arr=$v['group_id'];
            $group_name='';
            $v['group_name']=$group_name;
            if(!empty($group_arr)){
                foreach($group_arr as $val){
                    $group_name.=$server_group[$val].',';
                }
                $group_name=rtrim($group_name,',');
                $v['group_name']=$group_name;
            }
        }

        $data = [
            'code' => 0,
            'msg'   => '正在请求中...',
            'count' => $nodes['total'],
            'data'  => $nodes['data']
        ];
        return response()->json($data);
    }
    public function create(Request $request)
    {
        $param=$request->all();

        $groups=ServerGroup::pluck('name','id')->toArray();
        $routes=ServerRoute::pluck('remarks','id')->toArray();
        $type=$param['type'];

        if($type==0){
            $parents=ServerShadowsocks::pluck('name','id')->toArray();
            return view('admin.server.node.create0',compact('groups','routes','parents','type'));
        }elseif ($type==1){
            $parents=ServerVmess::pluck('name','id')->toArray();
            return view('admin.server.node.create1',compact('groups','routes','parents','type'));
        }elseif ($type==2){
            $parents=ServerTrojan::pluck('name','id')->toArray();
            return view('admin.server.node.create2',compact('groups','routes','parents','type'));
        }elseif ($type==3){
            $parents=ServerHysteria::pluck('name','id')->toArray();
            return view('admin.server.node.create3',compact('groups','routes','parents','type'));
        }


    }
    public function store(Request $request)
    {
        $params=$request->all();

        if (empty($params['name'])) {
            return redirect(route('admin.node.create',['type'=>$params['type']]))->withErrors(['status'=>'节点名称不能为空']);
        }

        if (empty($params['group_id'])) {
            return redirect(route('admin.node.create',['type'=>$params['type']]))->withErrors(['status'=>'权限组不能为空']);
        }

        if (empty($params['host'])) {
            return redirect(route('admin.node.create',['type'=>$params['type']]))->withErrors(['status'=>'节点地址不能为空']);
        }
        if (empty($params['port'])) {
            return redirect(route('admin.node.create',['type'=>$params['type']]))->withErrors(['status'=>'连接端口不能为空']);
        }
        if (empty($params['server_port'])) {
            return redirect(route('admin.node.create',['type'=>$params['type']]))->withErrors(['status'=>'服务端口不能为空']);
        }
        $arr=[
//            'group_id'=>json_encode(explode(',',$params['group_id'])),
            'group_id'=>explode(',',$params['group_id']),
            'name'=>$params['name'],
            'rate'=>$params['rate']?$params['rate']:'',
            'host'=>$params['host'],
            'port'=>$params['port'],
            'server_port'=>$params['server_port'],
            'parent_id'=>$params['parent_id']?$params['parent_id']:null,
//            'route_id'=>$params['route_id']?json_encode(array_values(explode(',',$params['route_id']))):null,
            'route_id'=>$params['route_id']?array_values(explode(',',$params['route_id'])):null,
//            'tags'=>$params['tags']?json_encode(array_values(explode(',',$params['tags']))):null,
            'tags'=>$params['tags']?array_values(explode(',',$params['tags'])):null,
        ];


        if($params['type']==0){
            $obfs_settings['path']=!empty($params['path1'])?$params['path1']:null;
            $obfs_settings['host']=!empty($params['host1'])?$params['host1']:null;

            $arr['cipher']=$params['cipher'];
            $arr['obfs']=$params['obfs']?$params['obfs']:'';
            $arr['obfs_settings']=json_encode($obfs_settings);

        }elseif($params['type']==1){

            $tlsSettings['serverName']=!empty($params['serverName'])?$params['serverName']:null;
            $tlsSettings['allowInsecure']=isset($params['allowInsecure'])?1:0;

            $arr['tls']=$params['tls'];
            $arr['network']=$params['network'];
            $arr['networkSettings']=$params['networkSettings']?json_encode($params['networkSettings']):null;
            $arr['tlsSettings']=json_encode($tlsSettings);


        }elseif ($params['type']==2){
            $arr['allow_insecure']=$params['insecure'];
            $arr['server_name']=$params['server_name']?$params['server_name']:'';

        }elseif ($params['type']==3){
            if (empty($params['up_mbps'])) {
                return redirect(route('admin.node.create',['type'=>$params['type']]))->withErrors(['status'=>'上行带宽不能为空']);
            }

            if (empty($params['down_mbps'])) {
                return redirect(route('admin.node.create',['type'=>$params['type']]))->withErrors(['status'=>'下行带宽不能为空']);
            }

            $arr['insecure']=$params['insecure'];
            $arr['server_name']=$params['server_name']?$params['server_name']:'';
            $arr['up_mbps']=$params['up_mbps'];
            $arr['down_mbps']=$params['down_mbps'];
        }


        DB::beginTransaction();
        try {
            $arr1=$arr;
            $arr1['type']=$params['type'];

            $res=Node::create($arr1);

            $arr['server_id']=$res->id;

            if($params['type']==0){
                ServerShadowsocks::create($arr);
            }elseif($params['type']==1){
                ServerVmess::create($arr);
//                ServerVmess::find($info->server_id)->update($arr);
            }elseif($params['type']==2){
                ServerTrojan::create($arr);
//                ServerTrojan::find($info->server_id)->update($arr);
            }elseif($params['type']==3){
                ServerHysteria::create($arr);
//                ServerHysteria::find($info->server_id)->update($arr);
            }


        } catch (\Exception $e) {
            DB::rollBack();
//            echo '<pre>';print_r($e->getMessage());exit;
            return redirect(route('admin.node.create',['type'=>$params['type']]))->withErrors(['status'=>'操作失败']);
        }
        DB::commit();
        return redirect(route('admin.node'))->with(['status'=>'添加成功']);

    }




    public function edit($id)
    {
        $node = Node::find($id)->toArray();
        if (!$node){
            return redirect(route('admin.node'))->withErrors(['status'=>'节点不存在']);
        }


        $groups=ServerGroup::pluck('name','id')->toArray();
        $routes=ServerRoute::pluck('remarks','id')->toArray();
//        $node['tags']=!empty($node['tags'])?implode(',',json_decode($node['tags'],true)):$node['tags'];
        $node['tags']=!empty($node['tags'])?implode(',',$node['tags']):$node['tags'];
//        $node['group_id']=json_decode($node['group_id'],true);
//        $node['group_id']=$node['group_id'];
//        $node['route_id']=!empty($node['route_id'])?json_decode($node['route_id'],true):$node['route_id'];
//        $node['route_id']=!empty($node['route_id'])?$node['route_id']:$node['route_id'];

        if($node['type']==0){
            $node['obfs_settings']=json_decode($node['obfs_settings'],true);
            $parents=ServerShadowsocks::where('server_id','<>',$node['id'])->select(['name','id'])->get()->toArray();
            $parents=array_column($parents,'name','id');
            return view('admin.server.node.edit0',compact('node','groups','routes','parents'));
        }elseif ($node['type']==1){
            $node['tlsSettings']=!empty($node['tlsSettings'])?json_decode($node['tlsSettings'],true):$node['tlsSettings'];
            $node['networkSettings']=!empty($node['networkSettings'])?json_decode($node['networkSettings'],true):$node['networkSettings'];
            $parents=ServerVmess::where('server_id','<>',$node['id'])->select(['name','id'])->get()->toArray();
            $parents=array_column($parents,'name','id');
            return view('admin.server.node.edit1',compact('node','groups','routes','parents'));
        }elseif ($node['type']==2){
            $parents=ServerTrojan::where('server_id','<>',$node['id'])->select(['name','id'])->get()->toArray();
            $parents=array_column($parents,'name','id');
            return view('admin.server.node.edit2',compact('node','groups','routes','parents'));
        }elseif ($node['type']==3){

            $parents=ServerHysteria::where('server_id','<>',$node['id'])->select(['name','id'])->get()->toArray();
            $parents=array_column($parents,'name','id');
            return view('admin.server.node.edit3',compact('node','groups','routes','parents'));
        }

//        return view('admin.server.group.edit',compact('node'));

    }


    public function update(Request $request)
    {

        $params=$request->all();

        if (empty($params['name'])) {
            return redirect(route('admin.node.edit',['id'=>$params['id']]))->withErrors(['status'=>'节点名称不能为空']);
        }

        if (empty($params['group_id'])) {
            return redirect(route('admin.node.edit',['id'=>$params['id']]))->withErrors(['status'=>'权限组不能为空']);
        }

        if (empty($params['host'])) {
            return redirect(route('admin.node.edit',['id'=>$params['id']]))->withErrors(['status'=>'节点地址不能为空']);
        }
        if (empty($params['port'])) {
            return redirect(route('admin.node.edit',['id'=>$params['id']]))->withErrors(['status'=>'连接端口不能为空']);
        }
        if (empty($params['server_port'])) {
            return redirect(route('admin.node.edit',['id'=>$params['id']]))->withErrors(['status'=>'服务端口不能为空']);
        }

        $arr=[
//            'group_id'=>json_encode(explode(',',$params['group_id'])),
            'group_id'=>explode(',',$params['group_id']),
            'name'=>$params['name'],
            'rate'=>$params['rate']?$params['rate']:'',
            'host'=>$params['host'],
            'port'=>$params['port'],
            'server_port'=>$params['server_port'],
            'parent_id'=>$params['parent_id']?$params['parent_id']:null,
//            'route_id'=>$params['route_id']?json_encode(array_values(explode(',',$params['route_id']))):null,
            'route_id'=>$params['route_id']?array_values(explode(',',$params['route_id'])):null,
//            'tags'=>$params['tags']?json_encode(array_values(explode(',',$params['tags']))):null,
            'tags'=>$params['tags']?array_values(explode(',',$params['tags'])):null,
            'updated_at'=>time()
        ];

//        print_r($arr);exit;


        if($params['type']==0){

            $obfs_settings['path']=!empty($params['path1'])?$params['path1']:null;
            $obfs_settings['host']=!empty($params['host1'])?$params['host1']:null;

            $arr['cipher']=$params['cipher'];
            $arr['obfs']=$params['obfs']?$params['obfs']:'';
            $arr['obfs_settings']=json_encode($obfs_settings);


        }elseif ($params['type']==1){
            $tlsSettings['serverName']=!empty($params['serverName'])?$params['serverName']:null;
            $tlsSettings['allowInsecure']=isset($params['allowInsecure'])?1:0;

            $arr['tls']=$params['tls'];
            $arr['network']=$params['network'];
            $arr['networkSettings']=$params['networkSettings']?json_encode($params['networkSettings']):null;
            $arr['tlsSettings']=json_encode($tlsSettings);

        }elseif ($params['type']==2){
            $arr['allow_insecure']=$params['insecure'];
            $arr['server_name']=$params['server_name']?$params['server_name']:'';
        }elseif ($params['type']==3){
            if (empty($params['up_mbps'])) {
                return redirect(route('admin.node.edit',['id'=>$params['id']]))->withErrors(['status'=>'上行带宽不能为空']);
//                return redirect(route('admin.node.create',['type'=>$params['type']]))->withErrors(['status'=>'上行带宽不能为空']);
            }
            if (empty($params['down_mbps'])) {
                return redirect(route('admin.node.edit',['id'=>$params['id']]))->withErrors(['status'=>'下行带宽不能为空']);
//                return redirect(route('admin.node.create',['type'=>$params['type']]))->withErrors(['status'=>'下行带宽不能为空']);
            }

            $arr['insecure']=$params['insecure'];
            $arr['server_name']=$params['server_name']?$params['server_name']:'';
            $arr['up_mbps']=$params['up_mbps'];
            $arr['down_mbps']=$params['down_mbps'];
        }


        DB::beginTransaction();
        try {
            $server = Node::find($request->input('id'));
            if($params['type']==0){
                $server1 = ServerShadowsocks::where('server_id',$server->id)->first();
                $server1->update($arr);
            }elseif($params['type']==1){
//                ServerVmess::create($arr);
                $server1 = ServerVmess::where('server_id',$server->id)->first();
                $server1->update($arr);
//                ServerVmess::find($info->server_id)->update($arr);
            }elseif($params['type']==2){
                $server1 = ServerTrojan::where('server_id',$server->id)->first();
                $server1->update($arr);
//                ServerTrojan::create($arr);
//                ServerTrojan::find($info->server_id)->update($arr);
            }elseif($params['type']==3){
                $server1 = ServerHysteria::where('server_id',$server->id)->first();
                $server1->update($arr);
//                ServerHysteria::create($arr);
//                ServerHysteria::find($info->server_id)->update($arr);
            }
            $server->update($arr);

        } catch (\Exception $e) {
            DB::rollBack();
//            echo '<pre>';print_r($e->getMessage());exit;
            return redirect(route('admin.node.edit',['id'=>$params['id']]))->withErrors(['status'=>'操作失败']);
        }
        DB::commit();
        return redirect(route('admin.node'))->with(['status'=>'更新成功']);

    }

    public function drop(Request $request)
    {
        if ($request->input('id')) {
            $server = ServerShadowsocks::find($request->input('id'));
            if (!$server) {
                abort(500, '节点ID不存在');
            }
        }
        return response([
            'data' => $server->delete()
        ]);
    }


    public function copy(Request $request)
    {
        $ids = $request->get('ids');
        $id=$ids[0];
        $server = Node::find($id)->toArray();
        $server['show'] = 0;

        DB::beginTransaction();
        try {
            $res=Node::create($server);
            $server['server_id']=$res->id;

            if($server['type']==0){
                ServerShadowsocks::create($server);
            }elseif($server['type']==1){
                ServerVmess::create($server);
//                ServerVmess::find($server->server_id)->update($arr);
//                $info=ServerVmess::find($server->server_id);
//                $info->show = 0;
//                $res=ServerVmess::create($info->toArray());
            }elseif($server['type']==2){
                ServerTrojan::create($server);
//                ServerTrojan::find($server->server_id)->update($arr);
//                $info=ServerTrojan::find($server->server_id);
//                $info->show = 0;
//                $res=ServerTrojan::create($info->toArray());
            }elseif($server['type']==3){
                ServerHysteria::create($server);
//                ServerHysteria::find($server->server_id)->update($arr);
//                $info=ServerHysteria::find($server->server_id);
//                $info->show = 0;
//                $res=ServerHysteria::create($info->toArray());
            }



        } catch (\Exception $e) {
            DB::rollBack();
//                abort(500, '保存失败');
            return response()->json(['code'=>1,'msg'=>'复制失败']);
        }
        DB::commit();
        return response()->json(['code'=>0,'msg'=>'复制成功']);



//        if (!ServerShadowsocks::create($server->toArray())) {
//            abort(500, '复制失败');
//        }
//
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
        $info = Node::find($id);
        if (!$info) {
            return response()->json(['code'=>1,'msg'=>'节点不存在']);
        }


        DB::beginTransaction();
        try {
            if($info->type==0){
//                $info=ServerShadowsocks::find($server->server_id);
//                $info::delete();
                $server1 = ServerShadowsocks::where('server_id',$info->id)->first();
                $server1->delete();
            }elseif($info->type==1){
//                $info=ServerVmess::find($info->server_id);
//                $info::delete();
                $server1 = ServerVmess::where('server_id',$info->id)->first();
                $server1->delete();
            }elseif($info->type==2){
//                $info=ServerTrojan::find($server->server_id);
//                $info::delete();
                $server1 = ServerTrojan::where('server_id',$info->id)->first();
                $server1->delete();
            }elseif($info->type==3){
//                $info=ServerHysteria::find($server->server_id);
//                $info::delete();
                $server1 = ServerHysteria::where('server_id',$info->id)->first();
                $server1->delete();
            }
            $info->delete();

        } catch (\Exception $e) {
            DB::rollBack();
//                abort(500, '保存失败');
            return response()->json(['code'=>1,'msg'=>'删除失败']);
        }
        DB::commit();
        return response()->json(['code'=>0,'msg'=>'删除成功']);

    }
}
