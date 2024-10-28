<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\UserFetch;
use App\Http\Requests\Admin\UserGenerate;
use App\Http\Requests\Admin\UserSendMail;
use App\Http\Requests\Admin\UserUpdate;
use App\Jobs\SendEmailJob;
use App\Services\AuthService;
use App\Services\UserService;
use App\Utils\Helper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\V2user as User;
use App\Models\Plan;
use App\Models\ServerGroup;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class V2userController extends Controller
{

    public function index(Request $request)
    {
        $plan = Plan::pluck('name','id')->toArray();
        $invite_user_id=$request->input('invite_user_id',0);

        return view('admin.v2user.index',compact('plan','invite_user_id'));
    }

    public function data(Request $request)
    {
        $v2users = User::select(
            DB::raw('*'),
            DB::raw('(u+d) as total_used')
        )->orderBy('created_at', 'desc');

        $this->filter($request,$v2users);
        $v2users=$v2users->paginate($request->get('limit',30))->toArray();
//echo '<pre>';print_R($v2users);exit;
        $plan_ids=$group_ids=$plan_info=$plan_info1=[];
        if(!empty($v2users['data'])){
            $plan_ids=array_column($v2users['data'],'plan_id');
        }
        $plan = Plan::whereIn('id',$plan_ids)->select(['id','name','group_id'])->get()->toArray();
        if(!empty($plan)){
            $group_ids=array_column($plan,'group_id');
            $plan_info=array_column($plan,'name','id');
            $plan_info1=array_column($plan,'group_id','id');
//            $transfer_enable=array_column($plan,'transfer_enable','id');

        }
        $group = ServerGroup::whereIn('id',$group_ids)->pluck('name','id')->toArray();


        for ($i = 0; $i < count($v2users['data']); $i++) {
            $v2users['data'][$i]['plan_name'] =$v2users['data'][$i]['plan_id']?$plan_info[$v2users['data'][$i]['plan_id']]:'-';
            $v2users['data'][$i]['group_name'] =$v2users['data'][$i]['plan_id']?$group[$plan_info1[$v2users['data'][$i]['plan_id']]]:'-';
//            $v2users['data'][$i]['flow'] =$v2users['data'][$i]['plan_id']?sprintf("%.2f", $transfer_enable[$v2users['data'][$i]['plan_id']]):'0.00';
            $v2users['data'][$i]['transfer_enable'] =$v2users['data'][$i]['plan_id']?sprintf("%.2f", $v2users['data'][$i]['transfer_enable']/1073741824):'0.00';
            $v2users['data'][$i]['subscribe_url'] = Helper::getSubscribeUrl('/api/v1/client/subscribe?token=' . $v2users['data'][$i]['token']);
            $v2users['data'][$i]['expired_at_val'] =$v2users['data'][$i]['expired_at']?date('Y-m-d H:i:s',$v2users['data'][$i]['expired_at']):'长期有效';
            $v2users['data'][$i]['created_at_val'] =date('Y-m-d H:i:s',$v2users['data'][$i]['created_at']);
            $v2users['data'][$i]['balance_val'] =sprintf("%.2f", $v2users['data'][$i]['balance']/100);
            $v2users['data'][$i]['commission_balance_val'] =sprintf("%.2f", $v2users['data'][$i]['commission_balance']/100);
            $v2users['data'][$i]['banned_val'] =$v2users['data'][$i]['banned']==0?'正常':'封禁';
            $v2users['data'][$i]['total_used'] =sprintf("%.2f", $v2users['data'][$i]['total_used']/1073741824);

        }

//        echo '<pre>';print_r($v2users);exit;

        $data = [
            'code' => 0,
            'msg'   => '正在请求中...',
            'count' => $v2users['total'],
            'data'  => $v2users['data']
        ];
        return response()->json($data);
    }


    public function create()
    {
        $plan = Plan::pluck('name','id')->toArray();
        return view('admin.v2user.create',compact('plan'));
    }

    public function store(Request $request)
    {
        $params=$request->all();

        if (empty($params['email_suffix'])) {
            return redirect(route('admin.v2user.create'))->withErrors(['status'=>'域不能为空']);
        }

        if (empty($params['email_prefix']) &&empty($params['generate_count'])) {
            return redirect(route('admin.v2user.create'))->withErrors(['status'=>'账号和生存数量不能都为空']);
        }

        if ($request->input('email_prefix')) {
            if ($request->input('plan_id')) {
                $plan = Plan::find($request->input('plan_id'));
                if (!$plan) {
                    return redirect(route('admin.v2user.create'))->withErrors(['status'=>'订阅计划不存在']);
                }
            }
            $user = [
                'email' => $request->input('email_prefix') . '@' . $request->input('email_suffix'),
                'plan_id' => isset($plan->id) ? $plan->id : NULL,
                'group_id' => isset($plan->group_id) ? $plan->group_id : NULL,
                'transfer_enable' => isset($plan->transfer_enable) ? $plan->transfer_enable * 1073741824 : 0,
                'expired_at' => $request->input('expired_at') ? strtotime($request->input('expired_at')):NULL,
                'uuid' => Helper::guid(true),
                'token' => Helper::guid()
            ];
            if (User::where('email', $user['email'])->first()) {
                return redirect(route('admin.v2user.create'))->withErrors(['status'=>'邮箱已存在于系统中']);
//                abort(500, '邮箱已存在于系统中');
            }
            $user['password'] = password_hash($request->input('password') ?? $user['email'], PASSWORD_DEFAULT);
            if (!User::create($user)) {
//                abort(500, '生成失败');
                return redirect(route('admin.v2user.create'))->withErrors(['status'=>'生成失败']);
            }
        }
        if ($request->input('generate_count')) {
            $this->multiGenerate($request);
        }
//echo 23;exit;
        return redirect(route('admin.v2user'))->with(['status'=>'添加成功']);

    }


    public function edit($id)
    {
        $user = User::find($id)->toArray();
        if (!$user){
            return redirect(route('user.user'))->withErrors(['status'=>'用户不存在']);
        }

        $plan = Plan::pluck('name','id')->toArray();

        if ($user['invite_user_id']) {
            $invite_user_id = User::find($user['invite_user_id'])->select(['email'])->toArray();
            $user['invite_user_id'] = $invite_user_id['email'];
        }
        $user['balance']=sprintf("%.2f", $user['balance']/100);
        $user['commission_balance']=sprintf("%.2f", $user['commission_balance']/100);
        $user['d']=sprintf("%.2f", $user['d']/1073741824);
        $user['u']=sprintf("%.2f", $user['u']/1073741824);
        $user['transfer_enable']=sprintf("%.2f", $user['transfer_enable']/1073741824);
        $user['expired_at']=$user['expired_at']?date('Y-m-d',$user['expired_at']):'长期有效';;


        return view('admin.v2user.edit',compact('user','plan'));

    }


    public function update(Request $request, $id)
    {
        $params=$request->all();
        $user = User::find($id);
        if (!$user) {
//            abort(500, '用户不存在');
            return redirect(route('admin.v2user.edit',['id'=>$id]))->withErrors(['status'=>'用户不存在']);
        }

        if (empty($params['email'])) {
//            abort(500, '邮箱已被使用');
            return redirect(route('admin.v2user.edit',['id'=>$id]))->withErrors(['status'=>'邮箱不能为空']);
        }
        if (User::where('email', $params['email'])->first() && $user->email !== $params['email']) {
//            abort(500, '邮箱已被使用');
            return redirect(route('admin.v2user.edit',['id'=>$id]))->withErrors(['status'=>'邮箱已被使用']);
        }
        if(!empty($params['password']) && strlen($params['password'])<8){
            return redirect(route('admin.v2user.edit',['id'=>$id]))->withErrors(['status'=>'密码长度最小8位']);
        }


        if (isset($params['password'])) {
            $params['password'] = password_hash($params['password'], PASSWORD_DEFAULT);
            $params['password_algo'] = NULL;
        } else {
            unset($params['password']);
        }
        if (isset($params['plan_id'])) {
            $plan = Plan::find($params['plan_id']);
            if (!$plan) {
//                abort(500, '订阅计划不存在');
                return redirect(route('admin.v2user.edit',['id'=>$id]))->withErrors(['status'=>'订阅计划不存在']);
            }
            $params['group_id'] = $plan->group_id;
        }
        if ($request->input('invite_user_email')) {
            $inviteUser = User::where('email', $request->input('invite_user_email'))->first();
            if ($inviteUser) {
                $params['invite_user_id'] = $inviteUser->id;
            }
        } else {
            $params['invite_user_id'] = null;
        }

        if (isset($params['banned']) && (int)$params['banned'] === 1) {
            $authService = new AuthService($user);
            $authService->removeAllSession();
        }
        $params['is_admin']=isset($params['is_admin'])?1:0;
        $params['is_staff']=isset($params['is_staff'])?1:0;

        $params['expired_at']=$params['is_staff']?strtotime($params['expired_at']):null;
        $params['balance']=$params['balance']?$params['balance']*100:$params['balance'];
        $params['commission_balance']=$params['commission_balance']?$params['commission_balance']*100:$params['commission_balance'];
        $params['d']=$params['d']?$params['d']*1073741824:$params['d'];
        $params['u']=$params['u']?$params['u']*1073741824:$params['u'];
        $params['transfer_enable']=$params['transfer_enable']?$params['transfer_enable']*1073741824:$params['transfer_enable'];

        unset($params['_method']);
        unset($params['_token']);
        try {
            $user->update($params);
            return redirect(route('admin.v2user'))->with(['status'=>'更新成功']);
        } catch (\Exception $e) {
//            abort(500, '保存失败');
            return redirect(route('admin.v2user.edit',['id'=>$id]))->withErrors(['status'=>'保存失败']);
        }



    }



    public function reset(Request $request)
    {

        $id=$request->input('ids')[0];
        $user = User::find($id);
//        if (!$user) return redirect(route('admin.v2user'))->withErrors(['status'=>'用户不存在']);
        if (!$user) return response()->json(['code'=>1,'msg'=>'更新失败']);
        $user->token = Helper::guid();
        $user->uuid = Helper::guid(true);
        return response()->json(['code'=>0,'msg'=>'更新成功']);
//        return redirect(route('admin.v2user'))->with(['status'=>'更新成功']);
//        return response([
//            'data' => $user->save()
//        ]);
    }

    private function filter(Request $request, $builder)
    {
        $params=$request->all();
//        echo '<pre>';print_R($request->input('page'));exit;
//        $filters = $request->input('filter');
        if(isset($params['is_admin'])&&$params['is_admin']!='-1'){
            $builder->where('is_admin', $request->input('is_admin'));
        }

        if(isset($params['plan_id'])&&$params['plan_id']!='-1'){
            $builder->where('plan_id', $request->input('plan_id'));
        }

        if(isset($params['banned'])&&$params['banned']!='-1'){
            $builder->where('banned', $request->input('banned'));
        }
        if(isset($params['invite_user_id'])&&$params['invite_user_id']){
            $builder->where('invite_user_id', $params['invite_user_id']);
        }
//        if($request->input('field_name')!='-1'){
        if(isset($params['field_name'])&&$params['field_name']!='-1'){
                if(in_array($request->input('field_name'),['email','uuid','token','remarks'])){
                    $builder->where($request->input('field_name'), 'like', '%'.$request->input('field_val').'%');
                }
                if(in_array($request->input('field_name'),['id','transfer_enable','d','invite_user_id'])){
                    $field_val=$request->input('field_val');
                    if ($request->input('field_name') === 'd' || $request->input('field_name') === 'transfer_enable') {
                        $field_val = $request->input('field_val') * 1073741824;
                    }
                    $builder->where($request->input('field_name'), '=', $field_val);
                }

                if(in_array($request->input('field_name'),['invite_by_email'])){
//                $user = User::where('email', 'like', $filter['value'])->select(['id'])->get();
                    $user = User::where('email', 'like', '%'.$request->input('field_val').'%')->pluck('id')->toArray();
//                $inviteUserId = isset($user->id) ? $user->id : 0;
                    $inviteUserId = $user ? $user :[];
//                    print_r($inviteUserId);exit;
                    $builder->whereIn('invite_user_id', $inviteUserId);
                }
            }


        $expired_at_start=$request->input('expired_at_start')?strtotime($request->input('expired_at_start')):0;
        $expired_at_end=$request->input('expired_at_end')?strtotime($request->input('expired_at_end')):0;
        if($expired_at_start || $expired_at_end){
            if($expired_at_start && $expired_at_end){
                $builder->whereBetween('expired_at', [$expired_at_start, $expired_at_end]);
            }
            if($expired_at_start && !$expired_at_end){
                $builder->whereBetween('expired_at', '>=',$expired_at_start);
            }
            if(!$expired_at_start && $expired_at_end){
                $builder->whereBetween('expired_at', '<=',$expired_at_end);
            }

        }



//        if ($filters) {
//            foreach ($filters as $k => $filter) {
//                if ($filter['condition'] === '模糊') {
//                    $filter['condition'] = 'like';
//                    $filter['value'] = "%{$filter['value']}%";
//                }
//                if ($filter['key'] === 'd' || $filter['key'] === 'transfer_enable') {
//                    $filter['value'] = $filter['value'] * 1073741824;
//                }
//                if ($filter['key'] === 'invite_by_email') {
//                    $user = User::where('email', $filter['condition'], $filter['value'])->first();
//                    $inviteUserId = isset($user->id) ? $user->id : 0;
//                    $builder->where('invite_user_id', $inviteUserId);
//                    unset($filters[$k]);
//                    continue;
//                }
//                $builder->where($filter['key'], $filter['condition'], $filter['value']);
//            }
//        }
    }

    public function fetch(UserFetch $request)
    {
        $current = $request->input('current') ? $request->input('current') : 1;
        $pageSize = $request->input('pageSize') >= 10 ? $request->input('pageSize') : 10;
        $sortType = in_array($request->input('sort_type'), ['ASC', 'DESC']) ? $request->input('sort_type') : 'DESC';
        $sort = $request->input('sort') ? $request->input('sort') : 'created_at';
        $userModel = User::select(
            DB::raw('*'),
            DB::raw('(u+d) as total_used')
        )
            ->orderBy($sort, $sortType);
        $this->filter($request, $userModel);
        $total = $userModel->count();
        $res = $userModel->forPage($current, $pageSize)
            ->get();
        $plan = Plan::get();
        for ($i = 0; $i < count($res); $i++) {
            for ($k = 0; $k < count($plan); $k++) {
                if ($plan[$k]['id'] == $res[$i]['plan_id']) {
                    $res[$i]['plan_name'] = $plan[$k]['name'];
                }
            }
            $res[$i]['subscribe_url'] = Helper::getSubscribeUrl('/api/v1/client/subscribe?token=' . $res[$i]['token']);
        }
        return response([
            'data' => $res,
            'total' => $total
        ]);
    }

    public function getUserInfoById(Request $request)
    {
        if (empty($request->input('id'))) {
            abort(500, '参数错误');
        }
        $user = User::find($request->input('id'));
        if ($user->invite_user_id) {
            $user['invite_user'] = User::find($user->invite_user_id);
        }
        return response([
            'data' => $user
        ]);
    }

    public function update111(UserUpdate $request)
    {
        $params = $request->validated();
        $user = User::find($request->input('id'));
        if (!$user) {
            abort(500, '用户不存在');
        }
        if (User::where('email', $params['email'])->first() && $user->email !== $params['email']) {
            abort(500, '邮箱已被使用');
        }
        if (isset($params['password'])) {
            $params['password'] = password_hash($params['password'], PASSWORD_DEFAULT);
            $params['password_algo'] = NULL;
        } else {
            unset($params['password']);
        }
        if (isset($params['plan_id'])) {
            $plan = Plan::find($params['plan_id']);
            if (!$plan) {
                abort(500, '订阅计划不存在');
            }
            $params['group_id'] = $plan->group_id;
        }
        if ($request->input('invite_user_email')) {
            $inviteUser = User::where('email', $request->input('invite_user_email'))->first();
            if ($inviteUser) {
                $params['invite_user_id'] = $inviteUser->id;
            }
        } else {
            $params['invite_user_id'] = null;
        }

        if (isset($params['banned']) && (int)$params['banned'] === 1) {
            $authService = new AuthService($user);
            $authService->removeAllSession();
        }

        try {
            $user->update($params);
        } catch (\Exception $e) {
            abort(500, '保存失败');
        }
        return response([
            'data' => true
        ]);
    }

    public function  csv(Request $request)
    {
        $userModel = User::orderBy('id', 'asc');
        $this->filter($request, $userModel);
        $res = $userModel->get();
        $plan = Plan::get();
        for ($i = 0; $i < count($res); $i++) {
            for ($k = 0; $k < count($plan); $k++) {
                if ($plan[$k]['id'] == $res[$i]['plan_id']) {
                    $res[$i]['plan_name'] = $plan[$k]['name'];
                }
            }
        }

//        $data = "邮箱,余额,推广佣金,总流量,剩余流量,套餐到期时间,订阅计划,订阅地址\r\n";
//        $title=['邮箱','余额','推广佣金','总流量','剩余流量','套餐到期时间','订阅计划','订阅地址'];
        $arr=[];
        foreach($res as $user) {
            $expireDate = $user['expired_at'] === NULL ? '长期有效' : date('Y-m-d H:i:s', $user['expired_at']);
            $balance = $user['balance'] / 100;
            $commissionBalance = $user['commission_balance'] / 100;
            $transferEnable = $user['transfer_enable'] ? $user['transfer_enable'] / 1073741824 : 0;
            $notUseFlow = (($user['transfer_enable'] - ($user['u'] + $user['d'])) / 1073741824) ?? 0;
            $planName = $user['plan_name'] ?? '无订阅';
            $subscribeUrl = Helper::getSubscribeUrl('/api/v1/client/subscribe?token=' . $user['token']);
//            $data .= "{$user['email']},{$balance},{$commissionBalance},{$transferEnable},{$notUseFlow},{$expireDate},{$planName},{$subscribeUrl}\r\n";
            $temp=[
                'email'=>$user['email'],
                'balance'=>$balance,
                'commission_balance'=>$commissionBalance,
                'transfer_enable'=>$transferEnable,
                'not_use_flow'=>$notUseFlow,
                'expired_at'=>$expireDate,
                'plan_name'=>$planName,
                'subscribeUrl'=>$subscribeUrl,

            ];
            $arr[]=$temp;
        }
        return response()->json(['code'=>0,'msg'=>'操作成功','data'=>$arr]);
//        echo "\xEF\xBB\xBF" . $data;
    }

    public function generate(UserGenerate $request)
    {
        if ($request->input('email_prefix')) {
            if ($request->input('plan_id')) {
                $plan = Plan::find($request->input('plan_id'));
                if (!$plan) {
                    abort(500, '订阅计划不存在');
                }
            }
            $user = [
                'email' => $request->input('email_prefix') . '@' . $request->input('email_suffix'),
                'plan_id' => isset($plan->id) ? $plan->id : NULL,
                'group_id' => isset($plan->group_id) ? $plan->group_id : NULL,
                'transfer_enable' => isset($plan->transfer_enable) ? $plan->transfer_enable * 1073741824 : 0,
                'expired_at' => $request->input('expired_at') ?? NULL,
                'uuid' => Helper::guid(true),
                'token' => Helper::guid()
            ];
            if (User::where('email', $user['email'])->first()) {
                abort(500, '邮箱已存在于系统中');
            }
            $user['password'] = password_hash($request->input('password') ?? $user['email'], PASSWORD_DEFAULT);
            if (!User::create($user)) {
                abort(500, '生成失败');
            }
            return response([
                'data' => true
            ]);
        }
        if ($request->input('generate_count')) {
            $this->multiGenerate($request);
        }
    }

    private function multiGenerate(Request $request)
    {
        if ($request->input('plan_id')) {
            $plan = Plan::find($request->input('plan_id'));
            if (!$plan) {
                return redirect(route('admin.v2user.create'))->withErrors(['status'=>'订阅计划不存在']);
//                abort(500, '订阅计划不存在');
            }
        }
        $users = [];
        for ($i = 0;$i < $request->input('generate_count');$i++) {
            $user = [
                'email' => Helper::randomChar(6) . '@' . $request->input('email_suffix'),
                'plan_id' => isset($plan->id) ? $plan->id : NULL,
                'group_id' => isset($plan->group_id) ? $plan->group_id : NULL,
                'transfer_enable' => isset($plan->transfer_enable) ? $plan->transfer_enable * 1073741824 : 0,
                'expired_at' =>$request->input('expired_at') ? strtotime($request->input('expired_at')):NULL,
                'uuid' => Helper::guid(true),
                'token' => Helper::guid(),
                'created_at' => time(),
                'updated_at' => time()
            ];
            $user['password'] = password_hash($request->input('password') ?? $user['email'], PASSWORD_DEFAULT);
            array_push($users, $user);
        }
        DB::beginTransaction();
        if (!User::insert($users)) {
            DB::rollBack();
            return redirect(route('admin.v2user.create'))->withErrors(['status'=>'生成失败']);
//            abort(500, '生成失败');
        }
        DB::commit();
//        $data = "账号,密码,过期时间,UUID,创建时间,订阅地址\r\n";
//        foreach($users as $user) {
//            $expireDate = $user['expired_at'] === NULL ? '长期有效' : date('Y-m-d H:i:s', $user['expired_at']);
//            $createDate = date('Y-m-d H:i:s', $user['created_at']);
//            $password = $request->input('password') ?? $user['email'];
//            $subscribeUrl = Helper::getSubscribeUrl('/api/v1/client/subscribe?token=' . $user['token']);
//            $data .= "{$user['email']},{$password},{$expireDate},{$user['uuid']},{$createDate},{$subscribeUrl}\r\n";
//        }
//
//        echo $data;
    }

    public function send(Request $request)
    {
        $sortType = in_array($request->input('sort_type'), ['ASC', 'DESC']) ? $request->input('sort_type') : 'DESC';
        $sort = $request->input('sort') ? $request->input('sort') : 'created_at';
        $builder = User::orderBy($sort, $sortType);
        $this->filter($request, $builder);
        $users = $builder->get();

        foreach ($users as $user) {
            SendEmailJob::dispatch([
                'email' => $user->email,
                'subject' => $request->input('subject'),
                'template_name' => 'notify',
                'template_value' => [
//                    'name' => config('v2board.app_name', 'V2Board'),
                    'name' => Cache::get('mail.from.name','V2Board'),
//                    'url' => config('v2board.app_url'),
//                    'url' => config('v2board.app_url'),
                    'url' =>Cache::get('mail.from.url','/'),
                    'content' => $request->input('content')
                ]
            ],
            'send_email_mass');
        }
        return response()->json(['code'=>0,'msg'=>'发送成功']);
//        return redirect(route('admin.v2user'))->with(['status'=>'发送成功']);

//        return response([
//            'data' => true
//        ]);
    }

    /**
     * 批量封禁
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function ban(Request $request)
    {
        $param=$request->all();
        try {
            User::whereIn('id', $param['ids'])->update([
                'banned' => 1,
                'updated_at' => time()
            ]);
        } catch (\Exception $e) {
            return response()->json(['code'=>1,'msg'=>'操作失败']);
//            abort(500, '处理失败');
        }
        return response()->json(['code'=>0,'msg'=>'操作成功']);
//        return response([
//            'data' => true
//        ]);
    }
}
