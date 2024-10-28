<?php

namespace App\Http\Controllers\Admin;

use App\Models\Site;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Config;
use Illuminate\Support\Facades\DB;
use App\Jobs\SendEmailJob;
//use Illuminate\Support\Facades\Config as Config2;
use Illuminate\Support\Facades\Cache;
use App\Services\TelegramService;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;


class ConfigController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        $config = Site::pluck('value','key');

        $plans = Plan::pluck('name', 'id');

        $config_rows = Config::select(['name', 'val'])
            ->where('type', 'sys')
            ->get()->toArray();

        $config = [];
        foreach ($config_rows as $val) {
            $temp_arr = json_decode($val['val'], true);
            $config[$val['name']] = $temp_arr;
            if($val['name']=='sys_safe'){
                $config[$val['name']]['email_whitelist_suffix'] = implode(',',$config[$val['name']]['email_whitelist_suffix']);
            }
            if($val['name']=='sys_invite'){
                $config[$val['name']]['commission_withdraw_method'] = implode(',',$config[$val['name']]['commission_withdraw_method']);
            }
        }

        $reset_traffic_method=[
            '每月1号',
            '按月重置',
            '不重置',
            '每年1月1日',
            '按年重置',
        ];
//echo '<pre>';
//print_r($config);exit;
        return view('admin.config.index', compact('config', 'plans','reset_traffic_method'));
    }


    public function theme()
    {
//        $config = Site::pluck('value','key');

//        $plans = Plan::pluck('name', 'id');

        $theme_rows = Config::select(['name', 'val'])
            ->where('name', 'sys_theme')
            ->first()->toArray();

        $theme = [];
        $temp_arr = json_decode($theme_rows['val'], true);
        $theme[$theme_rows['name']] = $temp_arr;
        return view('admin.theme.index', compact('theme'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */

    protected function checkUrl($url)
    {
//        $url = "http:/2/";
        $preg = "/^http(s)?:\\/\\/.+/";
        if (preg_match($preg, $url)) {
            return true;
        }
        return false;

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

    public function update(Request $request)
    {
//        return redirect(route('admin.config'))->with(['status'=>'更新成功']);
//        return redirect(route('admin.config'))->withErrors(['status'=>'背景图不存在']);
//        return back()->withErrors(['status' => '背景图不存在']);
//        return back()->with(['status' => '更新成功']);;
//        exit;
        $param = $request->all();

        $config_type = $param['config_type'];
        if ($config_type == 'sys_site') {
            $msg = ['logo' => 'LOGO URL格式不正确', 'app_url' => '站点URL格式不正确', 'tos_url' => '服务条款URL格式不正确'];
            $keys_arr = array_keys($msg);
            foreach ($param as $kk => $vl) {
                if (in_array($kk, $keys_arr) && !empty($vl) && !$this->checkUrl($vl)) {
                    return back()->withErrors(['status' => $msg[$kk]]);
                    return redirect(route('admin.config'))->withErrors(['status'=>'背景图不存在']);
                }
            }
            //货币无法修改
//            unset($param['currency']);
//            unset($param['currency_symbol']);
            $param['force_https'] = isset($param['force_https'])?1:0;
            $param['stop_register'] = isset($param['stop_register'])?1:0;

//            Config::set('mail.from.name', config('v2board.app_name', 'V2Board'));
            Cache::forever('mail.from.name', $param['app_name']);
            Cache::forever('mail.from.url', $param['app_url']);
        }

        if ($config_type == 'sys_safe') {
            $param['email_verify'] = isset($param['email_verify'])?1:0;
            $param['email_gmail_limit_enable'] = isset($param['email_gmail_limit_enable'])?1:0;
            $param['safe_mode_enable'] = isset($param['safe_mode_enable'])?1:0;
            $param['email_whitelist_enable'] = isset($param['email_whitelist_enable'])?1:0;
            $param['recaptcha_enable'] = isset($param['recaptcha_enable'])?1:0;
            $param['register_limit_by_ip_enable'] = isset($param['register_limit_by_ip_enable'])?1:0;
            $param['password_limit_enable'] = isset($param['password_limit_enable'])?1:0;

            $param['email_whitelist_suffix'] = !empty($param['email_whitelist_suffix'])?explode(',',$param['email_whitelist_suffix']):[];
        }

        if ($config_type == 'sys_plan') {
            $param['plan_change_enable'] = isset($param['plan_change_enable'])?1:0;
            $param['surplus_enable'] = isset($param['surplus_enable'])?1:0;
            $param['show_info_to_server_enable'] = isset($param['show_info_to_server_enable'])?1:0;
        }

        if ($config_type == 'sys_invite') {
            $param['invite_force'] = isset($param['invite_force'])?1:0;
            $param['invite_never_expire'] = isset($param['invite_never_expire'])?1:0;
            $param['commission_first_time_enable'] = isset($param['commission_first_time_enable'])?1:0;
            $param['commission_auto_check_enable'] = isset($param['commission_auto_check_enable'])?1:0;
            $param['withdraw_close_enable'] = isset($param['withdraw_close_enable'])?1:0;
            $param['commission_distribution_enable'] = isset($param['commission_distribution_enable'])?1:0;

            $param['commission_withdraw_method'] = !empty($param['commission_withdraw_method'])?explode(',',$param['commission_withdraw_method']):[];
        }

        if ($config_type == 'sys_personal') {

//            if (!empty($param['frontend_background_url']) && !$this->checkFileExists($param['frontend_background_url'])) {
//                return back()->withErrors(['status' => '背景图不存在']);
//            }
            $param['frontend_theme_sidebar'] = isset($param['frontend_theme_sidebar'])?'light':'dark';
            $param['frontend_theme_header'] = isset($param['frontend_theme_header'])?'light':'dark';
        }

        if ($config_type == 'sys_node') {

            if (!empty($param['server_token']) && strlen($param['server_token'])<17) {
                return back()->withErrors(['status' => '通讯密钥长度必须大于16位']);
            }
        }

        if($config_type == 'sys_email'){
            if(!empty($param['email_host'])){
                Cache::forever('mail.host', $param['email_host']);
//            Config::set('mail.port', config('v2board.email_port', env('mail.port')));
                Cache::forever('mail.port', $param['email_port']);
//            Config::set('mail.encryption', config('v2board.email_encryption', env('mail.encryption')));
                Cache::forever('mail.encryption', $param['email_encryption']);
//            Config::set('mail.username', config('v2board.email_username', env('mail.username')));
                Cache::forever('mail.username', $param['email_username']);
//            Config::set('mail.password', config('v2board.email_password', env('mail.password')));
                Cache::forever('mail.password', $param['email_password']);
//            Config::set('mail.from.address', config('v2board.email_from_address', env('mail.from.address')));
                Cache::forever('mail.from.address', $param['email_from_address']);

                Cache::forever('mail.email_template', $param['email_template']);
            }


        }

        if ($config_type == 'sys_telegram') {

            if (!empty($param['telegram_discuss_link']) && !$this->checkUrl($param['telegram_discuss_link'])) {
                return back()->withErrors(['status' => 'Telegram群组地址必须为URL格式']);
            }
            $param['telegram_bot_enable'] = isset($param['telegram_bot_enable'])?1:0;
            Cache::forever('telegram_bot_token', $param['telegram_bot_token']);
        }

        if ($config_type == 'sys_app') {

            if (!empty($param['forward_url']) && !$this->checkUrl($param['forward_url'])) {
                return back()->withErrors(['status' => '转发url地址必须为URL格式']);
            }
//            $param['telegram_bot_enable'] = isset($param['telegram_bot_enable'])?1:0;
            Cache::forever('forward_url', $param['forward_url']);
            Cache::forever('api_key', $param['api_key']);
        }

        unset($param['_token']);
        unset($param['_method']);
        unset($param['config_type']);
//        Config::where('name', $config_type)->update([
//            'val' => json_encode($param)
//        ]);

        $all_config = config('v2board');
        $file_data=array_merge($all_config,$param);

        $data = var_export($file_data, 1);
        if (!File::put(base_path() . '/config/v2board.php', "<?php\n return $data ;")) {
//            abort(500, '修改失败');
            return back()->withErrors(['status' => '修改失败']);
        }
        if (function_exists('opcache_reset')) {
            if (opcache_reset() === false) {
//                abort(500, '缓存清除失败，请卸载或检查opcache配置状态');
                return back()->withErrors(['status' => '缓存清除失败，请卸载或检查opcache配置状态']);
            }
        }
        Artisan::call('config:cache');
//        return redirect(route('admin.config'))->with(['status'=>'更新成功']);
        return back()->with(['status' => '更新成功']);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    /**
     * 发送邮件
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function send(Request $request)
    {
        $obj = new SendEmailJob([
//            'email' => $request->user['email'],
//            'email' => '2423550953@qq.com',
            'email' =>env('email_test'),
//            'subject' => 'This is v2board test email',
            'subject' => 'This is test email',
            'template_name' => 'notify',
            'template_value' => [
//                'name' => config('v2board.app_name', 'V2Board'),
                'name' => Cache::get('mail.from.name','V2Board'),
//                'content' => 'This is v2board test email',
                'content' => 'This is test email',
//                'url' => config('v2board.app_url')
                'url' => Cache::get('mail.from.url','/')
            ]
        ]);


        $res=$obj->handle();
        if($res['error']){
            return response()->json(['code'=>1,'msg'=>'发送失败'.$res['error']]);
        }

        return response()->json(['code'=>0,'msg'=>'发送成功']);


//        return response([
//            'data' => true,
//            'log' => $obj->handle()
//        ]);
    }


    public function telegram(Request $request)
    {
        $this_token=$request->input('telegram_bot_token');
        $save_token=Cache::get('telegram_bot_token', $this_token);
//        $hookUrl = url('/api/v1/guest/telegram/webhook?access_token=' . md5(config('v2board.telegram_bot_token', $request->input('telegram_bot_token'))));
        $hookUrl = url('/guest/telegram/webhook?access_token=' . md5($save_token));

//        $telegramService = new TelegramService($request->input('telegram_bot_token'));
        $telegramService = new TelegramService($this_token);

        $res1=$telegramService->getMe();
        if(is_numeric($res1)&&$res1==500){
            return response()->json(['code'=>1,'msg'=>'设置失败']);
        }
        $res2=$telegramService->setWebhook($hookUrl);
        if(is_numeric($res2)&&$res2==500){
            return response()->json(['code'=>1,'msg'=>'设置失败']);
        }


        return response()->json(['code'=>0,'msg'=>'设置成功']);
        return response([
            'data' => true
        ]);
    }

}
