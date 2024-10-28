@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <div class="layui-btn-group ">
                {{--@can('v2board.knowledge.create')--}}
                    <a class="layui-btn layui-btn-sm" href="{{ route('admin.index') }}">回到仪表盘</a>
                {{--@endcan--}}
            </div>

            <div class="layui-form" >

                <div class="layui-input-inline">
                    <select name="status" id="status">
                        <option value="-1" >请选择状态</option>
                        <option value="0" >已开启</option>
                        <option value="1" >已关闭</option>
                    </select>
                </div>

                <div class="layui-input-inline">
                    <input type="text" name="email" id="email" placeholder="请输入邮箱搜索" class="layui-input" >
                </div>

                <button class="layui-btn" id="searchBtn">搜 索</button>
                <a class="layui-btn layui-btn-primary" href="{{ route('admin.ticket') }}" >重置</a>

            </div>

        </div>
        <div class="layui-card-body">
            <table id="dataTable" lay-filter="dataTable"></table>
            {{--<script type="text/html" id="options">--}}
                {{--<div class="layui-btn-group">--}}
                    {{--@can('v2board.ticket.detail')--}}
                        {{--<a class="layui-btn layui-btn-sm" lay-event="detail">查看</a>--}}
                    {{--@endcan--}}
                    {{--@can('v2board.ticket.close')--}}


                            {{--@if()--}}
                                {{--<a class="layui-btn layui-btn-sm" lay-event="close">关闭0</a>--}}
                            {{--@endif--}}
                            {{--{{#  if(d.status== "1"){ }}--}}
                                    {{--<a class="layui-btn layui-btn-sm">关闭1</a>--}}
                            {{--{{#  } }}--}}



                    {{--@endcan--}}


                {{--</div>--}}
            {{--</script>--}}

            {{--<script type="text/html" id="show">--}}
                {{--<input type="checkbox" name="show" data-id="@{{ d.id }}" lay-skin="switch"  lay-filter="show" @{{ d.show ==1?"checked":""}}>--}}
            {{--</script>--}}

        </div>
    </div>
@endsection

@section('script')
    @can('v2board.ticket')
        <script>
            layui.use(['layer','table','form'],function () {
                var layer = layui.layer;
                var form = layui.form;
                var table = layui.table;
                //用户表格初始化
                var dataTable = table.render({
                    elem: '#dataTable'
                    ,height: 500
                    ,url: "{{ route('admin.ticket.data') }}" //数据接口
                    ,page: true //开启分页
                    ,cols: [[ //表头
                        {field: 'id', title: '#'}
                        ,{field: 'subject', title: '主题'}
                        ,{field: 'level', title: '工单级别'}
                        ,{field: 'status_str', title: '工单状态'}
                        ,{field: 'created_at', title: '创建时间'}
                        ,{field: 'updated_at', title: '最后回复'}
                        ,{fixed: 'right',title: '操作',align:'center',templet: function(d){
                                if(d.status==0){
                                    return '<a class="layui-btn layui-btn-sm" lay-event="detail">查看</a><a class="layui-btn layui-btn-sm" lay-event="close">关闭</a>';
                                }else{
                                    return '<a class="layui-btn layui-btn-sm" lay-event="detail">查看</a>';
                                }
                }}
                    ]]
                });

                //监听工具条
                table.on('tool(dataTable)', function(obj){ //注：tool是工具条事件名，dataTable是table原始容器的属性 lay-filter="对应的值"
                    var data = obj.data //获得当前行数据
                        ,layEvent = obj.event; //获得 lay-event 对应的值
                    if(layEvent === 'close'){
                        location.href = '/admin/ticket/'+data.id+'/close';
                    } else if(layEvent === 'detail'){
                        location.href = '/admin/ticket/'+data.id+'/detail';
                    }
                });

                //搜索
                $("#searchBtn").click(function (k) {
                    var status = $("#status option:selected").val();
                    var email = $.trim($("#email").val());
                    // if(field_name!='-1' && !field_val){
                    //     layer.msg('请输入搜索内容');
                    //     return false;
                    // }
                    //     console.log(223,status,email);
                    //     return false;
                    dataTable.reload({
                        where:{'status':status,'email':email },
                        page:{curr:1}
                    })
                });

            })
        </script>
    @endcan
@endsection