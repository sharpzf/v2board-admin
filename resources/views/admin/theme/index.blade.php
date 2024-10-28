@extends('admin.base')

@section('content')

    <style>
        .layui-form-mid{
            float: right;
        }
        .layui-input-inline input{
            width:200%;
        }
        .layui-select-title input{
            width:100%;
        }
        .try_out_hour_hide,.email_whitelist_suffix_hide,.recaptcha_key_hide,
        .recaptcha_site_key_hide,.password_limit_expire_hide,
        .password_limit_count_hide,.register_limit_expire_hide,.register_limit_count_hide,
        .commission_distribution_l1_hide,.commission_distribution_l2_hide,.commission_distribution_l3_hide,.send_telegram_hide{
            display: none;
        }
        
        .personal{
            color: #FF5722;
        }
        .personal a{
            color: #01AAED;
        }
    </style>    
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            {{--<h2>站点配置</h2>--}}
            <div class="personal">如果你采用前后分离的方式部署V2board，那么主题配置将不会生效。了解
                <a  target="_blank" href="https://docs.v2board.com/use/advanced.html#%E5%89%8D%E7%AB%AF%E5%88%86%E7%A6%BB">前后分离</a>
            </div>

            {{--<div>配置v2board主题</div>--}}

        </div>
        <div class="layui-card-body">
            <form class="layui-form" action="{{route('admin.config.update')}}" method="post">
                {{csrf_field()}}
                {{method_field('put')}}
                <div class="layui-form-item">
                    <label for="" class="layui-form-label">主题</label>
                    <div class="layui-input-block">
                        {{--<span class="layui-input">v2board</span>--}}
                        <div class="layui-form-mid layui-word-aux" style="float: left">v2board</div>
                        {{--<input type="text" value="v2board" class="layui-btn layui-btn-radius layui-btn-disabled" >--}}
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="" class="layui-form-label">主题描述</label>
                    <div class="layui-input-block">
                        <div class="layui-form-mid layui-word-aux" style="float: left">v2board</div>
                        {{--<span class="layui-input">v2board</span>--}}
                        {{--<input type="text" name="title" value="{{ $config['title']??'' }}" lay-verify="required" placeholder="请输入标题" class="layui-input" >--}}
                    </div>
                </div>

                <div class="layui-form-item">
                    <label for="" class="layui-form-label">主题色</label>
                    <div class="layui-input-inline">
                        <select name="theme_color" lay-filter="theme_color">
                            <option value="default"  @if($theme['sys_theme']['theme_color']=='default') selected @endif>默认(蓝色)</option>
                            <option value="green"  @if($theme['sys_theme']['theme_color']=='green') selected @endif>奶绿色</option>
                            <option value="black"  @if($theme['sys_theme']['theme_color']=='black') selected @endif>黑色</option>
                            <option value="darkblue"  @if($theme['sys_theme']['theme_color']=='darkblue') selected @endif>暗蓝色</option>
                        </select>
                    </div>
                    {{--<div class="layui-form-mid layui-word-aux">选择需要试用的订阅，如果没有选项请先前往订阅管理添加</div>--}}
                </div>

                <div class="layui-form-item">
                    <label for="" class="layui-form-label">背景</label>
                    <div class="layui-input-inline">
                        {{--<input type="text" name="title" value="{{ $config['title']??'' }}" lay-verify="required" placeholder="用于显示需要站点名称的地方" class="layui-input" >--}}
                        <input type="text" name="background_url" value="{{ $theme['sys_theme']['background_url']??'' }}"   placeholder="请输入背景图片URL" autocomplete="off" class="layui-input">
                    </div>
                    {{--<div class="layui-form-mid layui-word-aux">用于显示需要站点名称的地方</div>--}}
                </div>


                <div class="layui-form-item">
                    <label for="" class="layui-form-label">边栏风格</label>
                    <div class="layui-input-inline">
                        <select name="theme_sidebar" lay-filter="theme_sidebar">
                            <option value="light"  @if($theme['sys_theme']['theme_sidebar']=='light') selected @endif>亮</option>
                            <option value="dark"  @if($theme['sys_theme']['theme_sidebar']=='dark') selected @endif>暗</option>
                        </select>
                    </div>
                    {{--<div class="layui-form-mid layui-word-aux">选择需要试用的订阅，如果没有选项请先前往订阅管理添加</div>--}}
                </div>


                <div class="layui-form-item">
                    <label for="" class="layui-form-label">顶部风格</label>
                    <div class="layui-input-inline">
                        <select name="theme_header" lay-filter="theme_header">
                            <option value="light"  @if($theme['sys_theme']['theme_header']=='light') selected @endif>亮</option>
                            <option value="dark"  @if($theme['sys_theme']['theme_header']=='dark') selected @endif>暗</option>
                        </select>
                    </div>
                    {{--<div class="layui-form-mid layui-word-aux">选择需要试用的订阅，如果没有选项请先前往订阅管理添加</div>--}}
                </div>

                <div class="layui-form-item">
                    <label for="" class="layui-form-label">自定义页脚HTML</label>
                    <div class="layui-input-inline" style="width:36.5%;">
                        {{--<textarea class="layui-textarea" name="description" cols="30" rows="4" placeholder="请输入订阅URL，末尾不要/逗号分割支持多域名">{{ $config['description']??'' }}</textarea>--}}
                        <textarea class="layui-textarea" name="custom_html" rows="6" cols="30" placeholder="可以实现客服JS代码的加入等">{{ $theme['sys_theme']['custom_html']??'' }}</textarea>
                    </div>
                    {{--<div class="layui-form-mid layui-word-aux">用于订阅所使用，留空则为站点URL如需多个订阅URL随机获取请使用逗号进行分割</div>--}}
                </div>



                <input type="hidden" name="config_type" value="sys_theme">

                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button type="submit" class="layui-btn" lay-submit="" lay-filter="formDemo">确 认</button>
                    </div>
                </div>
            </form>
        </div>
    </div>




    </div>


