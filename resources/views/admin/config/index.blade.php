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
    {{--<div class="layui-card">--}}
        {{--<div class="layui-card-header layuiadmin-card-header-auto">--}}
            {{--<h2>站点配置</h2>--}}
        {{--</div>--}}
        {{--<div class="layui-card-body">--}}
            {{--<form class="layui-form" action="{{route('admin.site.update')}}" method="post">--}}
                {{--{{csrf_field()}}--}}
                {{--{{method_field('put')}}--}}
                {{--<div class="layui-form-item">--}}
                    {{--<label for="" class="layui-form-label">站点标题</label>--}}
                    {{--<div class="layui-input-block">--}}
                        {{--<input type="text" name="title" value="{{ $config['title']??'' }}" lay-verify="required" placeholder="请输入标题" class="layui-input" >--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="layui-form-item">--}}
                    {{--<label for="" class="layui-form-label">站点关键词</label>--}}
                    {{--<div class="layui-input-block">--}}
                        {{--<input type="text" name="keywords" value="{{ $config['keywords']??'' }}" lay-verify="required" placeholder="请输入关键词" class="layui-input" >--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="layui-form-item">--}}
                    {{--<label for="" class="layui-form-label">站点描述</label>--}}
                    {{--<div class="layui-input-block">--}}
                        {{--<textarea class="layui-textarea" name="description" cols="30" rows="10">{{ $config['description']??'' }}</textarea>--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="layui-form-item">--}}
                    {{--<label for="" class="layui-form-label">CopyRight</label>--}}
                    {{--<div class="layui-input-block">--}}
                        {{--<input type="text" name="copyright" value="{{ $config['copyright']??'' }}" lay-verify="required" placeholder="请输入copyright" class="layui-input" >--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="layui-form-item">--}}
                    {{--<label for="" class="layui-form-label">电话</label>--}}
                    {{--<div class="layui-input-block">--}}
                        {{--<input type="text" name="phone" value="{{ $config['phone']??'' }}" lay-verify="required" placeholder="请输入电话" class="layui-input" >--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="layui-form-item">--}}
                    {{--<label for="" class="layui-form-label">城市</label>--}}
                    {{--<div class="layui-input-block">--}}
                        {{--<input type="text" name="city" value="{{ $config['city']??'' }}" lay-verify="required" placeholder="请输入城市" class="layui-input" >--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="layui-form-item">--}}
                    {{--<div class="layui-input-block">--}}
                        {{--<button type="submit" class="layui-btn" lay-submit="" lay-filter="formDemo">确 认</button>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</form>--}}
        {{--</div>--}}
    {{--</div>--}}




    <div class="layui-card">

        <div class="layui-tab layui-tab-brief layadmin-latestData">

            <ul class="layui-tab-title">

                <li class="layui-this">站点</li>
                <li>安全</li>
                <li>订阅</li>
                <li>邀请&佣金</li>
                {{--<li>个性化</li>--}}
                <li>节点</li>
                <li>邮件</li>
                <li>Telegram</li>
                <li>APP</li>

            </ul>

            <div class="layui-tab-content">

                <div class="layui-tab-item layui-show">

                    {{--<table id="LAY-index-topSearch">1111</table>--}}

                    <div class="layui-card-body">
                        <form class="layui-form" action="{{route('admin.config.update')}}" method="post">
                            {{csrf_field()}}
                            {{method_field('put')}}
                            <div class="layui-form-item">
                                <label for="" class="layui-form-label">站点名称</label>
                                <div class="layui-input-inline">
                                    {{--<input type="text" name="title" value="{{ $config['title']??'' }}" lay-verify="required" placeholder="用于显示需要站点名称的地方" class="layui-input" >--}}
                                    <input type="text" name="app_name" value="{{ $config['sys_site']['app_name']??'' }}"   placeholder="请输入站点名称" autocomplete="off" class="layui-input">
                                </div>
                                <div class="layui-form-mid layui-word-aux">用于显示需要站点名称的地方</div>
                            </div>
                            <div class="layui-form-item">
                                <label for="" class="layui-form-label">站点描述</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="app_description" value="{{ $config['sys_site']['app_description']??'' }}" placeholder="请输入站点描述" class="layui-input" >
                                </div>
                                <div class="layui-form-mid layui-word-aux">用于显示需要站点描述的地方</div>
                            </div>

                            <div class="layui-form-item">
                                <label for="" class="layui-form-label">站点网址</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="app_url" value="{{ $config['sys_site']['app_url']??'' }}" placeholder="请输入站点URL，末尾不要/" class="layui-input" >
                                </div>
                                <div class="layui-form-mid layui-word-aux">当前网站最新网址，将会在邮件等需要用于网址处体现</div>
                            </div>
                            <div class="layui-form-item">
                                <label for="" class="layui-form-label">强制HTTPS</label>
                                <div class="layui-input-inline">
                                    <input type="checkbox" name="force_https" lay-skin="switch"  @if(isset($config['sys_site']['force_https'])&&$config['sys_site']['force_https']==1) checked @endif>
                                    {{--<input type="text" name="app_url" value="{{ $config['app_url']??'' }}" placeholder="当前网站最新网址，将会在邮件等需要用于网址处体现" class="layui-input" >--}}
                                </div>
                                <div class="layui-form-mid layui-word-aux">当站点没有使用HTTPS，CDN或反代开启强制HTTPS时需要开启</div>
                            </div>



                            <div class="layui-form-item">
                                <label for="" class="layui-form-label">LOGO</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="logo" value="{{ $config['sys_site']['logo']??'' }}" placeholder="请输入LOGO URL，末尾不要/" class="layui-input" >
                                    {{--<input type="text" name="app_url" value="{{ $config['app_url']??'' }}" placeholder="当前网站最新网址，将会在邮件等需要用于网址处体现" class="layui-input" >--}}
                                </div>
                                <div class="layui-form-mid layui-word-aux">用于显示需要LOGO的地方</div>
                            </div>


                            <div class="layui-form-item">
                                <label for="" class="layui-form-label">订阅URL</label>
                                <div class="layui-input-inline" style="width: 36.5%;">
                                    {{--<textarea class="layui-textarea" name="description" cols="30" rows="4" placeholder="请输入订阅URL，末尾不要/逗号分割支持多域名">{{ $config['description']??'' }}</textarea>--}}
                                    <textarea class="layui-textarea" name="subscribe_url" rows="6" placeholder="请输入订阅URL，末尾不要/逗号分割支持多域名">{{ $config['sys_site']['subscribe_url']??'' }}</textarea>
                                </div>
                                <div class="layui-form-mid layui-word-aux">用于订阅所使用，留空则为站点URL如需多个订阅URL随机获取请使用逗号进行分割</div>
                            </div>

                            <div class="layui-form-item">
                                <label for="" class="layui-form-label">用户条款(TOS)URL</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="tos_url" value="{{ $config['sys_site']['tos_url']??'' }}" placeholder="请输入用户条款URL，末尾不要/" class="layui-input tos_url" >
                                    {{--<input type="text" name="app_url" value="{{ $config['app_url']??'' }}" placeholder="当前网站最新网址，将会在邮件等需要用于网址处体现" class="layui-input" >--}}
                                </div>
                                <div class="layui-form-mid layui-word-aux">用于跳转到用户条款(TOS)</div>
                            </div>


                            <div class="layui-form-item">
                                <label for="" class="layui-form-label">停止新用户注册</label>
                                <div class="layui-input-inline">
                                    <input type="checkbox" name="stop_register" lay-skin="switch"  @if(isset($config['sys_site']['stop_register'])&&$config['sys_site']['stop_register']==1) checked @endif >
                                    {{--<input type="text" name="app_url" value="{{ $config['app_url']??'' }}" placeholder="当前网站最新网址，将会在邮件等需要用于网址处体现" class="layui-input" >--}}
                                </div>
                                <div class="layui-form-mid layui-word-aux">开启后任何人都将无法进行注册</div>
                            </div>



                            <div class="layui-form-item">
                                <label for="" class="layui-form-label">注册试用</label>
                                <div class="layui-input-inline">
                                    <select name="try_out_plan_id" lay-filter="try_out_plan_id">
                                        <option value="0"  @if($config['sys_site']['try_out_plan_id']==0) selected @endif>关闭</option>
                                        @foreach($plans as $kk=>$first)
{{--                                            <option value="{{ $kk }}" @if(isset($plan)&&$plan->group_id==$first['id']) selected @endif>{{ $first['name'] }}</option>--}}
                                            <option value="{{ $kk }}" @if($config['sys_site']['try_out_plan_id']==$kk) selected @endif >{{ $first }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="layui-form-mid layui-word-aux">选择需要试用的订阅，如果没有选项请先前往订阅管理添加</div>
                            </div>


                            <div class="layui-form-item try_out_hour @if($config['sys_site']['try_out_plan_id']==0) try_out_hour_hide @endif">
                                <label for="" class="layui-form-label">试用时间(小时)</label>
                                <div class="layui-input-inline">
                                    <input type="number" name="try_out_hour" value="{{ $config['sys_site']['try_out_hour']??'' }}" placeholder="请输入" class="layui-input" >
                                    {{--<input type="text" name="app_url" value="{{ $config['app_url']??'' }}" placeholder="当前网站最新网址，将会在邮件等需要用于网址处体现" class="layui-input" >--}}
                                </div>
                                {{--<div class="layui-form-mid layui-word-aux">仅用于展示使用，更改后系统中所有的货币单位都将发生变更</div>--}}
                            </div>



                            <div class="layui-form-item">
                                <label for="" class="layui-form-label">货币单位</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="currency" value="{{ $config['sys_site']['currency']??'' }}" placeholder="CNY" class="layui-input" >
                                    {{--<input type="text" name="app_url" value="{{ $config['app_url']??'' }}" placeholder="当前网站最新网址，将会在邮件等需要用于网址处体现" class="layui-input" >--}}
                                </div>
                                <div class="layui-form-mid layui-word-aux">仅用于展示使用，更改后系统中所有的货币单位都将发生变更</div>
                            </div>

                            <div class="layui-form-item">
                                <label for="" class="layui-form-label">货币符号</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="currency_symbol" value="{{ $config['sys_site']['currency_symbol']??'' }}" placeholder="¥" class="layui-input" >
                                    {{--<input type="text" name="app_url" value="{{ $config['app_url']??'' }}" placeholder="当前网站最新网址，将会在邮件等需要用于网址处体现" class="layui-input" >--}}
                                </div>
                                <div class="layui-form-mid layui-word-aux">仅用于展示使用，更改后系统中所有的货币单位都将发生变更</div>
                            </div>

                            <input type="hidden" name="config_type" value="sys_site">

                            <div class="layui-form-item">
                                <div class="layui-input-block">
                                    <button type="submit" class="layui-btn" lay-submit="" lay-filter="formDemo">确 认</button>
                                </div>
                            </div>
                        </form>
                    </div>


                </div>

                <div class="layui-tab-item">
                    {{--<table id="LAY-index-topCard">安全</table>--}}
                    <div class="layui-card-body">
                        <form class="layui-form" action="{{route('admin.config.update')}}" method="post">
                            {{csrf_field()}}
                            {{method_field('put')}}

                            <div class="layui-form-item">
                                <label for="" class="layui-form-label">邮箱验证</label>
                                <div class="layui-input-inline">
                                    <input type="checkbox" name="email_verify" lay-skin="switch"  @if(isset($config['sys_safe']['email_verify'])&&$config['sys_safe']['email_verify']==1) checked @endif>
                                    {{--<input type="text" name="app_url" value="{{ $config['app_url']??'' }}" placeholder="当前网站最新网址，将会在邮件等需要用于网址处体现" class="layui-input" >--}}
                                </div>
                                <div class="layui-form-mid layui-word-aux">开启后将会强制要求用户进行邮箱验证。</div>
                            </div>



                            <div class="layui-form-item">
                                <label for="" class="layui-form-label">禁止使用Gmail多别名</label>
                                <div class="layui-input-inline">
                                    <input type="checkbox" name="email_gmail_limit_enable" lay-skin="switch"  @if(isset($config['sys_safe']['email_gmail_limit_enable'])&&$config['sys_safe']['email_gmail_limit_enable']==1) checked @endif>
                                    {{--<input type="text" name="app_url" value="{{ $config['app_url']??'' }}" placeholder="当前网站最新网址，将会在邮件等需要用于网址处体现" class="layui-input" >--}}
                                </div>
                                <div class="layui-form-mid layui-word-aux">开启后Gmail多别名将无法注册。</div>
                            </div>



                            <div class="layui-form-item">
                                <label for="" class="layui-form-label">安全模式</label>
                                <div class="layui-input-inline">
                                    <input type="checkbox" name="safe_mode_enable" lay-skin="switch"  @if(isset($config['sys_safe']['safe_mode_enable'])&&$config['sys_safe']['safe_mode_enable']==1) checked @endif>
                                    {{--<input type="text" name="app_url" value="{{ $config['app_url']??'' }}" placeholder="当前网站最新网址，将会在邮件等需要用于网址处体现" class="layui-input" >--}}
                                </div>
                                <div class="layui-form-mid layui-word-aux">开启后除了站点URL以外的绑定本站点的域名访问都将会被403。</div>
                            </div>


                            <div class="layui-form-item">
                                <label for="" class="layui-form-label">后台路径</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="secure_path" value="{{ $config['sys_safe']['secure_path']??'' }}" placeholder="admin" class="layui-input" >
                                    {{--<input type="text" name="app_url" value="{{ $config['app_url']??'' }}" placeholder="当前网站最新网址，将会在邮件等需要用于网址处体现" class="layui-input" >--}}
                                </div>
                                <div class="layui-form-mid layui-word-aux">后台管理路径，修改后将会改变原有的admin路径</div>
                            </div>


                            <div class="layui-form-item">
                                <label for="" class="layui-form-label">邮箱后缀白名单</label>
                                <div class="layui-input-inline">
                                    <input type="checkbox" lay-filter="email_whitelist_enable" name="email_whitelist_enable" lay-skin="switch"  @if(isset($config['sys_safe']['email_whitelist_enable'])&&$config['sys_safe']['email_whitelist_enable']==1) checked @endif>
                                    {{--<input type="text" name="app_url" value="{{ $config['app_url']??'' }}" placeholder="当前网站最新网址，将会在邮件等需要用于网址处体现" class="layui-input" >--}}
                                </div>
                                <div class="layui-form-mid layui-word-aux">开启后在名单中的邮箱后缀才允许进行注册。</div>
                            </div>



                            <div class="layui-form-item email_whitelist_suffix @if($config['sys_safe']['email_whitelist_enable']==0) email_whitelist_suffix_hide @endif">
                                <label for="" class="layui-form-label">白名单后缀</label>
                                <div class="layui-input-inline" style="width: 36.5%;">
                                    {{--<textarea class="layui-textarea" name="description" cols="30" rows="4" placeholder="请输入订阅URL，末尾不要/逗号分割支持多域名">{{ $config['description']??'' }}</textarea>--}}
                                    <textarea class="layui-textarea" name="email_whitelist_suffix" rows="6" placeholder="请输入后缀域名，逗号分割 如：qq.com,gmail.com">{{ $config['sys_safe']['email_whitelist_suffix']??'' }}</textarea>
                                </div>
                                <div class="layui-form-mid layui-word-aux">请使用逗号进行分割，如：qq.com,gmail.com</div>
                            </div>




                            <div class="layui-form-item">
                                <label for="" class="layui-form-label">防机器人</label>
                                <div class="layui-input-inline">
                                    <input type="checkbox" name="recaptcha_enable" lay-filter="recaptcha_enable" lay-skin="switch"  @if(isset($config['sys_safe']['recaptcha_enable'])&&$config['sys_safe']['recaptcha_enable']==1) checked @endif>
                                    {{--<input type="text" name="app_url" value="{{ $config['app_url']??'' }}" placeholder="当前网站最新网址，将会在邮件等需要用于网址处体现" class="layui-input" >--}}
                                </div>
                                <div class="layui-form-mid layui-word-aux">开启后将会使用Google reCAPTCHA防止机器人。</div>
                            </div>




                            <div class="layui-form-item recaptcha_key @if($config['sys_safe']['recaptcha_enable']==0) recaptcha_key_hide @endif">
                                <label for="" class="layui-form-label">密钥</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="recaptcha_key" value="{{ $config['sys_safe']['recaptcha_key']??'' }}" placeholder="请输入" class="layui-input" >
                                    {{--<input type="text" name="app_url" value="{{ $config['app_url']??'' }}" placeholder="当前网站最新网址，将会在邮件等需要用于网址处体现" class="layui-input" >--}}
                                </div>
                                <div class="layui-form-mid layui-word-aux">在Google reCAPTCHA申请的密钥</div>
                            </div>



                            <div class="layui-form-item recaptcha_site_key @if($config['sys_safe']['recaptcha_enable']==0) recaptcha_site_key_hide @endif">
                                <label for="" class="layui-form-label">网站密钥</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="recaptcha_site_key" value="{{ $config['sys_safe']['recaptcha_site_key']??'' }}" placeholder="请输入" class="layui-input" >
                                    {{--<input type="text" name="app_url" value="{{ $config['app_url']??'' }}" placeholder="当前网站最新网址，将会在邮件等需要用于网址处体现" class="layui-input" >--}}
                                </div>
                                <div class="layui-form-mid layui-word-aux">在Google reCAPTCH申请的网站密钥</div>
                            </div>



                            <div class="layui-form-item">
                                <label for="" class="layui-form-label">IP注册限制</label>
                                <div class="layui-input-inline">
                                    <input type="checkbox" name="register_limit_by_ip_enable" lay-filter="register_limit_by_ip_enable" lay-skin="switch"  @if(isset($config['sys_safe']['register_limit_by_ip_enable'])&&$config['sys_safe']['register_limit_by_ip_enable']==1) checked @endif>
                                    {{--<input type="text" name="app_url" value="{{ $config['app_url']??'' }}" placeholder="当前网站最新网址，将会在邮件等需要用于网址处体现" class="layui-input" >--}}
                                </div>
                                <div class="layui-form-mid layui-word-aux">开启后如果IP注册账户达到规则要求将会被限制注册，请注意IP判断可能因为CDN或前置代理导致问题。</div>
                            </div>



                            <div class="layui-form-item register_limit_count @if($config['sys_safe']['register_limit_by_ip_enable']==0) register_limit_count_hide @endif">
                                <label for="" class="layui-form-label">次数</label>
                                <div class="layui-input-inline">
                                    <input type="number" name="register_limit_count" value="{{ $config['sys_safe']['register_limit_count']??'' }}" placeholder="请输入" class="layui-input" >
                                    {{--<input type="text" name="app_url" value="{{ $config['app_url']??'' }}" placeholder="当前网站最新网址，将会在邮件等需要用于网址处体现" class="layui-input" >--}}
                                </div>
                                <div class="layui-form-mid layui-word-aux">达到注册次数后开启惩罚</div>
                            </div>



                            <div class="layui-form-item register_limit_expire @if($config['sys_safe']['register_limit_by_ip_enable']==0) register_limit_expire_hide @endif">
                                <label for="" class="layui-form-label">惩罚时间(分钟)</label>
                                <div class="layui-input-inline">
                                    <input type="number" name="register_limit_expire" value="{{ $config['sys_safe']['register_limit_expire']??'' }}" placeholder="请输入" class="layui-input" >
                                    {{--<input type="text" name="app_url" value="{{ $config['app_url']??'' }}" placeholder="当前网站最新网址，将会在邮件等需要用于网址处体现" class="layui-input" >--}}
                                </div>
                                <div class="layui-form-mid layui-word-aux">需要等待惩罚时间过后才可以再次注册</div>
                            </div>





                            <div class="layui-form-item">
                                <label for="" class="layui-form-label">防爆破限制</label>
                                <div class="layui-input-inline">
                                    <input type="checkbox" name="password_limit_enable" lay-filter="password_limit_enable" lay-skin="switch"  @if(isset($config['sys_safe']['password_limit_enable'])&&$config['sys_safe']['password_limit_enable']==1) checked @endif>
                                    {{--<input type="text" name="app_url" value="{{ $config['app_url']??'' }}" placeholder="当前网站最新网址，将会在邮件等需要用于网址处体现" class="layui-input" >--}}
                                </div>
                                <div class="layui-form-mid layui-word-aux">开启后如果该账户尝试登陆失败次数过多将会被限制。</div>
                            </div>



                            <div class="layui-form-item password_limit_count @if($config['sys_safe']['password_limit_enable']==0) password_limit_count_hide @endif">
                                <label for="" class="layui-form-label">次数</label>
                                <div class="layui-input-inline">
                                    <input type="number" name="password_limit_count" value="{{ $config['sys_safe']['password_limit_count']??'' }}" placeholder="请输入" class="layui-input" >
                                    {{--<input type="text" name="app_url" value="{{ $config['app_url']??'' }}" placeholder="当前网站最新网址，将会在邮件等需要用于网址处体现" class="layui-input" >--}}
                                </div>
                                <div class="layui-form-mid layui-word-aux">达到失败次数后开启惩罚</div>
                            </div>



                            <div class="layui-form-item password_limit_expire @if($config['sys_safe']['password_limit_enable']==0) password_limit_expire_hide @endif">
                                <label for="" class="layui-form-label">惩罚时间(分钟)</label>
                                <div class="layui-input-inline">
                                    <input type="number" name="password_limit_expire" value="{{ $config['sys_safe']['password_limit_expire']??'' }}" placeholder="请输入" class="layui-input" >
                                    {{--<input type="text" name="app_url" value="{{ $config['app_url']??'' }}" placeholder="当前网站最新网址，将会在邮件等需要用于网址处体现" class="layui-input" >--}}
                                </div>
                                <div class="layui-form-mid layui-word-aux">需要等待惩罚时间过后才可以再次登陆</div>
                            </div>



                            <input type="hidden" name="config_type" value="sys_safe">

                            <div class="layui-form-item">
                                <div class="layui-input-block">
                                    <button type="submit" class="layui-btn" lay-submit="" lay-filter="formDemo">确 认</button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>

                <div class="layui-tab-item">
                    {{--<table id="LAY-index-topCard">订阅</table>--}}

                    <div class="layui-card-body">
                        <form class="layui-form" action="{{route('admin.config.update')}}" method="post">
                            {{csrf_field()}}
                            {{method_field('put')}}

                            <div class="layui-form-item">
                                <label for="" class="layui-form-label">允许用户更改订阅</label>
                                <div class="layui-input-inline">
                                    <input type="checkbox" name="plan_change_enable" lay-skin="switch"  @if(isset($config['sys_plan']['plan_change_enable'])&&$config['sys_plan']['plan_change_enable']==1) checked @endif>
                                    {{--<input type="text" name="app_url" value="{{ $config['app_url']??'' }}" placeholder="当前网站最新网址，将会在邮件等需要用于网址处体现" class="layui-input" >--}}
                                </div>
                                <div class="layui-form-mid layui-word-aux">开启后用户将会可以对订阅计划进行变更。</div>
                            </div>



                            <div class="layui-form-item">
                                <label for="" class="layui-form-label">月流量重置方式</label>
                                <div class="layui-input-inline">
                                    <select name="reset_traffic_method" lay-filter="reset_traffic_method">

                                        @foreach($reset_traffic_method as $kk=>$first)
                                            {{--                                            <option value="{{ $kk }}" @if(isset($plan)&&$plan->group_id==$first['id']) selected @endif>{{ $first['name'] }}</option>--}}
                                            <option value="{{ $kk }}" @if($config['sys_plan']['reset_traffic_method']==$kk) selected @endif >{{ $first }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="layui-form-mid layui-word-aux">全局流量重置方式，默认每月1号。可以在订阅管理为订阅单独设置</div>
                            </div>



                            <div class="layui-form-item">
                                <label for="" class="layui-form-label">开启折抵方案</label>
                                <div class="layui-input-inline">
                                    <input type="checkbox" name="surplus_enable" lay-skin="switch"  @if(isset($config['sys_plan']['surplus_enable'])&&$config['sys_plan']['surplus_enable']==1) checked @endif>
                                    {{--<input type="text" name="app_url" value="{{ $config['app_url']??'' }}" placeholder="当前网站最新网址，将会在邮件等需要用于网址处体现" class="layui-input" >--}}
                                </div>
                                <div class="layui-form-mid layui-word-aux">开启后用户更换订阅将会由系统对原有订阅进行折抵，方案参考文档。</div>
                            </div>



                            <div class="layui-form-item">
                                <label for="" class="layui-form-label">当订阅新购时触发事件</label>
                                <div class="layui-input-inline">
                                    <select name="new_order_event_id" lay-filter="new_order_event_id">
                                            <option value="0" @if($config['sys_plan']['new_order_event_id']==0) selected @endif >不执行任何动作</option>
                                            <option value="1" @if($config['sys_plan']['new_order_event_id']==1) selected @endif >重置用户流量</option>

                                    </select>
                                </div>
                                <div class="layui-form-mid layui-word-aux">新购订阅完成时将触发该任务</div>
                            </div>


                            <div class="layui-form-item">
                                <label for="" class="layui-form-label">当订阅续费时触发事件</label>
                                <div class="layui-input-inline">
                                    <select name="renew_order_event_id" lay-filter="renew_order_event_id">
                                        <option value="0" @if($config['sys_plan']['renew_order_event_id']==0) selected @endif >不执行任何动作</option>
                                        <option value="1" @if($config['sys_plan']['renew_order_event_id']==1) selected @endif >重置用户流量</option>

                                    </select>
                                </div>
                                <div class="layui-form-mid layui-word-aux">续费订阅完成时将触发该任务</div>
                            </div>


                            <div class="layui-form-item">
                                <label for="" class="layui-form-label">当订阅变更时触发事件</label>
                                <div class="layui-input-inline">
                                    <select name="change_order_event_id" lay-filter="change_order_event_id">
                                        <option value="0" @if($config['sys_plan']['change_order_event_id']==0) selected @endif >不执行任何动作</option>
                                        <option value="1" @if($config['sys_plan']['change_order_event_id']==1) selected @endif >重置用户流量</option>

                                    </select>
                                </div>
                                <div class="layui-form-mid layui-word-aux">变更订阅完成时将触发该任务</div>
                            </div>


                            <div class="layui-form-item">
                                <label for="" class="layui-form-label">在订阅中展示订阅信息</label>
                                <div class="layui-input-inline">
                                    <input type="checkbox" name="show_info_to_server_enable" lay-skin="switch"  @if(isset($config['sys_plan']['show_info_to_server_enable'])&&$config['sys_plan']['show_info_to_server_enable']==1) checked @endif>
                                    {{--<input type="text" name="app_url" value="{{ $config['app_url']??'' }}" placeholder="当前网站最新网址，将会在邮件等需要用于网址处体现" class="layui-input" >--}}
                                </div>
                                <div class="layui-form-mid layui-word-aux">开启后将会在用户订阅节点时输出订阅信息。</div>
                            </div>


                            <input type="hidden" name="config_type" value="sys_plan">

                            <div class="layui-form-item">
                                <div class="layui-input-block">
                                    <button type="submit" class="layui-btn" lay-submit="" lay-filter="formDemo">确 认</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="layui-tab-item">
                    {{--<table id="LAY-index-topCard"> 邀请&佣金</table>--}}
                    <div class="layui-card-body">
                        <form class="layui-form" action="{{route('admin.config.update')}}" method="post">
                            {{csrf_field()}}
                            {{method_field('put')}}

                            <div class="layui-form-item">
                                <label for="" class="layui-form-label">开启强制邀请</label>
                                <div class="layui-input-inline">
                                    <input type="checkbox" name="invite_force" lay-skin="switch"  @if(isset($config['sys_invite']['invite_force'])&&$config['sys_invite']['invite_force']==1) checked @endif>
                                    {{--<input type="text" name="app_url" value="{{ $config['app_url']??'' }}" placeholder="当前网站最新网址，将会在邮件等需要用于网址处体现" class="layui-input" >--}}
                                </div>
                                <div class="layui-form-mid layui-word-aux">开启后只有被邀请的用户才可以进行注册。</div>
                            </div>


                            <div class="layui-form-item">
                                <label for="" class="layui-form-label">邀请佣金百分比</label>
                                <div class="layui-input-inline">
                                    <input type="number" name="invite_commission" value="{{ $config['sys_invite']['invite_commission']??'' }}" placeholder="请输入" class="layui-input" >
                                    {{--<input type="text" name="app_url" value="{{ $config['app_url']??'' }}" placeholder="当前网站最新网址，将会在邮件等需要用于网址处体现" class="layui-input" >--}}
                                </div>
                                <div class="layui-form-mid layui-word-aux">默认全局的佣金分配比例，你可以在用户管理单独配置单个比例</div>
                            </div>


                            <div class="layui-form-item">
                                <label for="" class="layui-form-label">用户可创建邀请码上限</label>
                                <div class="layui-input-inline">
                                    <input type="number" name="invite_gen_limit" value="{{ $config['sys_invite']['invite_gen_limit']??'' }}" placeholder="请输入" class="layui-input" >
                                    {{--<input type="text" name="app_url" value="{{ $config['app_url']??'' }}" placeholder="当前网站最新网址，将会在邮件等需要用于网址处体现" class="layui-input" >--}}
                                </div>
                                {{--<div class="layui-form-mid layui-word-aux">默认全局的佣金分配比例，你可以在用户管理单独配置单个比例</div>--}}
                            </div>



                            <div class="layui-form-item">
                                <label for="" class="layui-form-label">邀请码永不失效</label>
                                <div class="layui-input-inline">
                                    <input type="checkbox" name="invite_never_expire" lay-skin="switch"  @if(isset($config['sys_invite']['invite_never_expire'])&&$config['sys_invite']['invite_never_expire']==1) checked @endif>
                                    {{--<input type="text" name="app_url" value="{{ $config['app_url']??'' }}" placeholder="当前网站最新网址，将会在邮件等需要用于网址处体现" class="layui-input" >--}}
                                </div>
                                <div class="layui-form-mid layui-word-aux">开启后邀请码被使用后将不会失效，否则使用过后即失效。</div>
                            </div>


                            <div class="layui-form-item">
                                <label for="" class="layui-form-label">佣金仅首次发放</label>
                                <div class="layui-input-inline">
                                    <input type="checkbox" name="commission_first_time_enable" lay-skin="switch"  @if(isset($config['sys_invite']['commission_first_time_enable'])&&$config['sys_invite']['commission_first_time_enable']==1) checked @endif>
                                    {{--<input type="text" name="app_url" value="{{ $config['app_url']??'' }}" placeholder="当前网站最新网址，将会在邮件等需要用于网址处体现" class="layui-input" >--}}
                                </div>
                                <div class="layui-form-mid layui-word-aux">开启后被邀请人首次支付时才会产生佣金，可以在用户管理对用户进行单独配置。</div>
                            </div>



                            <div class="layui-form-item">
                                <label for="" class="layui-form-label">佣金自动确认</label>
                                <div class="layui-input-inline">
                                    <input type="checkbox" name="commission_auto_check_enable" lay-skin="switch"  @if(isset($config['sys_invite']['commission_auto_check_enable'])&&$config['sys_invite']['commission_auto_check_enable']==1) checked @endif>
                                    {{--<input type="text" name="app_url" value="{{ $config['app_url']??'' }}" placeholder="当前网站最新网址，将会在邮件等需要用于网址处体现" class="layui-input" >--}}
                                </div>
                                <div class="layui-form-mid layui-word-aux">开启后佣金将会在订单完成3日后自动进行确认。</div>
                            </div>



                            <div class="layui-form-item">
                                <label for="" class="layui-form-label">提现单申请门槛(元)</label>
                                <div class="layui-input-inline">
                                    <input type="number" name="commission_withdraw_limit" value="{{ $config['sys_invite']['commission_withdraw_limit']??'' }}" placeholder="请输入" class="layui-input" >
                                    {{--<input type="text" name="app_url" value="{{ $config['app_url']??'' }}" placeholder="当前网站最新网址，将会在邮件等需要用于网址处体现" class="layui-input" >--}}
                                </div>
                                <div class="layui-form-mid layui-word-aux">小于门槛金额的提现单将不会被提交</div>
                            </div>



                            <div class="layui-form-item">
                                <label for="" class="layui-form-label">提现方式</label>
                                <div class="layui-input-inline" style="width: 36.5%;">
                                    {{--<textarea class="layui-textarea" name="description" cols="30" rows="4" placeholder="请输入订阅URL，末尾不要/逗号分割支持多域名">{{ $config['description']??'' }}</textarea>--}}
                                    <textarea class="layui-textarea" name="commission_withdraw_method" rows="6" placeholder="请输入后缀域名，逗号分割 如：支付宝,USDT,贝宝">{{ $config['sys_invite']['commission_withdraw_method']??'' }}</textarea>
                                </div>
                                <div class="layui-form-mid layui-word-aux">可以支持的提现方式</div>
                            </div>


                            <div class="layui-form-item">
                                <label for="" class="layui-form-label">关闭提现</label>
                                <div class="layui-input-inline">
                                    <input type="checkbox" name="withdraw_close_enable" lay-skin="switch"  @if(isset($config['sys_invite']['withdraw_close_enable'])&&$config['sys_invite']['withdraw_close_enable']==1) checked @endif>
                                    {{--<input type="text" name="app_url" value="{{ $config['app_url']??'' }}" placeholder="当前网站最新网址，将会在邮件等需要用于网址处体现" class="layui-input" >--}}
                                </div>
                                <div class="layui-form-mid layui-word-aux">关闭后将禁止用户申请提现，且邀请佣金将会直接进入用户余额。</div>
                            </div>



                            <div class="layui-form-item">
                                <label for="" class="layui-form-label">三级分销</label>
                                <div class="layui-input-inline">
                                    <input type="checkbox" name="commission_distribution_enable" lay-filter="commission_distribution_enable"  lay-skin="switch"  @if(isset($config['sys_invite']['commission_distribution_enable'])&&$config['sys_invite']['commission_distribution_enable']==1) checked @endif>
                                    {{--<input type="text" name="app_url" value="{{ $config['app_url']??'' }}" placeholder="当前网站最新网址，将会在邮件等需要用于网址处体现" class="layui-input" >--}}
                                </div>
                                <div class="layui-form-mid layui-word-aux">开启后将佣金将按照设置的3成比例进行分成，三成比例合计请不要>100%。</div>
                            </div>


                            <div class="layui-form-item commission_distribution_l1 @if($config['sys_invite']['commission_distribution_enable']==0) commission_distribution_l1_hide @endif">
                                <label for="" class="layui-form-label">一级邀请人比例</label>
                                <div class="layui-input-inline">
                                    <input type="number" name="commission_distribution_l1" value="{{ $config['sys_invite']['commission_distribution_l1']??'' }}" placeholder="请输入比例如：50" class="layui-input" >
                                    {{--<input type="text" name="app_url" value="{{ $config['app_url']??'' }}" placeholder="当前网站最新网址，将会在邮件等需要用于网址处体现" class="layui-input" >--}}
                                </div>
                                {{--<div class="layui-form-mid layui-word-aux">在Google reCAPTCHA申请的密钥</div>--}}
                            </div>


                            <div class="layui-form-item commission_distribution_l2 @if($config['sys_invite']['commission_distribution_enable']==0) commission_distribution_l2_hide @endif">
                                <label for="" class="layui-form-label">二级邀请人比例</label>
                                <div class="layui-input-inline">
                                    <input type="number" name="commission_distribution_l2" value="{{ $config['sys_invite']['commission_distribution_l2']??'' }}" placeholder="请输入比例如：30" class="layui-input" >
                                    {{--<input type="text" name="app_url" value="{{ $config['app_url']??'' }}" placeholder="当前网站最新网址，将会在邮件等需要用于网址处体现" class="layui-input" >--}}
                                </div>
                                {{--<div class="layui-form-mid layui-word-aux">在Google reCAPTCHA申请的密钥</div>--}}
                            </div>



                            <div class="layui-form-item commission_distribution_l3 @if($config['sys_invite']['commission_distribution_enable']==0) commission_distribution_l3_hide @endif">
                                <label for="" class="layui-form-label">三级邀请人比例</label>
                                <div class="layui-input-inline">
                                    <input type="number" name="commission_distribution_l3" value="{{ $config['sys_invite']['commission_distribution_l3']??'' }}" placeholder="请输入比例如：20" class="layui-input" >
                                    {{--<input type="text" name="app_url" value="{{ $config['app_url']??'' }}" placeholder="当前网站最新网址，将会在邮件等需要用于网址处体现" class="layui-input" >--}}
                                </div>
                                {{--<div class="layui-form-mid layui-word-aux">在Google reCAPTCHA申请的密钥</div>--}}
                            </div>


                            <input type="hidden" name="config_type" value="sys_invite">

                            <div class="layui-form-item">
                                <div class="layui-input-block">
                                    <button type="submit" class="layui-btn" lay-submit="" lay-filter="formDemo">确 认</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                {{--<div class="layui-tab-item">--}}
                    {{--<div class="personal">如果你采用前后分离的方式部署V2board管理端，那么本页配置将不会生效。了解--}}
                        {{--<a  target="_blank" href="https://docs.v2board.com/use/advanced.html#%E5%89%8D%E7%AB%AF%E5%88%86%E7%A6%BB">前后分离</a>--}}
                        {{--</div>--}}
                    {{--<hr class="layui-bg-red">--}}


                    {{--<div class="layui-card-body">--}}
                        {{--<form class="layui-form" action="{{route('admin.config.update')}}" method="post">--}}
                            {{--{{csrf_field()}}--}}
                            {{--{{method_field('put')}}--}}

                            {{--<div class="layui-form-item">--}}
                                {{--<label for="" class="layui-form-label">边栏风格</label>--}}
                                {{--<div class="layui-input-inline">--}}
                                    {{--<input type="checkbox" name="frontend_theme_sidebar" lay-skin="switch" lay-text="亮|暗"  @if(isset($config['sys_personal']['frontend_theme_sidebar'])&&$config['sys_personal']['frontend_theme_sidebar']=='light') checked @endif>--}}
                                    {{--<input type="text" name="app_url" value="{{ $config['app_url']??'' }}" placeholder="当前网站最新网址，将会在邮件等需要用于网址处体现" class="layui-input" >--}}
                                {{--</div>--}}
                                {{--<div class="layui-form-mid layui-word-aux">开启后只有被邀请的用户才可以进行注册。</div>--}}
                            {{--</div>--}}


                            {{--<div class="layui-form-item">--}}
                                {{--<label for="" class="layui-form-label">头部风格</label>--}}
                                {{--<div class="layui-input-inline">--}}
                                    {{--<input type="checkbox" name="frontend_theme_header" lay-skin="switch" lay-text="亮|暗"  @if(isset($config['sys_personal']['frontend_theme_header'])&&$config['sys_personal']['frontend_theme_header']=='light') checked @endif>--}}
                                    {{--<input type="text" name="app_url" value="{{ $config['app_url']??'' }}" placeholder="当前网站最新网址，将会在邮件等需要用于网址处体现" class="layui-input" >--}}
                                {{--</div>--}}
                                {{--<div class="layui-form-mid layui-word-aux">开启后只有被邀请的用户才可以进行注册。</div>--}}
                            {{--</div>--}}



                            {{--<div class="layui-form-item">--}}
                                {{--<label for="" class="layui-form-label">主题色</label>--}}
                                {{--<div class="layui-input-inline">--}}
                                    {{--<select name="frontend_theme_color" lay-filter="frontend_theme_color">--}}
                                      {{--<option value="default" @if($config['sys_personal']['frontend_theme_color']=='default') selected @endif >默认</option>--}}
                                      {{--<option value="black" @if($config['sys_personal']['frontend_theme_color']=='black') selected @endif >黑色</option>--}}
                                      {{--<option value="darkblue" @if($config['sys_personal']['frontend_theme_color']=='darkblue') selected @endif >暗蓝色</option>--}}
                                      {{--<option value="green" @if($config['sys_personal']['frontend_theme_color']=='green') selected @endif >奶绿色</option>--}}
                                    {{--</select>--}}
                                {{--</div>--}}
                                {{--<div class="layui-form-mid layui-word-aux">全局流量重置方式，默认每月1号。可以在订阅管理为订阅单独设置</div>--}}
                            {{--</div>--}}


                            {{--<div class="layui-form-item">--}}
                                {{--<label for="" class="layui-form-label">背景</label>--}}
                                {{--<div class="layui-input-inline">--}}
                                    {{--<input type="text" name="frontend_background_url" value="{{ $config['sys_personal']['frontend_background_url']??'' }}" placeholder="https://xxxxx.com/wallpaper.png" class="layui-input" >--}}
                                    {{--<input type="text" name="app_url" value="{{ $config['app_url']??'' }}" placeholder="当前网站最新网址，将会在邮件等需要用于网址处体现" class="layui-input" >--}}
                                {{--</div>--}}
                                {{--<div class="layui-form-mid layui-word-aux">将会在后台登录页面进行展示</div>--}}
                            {{--</div>--}}



                            {{--<input type="hidden" name="config_type" value="sys_personal">--}}

                            {{--<div class="layui-form-item">--}}
                                {{--<div class="layui-input-block">--}}
                                    {{--<button type="submit" class="layui-btn" lay-submit="" lay-filter="formDemo">确 认</button>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</form>--}}
                    {{--</div>--}}



                {{--</div>--}}

                <div class="layui-tab-item">
                    {{--<table id="LAY-index-topCard">节点</table>--}}

                    <div class="layui-card-body">
                        <form class="layui-form" action="{{route('admin.config.update')}}" method="post">
                            {{csrf_field()}}
                            {{method_field('put')}}

                            <div class="layui-form-item">
                                <label for="" class="layui-form-label">通讯密钥</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="server_token" value="{{ $config['sys_node']['server_token']??'' }}" placeholder="请输入" class="layui-input" >
                                    {{--<input type="text" name="app_url" value="{{ $config['app_url']??'' }}" placeholder="当前网站最新网址，将会在邮件等需要用于网址处体现" class="layui-input" >--}}
                                </div>
                                <div class="layui-form-mid layui-word-aux">V2board与节点通讯的密钥，以便数据不会被他人获取</div>
                            </div>


                            <div class="layui-form-item">
                                <label for="" class="layui-form-label">节点拉取动作轮询间隔</label>
                                <div class="layui-input-inline">
                                    <input type="number" name="server_pull_interval" value="{{ $config['sys_node']['server_pull_interval']??'' }}" placeholder="请输入(秒)" class="layui-input" >
                                    {{--<input type="text" name="app_url" value="{{ $config['app_url']??'' }}" placeholder="当前网站最新网址，将会在邮件等需要用于网址处体现" class="layui-input" >--}}
                                </div>
                                <div class="layui-form-mid layui-word-aux">节点从面板获取数据的间隔频率</div>
                            </div>


                            <div class="layui-form-item">
                                <label for="" class="layui-form-label">节点推送动作轮询间隔</label>
                                <div class="layui-input-inline">
                                    <input type="number" name="server_push_interval" value="{{ $config['sys_node']['server_push_interval']??'' }}" placeholder="请输入(秒)" class="layui-input" >
                                    {{--<input type="text" name="app_url" value="{{ $config['app_url']??'' }}" placeholder="当前网站最新网址，将会在邮件等需要用于网址处体现" class="layui-input" >--}}
                                </div>
                                <div class="layui-form-mid layui-word-aux">节点推送数据到面板的间隔频率</div>
                            </div>



                            <input type="hidden" name="config_type" value="sys_node">

                            <div class="layui-form-item">
                                <div class="layui-input-block">
                                    <button type="submit" class="layui-btn" lay-submit="" lay-filter="formDemo">确 认</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="layui-tab-item">
                    {{--<table id="LAY-index-topCard">邮件</table>--}}
                    <div class="personal">如果你更改了本页配置，需要对队列服务进行重启。另外本页配置优先级高于.env中邮件配置。 </div>
                    {{--<hr class="layui-bg-red">--}}


                    <div class="layui-card-body">
                        <form class="layui-form" action="{{route('admin.config.update')}}" method="post">
                            {{csrf_field()}}
                            {{method_field('put')}}

                            <div class="layui-form-item">
                                <label for="" class="layui-form-label">SMTP服务器地址</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="email_host" value="{{ $config['sys_email']['email_host']??'' }}" placeholder="请输入" class="layui-input" >
                                    {{--<input type="text" name="app_url" value="{{ $config['app_url']??'' }}" placeholder="当前网站最新网址，将会在邮件等需要用于网址处体现" class="layui-input" >--}}
                                </div>
                                <div class="layui-form-mid layui-word-aux">由邮件服务商提供的服务地址</div>
                            </div>


                            <div class="layui-form-item">
                                <label for="" class="layui-form-label">SMTP服务端口</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="email_port" value="{{ $config['sys_email']['email_port']??'' }}" placeholder="请输入" class="layui-input" >
                                    {{--<input type="text" name="app_url" value="{{ $config['app_url']??'' }}" placeholder="当前网站最新网址，将会在邮件等需要用于网址处体现" class="layui-input" >--}}
                                </div>
                                <div class="layui-form-mid layui-word-aux">常见的端口有25, 465, 587</div>
                            </div>


                            <div class="layui-form-item">
                                <label for="" class="layui-form-label">SMTP加密方式</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="email_encryption" value="{{ $config['sys_email']['email_encryption']??'' }}" placeholder="请输入" class="layui-input" >
                                    {{--<input type="text" name="app_url" value="{{ $config['app_url']??'' }}" placeholder="当前网站最新网址，将会在邮件等需要用于网址处体现" class="layui-input" >--}}
                                </div>
                                <div class="layui-form-mid layui-word-aux">465端口加密方式一般为SSL，587端口加密方式一般为TLS</div>
                            </div>


                            <div class="layui-form-item">
                                <label for="" class="layui-form-label">SMTP账号</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="email_username" value="{{ $config['sys_email']['email_username']??'' }}" placeholder="请输入" class="layui-input" >
                                    {{--<input type="text" name="app_url" value="{{ $config['app_url']??'' }}" placeholder="当前网站最新网址，将会在邮件等需要用于网址处体现" class="layui-input" >--}}
                                </div>
                                <div class="layui-form-mid layui-word-aux">由邮件服务商提供的账号</div>
                            </div>



                            <div class="layui-form-item">
                                <label for="" class="layui-form-label">SMTP密码</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="email_password" value="{{ $config['sys_email']['email_password']??'' }}" placeholder="请输入" class="layui-input" >
                                    {{--<input type="text" name="app_url" value="{{ $config['app_url']??'' }}" placeholder="当前网站最新网址，将会在邮件等需要用于网址处体现" class="layui-input" >--}}
                                </div>
                                <div class="layui-form-mid layui-word-aux">由邮件服务商提供的密码</div>
                            </div>

                            <div class="layui-form-item">
                                <label for="" class="layui-form-label">发件地址</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="email_from_address" value="{{ $config['sys_email']['email_from_address']??'' }}" placeholder="请输入" class="layui-input" >
                                    {{--<input type="text" name="app_url" value="{{ $config['app_url']??'' }}" placeholder="当前网站最新网址，将会在邮件等需要用于网址处体现" class="layui-input" >--}}
                                </div>
                                <div class="layui-form-mid layui-word-aux">由邮件服务商提供的发件地址</div>
                            </div>


                            <div class="layui-form-item">
                                <label for="" class="layui-form-label">邮件模板</label>
                                <div class="layui-input-inline">
                                    <select name="email_template" lay-filter="frontend_theme_color">
                                        <option value="default" @if($config['sys_email']['email_template']=='default') selected @endif >default</option>
                                        <option value="classic" @if($config['sys_email']['email_template']=='classic') selected @endif >classic</option>
                                    </select>
                                </div>
                                <div class="layui-form-mid layui-word-aux">你可以在文档查看如何自定义邮件模板</div>
                            </div>



                            <div class="layui-form-item">
                                <label for="" class="layui-form-label">发送测试邮件</label>
                                <div class="layui-input-inline">

                                    <div class="layui-btn layui-btn-normal send_mail" data-url="{{route('admin.config.send')}}">发送测试邮件</div>
                                </div>
                                <div class="layui-form-mid layui-word-aux">邮件将会发送到当前登陆用户邮箱</div>
                            </div>





                            <input type="hidden" name="config_type" value="sys_email">

                            <div class="layui-form-item">
                                <div class="layui-input-block">
                                    <button type="submit" class="layui-btn" lay-submit="" lay-filter="formDemo">确 认</button>
                                </div>
                            </div>
                        </form>
                    </div>


                </div>

                <div class="layui-tab-item">
                    {{--<table id="LAY-index-topCard">Telegram</table>--}}

                    <div class="layui-card-body">
                        <form class="layui-form" action="{{route('admin.config.update')}}" method="post">
                            {{csrf_field()}}
                            {{method_field('put')}}

                            <div class="layui-form-item">
                                <label for="" class="layui-form-label">机器人Token</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="telegram_bot_token" value="{{ $config['sys_telegram']['telegram_bot_token']??'' }}" placeholder="0000000000:xxxxxxxxx_xxxxxxxxxxxxxxx" class="layui-input telegram_bot_token" >
                                    {{--<input type="text" name="app_url" value="{{ $config['app_url']??'' }}" placeholder="当前网站最新网址，将会在邮件等需要用于网址处体现" class="layui-input" >--}}
                                </div>
                                <div class="layui-form-mid layui-word-aux">请输入由Botfather提供的token</div>
                            </div>

                            <div class="layui-form-item send_telegram @if(!$config['sys_telegram']['telegram_bot_token']) send_telegram_hide @endif"">
                                <label for="" class="layui-form-label">设置Webhook</label>
                                <div class="layui-input-inline">

                                    <div class="layui-btn layui-btn-normal send_telegram" data-url="{{route('admin.config.telegram')}}">一键设置</div>
                                    {{--<input type="checkbox" name="telegram_bot_enable" lay-skin="switch"  @if(isset($config['sys_telegram']['telegram_bot_enable'])&&$config['sys_telegram']['telegram_bot_enable']=='1') checked @endif>--}}
                                    {{--<input type="text" name="app_url" value="{{ $config['app_url']??'' }}" placeholder="当前网站最新网址，将会在邮件等需要用于网址处体现" class="layui-input" >--}}
                                </div>
                                <div class="layui-form-mid layui-word-aux">对机器人进行Webhook设置，不设置将无法收到Telegram通知。</div>
                            </div>


                            <div class="layui-form-item">
                                <label for="" class="layui-form-label">开启机器人通知</label>
                                <div class="layui-input-inline">
                                    <input type="checkbox" name="telegram_bot_enable" lay-skin="switch"  @if(isset($config['sys_telegram']['telegram_bot_enable'])&&$config['sys_telegram']['telegram_bot_enable']=='1') checked @endif>
                                    {{--<input type="text" name="app_url" value="{{ $config['app_url']??'' }}" placeholder="当前网站最新网址，将会在邮件等需要用于网址处体现" class="layui-input" >--}}
                                </div>
                                <div class="layui-form-mid layui-word-aux">开启后bot将会对绑定了telegram的管理员和用户进行基础通知。</div>
                            </div>


                            <div class="layui-form-item">
                                <label for="" class="layui-form-label">群组地址</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="telegram_discuss_link" value="{{ $config['sys_telegram']['telegram_discuss_link']??'' }}" placeholder="https://t.me/xxxxxx" class="layui-input" >
                                    {{--<input type="text" name="app_url" value="{{ $config['app_url']??'' }}" placeholder="当前网站最新网址，将会在邮件等需要用于网址处体现" class="layui-input" >--}}
                                </div>
                                <div class="layui-form-mid layui-word-aux">填写后将会在用户端展示，或者被用于需要的地方</div>
                            </div>



                            <input type="hidden" name="config_type" value="sys_telegram">

                            <div class="layui-form-item">
                                <div class="layui-input-block">
                                    <button type="submit" class="layui-btn" lay-submit="" lay-filter="formDemo">确 认</button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>

                <div class="layui-tab-item">
                    {{--<table id="LAY-index-topCard">APP</table>--}}

                    {{--<table id="LAY-index-topCard">邮件</table>--}}
                    <div class="personal">用于自有客户端(APP)的版本管理及更新</div>
                    {{--<hr class="layui-bg-red">--}}


                    <div class="layui-card-body">
                        <form class="layui-form" action="{{route('admin.config.update')}}" method="post">
                            {{csrf_field()}}
                            {{method_field('put')}}


                            <div class="layui-form-item">
                                <label for="" class="layui-form-label">api秘钥</label>
                                <div class="layui-input-block">
                                    <input type="text" name="api_key" value="{{ $config['sys_app']['api_key']??'' }}" placeholder="请输入api秘钥" class="layui-input" >
                                </div>
                                {{--<div class="layui-form-mid layui-word-aux">Windows端版本号及下载地址</div>--}}
                            </div>

                            <div class="layui-form-item">
                                <label for="" class="layui-form-label">转发url</label>
                                <div class="layui-input-block">
                                    <input type="text" name="forward_url" value="{{ $config['sys_app']['forward_url']??'' }}" placeholder="请输入转发url" class="layui-input" >
                                </div>
                                {{--<div class="layui-form-mid layui-word-aux">Windows端版本号及下载地址</div>--}}
                            </div>


                            <div class="layui-form-item">
                                <label for="" class="layui-form-label">联系邮箱</label>
                                <div class="layui-input-block">
                                    <input type="text" name="contact_email" value="{{ $config['sys_app']['contact_email']??'' }}" placeholder="请输入联系邮箱" class="layui-input" >
                                </div>
                                {{--<div class="layui-form-mid layui-word-aux">Windows端版本号及下载地址</div>--}}
                            </div>


                            <div class="layui-form-item">
                                <label for="" class="layui-form-label">Windows版本号</label>
                                <div class="layui-input-block">
                                    <input type="text" name="windows_version" value="{{ $config['sys_app']['windows_version']??'' }}" placeholder="1.0.0" class="layui-input" >
                                    {{--<input type="text" style="margin-top: 3px" name="windows_download_url" value="{{ $config['sys_app']['windows_download_url']??'' }}" placeholder="https://xxxx.com/xxx.exe" class="layui-input" >--}}
                                    {{--<input type="text" name="app_url" value="{{ $config['app_url']??'' }}" placeholder="当前网站最新网址，将会在邮件等需要用于网址处体现" class="layui-input" >--}}
                                </div>
                                {{--<div class="layui-form-mid layui-word-aux">Windows端版本号</div>--}}
                            </div>
                            <div class="layui-form-item">
                                <label for="" class="layui-form-label">Windows端注册url</label>
                                <div class="layui-input-block">
                                    <input type="text" name="windows_reg_url" value="{{ $config['sys_app']['windows_reg_url']??'' }}" placeholder="请输入Windows端注册url" class="layui-input" >
                                </div>
                                {{--<div class="layui-form-mid layui-word-aux">Windows端下载地址2</div>--}}
                            </div>
                            <div class="layui-form-item">
                                <label for="" class="layui-form-label">Windows端找回密码url</label>
                                <div class="layui-input-block">
                                    <input type="text" name="windows_pwd_url" value="{{ $config['sys_app']['windows_pwd_url']??'' }}" placeholder="请输入Windows端找回密码url" class="layui-input" >
                                </div>
                                {{--<div class="layui-form-mid layui-word-aux">Windows端下载地址2</div>--}}
                            </div>
                            <div class="layui-form-item">
                                <div class="layui-inline" style="width: 45%">
                                    <label class="layui-form-label">Windows下载地址1</label>
                                    <div class="layui-input-inline">
                                        <input type="text" name="windows_download_url" value="{{ $config['sys_app']['windows_download_url']??'' }}" placeholder="https://xxxx.com/xxx.exe" class="layui-input" >
                                    </div>
                                </div>

                                <div class="layui-inline">
                                    <label class="layui-form-label">Windows下载地址2</label>
                                    <div class="layui-input-inline">
                                        <input type="text" name="windows_download_url2" value="{{ $config['sys_app']['windows_download_url2']??'' }}" placeholder="https://xxxx.com/xxx.exe" class="layui-input" >
                                    </div>
                                </div>

                            </div>
                            <div class="layui-form-item">

                                <div class="layui-inline" style="width: 45%">
                                    <label for="" class="layui-form-label">Windows端api url1</label>
                                    <div class="layui-input-inline">
                                        <input type="text" name="windows_api_url1" value="{{ $config['sys_app']['windows_api_url1']??'' }}" placeholder="请输入Windows端api url1" class="layui-input" >
                                    </div>
                                </div>

                                <div class="layui-inline">
                                    <label for="" class="layui-form-label">Windows端api url2</label>
                                    <div class="layui-input-inline">
                                        <input type="text" name="windows_api_url2" value="{{ $config['sys_app']['windows_api_url2']??'' }}" placeholder="请输入Windows端api url2" class="layui-input" >
                                    </div>
                                </div>

                            </div>
                            <div class="layui-form-item">

                                <div class="layui-inline" style="width: 45%">
                                    <label for="" class="layui-form-label">Windows端购买Url1</label>
                                    <div class="layui-input-inline">
                                        <input type="text" name="windows_buy_url1" value="{{ $config['sys_app']['windows_buy_url1']??'' }}" placeholder="请输入Windows端购买Url1" class="layui-input" >
                                    </div>
                                </div>

                                <div class="layui-inline">
                                    <label for="" class="layui-form-label">Windows端购买Url2</label>
                                    <div class="layui-input-inline">
                                        <input type="text" name="windows_buy_url2" value="{{ $config['sys_app']['windows_buy_url2']??'' }}" placeholder="请输入Windows端购买Url2" class="layui-input" >
                                    </div>
                                </div>

                            </div>


                            <div class="layui-form-item">
                                <label for="" class="layui-form-label">Windows是否强制更新</label>
                                <div class="layui-input-inline">
                                    <input type="checkbox" name="is_windows_force" lay-skin="switch"  @if(isset($config['sys_app']['is_windows_force'])&&$config['sys_app']['is_windows_force']=='1') checked @endif>
                                    {{--<input type="text" name="app_url" value="{{ $config['app_url']??'' }}" placeholder="当前网站最新网址，将会在邮件等需要用于网址处体现" class="layui-input" >--}}
                                </div>
                                {{--<div class="layui-form-mid layui-word-aux">开启后bot将会对绑定了telegram的管理员和用户进行基础通知。</div>--}}
                            </div>


                            <div class="layui-form-item">
                                <label for="" class="layui-form-label">macOS版本号</label>
                                <div class="layui-input-block">
                                    <input type="text" name="macos_version" value="{{ $config['sys_app']['macos_version']??'' }}" placeholder="1.0.0" class="layui-input" >
                                    {{--<input type="text" style="margin-top: 3px" name="windows_download_url" value="{{ $config['sys_app']['windows_download_url']??'' }}" placeholder="https://xxxx.com/xxx.exe" class="layui-input" >--}}
                                    {{--<input type="text" name="app_url" value="{{ $config['app_url']??'' }}" placeholder="当前网站最新网址，将会在邮件等需要用于网址处体现" class="layui-input" >--}}
                                </div>
                                {{--<div class="layui-form-mid layui-word-aux">Windows端版本号</div>--}}
                            </div>
                            <div class="layui-form-item">
                                <label for="" class="layui-form-label">macOS端注册url</label>
                                <div class="layui-input-block">
                                    <input type="text" name="macos_reg_url" value="{{ $config['sys_app']['macos_reg_url']??'' }}" placeholder="请输入macOS端注册url" class="layui-input" >
                                </div>
                                {{--<div class="layui-form-mid layui-word-aux">Windows端下载地址2</div>--}}
                            </div>
                            <div class="layui-form-item">
                                <label for="" class="layui-form-label">macOS端找回密码url</label>
                                <div class="layui-input-block">
                                    <input type="text" name="macos_pwd_url" value="{{ $config['sys_app']['macos_pwd_url']??'' }}" placeholder="请输入macOS端找回密码url" class="layui-input">
                                </div>
                                {{--<div class="layui-form-mid layui-word-aux">Windows端下载地址2</div>--}}
                            </div>
                            <div class="layui-form-item">
                                <div class="layui-inline" style="width: 45%">
                                    <label class="layui-form-label">macOS下载地址1</label>
                                    <div class="layui-input-inline">
                                        <input type="text" name="macos_download_url" value="{{ $config['sys_app']['macos_download_url']??'' }}" placeholder="https://xxxx.com/xxx.dmg" class="layui-input" >
                                    </div>
                                </div>

                                <div class="layui-inline">
                                    <label class="layui-form-label">macOS下载地址2</label>
                                    <div class="layui-input-inline">
                                        <input type="text" name="macos_download_url2" value="{{ $config['sys_app']['macos_download_url2']??'' }}" placeholder="https://xxxx.com/xxx.dmg" class="layui-input"  >
                                    </div>
                                </div>

                            </div>
                            <div class="layui-form-item">

                                <div class="layui-inline" style="width: 45%">
                                    <label for="" class="layui-form-label">macOS端api url1</label>
                                    <div class="layui-input-inline">
                                        <input type="text" name="macos_api_url1" value="{{ $config['sys_app']['macos_api_url1']??'' }}" placeholder="请输入macOS端api url1" class="layui-input" >
                                    </div>
                                </div>

                                <div class="layui-inline">
                                    <label for="" class="layui-form-label">macOS端api url2</label>
                                    <div class="layui-input-inline">
                                        <input type="text" name="macos_api_url2" value="{{ $config['sys_app']['macos_api_url2']??'' }}" placeholder="请输入macOS端api url2" class="layui-input" >
                                    </div>
                                </div>

                            </div>
                            <div class="layui-form-item">

                                <div class="layui-inline" style="width: 45%">
                                    <label for="" class="layui-form-label">macOS端购买Url1</label>
                                    <div class="layui-input-inline">
                                        <input type="text" name="macos_buy_url1" value="{{ $config['sys_app']['macos_buy_url1']??'' }}" placeholder="请输入macOS端购买Url1" class="layui-input" >
                                    </div>
                                </div>

                                <div class="layui-inline">
                                    <label for="" class="layui-form-label">macOS端购买Url2</label>
                                    <div class="layui-input-inline">
                                        <input type="text" name="macos_buy_url2" value="{{ $config['sys_app']['macos_buy_url2']??'' }}" placeholder="请输入macOS端购买Url2" class="layui-input" >
                                    </div>
                                </div>

                            </div>

                            <div class="layui-form-item">
                                <label for="" class="layui-form-label">macOS是否强制更新</label>
                                <div class="layui-input-inline">
                                    <input type="checkbox" name="is_macos_force" lay-skin="switch"  @if(isset($config['sys_app']['is_macos_force'])&&$config['sys_app']['is_macos_force']=='1') checked @endif>
                                    {{--<input type="text" name="app_url" value="{{ $config['app_url']??'' }}" placeholder="当前网站最新网址，将会在邮件等需要用于网址处体现" class="layui-input" >--}}
                                </div>
                                {{--<div class="layui-form-mid layui-word-aux">开启后bot将会对绑定了telegram的管理员和用户进行基础通知。</div>--}}
                            </div>


                            <div class="layui-form-item">
                                <label for="" class="layui-form-label">Android版本号</label>
                                <div class="layui-input-block">
                                    <input type="text" name="android_version" value="{{ $config['sys_app']['android_version']??'' }}" placeholder="1.0.0" class="layui-input" >
                                    {{--<input type="text" style="margin-top: 3px" name="windows_download_url" value="{{ $config['sys_app']['windows_download_url']??'' }}" placeholder="https://xxxx.com/xxx.exe" class="layui-input" >--}}
                                    {{--<input type="text" name="app_url" value="{{ $config['app_url']??'' }}" placeholder="当前网站最新网址，将会在邮件等需要用于网址处体现" class="layui-input" >--}}
                                </div>
                                {{--<div class="layui-form-mid layui-word-aux">Windows端版本号</div>--}}
                            </div>
                            <div class="layui-form-item">
                                <label for="" class="layui-form-label">Android端注册url</label>
                                <div class="layui-input-block">
                                    <input type="text" name="android_reg_url" value="{{ $config['sys_app']['android_reg_url']??'' }}" placeholder="请输入Android端注册url" class="layui-input" >
                                </div>
                                {{--<div class="layui-form-mid layui-word-aux">Windows端下载地址2</div>--}}
                            </div>
                            <div class="layui-form-item">
                                <label for="" class="layui-form-label">Android端找回密码url</label>
                                <div class="layui-input-block">
                                    <input type="text" name="android_pwd_url" value="{{ $config['sys_app']['android_pwd_url']??'' }}" placeholder="请输入Android端找回密码url" class="layui-input" >
                                </div>
                                {{--<div class="layui-form-mid layui-word-aux">Windows端下载地址2</div>--}}
                            </div>
                            <div class="layui-form-item">
                                <div class="layui-inline" style="width: 45%">
                                    <label class="layui-form-label">Android下载地址1</label>
                                    <div class="layui-input-inline">
                                        <input type="text" name="android_download_url" value="{{ $config['sys_app']['android_download_url']??'' }}" placeholder="https://xxxx.com/xxx.apk" class="layui-input" >
                                    </div>
                                </div>

                                <div class="layui-inline">
                                    <label class="layui-form-label">Android下载地址2</label>
                                    <div class="layui-input-inline">
                                        <input type="text" name="android_download_url2" value="{{ $config['sys_app']['android_download_url2']??'' }}" placeholder="https://xxxx.com/xxx.apk" class="layui-input" >
                                    </div>
                                </div>

                            </div>
                            <div class="layui-form-item">

                                <div class="layui-inline" style="width: 45%">
                                    <label for="" class="layui-form-label">Android端api url1</label>
                                    <div class="layui-input-inline">
                                        <input type="text" name="android_api_url1" value="{{ $config['sys_app']['android_api_url1']??'' }}" placeholder="请输入Android端api url1" class="layui-input" >
                                    </div>
                                </div>

                                <div class="layui-inline">
                                    <label for="" class="layui-form-label">Android端api url2</label>
                                    <div class="layui-input-inline">
                                        <input type="text" name="android_api_url2" value="{{ $config['sys_app']['android_api_url2']??'' }}" placeholder="请输入Android端api url2" class="layui-input" >
                                    </div>
                                </div>

                            </div>
                            <div class="layui-form-item">

                                <div class="layui-inline" style="width: 45%">
                                    <label for="" class="layui-form-label">Android端购买Url1</label>
                                    <div class="layui-input-inline">
                                        <input type="text" name="android_buy_url1" value="{{ $config['sys_app']['android_buy_url1']??'' }}" placeholder="请输入Android端购买Url1" class="layui-input" >
                                    </div>
                                </div>

                                <div class="layui-inline">
                                    <label for="" class="layui-form-label">Android端购买Url2</label>
                                    <div class="layui-input-inline">
                                        <input type="text" name="android_buy_url2" value="{{ $config['sys_app']['android_buy_url2']??'' }}" placeholder="请输入Android端购买Url2" class="layui-input" >
                                    </div>
                                </div>

                            </div>

                            <div class="layui-form-item">
                                <label for="" class="layui-form-label">Android是否强制更新</label>
                                <div class="layui-input-inline">
                                    <input type="checkbox" name="is_android_force" lay-skin="switch"  @if(isset($config['sys_app']['is_android_force'])&&$config['sys_app']['is_android_force']=='1') checked @endif>
                                    {{--<input type="text" name="app_url" value="{{ $config['app_url']??'' }}" placeholder="当前网站最新网址，将会在邮件等需要用于网址处体现" class="layui-input" >--}}
                                </div>
                                {{--<div class="layui-form-mid layui-word-aux">开启后bot将会对绑定了telegram的管理员和用户进行基础通知。</div>--}}
                            </div>




                            <div class="layui-form-item">
                                <label for="" class="layui-form-label">Ios版本号</label>
                                <div class="layui-input-block">
                                    <input type="text" name="ios_version" value="{{ $config['sys_app']['ios_version']??'' }}" placeholder="1.0.0" class="layui-input" >
                                    {{--<input type="text" style="margin-top: 3px" name="windows_download_url" value="{{ $config['sys_app']['windows_download_url']??'' }}" placeholder="https://xxxx.com/xxx.exe" class="layui-input" >--}}
                                    {{--<input type="text" name="app_url" value="{{ $config['app_url']??'' }}" placeholder="当前网站最新网址，将会在邮件等需要用于网址处体现" class="layui-input" >--}}
                                </div>
                                {{--<div class="layui-form-mid layui-word-aux">Windows端版本号</div>--}}
                            </div>
                            <div class="layui-form-item">
                                <label for="" class="layui-form-label">Ios端注册url</label>
                                <div class="layui-input-block">
                                    <input type="text" name="ios_reg_url" value="{{ $config['sys_app']['ios_reg_url']??'' }}" placeholder="请输入Ios端注册url" class="layui-input" >
                                </div>
                                {{--<div class="layui-form-mid layui-word-aux">Windows端下载地址2</div>--}}
                            </div>
                            <div class="layui-form-item">
                                <label for="" class="layui-form-label">Ios端找回密码url</label>
                                <div class="layui-input-block">
                                    <input type="text" name="ios_pwd_url" value="{{ $config['sys_app']['ios_pwd_url']??'' }}" placeholder="请输入Ios端找回密码url" class="layui-input" >
                                </div>
                                {{--<div class="layui-form-mid layui-word-aux">Windows端下载地址2</div>--}}
                            </div>
                            <div class="layui-form-item">
                                <div class="layui-inline" style="width: 45%">
                                    <label class="layui-form-label">Ios下载地址1</label>
                                    <div class="layui-input-inline">
                                        <input type="text" name="ios_download_url" value="{{ $config['sys_app']['ios_download_url']??'' }}" placeholder="https://xxxx.com/xxx.ipa" class="layui-input" >
                                    </div>
                                </div>

                                <div class="layui-inline">
                                    <label class="layui-form-label">Ios下载地址2</label>
                                    <div class="layui-input-inline">
                                        <input type="text" name="ios_download_url2" value="{{ $config['sys_app']['ios_download_url2']??'' }}" placeholder="https://xxxx.com/xxx.ipa" class="layui-input" >
                                    </div>
                                </div>

                            </div>
                            <div class="layui-form-item">

                                <div class="layui-inline" style="width: 45%">
                                    <label for="" class="layui-form-label">Ios端api url1</label>
                                    <div class="layui-input-inline">
                                        <input type="text" name="ios_api_url1" value="{{ $config['sys_app']['ios_api_url1']??'' }}" placeholder="请输入Ios端api url1" class="layui-input" >
                                    </div>
                                </div>

                                <div class="layui-inline">
                                    <label for="" class="layui-form-label">Ios端api url2</label>
                                    <div class="layui-input-inline">
                                        <input type="text" name="ios_api_url2" value="{{ $config['sys_app']['ios_api_url2']??'' }}" placeholder="请输入Ios端api url2" class="layui-input" >
                                    </div>
                                </div>

                            </div>
                            <div class="layui-form-item">

                                <div class="layui-inline" style="width: 45%">
                                    <label for="" class="layui-form-label">Ios端购买Url1</label>
                                    <div class="layui-input-inline">
                                        <input type="text" name="ios_buy_url1" value="{{ $config['sys_app']['ios_buy_url1']??'' }}" placeholder="请输入Ios端购买Url1" class="layui-input" >
                                    </div>
                                </div>

                                <div class="layui-inline">
                                    <label for="" class="layui-form-label">Ios端购买Url2</label>
                                    <div class="layui-input-inline">
                                        <input type="text" name="ios_buy_url2" value="{{ $config['sys_app']['ios_buy_url2']??'' }}" placeholder="请输入Ios端购买Url2" class="layui-input" >
                                    </div>
                                </div>

                            </div>


                            <div class="layui-form-item">
                                <label for="" class="layui-form-label">Ios是否强制更新</label>
                                <div class="layui-input-inline">
                                    <input type="checkbox" name="is_ios_force" lay-skin="switch"  @if(isset($config['sys_app']['is_ios_force'])&&$config['sys_app']['is_ios_force']=='1') checked @endif>
                                    {{--<input type="text" name="app_url" value="{{ $config['app_url']??'' }}" placeholder="当前网站最新网址，将会在邮件等需要用于网址处体现" class="layui-input" >--}}
                                </div>
                                {{--<div class="layui-form-mid layui-word-aux">开启后bot将会对绑定了telegram的管理员和用户进行基础通知。</div>--}}
                            </div>



                            {{--<div class="layui-form-item">--}}
                                {{--<label for="" class="layui-form-label">macOS</label>--}}
                                {{--<div class="layui-input-inline">--}}
                                    {{--<input type="text" name="macos_version"  value="{{ $config['sys_app']['macos_version']??'' }}" placeholder="1.0.0" class="layui-input" >--}}
                                    {{--<input type="text" style="margin-top: 3px" name="macos_download_url" value="{{ $config['sys_app']['macos_download_url']??'' }}" placeholder="https://xxxx.com/xxx.dmg" class="layui-input" >--}}
                                    {{--<input type="text" name="app_url" value="{{ $config['app_url']??'' }}" placeholder="当前网站最新网址，将会在邮件等需要用于网址处体现" class="layui-input" >--}}
                                {{--</div>--}}
                                {{--<div class="layui-form-mid layui-word-aux">macOS端版本号及下载地址</div>--}}
                            {{--</div>--}}


                            {{--<div class="layui-form-item">--}}
                                {{--<label for="" class="layui-form-label">Android</label>--}}
                                {{--<div class="layui-input-inline">--}}
                                    {{--<input type="text" name="android_version" value="{{ $config['sys_app']['android_version']??'' }}" placeholder="1.0.0" class="layui-input" >--}}
                                    {{--<input type="text" style="margin-top: 3px" name="android_download_url" value="{{ $config['sys_app']['android_download_url']??'' }}" placeholder="https://xxxx.com/xxx.apk" class="layui-input" >--}}
                                    {{--<input type="text" name="app_url" value="{{ $config['app_url']??'' }}" placeholder="当前网站最新网址，将会在邮件等需要用于网址处体现" class="layui-input" >--}}
                                {{--</div>--}}
                                {{--<div class="layui-form-mid layui-word-aux">Android端版本号及下载地址</div>--}}
                            {{--</div>--}}


                            <input type="hidden" name="config_type" value="sys_app">

                            <div class="layui-form-item">
                                <div class="layui-input-block">
                                    <button type="submit" class="layui-btn" lay-submit="" lay-filter="formDemo">确 认</button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>

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

