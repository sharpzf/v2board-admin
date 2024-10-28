<?php

namespace App\Http\Controllers\Admin;

use App\Utils\CacheKey;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Laravel\Horizon\Contracts\JobRepository;
use Laravel\Horizon\Contracts\MasterSupervisorRepository;
use Laravel\Horizon\Contracts\MetricsRepository;
use Laravel\Horizon\Contracts\SupervisorRepository;
use Laravel\Horizon\Contracts\WorkloadRepository;
use Laravel\Horizon\WaitTimeCalculator;

class SystemController extends Controller
{
    public function getSystemStatus()
    {
        return response([
            'data' => [
                'schedule' => $this->getScheduleStatus(),
                'horizon' => $this->getHorizonStatus(),
                'schedule_last_runtime' => Cache::get(CacheKey::get('SCHEDULE_LAST_CHECK_AT', null))
            ]
        ]);
    }

    public function index()
    {
        return view('admin.monitor.index');

    }

    public function getQueueWorkload(WorkloadRepository $workload)
    {
//        $arr=[
//            'data'=>[
//                ['name'=>'default','length'=>0,'wait'=>0,'processes'=>2,'split_queues'=>null],
//                ['name'=>'order_handle','length'=>0,'wait'=>0,'processes'=>2,'split_queues'=>null],
//                ['name'=>'send_email','length'=>0,'wait'=>0,'processes'=>2,'split_queues'=>null],
//                ['name'=>'send_email_mass','length'=>0,'wait'=>0,'processes'=>2,'split_queues'=>null],
//                ['name'=>'send_telegram','length'=>0,'wait'=>0,'processes'=>2,'split_queues'=>null],
//                ['name'=>'stat','length'=>0,'wait'=>0,'processes'=>1,'split_queues'=>null],
//                ['name'=>'traffic_fetch','length'=>0,'wait'=>0,'processes'=>2,'split_queues'=>null]
//            ],
//        ];
//
//        $arr=[
//            'data'=>[
//                ['name'=>'default','length'=>1,'wait'=>2,'processes'=>3,'split_queues'=>4],
//                ['name'=>'order_handle','length'=>5,'wait'=>6,'processes'=>7,'split_queues'=>8],
//                ['name'=>'send_email','length'=>9,'wait'=>10,'processes'=>11,'split_queues'=>12],
//                ['name'=>'send_email_mass','length'=>13,'wait'=>14,'processes'=>15,'split_queues'=>16],
//                ['name'=>'send_telegram','length'=>17,'wait'=>18,'processes'=>19,'split_queues'=>20],
//                ['name'=>'stat','length'=>21,'wait'=>22,'processes'=>23,'split_queues'=>24],
//                ['name'=>'traffic_fetch','length'=>25,'wait'=>26,'processes'=>27,'split_queues'=>28]
//            ],
//        ];
//
//        return response($arr);

        $arr=collect($workload->get())->sortBy('name')->values()->toArray();
        if(empty($arr)){
            $arr=[
                ['name'=>'default','length'=>0,'wait'=>0,'processes'=>2,'split_queues'=>null],
                ['name'=>'order_handle','length'=>0,'wait'=>0,'processes'=>2,'split_queues'=>null],
                ['name'=>'send_email','length'=>0,'wait'=>0,'processes'=>2,'split_queues'=>null],
                ['name'=>'send_email_mass','length'=>0,'wait'=>0,'processes'=>2,'split_queues'=>null],
                ['name'=>'send_telegram','length'=>0,'wait'=>0,'processes'=>2,'split_queues'=>null],
                ['name'=>'stat','length'=>0,'wait'=>0,'processes'=>1,'split_queues'=>null],
                ['name'=>'traffic_fetch','length'=>0,'wait'=>0,'processes'=>2,'split_queues'=>null]
        ];

        }
        $job_arr=[
            'order_handle'=>'订单队列',
            'send_email'=>'邮件队列',
            'send_email_mass'=>'邮件群发队列',
            'send_telegram'=>'Telegram消息队列',
            'stat'=>'统计队列',
            'traffic_fetch'=>'流量消费队列'
        ];


        return response([
            'data' => $arr,
            'job_arr' => $job_arr
        ]);


        return response([
            'data' => collect($workload->get())->sortBy('name')->values()->toArray()
        ]);
    }

    protected function getScheduleStatus():bool
    {
        return (time() - 120) < Cache::get(CacheKey::get('SCHEDULE_LAST_CHECK_AT', null));
    }

    protected function getHorizonStatus():bool
    {
        if (! $masters = app(MasterSupervisorRepository::class)->all()) {
            return false;
        }

        return collect($masters)->contains(function ($master) {
            return $master->status === 'paused';
        }) ? false : true;
    }

    public function getQueueStats()
    {

//        $arr=[
//            'data'=>[
//                'failedJobs'=>0,
//                'jobsPerMinute'=>0,
//                'pausedMasters'=>0,
//                'periods'=>['failedJobs'=>10080,'recentJobs'=>60],
//                'processes'=>13,
//                'queueWithMaxRuntime'=>'send_email',
//                'queueWithMaxThroughput'=>'send_email',
//                'recentJobs'=>0,
//                'status'=>true,
//                'wait'=>['redis:stat'=>9]
//            ]
//        ];

//        $arr=[
//            'data'=>[
//                'failedJobs'=>1,
//                'jobsPerMinute'=>2,
//                'pausedMasters'=>3,
//                'periods'=>['failedJobs'=>4,'recentJobs'=>5],
//                'processes'=>6,
//                'queueWithMaxRuntime'=>'send_email111',
//                'queueWithMaxThroughput'=>'send_email222',
//                'recentJobs'=>7,
//                'status'=>true,
//                'wait'=>['redis:stat'=>8]
//            ]
//        ];
//        return response($arr);


        return response([
            'data' => [
                'failedJobs' => app(JobRepository::class)->countRecentlyFailed(),
                'jobsPerMinute' => app(MetricsRepository::class)->jobsProcessedPerMinute(),
                'pausedMasters' => $this->totalPausedMasters(),
                'periods' => [
                    'failedJobs' => config('horizon.trim.recent_failed', config('horizon.trim.failed')),
                    'recentJobs' => config('horizon.trim.recent'),
                ],
                'processes' => $this->totalProcessCount(),
                'queueWithMaxRuntime' => app(MetricsRepository::class)->queueWithMaximumRuntime(),
                'queueWithMaxThroughput' => app(MetricsRepository::class)->queueWithMaximumThroughput(),
                'recentJobs' => app(JobRepository::class)->countRecent(),
                'status' => $this->getHorizonStatus(),
                'wait' => collect(app(WaitTimeCalculator::class)->calculate())->take(1),
            ]
        ]);
    }

    /**
     * Get the total process count across all supervisors.
     *
     * @return int
     */
    protected function totalProcessCount()
    {
        $supervisors = app(SupervisorRepository::class)->all();

        return collect($supervisors)->reduce(function ($carry, $supervisor) {
            return $carry + collect($supervisor->processes)->sum();
        }, 0);
    }

    /**
     * Get the number of master supervisors that are currently paused.
     *
     * @return int
     */
    protected function totalPausedMasters()
    {
        if (! $masters = app(MasterSupervisorRepository::class)->all()) {
            return 0;
        }

        return collect($masters)->filter(function ($master) {
            return $master->status === 'paused';
        })->count();
    }
}

