<?php

namespace App\Http\Controllers\Guest;

use App\Utils\Dict;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class CommController extends Controller
{
    public function config()
    {

        return response([
            'data' => [
                'tos_url' => config('v2board.tos_url'),
                'is_email_verify' => (int)config('v2board.email_verify', 0) ? 1 : 0,
                'is_invite_force' => (int)config('v2board.invite_force', 0) ? 1 : 0,
                'email_whitelist_suffix' => (int)config('v2board.email_whitelist_enable', 0)
                    ? $this->getEmailSuffix()
                    : 0,
                'is_recaptcha' => (int)config('v2board.recaptcha_enable', 0) ? 1 : 0,
                'recaptcha_site_key' => config('v2board.recaptcha_site_key'),
                'app_description' => config('v2board.app_description'),
                'app_url' => config('v2board.app_url'),
                'logo' => config('v2board.logo'),
            ]
        ]);
    }


    public function app()
{

    return response([
        'data' => [
//            'api_key' => config('v2board.api_key',''),
//            'forward_url' => (int)config('v2board.forward_url', ''),
            'windows_version' => config('v2board.windows_version', ''),
            'windows_reg_url' => config('v2board.windows_reg_url', ''),
            'windows_pwd_url' => config('v2board.windows_pwd_url', ''),
            'windows_download_url1' => config('v2board.windows_download_url1', ''),
            'windows_download_url2' => config('v2board.windows_download_url2', ''),
            'windows_api_url1' => config('v2board.windows_api_url1', ''),
            'windows_api_url2' => config('v2board.windows_api_url2', ''),
            'windows_buy_url1' => config('v2board.windows_buy_url1', ''),
            'windows_buy_url2' => config('v2board.windows_buy_url2', ''),
            'macos_version' => config('v2board.macos_version', ''),
            'macos_reg_url' => config('v2board.macos_reg_url', ''),
            'macos_pwd_url' => config('v2board.macos_pwd_url', ''),
            'macos_download_url1' => config('v2board.macos_download_url1', ''),
            'macos_download_url2' => config('v2board.macos_download_url2', ''),
            'macos_api_url1' => config('v2board.macos_api_url1', ''),
            'macos_api_url2' => config('v2board.macos_api_url2', ''),
            'macos_buy_url1' => config('v2board.macos_buy_url1', ''),
            'macos_buy_url2' => config('v2board.macos_buy_url2', ''),
            'android_version' => config('v2board.android_version', ''),
            'android_reg_url' => config('v2board.android_reg_url', ''),
            'android_pwd_url' => config('v2board.android_pwd_url', ''),
            'android_download_url1' => config('v2board.android_download_url1', ''),
            'android_download_url2' => config('v2board.android_download_url2', ''),
            'android_api_url1' => config('v2board.android_api_url1', ''),
            'android_api_url2' => config('v2board.android_api_url2', ''),
            'android_buy_url1' => config('v2board.android_buy_url1', ''),
            'android_buy_url2' => config('v2board.android_buy_url2', ''),
            'ios_version' => config('v2board.ios_version', ''),
            'ios_reg_url' => config('v2board.ios_reg_url', ''),
            'ios_pwd_url' => config('v2board.ios_pwd_url', ''),
            'ios_download_url1' => config('v2board.ios_download_url1', ''),
            'ios_download_url2' => config('v2board.ios_download_url2', ''),
            'ios_api_url1' => config('v2board.ios_api_url1', ''),
            'ios_api_url2' => config('v2board.ios_api_url2', ''),
            'ios_buy_url1' => config('v2board.ios_buy_url1', ''),
            'ios_buy_url2' => config('v2board.ios_buy_url2', ''),
            'contact_email' => config('v2board.contact_email', ''),
            'is_ios_force' => config('v2board.is_ios_force', 0),
            'is_android_force' => config('v2board.is_android_force', 0),
            'is_macos_force' => config('v2board.is_macos_force', 0),
            'is_windows_force' => config('v2board.is_windows_force', 0),
        ]
    ]);
}

    private function getEmailSuffix()
    {
        $suffix = config('v2board.email_whitelist_suffix', Dict::EMAIL_WHITELIST_SUFFIX_DEFAULT);
        if (!is_array($suffix)) {
            return preg_split('/,/', $suffix);
        }
        return $suffix;
    }
}
