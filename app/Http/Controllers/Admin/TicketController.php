<?php

namespace App\Http\Controllers\Admin;

use App\Jobs\SendEmailJob;
use App\Services\TicketService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\V2user as User;
use App\Models\TicketMessage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function fetch(Request $request)
    {
        if ($request->input('id')) {
            $ticket = Ticket::where('id', $request->input('id'))
                ->first();
            if (!$ticket) {
                abort(500, '工单不存在');
            }
            $ticket['message'] = TicketMessage::where('ticket_id', $ticket->id)->get();
            for ($i = 0; $i < count($ticket['message']); $i++) {
                if ($ticket['message'][$i]['user_id'] !== $ticket->user_id) {
                    $ticket['message'][$i]['is_me'] = true;
                } else {
                    $ticket['message'][$i]['is_me'] = false;
                }
            }
            return response([
                'data' => $ticket
            ]);
        }
        $current = $request->input('current') ? $request->input('current') : 1;
        $pageSize = $request->input('pageSize') >= 10 ? $request->input('pageSize') : 10;
        $model = Ticket::orderBy('updated_at', 'DESC');
        if ($request->input('status') !== NULL) {
            $model->where('status', $request->input('status'));
        }
        if ($request->input('reply_status') !== NULL) {
            $model->whereIn('reply_status', $request->input('reply_status'));
        }
        if ($request->input('email') !== NULL) {
            $user = User::where('email', $request->input('email'))->first();
            if ($user) $model->where('user_id', $user->id);
        }
        $total = $model->count();
        $res = $model->forPage($current, $pageSize)
            ->get();
        return response([
            'data' => $res,
            'total' => $total
        ]);
    }


    public function index(Request $request)
    {
        $status=0;
        return view('admin.ticket.index',compact('status'));
    }


    public function data(Request $request)
    {
        $model = Ticket::orderBy('updated_at', 'DESC');
        $params=$request->all();
        if (isset($params['status']) && $params['status']>-1) {
            $model->where('status', $params['status']);
        }
//        if ($request->input('reply_status') !== NULL) {
//            $model->whereIn('reply_status', $request->input('reply_status'));
//        }
        if ($request->input('email')) {
            $user = User::where('email', $request->input('email'))->first();

            if ($user) {
                $model->where('user_id', $user->id);
            }else{
                $model->where('user_id', 0);
            }
        }

        $tickets=$model->paginate($request->get('limit',30))->toArray();
        $level_arr=['低','中','高'];
        foreach($tickets['data'] as &$val){
            $val['updated_at']=date('Y-m-d H:i:s',$val['updated_at']);
            $val['created_at']=date('Y-m-d H:i:s',$val['created_at']);
            $val['level']=$level_arr[$val['level']];
//            $val['status']=$val['status']==1?'已关闭':'已开启';
            $val['status_str']=$val['status']==1?'已关闭':'已开启';
        }
        $data = [
            'code' => 0,
            'msg'   => '正在请求中...',
            'count' => $tickets['total'],
            'data'  => $tickets['data']
        ];
        return response()->json($data);
    }

    public function detail($id)
    {
        if ($id) {

            $ticket = Ticket::where('id', $id)
                ->first()->toArray();

            if (!$ticket) {
//                abort(500, '工单不存在');
                return redirect(route('admin.ticket'))->withErrors(['status'=>'工单不存在']);
            }
            $ticket['message'] = TicketMessage::where('ticket_id', $ticket['id'])->get()->toArray();

            for ($i = 0; $i < count($ticket['message']); $i++) {
                if ($ticket['message'][$i]['user_id'] !== $ticket['user_id']) {
//                    $ticket['message'][$i]['is_me'] = true;
                    $ticket['message'][$i]['is_me'] = false;
                } else {
//                    $ticket['message'][$i]['is_me'] = false;
                    $ticket['message'][$i]['is_me'] = true;
                }
                $ticket['message'][$i]['created_at']=date('Y-m-d H:i',$ticket['message'][$i]['created_at']);
            }
//        echo '<pre>';print_r($ticket);exit;
            return view('admin.ticket.detail',compact('ticket'));

//            return response([
//                'data' => $ticket
//            ]);
        }

    }

    public function close($id)
    {

        if (!$id) {
            return redirect(route('admin.ticket'))->withErrors(['status'=>'参数错误']);
        }
        $ticket = Ticket::where('id', $id)
            ->first();
        if (!$ticket) {
            return redirect(route('admin.ticket'))->withErrors(['status'=>'工单不存在']);
//            abort(500, '工单不存在');
        }
        $ticket->status = 1;
        if (!$ticket->save()) {
            return redirect(route('admin.ticket'))->withErrors(['status'=>'关闭失败']);
//            abort(500, '关闭失败');
        }
        return redirect(route('admin.ticket'))->with(['status'=>'更新成功']);
//        return view('admin.knowledge.edit',compact('knowledge','lang_arr'));

    }

    public function reply(Request $request)
    {
//        Auth::guard('admin')->user();
//        echo '<pre>';
//        print_r(Auth::user()->toArray());exit;
        $admin_info=Auth::user()->toArray();
        if (empty($request->input('id'))) {
//            abort(500, '参数错误');
            return response()->json(['code'=>1,'msg'=>'参数错误']);
        }
        if (empty($request->input('message'))) {
//            abort(500, '消息不能为空');
            return response()->json(['code'=>1,'msg'=>'消息不能为空']);
        }
//        echo '<pre>';
//        print_r($request->all());exit;
        $ticketService = new TicketService();
        $ticketService->replyByAdmin(
            $request->input('id'),
            $request->input('message'),
//            $request->user['id']
            $admin_info['id'],
            1
        );
        $arr=[
            'time'=>date('Y-m-d H:i'),
            'message'=>$request->input('message')
        ];

        return response()->json(['code'=>0,'msg'=>'回复成功','data'=>$arr]);
//        return response([
//            'data' => true
//        ]);
    }

    public function close111(Request $request)
    {
        if (empty($request->input('id'))) {
            abort(500, '参数错误');
        }
        $ticket = Ticket::where('id', $request->input('id'))
            ->first();
        if (!$ticket) {
            abort(500, '工单不存在');
        }
        $ticket->status = 1;
        if (!$ticket->save()) {
            abort(500, '关闭失败');
        }
        return response([
            'data' => true
        ]);
    }
}
