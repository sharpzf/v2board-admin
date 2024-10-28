<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use App\Models\MailLog;
use Illuminate\Support\Facades\Cache;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $params;

    public $tries = 3;
    public $timeout = 10;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($params, $queue = 'send_email')
    {
        $this->onQueue($queue);
        $this->params = $params;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
//        if (config('v2board.email_host')) {
        if (Cache::get('mail.host')) {
//            Config::set('mail.host', config('v2board.email_host', env('mail.host')));
//            $email_host=Cache::get('mail.host')?Cache::get('mail.host'):env('email_host');
            Config::set('mail.host', Cache::get('mail.host',env('email_host')));

//            Config::set('mail.port', config('v2board.email_port', env('mail.port')));
//            $email_port=Cache::get('mail.port')?Cache::get('mail.port'):env('email_port');
            Config::set('mail.port', Cache::get('mail.port',env('email_port')));

//            Config::set('mail.encryption', config('v2board.email_encryption', env('mail.encryption')));
//            $email_encryption=Cache::get('mail.encryption')?Cache::get('mail.encryption'):env('email_encryption');
            Config::set('mail.encryption', Cache::get('mail.encryption',env('email_encryption')));

//            Config::set('mail.username', config('v2board.email_username', env('mail.username')));
//            $email_username=Cache::get('mail.username')?Cache::get('mail.username'):env('email_username');
            Config::set('mail.username', Cache::get('mail.username',env('email_username')));

//            Config::set('mail.password', config('v2board.email_password', env('mail.password')));
//            $email_password=Cache::get('mail.password')?Cache::get('mail.password'):env('email_password');
            Config::set('mail.password', Cache::get('mail.password',env('email_password')));

//            Config::set('mail.from.address', config('v2board.email_from_address', env('mail.from.address')));
//            $email_from_address=Cache::get('mail.from.address')?Cache::get('mail.from.address'):env('email_from_address');
            Config::set('mail.from.address', Cache::get('mail.from.address',env('email_from_address')));

//            Config::set('mail.from.name', config('v2board.app_name', 'V2Board'));
//            $app_name=Cache::get('mail.from.name')?Cache::get('mail.from.name'):'V2Board';
            Config::set('mail.from.name',Cache::get('mail.from.name','V2Board'));
        }
        $params = $this->params;
        $email = $params['email'];
        $subject = $params['subject'];

        $select_email_template=Cache::get('mail.email_template',env('email_template'));
//        $params['template_name'] = 'mail.' . config('v2board.email_template', 'default') . '.' . $params['template_name'];
        $params['template_name'] = 'mail.' . $select_email_template . '.' . $params['template_name'];

        try {
            Mail::send(
                $params['template_name'],
                $params['template_value'],
                function ($message) use ($email, $subject) {
                    $message->to($email)->subject($subject);
                }
            );
        } catch (\Exception $e) {
            $error = $e->getMessage();
        }

        $log = [
            'email' => $params['email'],
            'subject' => $params['subject'],
            'template_name' => $params['template_name'],
            'error' => isset($error) ? $error : NULL
        ];

        MailLog::create($log);
        $log['config'] = config('mail');
        return $log;
    }
}
