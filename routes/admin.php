<?php
/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| 后台公共路由部分
|
*/
Route::group(['namespace'=>'Admin','prefix'=>'admin'],function (){
    //登录、注销
    Route::get('login','LoginController@showLoginForm')->name('admin.loginForm');
    Route::post('login','LoginController@login')->name('admin.login');
    Route::get('logout','LoginController@logout')->name('admin.logout');

});


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| 后台需要授权的路由 admins
|
*/
Route::group(['namespace'=>'Admin','prefix'=>'admin','middleware'=>'auth'],function (){
    //后台布局
    Route::get('/','IndexController@layout')->name('admin.layout');
    //后台首页
    Route::get('/index','IndexController@index')->name('admin.index');
    Route::get('/index1','IndexController@index1')->name('admin.index1');
    Route::get('/index2','IndexController@index2')->name('admin.index2');
    //图标
    Route::get('icons','IndexController@icons')->name('admin.icons');
});

//系统管理
Route::group(['namespace'=>'Admin','prefix'=>'admin','middleware'=>['auth','permission:system.manage']],function (){
    //数据表格接口
    Route::get('data','IndexController@data')->name('admin.data')->middleware('permission:system.role|system.user|system.permission');
    //用户管理
    Route::group(['middleware'=>['permission:system.user']],function (){
        Route::get('user','UserController@index')->name('admin.user');
        //添加
        Route::get('user/create','UserController@create')->name('admin.user.create')->middleware('permission:system.user.create');
        Route::post('user/store','UserController@store')->name('admin.user.store')->middleware('permission:system.user.create');
        //编辑
        Route::get('user/{id}/edit','UserController@edit')->name('admin.user.edit')->middleware('permission:system.user.edit');
        Route::put('user/{id}/update','UserController@update')->name('admin.user.update')->middleware('permission:system.user.edit');
        //删除
        Route::delete('user/destroy','UserController@destroy')->name('admin.user.destroy')->middleware('permission:system.user.destroy');
        //分配角色
        Route::get('user/{id}/role','UserController@role')->name('admin.user.role')->middleware('permission:system.user.role');
        Route::put('user/{id}/assignRole','UserController@assignRole')->name('admin.user.assignRole')->middleware('permission:system.user.role');
        //分配权限
        Route::get('user/{id}/permission','UserController@permission')->name('admin.user.permission')->middleware('permission:system.user.permission');
        Route::put('user/{id}/assignPermission','UserController@assignPermission')->name('admin.user.assignPermission')->middleware('permission:system.user.permission');
    });
    //角色管理
    Route::group(['middleware'=>'permission:system.role'],function (){
        Route::get('role','RoleController@index')->name('admin.role');
        //添加
        Route::get('role/create','RoleController@create')->name('admin.role.create')->middleware('permission:system.role.create');
        Route::post('role/store','RoleController@store')->name('admin.role.store')->middleware('permission:system.role.create');
        //编辑
        Route::get('role/{id}/edit','RoleController@edit')->name('admin.role.edit')->middleware('permission:system.role.edit');
        Route::put('role/{id}/update','RoleController@update')->name('admin.role.update')->middleware('permission:system.role.edit');
        //删除
        Route::delete('role/destroy','RoleController@destroy')->name('admin.role.destroy')->middleware('permission:system.role.destroy');
        //分配权限
        Route::get('role/{id}/permission','RoleController@permission')->name('admin.role.permission')->middleware('permission:system.role.permission');
        Route::put('role/{id}/assignPermission','RoleController@assignPermission')->name('admin.role.assignPermission')->middleware('permission:system.role.permission');
    });
    //权限管理
    Route::group(['middleware'=>'permission:system.permission'],function (){
        Route::get('permission','PermissionController@index')->name('admin.permission');
        //添加
        Route::get('permission/create','PermissionController@create')->name('admin.permission.create')->middleware('permission:system.permission.create');
        Route::post('permission/store','PermissionController@store')->name('admin.permission.store')->middleware('permission:system.permission.create');
        //编辑
        Route::get('permission/{id}/edit','PermissionController@edit')->name('admin.permission.edit')->middleware('permission:system.permission.edit');
        Route::put('permission/{id}/update','PermissionController@update')->name('admin.permission.update')->middleware('permission:system.permission.edit');
        //删除
        Route::delete('permission/destroy','PermissionController@destroy')->name('admin.permission.destroy')->middleware('permission:system.permission.destroy');
    });

    //菜单管理
    Route::group(['middleware'=>'permission:system.menu'],function (){
        Route::get('menu','MenuController@index')->name('admin.menu');
        Route::get('menu/data','MenuController@data')->name('admin.menu.data');
        //添加
        Route::get('menu/create','MenuController@create')->name('admin.menu.create')->middleware('permission:system.menu.create');
        Route::post('menu/store','MenuController@store')->name('admin.menu.store')->middleware('permission:system.menu.create');
        //编辑
        Route::get('menu/{id}/edit','MenuController@edit')->name('admin.menu.edit')->middleware('permission:system.menu.edit');
        Route::put('menu/{id}/update','MenuController@update')->name('admin.menu.update')->middleware('permission:system.menu.edit');
        //删除
        Route::delete('menu/destroy','MenuController@destroy')->name('admin.menu.destroy')->middleware('permission:system.menu.destroy');
    });
});


