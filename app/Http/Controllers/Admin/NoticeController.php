<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\NoticeSave;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Notice;
use Illuminate\Support\Facades\Cache;

class NoticeController extends Controller
{
    public function fetch(Request $request)
    {
        return response([
            'data' => Notice::orderBy('id', 'DESC')->get()
        ]);
    }
    public function index(Request $request)
    {

        if($request->input('id')){
            $arr=$request->all();
            $id=$request->input('id');
            if(isset($arr['show'])){
                $arr=[
                    'show'=>$arr['show'],
                    'updated_at'=>time()
                ];
            }

            Notice::find($id)->update($arr);
            return response()->json(['code'=>0,'msg'=>'操作成功']);

        }
        return view('admin.notice.index');
    }

    public function data(Request $request)
    {
        $notices=Notice::orderBy('id', 'DESC')->paginate($request->get('limit',30))->toArray();
        foreach($notices['data'] as &$val){
            $val['created_at']=date('Y-m-d',$val['created_at']);
        }

        $data = [
            'code' => 0,
            'msg'   => '正在请求中...',
            'count' => $notices['total'],
            'data'  => $notices['data']
        ];
        return response()->json($data);
    }

    public function create()
    {
        $notice=[];
        return view('admin.notice.create',compact('notice'));
    }

    public function store(Request $request)
    {

        $params=$request->all();
        unset($params['_token']);
        if(empty($params['title'])){
            return redirect(route('admin.notice.create'))->withErrors(['status'=>'标题不能为空']);
        }
        if(empty($params['content'])){
            return redirect(route('admin.notice.create'))->withErrors(['status'=>'公告内容不能为空']);
        }
        if (!empty($param['img_url']) && !$this->checkFileExists($param['img_url'])) {
            return back()->withErrors(['status' => '图片URL格式不正确']);
        }
        $params['tags']=array_values(explode(",",$params['tags']));
        $params['tags']=json_encode($params['tags']);
        if (!Notice::create($params)) {
            return redirect(route('admin.notice.create'))->withErrors(['status'=>'创建失败']);
        }
        return redirect(route('admin.notice'))->with(['status'=>'添加成功']);

    }

    public function edit($id)
    {
        $notice = Notice::find($id)->toArray();
        if (!$notice){
            return redirect(route('admin.notice'))->withErrors(['status'=>'公告不存在']);
        }
        $notice['tags']=json_decode($notice['tags'],true);
        $notice['tags']=implode(',',$notice['tags']);

        return view('admin.notice.edit',compact('notice'));

    }

    public function update(Request $request, $id)
    {

        $params=$request->all();
        if(empty($params['title'])){
            return redirect(route('admin.notice.edit',['id'=>$id]))->withErrors(['status'=>'标题不能为空']);
        }
        if(empty($params['content'])){
            return redirect(route('admin.notice.edit',['id'=>$id]))->withErrors(['status'=>'公告内容不能为空']);
        }

        if (!empty($param['img_url']) && !$this->checkFileExists($param['img_url'])) {
            return back()->withErrors(['status' => '图片URL格式不正确']);
        }
        $params['tags']=array_values(explode(",",$params['tags']));
        $params['tags']=json_encode($params['tags']);
        unset($params['_token']);
        try {
            Notice::find($id)->update($params);
            return redirect(route('admin.notice'))->with(['status'=>'更新成功']);
        } catch (\Exception $e) {
            return redirect(route('admin.notice.edit',['id'=>$id]))->withErrors(['status'=>'更新失败']);
        }

    }


    public function save(NoticeSave $request)
    {
        $data = $request->only([
            'title',
            'content',
            'img_url',
            'tags'
        ]);
        if (!$request->input('id')) {
            if (!Notice::create($data)) {
                abort(500, '保存失败');
            }
        } else {
            try {
                Notice::find($request->input('id'))->update($data);
            } catch (\Exception $e) {
                abort(500, '保存失败');
            }
        }
        return response([
            'data' => true
        ]);
    }
    public function destroy(Request $request)
    {
        $ids = $request->get('ids');
        $id=$ids[0];
        if (empty($ids)){
            return response()->json(['code'=>1,'msg'=>'请选择删除项']);
        }

        if ($id) {
            $notice = Notice::find($id);
            if (!$notice) {
                return response()->json(['code'=>1,'msg'=>'公告不存在']);
            }
        }

        if($notice->delete()){
            return response()->json(['code'=>0,'msg'=>'删除成功']);
        }
        return response()->json(['code'=>1,'msg'=>'删除失败']);

    }

    protected function checkFileExists($url) {
        $curl = curl_init($url);
        //不取回数据
        curl_setopt($curl, CURLOPT_NOBODY, true);
        //发送请求
        $result = curl_exec($curl);
        $found = false;
        if ($result !== false) {
            //检查http响应码是否为200
            $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if ($statusCode == 200) {
                $found = true;
            }
        }
        curl_close($curl);
        return $found;
    }


    public function show(Request $request)
    {
        if (empty($request->input('id'))) {
            abort(500, '参数有误');
        }
        $notice = Notice::find($request->input('id'));
        if (!$notice) {
            abort(500, '公告不存在');
        }
        $notice->show = $notice->show ? 0 : 1;
        if (!$notice->save()) {
            abort(500, '保存失败');
        }

        return response([
            'data' => true
        ]);
    }

    public function drop(Request $request)
    {
        if (empty($request->input('id'))) {
            abort(500, '参数错误');
        }
        $notice = Notice::find($request->input('id'));
        if (!$notice) {
            abort(500, '公告不存在');
        }
        if (!$notice->delete()) {
            abort(500, '删除失败');
        }
        return response([
            'data' => true
        ]);
    }
}
