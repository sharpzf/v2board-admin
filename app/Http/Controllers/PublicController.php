<?php
namespace App\Http\Controllers;

use App\Traits\Msg;
use Illuminate\Http\Request;
use zgldh\QiniuStorage\QiniuStorage;
use Illuminate\Support\Facades\Cache;
use App\Models\Config;

class PublicController extends Controller
{
    use Msg;
    //图片上传处理
    public function uploadImg(Request $request)
    {

        //上传文件最大大小,单位M
        $maxSize = 10;
        //支持的上传图片类型
        $allowed_extensions = ["png", "jpg", "gif"];
        //返回信息json
        $data = ['code'=>200, 'msg'=>'上传失败', 'data'=>''];
        $file = $request->file('file');

        //检查文件是否上传完成
        if ($file->isValid()){
            //检测图片类型
            $ext = $file->getClientOriginalExtension();
            if (!in_array(strtolower($ext),$allowed_extensions)){
                $data['msg'] = "请上传".implode(",",$allowed_extensions)."格式的图片";
                return response()->json($data);
            }
            //检测图片大小
            if ($file->getClientSize() > $maxSize*1024*1024){
                $data['msg'] = "图片大小限制".$maxSize."M";
                return response()->json($data);
            }
        }else{
            $data['msg'] = $file->getErrorMessage();
            return response()->json($data);
        }
        $newFile = date('Y-m-d')."_".time()."_".uniqid().".".$file->getClientOriginalExtension();
        $disk = QiniuStorage::disk('qiniu');
        $res = $disk->put($newFile,file_get_contents($file->getRealPath()));
        if($res){
            $data = [
                'code'  => 0,
                'msg'   => '上传成功',
                'data'  => $newFile,
                'url'   => $disk->downloadUrl($newFile)
            ];
        }else{
            $data['data'] = $file->getErrorMessage();
        }
        return response()->json($data);
    }


    public function adminApi(){
//echo '<pre>';
//print_r($_REQUEST);exit;
        $forward_url=Cache::get('forward_url', '');
        $api_key=Cache::get('api_key', '');
//        echo 23333333;exit;
        if(!$forward_url || !$api_key){
            $config_info = Config::where('name', 'sys_app')
                ->select(['name', 'val'])
                ->first()->toArray();
            $temp_arr = json_decode($config_info['val'], true);

            $forward_url=$temp_arr['forward_url'];
            $api_key=$temp_arr['api_key'];
            Cache::forever('forward_url', $forward_url);
            Cache::forever('api_key', $api_key);
        }
        // $api_name = base64_decode(xor_enc(base64_decode($_REQUEST['api_name'])));
        $api_name = self::xor_enc(base64_decode($_REQUEST['api_name']),$api_key);
        // $api_name = base64_decode(xor_enc($_REQUEST['api_name']));
        $method = $_REQUEST['method']??'';


        $request_options=isset($_REQUEST['options'])?$_REQUEST['options']:'';
        // $options_json = base64_decode(xor_enc(base64_decode($_REQUEST['options'])));
//        $options_json = self::xor_enc(base64_decode($_REQUEST['options']),$api_key);
        $options_json = self::xor_enc(base64_decode($request_options),$api_key);
        $options_json=json_decode($options_json,true);


        if(!empty($options_json)){
            foreach($options_json as $key=>$val){
                $options_json[$key]=self::unicodeDecode($val);
            }
        }
        echo '<pre>';
        print_r($options_json);
        print_r($api_name);
        exit;

        $options_json=json_encode($options_json);
        if(empty($api_name)){
            exit("接口名称不存在");
        }
        if(!in_array($method,['get','post'])){
            exit("接口请求方式错误");
        }
        $forward_url='http://www.laravel-layui.com/api/v1';
        $function = 'v'.$method;
        // $url = 'https://v1b.yihrt.one/api/v1'.$api_name;

//        $url = 'https://v3-admin.yihrt.one/api/v1'.$api_name;
        $url = $forward_url.$api_name;
//echo $url;exit;
        $json = self::$function($url,$options_json);
        // file_put_contents($filename,'ret:'.$json.PHP_EOL.PHP_EOL,FILE_APPEND);
        $ret = base64_encode(self::xor_enc($json,$api_key));
        // $ret = xor_enc(base64_encode($json));
        exit($ret);
    }