//资讯管理
Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'middleware' => ['auth', 'permission:zixun.manage']], function () {
    //分类管理
    Route::group(['middleware' => 'permission:zixun.category'], function () {
        Route::get('category/data', 'CategoryController@data')->name('admin.category.data');
        Route::get('category', 'CategoryController@index')->name('admin.category');
        //添加分类
        Route::get('category/create', 'CategoryController@create')->name('admin.category.create')->middleware('permission:zixun.category.create');
        Route::post('category/store', 'CategoryController@store')->name('admin.category.store')->middleware('permission:zixun.category.create');
        //编辑分类
        Route::get('category/{id}/edit', 'CategoryController@edit')->name('admin.category.edit')->middleware('permission:zixun.category.edit');
        Route::put('category/{id}/update', 'CategoryController@update')->name('admin.category.update')->middleware('permission:zixun.category.edit');
        //删除分类
        Route::delete('category/destroy', 'CategoryController@destroy')->name('admin.category.destroy')->middleware('permission:zixun.category.destroy');
    });
    //文章管理
    Route::group(['middleware' => 'permission:zixun.article'], function () {
        Route::get('article/data', 'ArticleController@data')->name('admin.article.data');
        Route::get('article', 'ArticleController@index')->name('admin.article');
        //添加
        Route::get('article/create', 'ArticleController@create')->name('admin.article.create')->middleware('permission:zixun.article.create');
        Route::post('article/store', 'ArticleController@store')->name('admin.article.store')->middleware('permission:zixun.article.create');
        //编辑
        Route::get('article/{id}/edit', 'ArticleController@edit')->name('admin.article.edit')->middleware('permission:zixun.article.edit');
        Route::put('article/{id}/update', 'ArticleController@update')->name('admin.article.update')->middleware('permission:zixun.article.edit');
        //删除
        Route::delete('article/destroy', 'ArticleController@destroy')->name('admin.article.destroy')->middleware('permission:zixun.article.destroy');
    });
    //标签管理
    Route::group(['middleware' => 'permission:zixun.tag'], function () {
        Route::get('tag/data', 'TagController@data')->name('admin.tag.data');
        Route::get('tag', 'TagController@index')->name('admin.tag');
        //添加
        Route::get('tag/create', 'TagController@create')->name('admin.tag.create')->middleware('permission:zixun.tag.create');
        Route::post('tag/store', 'TagController@store')->name('admin.tag.store')->middleware('permission:zixun.tag.create');
        //编辑
        Route::get('tag/{id}/edit', 'TagController@edit')->name('admin.tag.edit')->middleware('permission:zixun.tag.edit');
        Route::put('tag/{id}/update', 'TagController@update')->name('admin.tag.update')->middleware('permission:zixun.tag.edit');
        //删除
        Route::delete('tag/destroy', 'TagController@destroy')->name('admin.tag.destroy')->middleware('permission:zixun.tag.destroy');
    });
});
//配置管理
Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'middleware' => ['auth', 'permission:config.manage']], function () {
    //站点配置
    Route::group(['middleware' => 'permission:config.site'], function () {
        Route::get('site', 'SiteController@index')->name('admin.site');
        Route::put('site', 'SiteController@update')->name('admin.site.update')->middleware('permission:config.site.update');
    });

    //系统配置
    Route::group(['middleware' => 'permission:config.config'], function () {
        Route::get('config', 'ConfigController@index')->name('admin.config');
        Route::get('config/send', 'ConfigController@send')->name('admin.config.send')->middleware('permission:config.config.send');
        Route::get('config/telegram', 'ConfigController@telegram')->name('admin.config.telegram')->middleware('permission:config.config.telegram');
        Route::put('config', 'ConfigController@update')->name('admin.config.update')->middleware('permission:config.config.update');
    });
    //支付配置
    Route::group(['middleware' => 'permission:config.payment'], function () {
        Route::get('payment/data', 'PaymentController@data')->name('admin.payment.data');
        Route::get('payment','PaymentController@index')->name('admin.payment');
        //添加
        Route::get('payment/create','PaymentController@create')->name('admin.payment.create')->middleware('permission:config.payment.create');
        Route::post('payment/store','PaymentController@store')->name('admin.payment.store')->middleware('permission:config.payment.create');
        //编辑
        Route::get('payment/{id}/edit','PaymentController@edit')->name('admin.payment.edit')->middleware('permission:config.payment.edit');
        Route::put('payment/{id}/update','PaymentController@update')->name('admin.payment.update')->middleware('permission:config.payment.edit');
        //删除
        Route::delete('payment/destroy','PaymentController@destroy')->name('admin.payment.destroy')->middleware('permission:config.payment.destroy');

    });

    //主题配置
    Route::group(['middleware' => 'permission:config.theme'], function () {
        Route::get('theme', 'ConfigController@theme')->name('admin.theme');
        Route::put('theme', 'ConfigController@update')->name('admin.theme.update')->middleware('permission:config.theme.update');
    });






    //广告位
    Route::group(['middleware' => 'permission:config.position'], function () {
        Route::get('position/data', 'PositionController@data')->name('admin.position.data');
        Route::get('position', 'PositionController@index')->name('admin.position');
        //添加
        Route::get('position/create', 'PositionController@create')->name('admin.position.create')->middleware('permission:config.position.create');
        Route::post('position/store', 'PositionController@store')->name('admin.position.store')->middleware('permission:config.position.create');
        //编辑
        Route::get('position/{id}/edit', 'PositionController@edit')->name('admin.position.edit')->middleware('permission:config.position.edit');
        Route::put('position/{id}/update', 'PositionController@update')->name('admin.position.update')->middleware('permission:config.position.edit');
        //删除
        Route::delete('position/destroy', 'PositionController@destroy')->name('admin.position.destroy')->middleware('permission:config.position.destroy');
    });
    //广告信息
    Route::group(['middleware' => 'permission:config.advert'], function () {
        Route::get('advert/data', 'AdvertController@data')->name('admin.advert.data');
        Route::get('advert', 'AdvertController@index')->name('admin.advert');
        //添加
        Route::get('advert/create', 'AdvertController@create')->name('admin.advert.create')->middleware('permission:config.advert.create');
        Route::post('advert/store', 'AdvertController@store')->name('admin.advert.store')->middleware('permission:config.advert.create');
        //编辑
        Route::get('advert/{id}/edit', 'AdvertController@edit')->name('admin.advert.edit')->middleware('permission:config.advert.edit');
        Route::put('advert/{id}/update', 'AdvertController@update')->name('admin.advert.update')->middleware('permission:config.advert.edit');
        //删除
        Route::delete('advert/destroy', 'AdvertController@destroy')->name('admin.advert.destroy')->middleware('permission:config.advert.destroy');
    });
});
//会员管理
Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'middleware' => ['auth', 'permission:member.manage']], function () {
    //账号管理
    Route::group(['middleware' => 'permission:member.member'], function () {
        Route::get('member/data', 'MemberController@data')->name('admin.member.data');
        Route::get('member', 'MemberController@index')->name('admin.member');
        //添加
        Route::get('member/create', 'MemberController@create')->name('admin.member.create')->middleware('permission:member.member.create');
        Route::post('member/store', 'MemberController@store')->name('admin.member.store')->middleware('permission:member.member.create');
        //编辑
        Route::get('member/{id}/edit', 'MemberController@edit')->name('admin.member.edit')->middleware('permission:member.member.edit');
        Route::put('member/{id}/update', 'MemberController@update')->name('admin.member.update')->middleware('permission:member.member.edit');
        //删除
        Route::delete('member/destroy', 'MemberController@destroy')->name('admin.member.destroy')->middleware('permission:member.member.destroy');
    });
});
//消息管理
Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'middleware' => ['auth', 'permission:message.manage']], function () {
    //消息管理
    Route::group(['middleware' => 'permission:message.message'], function () {
        Route::get('message/data', 'MessageController@data')->name('admin.message.data');
        Route::get('message/getUser', 'MessageController@getUser')->name('admin.message.getUser');
        Route::get('message', 'MessageController@index')->name('admin.message');
        //添加
        Route::get('message/create', 'MessageController@create')->name('admin.message.create')->middleware('permission:message.message.create');
        Route::post('message/store', 'MessageController@store')->name('admin.message.store')->middleware('permission:message.message.create');
        //删除
        Route::delete('message/destroy', 'MessageController@destroy')->name('admin.message.destroy')->middleware('permission:message.message.destroy');
        //我的消息
        Route::get('mine/message', 'MessageController@mine')->name('admin.message.mine')->middleware('permission:message.message.mine');
        Route::post('message/{id}/read', 'MessageController@read')->name('admin.message.read')->middleware('permission:message.message.mine');

        Route::get('message/count', 'MessageController@getMessageCount')->name('admin.message.get_count');
    });

});