@endsection


@section('script')
    <script>
        layui.use(['layer','table','form'],function () {
            var layer = layui.layer;
            var form = layui.form;
            var table = layui.table;
            //用户表格初始化
            {{--var dataTable = table.render({--}}
                {{--elem: '#dataTable'--}}
                {{--,height: 300--}}
                {{--,url: "{{route('admin.message.getUser')}}" //数据接口--}}
                {{--,page: true //开启分页--}}
                {{--,cols: [[ //表头--}}
                    {{--{checkbox: true,fixed: true}--}}
                    {{--,{field: 'id', title: 'ID', sort: true,width:80}--}}
                    {{--,{field: 'name', title: '用户名'}--}}
                    {{--,{field: 'phone', title: '电话'}--}}
                {{--]]--}}
            {{--});--}}
            //搜索
            // $(".tos_url").blur(function () {
            //     alert(21321);
            //     // var keywords = $("input[name='keywords']").val();
            //     // var user_type = $("select[name='user_type']").val();
            //     // dataTable.reload({
            //     //     page:{curr:1},
            //     //     where:{keywords:keywords,user_type:user_type}
            //     // })
            // });

            //监听select选择
            form.on('select(try_out_plan_id)', function(data){
                if(data.value>0){
                    $('.try_out_hour').show();
                }else{
                    // alert(222,data.value);
                    $('.try_out_hour').hide();
                }
            });

            //邮箱后缀白名单
            form.on('switch(email_whitelist_enable)', function(data){
                if(data.elem.checked){
                    $('.email_whitelist_suffix').show();
                }else{
                    $('.email_whitelist_suffix').hide();
                }
            });

            //防机器人
            form.on('switch(recaptcha_enable)', function(data){
                if(data.elem.checked){
                    $('.recaptcha_key').show();
                    $('.recaptcha_site_key').show();
                }else{
                    $('.recaptcha_key').hide();
                    $('.recaptcha_site_key').hide();
                }
            });


            //IP注册限制
            form.on('switch(register_limit_by_ip_enable)', function(data){
                if(data.elem.checked){
                    $('.register_limit_count').show();
                    $('.register_limit_expire').show();
                }else{

                    $('.register_limit_count').hide();
                    $('.register_limit_expire').hide();
                }
            });


            //防爆破限制
            form.on('switch(password_limit_enable)', function(data){
                if(data.elem.checked){
                    $('.password_limit_count').show();
                    $('.password_limit_expire').show();
                }else{
                    $('.password_limit_count').hide();
                    $('.password_limit_expire').hide();
                }
            });



            //三级分销
            form.on('switch(commission_distribution_enable)', function(data){
                if(data.elem.checked){
                    $('.commission_distribution_l1').show();
                    $('.commission_distribution_l2').show();
                    $('.commission_distribution_l3').show();
                }else{
                    $('.commission_distribution_l1').hide();
                    $('.commission_distribution_l2').hide();
                    $('.commission_distribution_l3').hide();
                }
            });

            //发送邮件
            $(".send_mail").click(function () {

                var loadingIndex = layer.load(1, {
                    shade: [0.1,'#fff'] //0.1透明度的白色背景
                });
                var url22=$(this).attr('data-url');
                $.get(url22,function (res) {

                    layer.closeAll("loading");
                    layer.msg(res.msg);
                },'json');

                return false;
            });


            //发送telegram
            $(".send_telegram").click(function () {

                var loadingIndex = layer.load(1, {
                    shade: [0.1,'#fff'] //0.1透明度的白色背景
                });
                var url22=$(this).attr('data-url');
                var telegram_bot_token=$.trim($('.telegram_bot_token').val());
                var url22=url22+'?telegram_bot_token='+telegram_bot_token;
                $.get(url22,function (res) {
                    layer.closeAll("loading");
                    layer.msg(res.msg);
                },'json');

                return false;



                $.get({
                    url: url22,
                    data: { "telegram_bot_token": telegram_bot_token },
                    beforeSend: function () {
                        layer.closeAll("loading");
                        //layer.msg('正在切换' + branch_name + '达达配送的状态' + ',请稍候!');
                    },
                    error: function (data) {
                        layer.closeAll("loading");
                        layer.msg("数据异常，操作失败");
                    },
                    success: function (data) {
                        // layer.msg(data);
                            layer.closeAll("loading");
                            layer.msg(res.msg);
                    },
                    // dataType: 'json'
                });
                return false;//很重要，防止冒泡


                // $.get(url22,function (res) {
                //
                //     layer.closeAll("loading");
                //     layer.msg(res.msg);
                // },'json');

                // return false;
            });

            $('.telegram_bot_token').blur(function(){
                if($.trim($(this).val())){
                    $('.send_telegram').show();
                }else{
                    $('.send_telegram').hide();
                }
            });
        })
    </script>
@endsection