    public static $http = array (
100 => 'HTTP/1.1 100 Continue',
101 => 'HTTP/1.1 101 Switching Protocols',
102 => 'HTTP/1.1 102 Processing',            // RFC2518
103 => 'HTTP/1.1 103 Early Hints',
200 => 'HTTP/1.1 200 OK',
201 => 'HTTP/1.1 201 Created',
202 => 'HTTP/1.1 202 Accepted',
203 => 'HTTP/1.1 203 Non-Authoritative Information',
204 => 'HTTP/1.1 204 No Content',
205 => 'HTTP/1.1 205 Reset Content',
206 => 'HTTP/1.1 206 Partial Content',
207 => 'HTTP/1.1 207 Multi-Status',          // RFC4918
208 => 'HTTP/1.1 208 Already Reported',      // RFC5842
226 => 'HTTP/1.1 226 IM Used',               // RFC3229
300 => 'HTTP/1.1 300 Multiple Choices',
301 => 'HTTP/1.1 301 Moved Permanently',
302 => 'HTTP/1.1 302 Found',
303 => 'HTTP/1.1 303 See Other',
304 => 'HTTP/1.1 304 Not Modified',
305 => 'HTTP/1.1 305 Use Proxy',
307 => 'HTTP/1.1 307 Temporary Redirect',
308 => 'HTTP/1.1 308 Permanent Redirect',    // RFC7238
400 => 'HTTP/1.1 400 Bad Request',
401 => 'HTTP/1.1 401 Unauthorized',
402 => 'HTTP/1.1 402 Payment Required',
403 => 'HTTP/1.1 403 Forbidden',
404 => 'HTTP/1.1 404 Not Found',
405 => 'HTTP/1.1 405 Method Not Allowed',
406 => 'HTTP/1.1 406 Not Acceptable',
407 => 'HTTP/1.1 407 Proxy Authentication Required',
408 => 'HTTP/1.1 408 Request Timeout',
409 => 'HTTP/1.1 409 Conflict',
410 => 'HTTP/1.1 410 Gone',
411 => 'HTTP/1.1 411 Length Required',
412 => 'HTTP/1.1 412 Precondition Failed',
413 => 'HTTP/1.1 413 Content Too Large',                                           // RFC-ietf-httpbis-semantics
414 => 'HTTP/1.1 414 URI Too Long',
415 => 'HTTP/1.1 415 Unsupported Media Type',
416 => 'HTTP/1.1 416 Range Not Satisfiable',
417 => 'HTTP/1.1 417 Expectation Failed',
418 => 'HTTP/1.1 418 I\'m a teapot',                                               // RFC2324
421 => 'HTTP/1.1 421 Misdirected Request',                                         // RFC7540
422 => 'HTTP/1.1 422 Unprocessable Content',                                       // RFC-ietf-httpbis-semantics
423 => 'HTTP/1.1 423 Locked',                                                      // RFC4918
424 => 'HTTP/1.1 424 Failed Dependency',                                           // RFC4918
425 => 'HTTP/1.1 425 Too Early',                                                   // RFC-ietf-httpbis-replay-04
426 => 'HTTP/1.1 426 Upgrade Required',                                            // RFC2817
428 => 'HTTP/1.1 428 Precondition Required',                                       // RFC6585
429 => 'HTTP/1.1 429 Too Many Requests',                                           // RFC6585
431 => 'HTTP/1.1 431 Request Header Fields Too Large',                             // RFC6585
451 => 'HTTP/1.1 451 Unavailable For Legal Reasons',                               // RFC7725
500 => 'HTTP/1.1 500 Internal Server Error',
501 => 'HTTP/1.1 501 Not Implemented',
502 => 'HTTP/1.1 502 Bad Gateway',
503 => 'HTTP/1.1 503 Service Unavailable',
504 => 'HTTP/1.1 504 Gateway Timeout',
505 => 'HTTP/1.1 505 HTTP Version Not Supported',
506 => 'HTTP/1.1 506 Variant Also Negotiates',                                     // RFC2295
507 => 'HTTP/1.1 507 Insufficient Storage',                                        // RFC4918
508 => 'HTTP/1.1 508 Loop Detected',                                               // RFC5842
510 => 'HTTP/1.1 510 Not Extended',                                                // RFC2774
511 => 'HTTP/1.1 511 Network Authentication Required',                             // RFC6585
);

public static function xor_enc($str,$api_key)
    {
        $crytxt = '';
//        $key = 'SIzLm51puIlCewdfDWCgWXQ_Kq-ST242UBEVBhKReO7guLPFH0=';
        $key =$api_key;
        $keylen = strlen($key);
        for($i=0;$i<strlen($str);$i++)
        {
            $k = $i%$keylen;
            $crytxt .= $str[$i] ^ $key[$k];
        }
        return $crytxt;
    }


public static function unicodeDecode($unicode_str){
        $json = '{"str":"'.$unicode_str.'"}';
        $arr = json_decode($json,true);
        if(empty($arr)) return '';
        return $arr['str'];
    }

public static function vget($url,$arr=[]) {
//        global $http;
    $http=self::$http;
            $request_header=isset($_REQUEST['header'])?$_REQUEST['header']:'';

//        $token = json_decode($_REQUEST['header'],true)['Authorization']??'';
        $token = json_decode($request_header,true)['Authorization']??'';
        $info = curl_init();
        curl_setopt($info, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($info, CURLOPT_HEADER, 0);
        curl_setopt($info, CURLOPT_NOBODY, 0);
        curl_setopt($info, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($info, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($info, CURLOPT_HTTPHEADER, array(
            'Authorization:'.$token
        ));
        curl_setopt($info, CURLOPT_URL, $url);
        $output = curl_exec($info);
        $httpCode = curl_getinfo($info, CURLINFO_HTTP_CODE);
        header($http[$httpCode]);
        curl_close($info);
        return $output;
    }

public static function vpost($url, $data = null)
    {
//        global $http;
        $http=self::$http;
    $request_header=isset($_REQUEST['header'])?$_REQUEST['header']:'';
//        $token = json_decode($_REQUEST['header'],true)['Authorization']??'';
        $token = json_decode($request_header,true)['Authorization']??'';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, TRUE);
            curl_setopt($curl, CURLOPT_POSTFIELDS,$data);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Authorization:'.$token
            ));
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        $output = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        header($http[$httpCode]);
        curl_close($curl);
        return $output;
    }

}