@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">

            <div class="layui-btn-group ">
                @can('v2board.user')
                    <a class="layui-btn layui-btn-sm" href="{{ route('admin.v2user') }}">返回用户列表</a>
                @endcan


            </div>

        </div>
        <div class="layui-card-body">
            <table id="dataTable" lay-filter="dataTable"></table>
        </div>
    </div>
@endsection

@section('script')
    @can('v2board.discharge')
        <script>
            layui.use(['layer','table','form'],function () {
                var layer = layui.layer;
                var form = layui.form;
                var table = layui.table;
                //用户表格初始化
                var dataTable = table.render({
                    elem: '#dataTable'
                    ,height: 500
                    ,url: "{{ route('admin.discharge.data',['user_id'=>$user_id]) }}" //数据接口
                    ,page: true //开启分页
                    ,cols: [[ //表头
                        {field: 'record_at', title: '日期'}
                        ,{field: 'u', title: '上行'}
                        ,{field: 'd', title: '下行'}
                        ,{field: 'server_rate', title: '倍率'}
                    ]]
                });

                {{--//监听工具条--}}
                {{--table.on('tool(dataTable)', function(obj){ //注：tool是工具条事件名，dataTable是table原始容器的属性 lay-filter="对应的值"--}}
                    {{--var data = obj.data //获得当前行数据--}}
                        {{--,layEvent = obj.event; //获得 lay-event 对应的值--}}
                    {{--if(layEvent === 'del'){--}}
                        {{--layer.confirm('确认删除吗？', function(index){--}}
                            {{--$.post("{{ route('admin.plan.destroy') }}",{_method:'delete',ids:[data.id]},function (result) {--}}
                                {{--if (result.code==0){--}}
                                    {{--obj.del(); //删除对应行（tr）的DOM结构--}}
                                {{--}--}}
                                {{--layer.close(index);--}}
                                {{--layer.msg(result.msg)--}}
                            {{--});--}}
                        {{--});--}}
                    {{--} else if(layEvent === 'edit'){--}}
                        {{--location.href = '/admin/plan/'+data.id+'/edit';--}}
                    {{--}--}}
                {{--});--}}

            })
        </script>
    @endcan
@endsection