<?php
//文件上传接口，前后台共用
Route::post('uploadImg', 'PublicController@uploadImg')->name('uploadImg');
//文件上传接口，前后台共用
Route::any('adminApi', 'PublicController@adminApi')->name('adminApi');
//发送短信
Route::post('/sendMsg', 'PublicController@sendMsg')->name('sendMsg');

Route::get('/','Home\IndexController@index')->name('home');

//支付
Route::group(['namespace' => 'Home'], function () {
    //微信支付
    Route::get('/wechatPay', 'PayController@wechatPay')->name('wechatPay');
    //微信支付回调
    Route::post('/wechatNotify', 'PayController@wechatNotify')->name('wechatNofity');
});

//会员-不需要认证
Route::group(['namespace'=>'Home','prefix'=>'member'],function (){
    //注册
    Route::get('register', 'MemberController@showRegisterForm')->name('home.member.showRegisterForm');
    Route::post('register', 'MemberController@register')->name('home.member.register');
    //登录
    Route::get('login', 'MemberController@showLoginForm')->name('home.member.showLoginForm');
    Route::post('login', 'MemberController@login')->name('home.member.login');
});
//会员-需要认证
Route::group(['namespace'=>'Home','prefix'=>'member','middleware'=>'member'],function (){
    //个人中心
    Route::get('/','MemberController@index')->name('home.member');
    //退出
    Route::get('logout', 'MemberController@logout')->name('home.member.logout');
});


//会员-需要认证
Route::group(['namespace'=>'Guest','prefix'=>'admin','middleware'=>['auth','permission:config.manage']],function (){

    //系统配置
    Route::group(['middleware' => 'permission:config.config'], function () {
//        Route::get('config', 'ConfigController@index')->name('admin.config');
//        Route::get('config/send', 'ConfigController@send')->name('admin.config.send')->middleware('permission:config.config.send');
        Route::get('guest/telegram/webhook', 'TelegramController@webhook')->name('guest.telegram.webhook')->middleware('permission:config.config.telegram');
//        Route::put('config', 'ConfigController@update')->name('admin.config.update')->middleware('permission:config.config.update');
    });
});