//服务器
Route::group(['namespace'=>'Admin','prefix'=>'admin','middleware'=>['auth','permission:server.manage']],function (){


    //节点管理
    Route::group(['middleware'=>['permission:server.node']],function (){
        Route::get('node/data', 'Server\\NodeController@data')->name('admin.node.data');
        Route::get('node','Server\\NodeController@index')->name('admin.node');
        //添加
        Route::get('node/create','Server\\NodeController@create')->name('admin.node.create')->middleware('permission:server.node.create');
        Route::post('node/store','Server\\NodeController@store')->name('admin.node.store')->middleware('permission:server.node.create');
        //编辑
        Route::get('node/{id}/edit','Server\\NodeController@edit')->name('admin.node.edit')->middleware('permission:server.node.edit');
        Route::post('node/{id}/update','Server\\NodeController@update')->name('admin.node.update')->middleware('permission:server.node.edit');
        //删除
        Route::delete('node/destroy','Server\\NodeController@destroy')->name('admin.node.destroy')->middleware('permission:server.node.destroy');

        //复制
        Route::get('node/copy','Server\\NodeController@copy')->name('admin.node.copy')->middleware('permission:server.node.copy');
    });

    //权限组管理
    Route::group(['middleware'=>['permission:server.group']],function (){
//        Route::get('Server/group','Server\\GroupController@index')->name('server.group');
        Route::get('group/data', 'Server\\GroupController@data')->name('admin.group.data');
        Route::get('group','Server\\GroupController@index')->name('admin.group');
        //添加
        Route::get('group/create','Server\\GroupController@create')->name('admin.group.create')->middleware('permission:server.group.create');
        Route::post('group/store','Server\\GroupController@store')->name('admin.group.store')->middleware('permission:server.group.create');
        //编辑
        Route::get('group/{id}/edit','Server\\GroupController@edit')->name('admin.group.edit')->middleware('permission:server.group.edit');
        Route::put('group/{id}/update','Server\\GroupController@update')->name('admin.group.update')->middleware('permission:server.group.edit');
        //删除
        Route::delete('group/destroy','Server\\GroupController@destroy')->name('admin.group.destroy')->middleware('permission:server.group.destroy');

    });

    //路由管理
    Route::group(['middleware'=>['permission:server.route']],function (){
//        Route::get('Server/group','Server\\GroupController@index')->name('server.group');
        Route::get('route/data', 'Server\\RouteController@data')->name('admin.route.data');
        Route::get('route','Server\\RouteController@index')->name('admin.route');
        //添加
        Route::get('route/create','Server\\RouteController@create')->name('admin.route.create')->middleware('permission:server.route.create');
        Route::post('route/store','Server\\RouteController@store')->name('admin.route.store')->middleware('permission:server.route.create');
        //编辑
        Route::get('route/{id}/edit','Server\\RouteController@edit')->name('admin.route.edit')->middleware('permission:server.route.edit');
        Route::put('route/{id}/update','Server\\RouteController@update')->name('admin.route.update')->middleware('permission:server.route.edit');
        //删除
        Route::delete('route/destroy','Server\\RouteController@destroy')->name('admin.route.destroy')->middleware('permission:server.route.destroy');
    });

});




