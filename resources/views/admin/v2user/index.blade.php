@extends('admin.base')

<style>
    div.layui-table-cell{
        height: 90px;
    }
    .diy_confirmed{
        color: #1E9FFF;
    }
    .diy_paid{
        color: #5FB878;
    }
    .diy_cancel{
        color: #FFB800;
    }

    /*.layui-body{overflow-y: scroll;}*/

    /*body{overflow-y: scroll;}*/

    /*table {*/
        /*table-layout: fixed;*/
        /*word-break: break-all;*/
    /*}*/
    /*td {*/
        /*overflow:auto;*/
    /*}*/

</style>


@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <div class="layui-btn-group ">
                @can('v2board.user.ban')
                    <button class="layui-btn layui-btn-sm layui-btn-danger" id="listDelete">批量封禁</button>
                @endcan
                @can('v2board.user.csv')
                    {{--<button class="layui-btn layui-btn-sm layui-btn-danger" id="listDelete">导出csv</button>--}}
                    {{--<button class="layui-btn layui-btn-sm" href="{{ route('admin.v2user.csv') }}">导出csv</button>--}}
                    <button class="layui-btn layui-btn-sm" id="down_csv">导出csv</button>
                @endcan
                @can('v2board.user.send')
                    {{--<button class="layui-btn layui-btn-sm layui-btn-danger" id="listDelete">发送邮件</button>--}}
                    {{--<a class="layui-btn layui-btn-sm" href="{{ route('admin.v2user.send') }}">发送邮件</a>--}}
                    {{--<a class="layui-btn layui-btn-sm" href="{{ route('admin.v2user.send') }}">发送邮件</a>--}}
                    <button class="layui-btn layui-btn-sm" lay-on="test-page-custom" id="send_email">发送邮件</button>
                @endcan
                @can('v2board.user.create')
                    <a class="layui-btn layui-btn-sm" href="{{ route('admin.v2user.create') }}">添加</a>
                @endcan


            </div>

            <div class="layui-form" >

                {{--<button class="layui-btn layui-btn-sm" id="searchBtn">搜 索</button>--}}

                <div class="layui-input-inline">
                    <select name="is_admin" id="is_admin" lay-filter="is_admin">
                        <option value="-1" >请选择是否管理员</option>
                        <option value="1" >是</option>
                        <option value="0" >否</option>
                    </select>
                </div>
                <div class="layui-input-inline">
                    <select name="plan_id" id="plan_id" lay-filter="plan_id">
                        <option value="-1" >请选择订阅</option>
                        @foreach($plan as $kk=>$first)
                            <option value="{{ $kk }}" >{{ $first }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="layui-input-inline">
                    <select name="banned" id="banned" lay-filter="banned">
                        <option value="-1" >请选择账号状态</option>
                        <option value="1" >封禁</option>
                        <option value="0" >正常</option>
                    </select>
                </div>

                <div id="ID-laydate-rangeLinked" class="layui-input-inline">
                    <div class="layui-input-inline">
                        <input type="text" name="expired_at_start" value="" class="layui-input"  autocomplete="off" id="ID-laydate-start-date-1" placeholder="到期开始时间">
                        {{--<input type="text" name="ended_at" value="" class="layui-input" lay-verify="required" autocomplete="off" id="ID-laydate-end-date-1" placeholder="结束时间">--}}
                    </div>
                    <div class="layui-input-inline">
                        {{--<input type="text" name="started_at" value="" class="layui-input" lay-verify="required" autocomplete="off" id="ID-laydate-start-date-1" placeholder="开始时间">--}}
                        <input type="text" name="expired_at_end" value="" class="layui-input"  autocomplete="off" id="ID-laydate-end-date-1" placeholder="到期结束时间">
                    </div>
                </div>
                <div class="layui-input-inline">
                    <select name="field_name" id="field_name" lay-filter="field_name">
                        <option value="-1" >请选择</option>
                        <option value="email" >邮箱</option>
                        <option value="id" >用户ID</option>
                        {{--<option value="commission_status" >订阅</option>--}}
                        <option value="transfer_enable" >流量</option>
                        <option value="d" >下行</option>
                        {{--<option value="callback_no" >到期时间</option>--}}
                        <option value="uuid" >UUID</option>
                        <option value="token" >TOKEN</option>
                        {{--<option value="commission_balance" >账号状态</option>--}}
                        <option value="invite_by_email" >邀请人邮箱</option>
                        <option value="invite_user_id" @if($invite_user_id) selected @endif>邀请人ID</option>
                        <option value="remarks" >备注</option>
                    </select>
                </div>
                <div class="layui-input-inline">
                    <input type="text" name="field_val" id="field_val" value="{{$invite_user_id?$invite_user_id:''}}" placeholder="请输入搜索内容" class="layui-input" >
                </div>

                <button class="layui-btn" id="searchBtn">搜 索</button>
                <a class="layui-btn layui-btn-primary" href="{{ route('admin.v2user') }}" >重置</a>

            </div>

        </div>
        <div class="layui-card-body">
            <table id="dataTable" lay-filter="dataTable"></table>
            <script type="text/html" id="options">
                <div class="layui-btn-group">
                    @can('v2board.user.edit')
                        <a class="layui-btn layui-btn-sm" lay-event="edit">编辑</a>
                    @endcan


                        @can('finance.order.create')
                            <a class="layui-btn layui-btn-sm" lay-event="order">分配订单</a>
                        @endcan

                        @can('v2board.user.copy')
                            <button class="layui-btn layui-btn-sm" id="copy_button" lay-event="copy">复制订阅URL</button>
                            <textarea id="copy_text" style="display:none;"></textarea>
                        @endcan

                        @can('v2board.user.reset')
                            <a class="layui-btn layui-btn-sm" lay-event="reset">重置UUID及订阅URL</a>
                        @endcan

                        @can('finance.order')
                            <a class="layui-btn layui-btn-sm" lay-event="ta_order">TA的订单</a>
                        @endcan

                        <a class="layui-btn layui-btn-sm" lay-event="invite">TA的邀请</a>

                        @can('v2board.discharge')
                            <a class="layui-btn layui-btn-sm" lay-event="discharge">TA的流量记录</a>
                        @endcan

                </div>
            </script>


        </div>
    </div>
@endsection

@section('script')

    @can('v2board.user')

        {{--<link href="/static/admin/layuiadmin/layui2.9.18.css" rel="stylesheet">--}}
        {{--<script src="/static/admin/layuiadmin/layui2.9.18.js" type="text/javascript" charset="utf-8"></script>--}}
        <script>
            layui.use(['layer','table','form','laydate'],function () {
                // var $ = layui.$;
                var layer = layui.layer;
                var $ = layui.jquery;
                var laydate = layui.laydate;
                var form = layui.form;
                var table = layui.table;
                var util = layui.util;

                // 日期范围 - 左右面板独立选择模式
                laydate.render({
                    elem: '#ID-laydate-range',
                    range: ['#ID-laydate-start-date', '#ID-laydate-end-date']
                });
                // 日期范围 - 左右面板联动选择模式
                laydate.render({
                    elem: '#ID-laydate-rangeLinked',
                    range: ['#ID-laydate-start-date-1', '#ID-laydate-end-date-1'],
                    rangeLinked: true, // 开启日期范围选择时的区间联动标注模式 ---  2.8+ 新增
                    fullPanel: true,
                    type: 'datetime',
                });

                // 年范围
                laydate.render({
                    elem: '#ID-laydate-range-year',
                    type: 'year',
                    range: true
                });

                // 年月范围
                laydate.render({
                    elem: '#ID-laydate-range-month',
                    type: 'month',
                    range: true
                });

                // 时间范围
                laydate.render({
                    elem: '#ID-laydate-range-time',
                    type: 'time',
                    range: true
                });

                // 日期时间范围
                laydate.render({
                    elem: '#ID-laydate-range-datetime',
                    type: 'datetime',
                    range: true
                });
                //用户表格初始化
                var dataTable = table.render({
                    elem: '#dataTable'
                    ,height: 500
                    ,url: "{{ route('admin.v2user.data',['invite_user_id'=>$invite_user_id]) }}" //数据接口
                    ,page: true //开启分页
                    ,cols: [[ //表头
                        {checkbox: true,fixed: true}
                        ,{field: 'id', title: 'ID',sort: true}
                        ,{field: 'email', width:'10%',title: '邮箱'}
                        ,{field: 'banned_val', title: '状态',sort: true}
                        ,{field: 'plan_name', width:'10%',title: '订阅',sort: true}
                        ,{field: 'group_name', width:'10%',title: '权限组',sort: true}
                        ,{field: 'total_used', width:'10%',title: '已用(G)',sort: true}
                        ,{field: 'transfer_enable',width:'10%', title: '流量(G)',sort: true}
                        ,{field: 'expired_at_val', width:'10%',title: '到期时间',sort: true}
                        ,{field: 'balance_val',width:'10%', title: '余额',sort: true}
                        ,{field: 'commission_balance_val',width:'10%', title: '佣金',sort: true}
                        ,{field: 'created_at_val',width:'18%', title: '加入时间',sort: true}
                        ,{fixed: 'right',width:'60%',title: '操作',align:'center', toolbar: '#options'}
                    ]]
                });

                //监听工具条
                table.on('tool(dataTable)', function(obj){ //注：tool是工具条事件名，dataTable是table原始容器的属性 lay-filter="对应的值"
                    var data = obj.data //获得当前行数据
                        ,layEvent = obj.event; //获得 lay-event 对应的值
                    if(layEvent === 'del'){
                        layer.confirm('确认删除吗？', function(index){
                            $.post("{{ route('admin.order.set') }}",{_method:'delete',ids:[data.id]},function (result) {
                                if (result.code==0){
                                    obj.del(); //删除对应行（tr）的DOM结构
                                }
                                layer.close(index);
                                layer.msg(result.msg)
                            });
                        });
                    } else if(layEvent === 'edit'){
                        location.href = '/admin/v2user/'+data.id+'/edit';
                    } else if(layEvent === 'order'){
                        location.href = '/admin/order/'+data.id+'/create';
                    }else if(layEvent === 'copy'){
                        var str=data.subscribe_url;
                        $('#copy_text').text(str).show();
                        var ele = document.getElementById("copy_text");
                        ele.select();
                        document.execCommand('copy', false, null);
                        $('#copy_text').hide();
                        layer.msg('复制成功')
                        // location.href = 'https://www.v2board.com/api/v1/client/subscribe?token=3c9b54f0e0a3e8a81c08434364c0edea'+data.token;
                    }else if(layEvent === 'reset'){
                        // var this_email=data.email;
                        layer.confirm('确定要重置'+data.email+'的安全信息吗？', function(index){
                            $.post("{{ route('admin.v2user.reset') }}",{_method:'get',ids:[data.id]},function (result) {
                                // if (result.code==0){
                                //     obj.del(); //删除对应行（tr）的DOM结构
                                // }
                                layer.close(index);
                                layer.msg(result.msg)
                            });
                        });
                    }else if(layEvent === 'ta_order'){
                        window.location.href = '/admin/order?user_id='+data.id;
                    }else if(layEvent === 'invite'){
                        window.location.href = '/admin/v2user?invite_user_id='+data.id;
                    }else if(layEvent === 'discharge'){
                        window.location.href = '/admin/discharge?user_id='+data.id;
                    }
                });


                //监听是否显示
                form.on('switch(isShow)', function(obj){
                    var index = layer.load();
                    var url = $(obj.elem).attr('url')
                    var data = {
                        "is_show" : obj.elem.checked==true?1:0,
                        "_method" : "put"
                    }
                    $.post(url,data,function (res) {
                        layer.close(index)
                        layer.msg(res.msg)
                    },'json');
                });


                //按钮批量删除
                $("#listDelete").click(function () {
                    var ids = []
                    var hasCheck = table.checkStatus('dataTable')
                    var hasCheckData = hasCheck.data
                    if (hasCheckData.length>0){
                        $.each(hasCheckData,function (index,element) {
                            ids.push(element.id)
                        })
                    }
                    if (ids.length>0){
                        layer.confirm('确认封禁吗？', function(index){
                            $.post("{{ route('admin.v2user.ban') }}",{_method:'delete',ids:ids},function (result) {
                                if (result.code==0){
                                    dataTable.reload()
                                }
                                layer.close(index);
                                layer.msg(result.msg)
                            });
                        })
                    }else {
                        layer.msg('请选择删除项')
                    }
                });

                //搜索
                $("#searchBtn").click(function (k) {
                    var field_name = $("#field_name option:selected").val();
                    var field_val = $.trim($("#field_val").val());
                    var is_admin = $("#is_admin option:selected").val();
                    var plan_id = $("#plan_id option:selected").val();
                    var banned = $("#banned option:selected").val();
                    var expired_at_start = $("#ID-laydate-start-date-1").val();
                    var expired_at_end = $("#ID-laydate-end-date-1").val();
                    if(field_name!='-1' && !field_val){
                        layer.msg('请输入搜索内容');
                        return false;
                    }

                    dataTable.reload({
                        where:{'field_name':field_name,'field_val':field_val,'is_admin':is_admin,'plan_id':plan_id,
                            'banned':banned,'expired_at_start':expired_at_start,'expired_at_end':expired_at_end
                        },
                        page:{curr:1}
                    })
                });


                //导出csv
                $("#down_csv").click(function () {

                    var field_name = $("#field_name option:selected").val();
                    var field_val = $.trim($("#field_val").val());
                    var is_admin = $("#is_admin option:selected").val();
                    var plan_id = $("#plan_id option:selected").val();
                    var banned = $("#banned option:selected").val();
                    var expired_at_start = $("#ID-laydate-start-date-1").val();
                    var expired_at_end = $("#ID-laydate-end-date-1").val();



                    $.ajax({
                        url:  "{{ route('admin.v2user.csv') }}",
                        type: 'post',
                        data: {
                            'field_name':field_name,
                            'field_val':field_val,
                            'is_admin':is_admin,
                            'plan_id':plan_id,
                            'banned':banned,
                            'expired_at_start':expired_at_start,
                            'expired_at_end':expired_at_end
                        },
                        async: false,
                        dataType: 'json',
                        success: function (res) {
                            //使用table.exportFile()导出数据
                            table.exportFile(
                                // "['邮箱','余额','推广佣金','总流量','剩余流量','套餐到期时间','订阅计划','订阅地址']",
                                ['邮箱','余额','推广佣金','总流量','剩余流量','套餐到期时间','订阅计划','订阅地址'],
                                // "exportTable",
                                res.data,
                                'v2board_user.csv'
                            );
                        }
                    });


                });

                //复制url
                $("#copy_button").click(function () {
                    var str=  "这是我要复制的内容";
                    $('#copy_text').text(str).show();
                    var ele = document.getElementById("copy_text");
                    ele.select();
                    document.execCommand('copy', false, null);
                    $('#copy_text').hide();
                });


                util.on('lay-on', {
                    // 'test-page': function(){
                    //     layer.open({
                    //         type: 1,
                    //         // area: ['420px', '240px'], // 宽高
                    //         content: '<div style="padding: 16px;">任意 HTML 内容</div>'
                    //     });
                    // },
                    // 'test-page-wrap': function(){
                    //     layer.open({
                    //         type: 1,
                    //         shade: false, // 不显示遮罩
                    //         content: $('#ID-test-layer-wrapper'), // 捕获的元素
                    //         end: function(){
                    //             // layer.msg('关闭后的回调', {icon:6});
                    //         }
                    //     });
                    // },
                    // 'test-page-title': function(){
                    //     layer.open({
                    //         type: 1,
                    //         area: ['420px', '240px'], // 宽高
                    //         title: false, // 不显示标题栏
                    //         closeBtn: 0,
                    //         shadeClose: true, // 点击遮罩关闭层
                    //         content: '<div style="padding: 16px;">任意 HTML 内容。可点击遮罩区域关闭。</div>'
                    //     });
                    // },
                    // 'test-page-move': function(){
                    //     layer.open({
                    //         type: 1,
                    //         area: ['420px', '240px'], // 宽高
                    //         title: false,
                    //         content: ['<div style="padding: 11px;">',
                    //             '任意 HTML 内容',
                    //             '<div style="padding: 16px 0;">',
                    //             '<button class="layui-btn" id="ID-test-layer-move">拖拽此处移动弹层</button>',
                    //             '</div>',
                    //             '</div>'].join(''),
                    //         move: '#ID-test-layer-move'
                    //     });
                    // },
                    'test-page-custom': function(){
                        console.log(23123);
                        var field_name = $("#field_name option:selected").val();
                        var field_val = $.trim($("#field_val").val());
                        var is_admin = $("#is_admin option:selected").val();
                        var plan_id = $("#plan_id option:selected").val();
                        var banned = $("#banned option:selected").val();
                        var expired_at_start = $("#ID-laydate-start-date-1").val();
                        var expired_at_end = $("#ID-laydate-end-date-1").val();

                        var is_filter=0;
                        if(field_name !='-1'|| is_admin!='-1' ||plan_id!='-1'||banned!='-1'||expired_at_start || expired_at_end){
                            is_filter=1;
                        }

                        var show_text=is_filter?'过滤用户':'全部用户';

                                {{--var html='<form class="layui-form" action="{{route('admin.v2user.send')}}" method="post">\n' +--}}
                        var html='' +
                            '            <div class="layui-form-item">\n' +
                            '                <label for="" class="layui-form-label">收件人</label>\n' +
                            '                <div class="layui-input-block">\n' +
                            '                    <input type="text" value="'+show_text+'" disabled class="layui-input" autocomplete="off" >\n' +
                            '                </div>\n' +
                            '            </div>\n' +
                            '\n' +
                            '            <div class="layui-form-item">\n' +
                            '                <label for="" class="layui-form-label">主题</label>\n' +
                            '                <div class="layui-input-block">\n' +
                            '                    <input type="text" name="subject" placeholder="请输入邮件主题" lay-verify="required" lay-reqtext="请填写邮件主题" lay-affix="clear" class="layui-input subject" autocomplete="off" >\n' +
                            '                </div>\n' +
                            '            </div>\n' +
                            '\n' +
                            '\n' +
                            '            <div class="layui-form-item">\n' +
                            '                <label for="" class="layui-form-label">发送内容</label>\n' +
                            '                <div class="layui-input-block">\n' +
                            '                    <textarea class="layui-textarea content" name="content" rows="6" lay-verify="required" placeholder="请输入邮件内容"></textarea>\n' +
                            '                </div>\n' +
                            '            </div>\n' +
                            '\n' +
                            '            <div class="layui-form-item">\n' +
                            '                <div class="layui-input-block">\n' +
                            '                    <button class="layui-btn confirm_send_email" lay-submit lay-filter="confirm_send_email">确 认</button>\n' +
                            '                    <a  class="layui-btn" href="{{route('admin.v2user')}}" >返 回</a>\n' +
                            '                </div>\n' +
                            '            </div>\n' +
                            // '            </form>' +
                            '';



                        layer.open({
                            type: 1,
                            // area: '550px',
                            area: ['800px', '350px'],
                            resize: false,
                            shadeClose: true,
                            // title: 'demo : layer + form',
                            title: '发送邮件',
                            content: html,
                            success: function(index){

                                // 对弹层中的表单进行初始化渲染
                                form.render();
                                // 表单提交事件
                                form.on('submit(confirm_send_email)', function(data){

                                    // var field = data.field; // 获取表单字段值
                                    var content = $.trim($(".content").val());
                                    var subject = $.trim($(".subject").val());
                                    if(!subject){
                                        layer.msg('主题不能为空');
                                        return false;
                                    }
                                    if(!content){
                                        layer.msg('内容不能为空');
                                        return false;
                                    }

                                    $('.confirm_send_email').attr("disabled",true);

                                    $.ajax({
                                        // async : false,
                                        // cache : false,
                                        type : 'POST',
                                        dataType: "json",
                                        data : {
                                            'field_name':field_name,
                                            'field_val':field_val,
                                            'is_admin':is_admin,
                                            'plan_id':plan_id,
                                            'banned':banned,
                                            'expired_at_start':expired_at_start,
                                            'expired_at_end':expired_at_end,
                                            'content':content,
                                            'subject':subject
                                        },
                                        url:  "{{ route('admin.v2user.send') }}",
                                        error : function() {// 请求失败处理函数
                                            $('.confirm_send_email').attr("disabled",false);
                                        },
                                        success : function(data) {
                                            $('.confirm_send_email').attr("disabled",false);
                                            layer.msg(data.msg);
                                            var index = parent.layer.getFrameIndex(window.name);
                                            // layer.close(index);
                                            setTimeout(function () {
                                                window.location.href="{{ route('admin.v2user') }}";
                                            }, 500);//延迟
                                            return false;
                                        }
                                    });



                                    // 显示填写结果，仅作演示用
                                    // layer.alert(JSON.stringify(field), {
                                    //     title: '当前填写的字段值'
                                    // });
                                    // 此处可执行 Ajax 等操作
                                    // …
                                    return false; // 阻止默认 form 跳转
                                });
                            }

                        });
                        return false;
                    }
                });
            })
        </script>




    @endcan
@endsection
