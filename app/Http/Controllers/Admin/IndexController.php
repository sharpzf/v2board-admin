<?php

namespace App\Http\Controllers\Admin;

use App\Models\Icon;
use App\Models\Permission;
use App\Models\Role;
use App\Models\V2user as User;
use App\Models\User as AdminUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\CommissionLog;
use App\Http\Controllers\Admin\StatController;
use Laravel\Horizon\Contracts\MasterSupervisorRepository;

use App\Models\ServerShadowsocks;
use App\Models\ServerHysteria;
use App\Models\ServerTrojan;
use App\Models\ServerVmess;


class IndexController extends Controller
{
    /**
     * 后台布局
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function layout()
    {
        return view('admin.layout');
    }

    /**
     * 后台首页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {

        $arr = [
            'month_income' => Order::where('created_at', '>=', strtotime(date('Y-m-1')))
                ->where('created_at', '<', time())
                ->whereNotIn('status', [0, 2])
                ->sum('total_amount'),
//        'month_income' => 111,
            'month_register_total' => User::where('created_at', '>=', strtotime(date('Y-m-1')))
                ->where('created_at', '<', time())
                ->count(),
//        'month_register_total' => 222,
            'ticket_pending_total' => Ticket::where('status', 0)
                ->count(),
//        'ticket_pending_total' => 333,
            'commission_pending_total' => Order::where('commission_status', 0)
                ->where('invite_user_id', '!=', NULL)
                ->whereNotIn('status', [0, 2])
                ->where('commission_balance', '>', 0)
                ->count(),
//        'commission_pending_total' => 444,
            'day_income' => Order::where('created_at', '>=', strtotime(date('Y-m-d')))
                ->where('created_at', '<', time())
                ->whereNotIn('status', [0, 2])
                ->sum('total_amount'),
//        'day_income' => 555,
            'last_month_income' => Order::where('created_at', '>=', strtotime('-1 month', strtotime(date('Y-m-1'))))
                ->where('created_at', '<', strtotime(date('Y-m-1')))
                ->whereNotIn('status', [0, 2])
                ->sum('total_amount'),
//        'last_month_income' => 666,
            'commission_month_payout' => CommissionLog::where('created_at', '>=', strtotime(date('Y-m-1')))
                ->where('created_at', '<', time())
                ->sum('get_amount'),
//        'commission_month_payout' => 777,
            'commission_last_month_payout' => CommissionLog::where('created_at', '>=', strtotime('-1 month', strtotime(date('Y-m-1'))))
                ->where('created_at', '<', strtotime(date('Y-m-1')))
                ->sum('get_amount'),
//        'commission_last_month_payout' => 888,
        ];

        $arr['status']= $this->currentStatus();
        $arr['month_income']= sprintf("%.2f", $arr['month_income']/100);
        $arr['day_income']= sprintf("%.2f", $arr['day_income']/100);
        $arr['last_month_income']= sprintf("%.2f", $arr['last_month_income']/100);
        $arr['commission_last_month_payout']= sprintf("%.2f", $arr['commission_last_month_payout']/100);

        $stat_controller=new StatController();
//        $arr['last_rank']=$stat_controller->getServerLastRank();
        $last_rank=$stat_controller->getServerLastRank();
        $order_list=$stat_controller->getOrder();
        $type1_arr=$type2_arr=$type3_arr=$type4_arr=$type_date=[];
        $node_name=$node_total=[];

        if(!empty($order_list['data'])){
            foreach($order_list['data'] as $kk=>$val){
                if($kk%4==0){
                    $type1_arr[]=$val['value'];
                    $type_date[]=$val['date'];
                }elseif($kk%4==1){
                    $type2_arr[]=$val['value'];
                }elseif($kk%4==2){
                    $type3_arr[]=$val['value'];
                }elseif($kk%4==3){
                    $type4_arr[]=$val['value'];
                }
            }
        }

        if(!empty($last_rank['data'])){
            foreach($last_rank['data'] as $vl){
                if($vl['server_type']=='shadowsocks'){
                    $info=ServerShadowsocks::where('id',$vl['server_id'])->select(['id','name'])->first();
                }elseif ($vl['server_type']=='vmess'){
                    $info=ServerVmess::where('id',$vl['server_id'])->select(['id','name'])->first();
                }elseif ($vl['server_type']=='trojan'){
                    $info=ServerTrojan::where('id',$vl['server_id'])->select(['id','name'])->first();
                }elseif ($vl['server_type']=='hysteria'){
                    $info=ServerHysteria::where('id',$vl['server_id'])->select(['id','name'])->first();
                }
                $node_name[]=!empty($info)?$info->name:'未知节点';
                $node_total[]=$vl['total'];
            }
        }

        $arr['order_list']['type1_arr']=$type1_arr;
        $arr['order_list']['type2_arr']=$type2_arr;
        $arr['order_list']['type3_arr']=$type3_arr;
        $arr['order_list']['type4_arr']=$type4_arr;
        $arr['order_list']['type_date']=$type_date;
        $arr['last_rank']['node_name']=$node_name;
        $arr['last_rank']['node_total']=$node_total;

//        echo '<pre>';
//        print_r($arr);exit;

//        $data=(new StatController)->getServerLastRank();

        return view('admin.index.index',compact('arr'));
    }


    protected function currentStatus()
    {
        if (! $masters = app(MasterSupervisorRepository::class)->all()) {
            return 'inactive';
        }

        return collect($masters)->contains(function ($master) {
            return $master->status === 'paused';
        }) ? 'paused' : 'running';
    }

    public function index1()
    {
        return view('admin.index.index1');
    }

    public function index2()
    {
        return view('admin.index.index2');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * 数据表格接口
     */
    public function data(Request $request)
    {
        $model = $request->get('model');
        switch (strtolower($model)) {
            case 'user':
//                $query = new User();
                $query = new AdminUser();
                break;
            case 'role':
                $query = new Role();
                break;
            case 'permission':
                $query = new Permission();
                $query = $query->where('parent_id', $request->get('parent_id', 0))->with('icon');
                break;
            default:
//                $query = new User();
                $query = new AdminUser();
                break;
        }
        $res = $query->paginate($request->get('limit', 30))->toArray();
        $data = [
            'code' => 0,
            'msg' => '正在请求中...',
            'count' => $res['total'],
            'data' => $res['data']
        ];
        return response()->json($data);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * 所有icon图标
     */
    public function icons()
    {
        $icons = Icon::orderBy('sort', 'desc')->get();
        return response()->json(['code' => 0, 'msg' => '请求成功', 'data' => $icons]);
    }

}