//财务
Route::group(['namespace'=>'Admin','prefix'=>'admin','middleware'=>['auth','permission:finance.manage']],function (){
    //数据表格接口
//    Route::get('data','IndexController@data')->name('admin.data')->middleware('permission:system.role|system.user|system.permission');
    //订阅管理
    Route::group(['middleware'=>['permission:finance.plan']],function (){
//        Route::get('Server/group','Server\\GroupController@index')->name('server.group');
        Route::get('plan/data', 'PlanController@data')->name('admin.plan.data');
        Route::get('plan','PlanController@index')->name('admin.plan');
//        Route::post('plan/set','PlanController@set')->name('admin.plan.set');
        //添加
        Route::get('plan/create','PlanController@create')->name('admin.plan.create')->middleware('permission:finance.plan.create');
        Route::post('plan/store','PlanController@store')->name('admin.plan.store')->middleware('permission:finance.plan.create');
        //编辑
        Route::get('plan/{id}/edit','PlanController@edit')->name('admin.plan.edit')->middleware('permission:finance.plan.edit');
        Route::put('plan/{id}/update','PlanController@update')->name('admin.plan.update')->middleware('permission:finance.plan.edit');
        //删除
        Route::delete('plan/destroy','PlanController@destroy')->name('admin.plan.destroy')->middleware('permission:finance.plan.destroy');
  });



    //订单管理
    Route::group(['middleware'=>['permission:finance.order']],function (){
//        Route::get('Server/group','Server\\GroupController@index')->name('server.group');
        Route::get('order/data', 'OrderController@data')->name('admin.order.data');
        Route::get('order','OrderController@index')->name('admin.order');
//        Route::post('plan/set','PlanController@set')->name('admin.plan.set');
        //添加
        Route::get('order/{id?}/create','OrderController@create')->name('admin.order.create')->middleware('permission:finance.order.create');
        Route::post('order/store','OrderController@store')->name('admin.order.store')->middleware('permission:finance.order.create');
        //详情
        Route::get('order/detail','OrderController@detail')->name('admin.order.detail')->middleware('permission:finance.order.detail');
//        Route::put('plan/{id}/update','PlanController@update')->name('admin.plan.update')->middleware('permission:finance.plan.edit');
        //订单状态-取消
        Route::get('order/cancel','OrderController@cancel')->name('admin.order.cancel')->middleware('permission:finance.order.cancel');
        //订单状态-已支付
        Route::get('order/paid','OrderController@paid')->name('admin.order.paid')->middleware('permission:finance.order.paid');
       //修改佣金状态
        Route::get('order/set','OrderController@update')->name('admin.order.set')->middleware('permission:finance.order.set');
    });


    //优惠卷管理
    Route::group(['middleware'=>['permission:finance.coupon']],function (){
        Route::get('coupon/data', 'CouponController@data')->name('admin.coupon.data');
        Route::get('coupon','CouponController@index')->name('admin.coupon');
//        Route::post('plan/set','PlanController@set')->name('admin.plan.set');
        //添加
        Route::get('coupon/create','CouponController@create')->name('admin.coupon.create')->middleware('permission:finance.coupon.create');
        Route::post('coupon/store','CouponController@store')->name('admin.coupon.store')->middleware('permission:finance.coupon.create');
        //编辑
        Route::get('coupon/{id}/edit','CouponController@edit')->name('admin.coupon.edit')->middleware('permission:finance.coupon.edit');
        Route::put('coupon/{id}/update','CouponController@update')->name('admin.coupon.update')->middleware('permission:finance.coupon.edit');
        //删除
        Route::delete('coupon/destroy','CouponController@destroy')->name('admin.coupon.destroy')->middleware('permission:finance.coupon.destroy');
    });
});




