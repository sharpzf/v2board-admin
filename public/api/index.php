<?php


$http = array (
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

function index(){
    $filename = './log/'.date('Ymd').'.log';


    // $api_name = base64_decode(xor_enc(base64_decode($_REQUEST['api_name'])));
    $api_name = xor_enc(base64_decode($_REQUEST['api_name']));
    // $api_name = base64_decode(xor_enc($_REQUEST['api_name']));
    $method = $_REQUEST['method']??'';



    // $options_json = base64_decode(xor_enc(base64_decode($_REQUEST['options'])));
    $options_json = xor_enc(base64_decode($_REQUEST['options']));
    $options_json=json_decode($options_json,true);


    if(!empty($options_json)){
        foreach($options_json as $key=>$val){
            $options_json[$key]=unicodeDecode($val);
        }
    }

    $options_json=json_encode($options_json);
    //  exit($options_json);
    //  $options_json = base64_decode(xor_enc($_REQUEST['options']));

    // file_put_contents($filename,'request:'.json_encode($_REQUEST).PHP_EOL,FILE_APPEND);
    // file_put_contents($filename,'api_name:'.$api_name.PHP_EOL,FILE_APPEND);
    // file_put_contents($filename,'options_json:'.$options_json.PHP_EOL,FILE_APPEND);

    //     $method='get';
    // $api_name='/guest/comm/config';
    if(empty($api_name)){
        exit("接口名称不存在");
    }
    if(!in_array($method,['get','post'])){
        exit("接口请求方式错误");
    }
    $function = 'v'.$method;
    // $url = 'https://v1b.yihrt.one/api/v1'.$api_name;
    $url = 'https://v3-admin.yihrt.one/api/v1'.$api_name;
    $json = $function($url,$options_json);
    // file_put_contents($filename,'ret:'.$json.PHP_EOL.PHP_EOL,FILE_APPEND);
    $ret = base64_encode(xor_enc($json));
    // $ret = xor_enc(base64_encode($json));
    exit($ret);
}

function xor_enc($str)
{
    $crytxt = '';
    $key = 'SIzLm51puIlCewdfDWCgWXQ_Kq-ST242UBEVBhKReO7guLPFH0=';
    $keylen = strlen($key);
    for($i=0;$i<strlen($str);$i++)
    {
        $k = $i%$keylen;
        $crytxt .= $str[$i] ^ $key[$k];
    }
    return $crytxt;
}


function unicodeDecode($unicode_str){
    $json = '{"str":"'.$unicode_str.'"}';
    $arr = json_decode($json,true);
    if(empty($arr)) return '';
    return $arr['str'];
}

function vget($url,$arr=[]) {
    global $http;
    $token = json_decode($_REQUEST['header'],true)['Authorization']??'';
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

function vpost($url, $data = null)
{
    global $http;
    $token = json_decode($_REQUEST['header'],true)['Authorization']??'';
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

index();

?>

