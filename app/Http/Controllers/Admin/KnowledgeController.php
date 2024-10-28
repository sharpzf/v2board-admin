<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\KnowledgeSave;
use App\Http\Requests\Admin\KnowledgeSort;
use App\Models\Knowledge;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class KnowledgeController extends Controller
{
    public function fetch(Request $request)
    {
        if ($request->input('id')) {
            $knowledge = Knowledge::find($request->input('id'))->toArray();
            if (!$knowledge) abort(500, '知识不存在');
            return response([
                'data' => $knowledge
            ]);
        }
        return response([
            'data' => Knowledge::select(['title', 'id', 'updated_at', 'category', 'show'])
                ->orderBy('sort', 'ASC')
                ->get()
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
            Knowledge::find($id)->update($arr);
            return response()->json(['code'=>0,'msg'=>'操作成功']);

        }
        return view('admin.knowledge.index');
    }

    public function data(Request $request)
    {
        $notices=Knowledge::select(['title', 'id', 'updated_at', 'category', 'show'])
            ->orderBy('sort', 'ASC')->paginate($request->get('limit',30))->toArray();
        foreach($notices['data'] as &$val){
            $val['updated_at']=date('Y-m-d H:i:s',$val['updated_at']);
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
        $knowledge=[];
        $lang_arr=[
            'en-US'=>'English',
            'ja-JP'=>'日本語',
            'ko-KR'=>'한국어',
            'vi-VN'=>'Tiếng Việt',
            'zh-CN'=>'简体中文',
            'zh-TW'=>'繁體中文',
        ];
        return view('admin.knowledge.create',compact('knowledge','lang_arr'));
    }
    public function store(Request $request)
    {

        $params=$request->all();
        unset($params['_token']);
        if(empty($params['title'])){
            return redirect(route('admin.knowledge.create'))->withErrors(['status'=>'标题不能为空']);
        }
        if(empty($params['category'])){
            return redirect(route('admin.knowledge.create'))->withErrors(['status'=>'分类不能为空']);
        }
        if(empty($params['body'])){
            return redirect(route('admin.knowledge.create'))->withErrors(['status'=>'内容不能为空']);
        }

        if (!Knowledge::create($params)) {
            return redirect(route('admin.knowledge.create'))->withErrors(['status'=>'创建失败']);
        }
        return redirect(route('admin.knowledge'))->with(['status'=>'添加成功']);

    }


    public function edit($id)
    {
        $knowledge = Knowledge::find($id)->toArray();
        if (!$knowledge){
            return redirect(route('admin.knowledge'))->withErrors(['status'=>'公告不存在']);
        }
        $lang_arr=[
            'en-US'=>'English',
            'ja-JP'=>'日本語',
            'ko-KR'=>'한국어',
            'vi-VN'=>'Tiếng Việt',
            'zh-CN'=>'简体中文',
            'zh-TW'=>'繁體中文',
        ];

        return view('admin.knowledge.edit',compact('knowledge','lang_arr'));

    }

    public function update(Request $request, $id)
    {

        $params=$request->all();
        if(empty($params['title'])){
            return redirect(route('admin.knowledge.edit',['id'=>$id]))->withErrors(['status'=>'标题不能为空']);
        }
        if(empty($params['category'])){
            return redirect(route('admin.knowledge.edit',['id'=>$id]))->withErrors(['status'=>'分类不能为空']);
        }
        if(empty($params['body'])){
            return redirect(route('admin.knowledge.edit',['id'=>$id]))->withErrors(['status'=>'内容不能为空']);
        }

        try {
            Knowledge::find($id)->update($params);
            return redirect(route('admin.knowledge'))->with(['status'=>'更新成功']);
        } catch (\Exception $e) {
            return redirect(route('admin.knowledge.edit',['id'=>$id]))->withErrors(['status'=>'更新失败']);
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
            $knowledge = Knowledge::find($id);
            if (!$knowledge) {
                return response()->json(['code'=>1,'msg'=>'知识不存在']);
            }
        }

        if($knowledge->delete()){
            return response()->json(['code'=>0,'msg'=>'删除成功']);
        }
        return response()->json(['code'=>1,'msg'=>'删除失败']);

    }



    public function getCategory(Request $request)
    {
        return response([
            'data' => array_keys(Knowledge::get()->groupBy('category')->toArray())
        ]);
    }

    public function save(KnowledgeSave $request)
    {
        $params = $request->validated();

        if (!$request->input('id')) {
            if (!Knowledge::create($params)) {
                abort(500, '创建失败');
            }
        } else {
            try {
                Knowledge::find($request->input('id'))->update($params);
            } catch (\Exception $e) {
                abort(500, '保存失败');
            }
        }

        return response([
            'data' => true
        ]);
    }

    public function show(Request $request)
    {
        if (empty($request->input('id'))) {
            abort(500, '参数有误');
        }
        $knowledge = Knowledge::find($request->input('id'));
        if (!$knowledge) {
            abort(500, '知识不存在');
        }
        $knowledge->show = $knowledge->show ? 0 : 1;
        if (!$knowledge->save()) {
            abort(500, '保存失败');
        }

        return response([
            'data' => true
        ]);
    }

    public function sort(KnowledgeSort $request)
    {
        DB::beginTransaction();
        try {
            foreach ($request->input('knowledge_ids') as $k => $v) {
                $knowledge = Knowledge::find($v);
                $knowledge->timestamps = false;
                $knowledge->update(['sort' => $k + 1]);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            abort(500, '保存失败');
        }
        DB::commit();
        return response([
            'data' => true
        ]);
    }

    public function drop(Request $request)
    {
        if (empty($request->input('id'))) {
            abort(500, '参数有误');
        }
        $knowledge = Knowledge::find($request->input('id'));
        if (!$knowledge) {
            abort(500, '知识不存在');
        }
        if (!$knowledge->delete()) {
            abort(500, '删除失败');
        }

        return response([
            'data' => true
        ]);
    }
}