//v2board 用户
Route::group(['namespace'=>'Admin','prefix'=>'admin','middleware'=>['auth','permission:v2board.member']],function (){

    //v2board用户管理
    Route::group(['middleware'=>['permission:v2board.user']],function (){

        Route::get('v2user/data', 'V2userController@data')->name('admin.v2user.data');
        Route::get('v2user','V2userController@index')->name('admin.v2user');

        //添加
        Route::get('v2user/create','V2userController@create')->name('admin.v2user.create')->middleware('permission:v2board.user.create');
        Route::post('v2user/store','V2userController@store')->name('admin.v2user.store')->middleware('permission:v2board.user.create');
        //编辑
        Route::get('v2user/{id}/edit','V2userController@edit')->name('admin.v2user.edit')->middleware('permission:v2board.user.edit');
        Route::put('v2user/{id}/update','V2userController@update')->name('admin.v2user.update')->middleware('permission:v2board.user.edit');
        //重置UUID及订阅URL
        Route::get('v2user/reset','V2userController@reset')->name('admin.v2user.reset')->middleware('permission:v2board.user.reset');
        //导出CSV
        Route::post('v2user/csv','V2userController@csv')->name('admin.v2user.csv')->middleware('permission:v2board.user.csv');
        //发送邮件
        Route::post('v2user/send','V2userController@send')->name('admin.v2user.send')->middleware('permission:v2board.user.send');
        //批量封禁
        Route::delete('v2user/ban','V2userController@ban')->name('admin.v2user.ban')->middleware('permission:v2board.user.ban');
        //复制订阅URL
//        Route::delete('v2user/copy','V2userController@ban')->name('admin.v2user.ban')->middleware('permission:v2board.user.ban');
    });


    //流量记录
    Route::group(['middleware'=>['permission:v2board.discharge']],function (){

        Route::get('discharge/data', 'StatController@data')->name('admin.discharge.data');
        Route::get('discharge','StatController@index')->name('admin.discharge');
    });


    //公告管理
    Route::group(['middleware'=>['permission:v2board.notice']],function (){

        Route::get('notice/data', 'NoticeController@data')->name('admin.notice.data');
        Route::get('notice','NoticeController@index')->name('admin.notice');

        //添加
        Route::get('notice/create','NoticeController@create')->name('admin.notice.create')->middleware('permission:v2board.notice.create');
        Route::post('notice/store','NoticeController@store')->name('admin.notice.store')->middleware('permission:v2board.notice.create');
        //编辑
        Route::get('notice/{id}/edit','NoticeController@edit')->name('admin.notice.edit')->middleware('permission:v2board.notice.edit');
        Route::put('notice/{id}/update','NoticeController@update')->name('admin.notice.update')->middleware('permission:v2board.notice.edit');

        Route::delete('notice/destroy','NoticeController@destroy')->name('admin.notice.destroy')->middleware('permission:v2board.notice.destroy');

    });


    //知识库管理
    Route::group(['middleware'=>['permission:v2board.knowledge']],function (){

        Route::get('knowledge/data', 'KnowledgeController@data')->name('admin.knowledge.data');
        Route::get('knowledge','KnowledgeController@index')->name('admin.knowledge');

        //添加
        Route::get('knowledge/create','KnowledgeController@create')->name('admin.knowledge.create')->middleware('permission:v2board.knowledge.create');
        Route::post('knowledge/store','KnowledgeController@store')->name('admin.knowledge.store')->middleware('permission:v2board.knowledge.create');
        //编辑
        Route::get('knowledge/{id}/edit','KnowledgeController@edit')->name('admin.knowledge.edit')->middleware('permission:v2board.knowledge.edit');
        Route::put('knowledge/{id}/update','KnowledgeController@update')->name('admin.knowledge.update')->middleware('permission:v2board.knowledge.edit');

        Route::delete('knowledge/destroy','KnowledgeController@destroy')->name('admin.knowledge.destroy')->middleware('permission:v2board.knowledge.destroy');

    });


    //工单管理
    Route::group(['middleware'=>['permission:v2board.ticket']],function (){

        Route::get('ticket/data', 'TicketController@data')->name('admin.ticket.data');
        Route::get('ticket','TicketController@index')->name('admin.ticket');

        //详情
        Route::get('ticket/{id}/detail','TicketController@detail')->name('admin.ticket.detail')->middleware('permission:v2board.ticket.detail');
        //关闭
        Route::get('ticket/{id}/close','TicketController@close')->name('admin.ticket.close')->middleware('permission:v2board.ticket.close');
        //回复
        Route::any('ticket/reply','TicketController@reply')->name('admin.ticket.reply')->middleware('permission:v2board.ticket.reply');


    });

});





//指标
Route::group(['namespace'=>'Admin','prefix'=>'admin','middleware'=>['auth','permission:monitor.manage']],function (){

    //队列监控
    Route::group(['middleware'=>['permission:monitor.monitor']],function (){

//        Route::get('monitor/data', 'StatController@data')->name('admin.monitor.data');
        Route::get('monitor','SystemController@index')->name('admin.monitor');
        Route::get('monitor/getQueueWorkload','SystemController@getQueueWorkload')->name('admin.monitor.getQueueWorkload');
        Route::get('monitor/getQueueStats','SystemController@getQueueStats')->name('admin.monitor.getQueueStats');
    });


});