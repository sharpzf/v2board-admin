@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <div class="layui-btn-group ">
                @can('v2board.knowledge.create')
                    <a class="layui-btn layui-btn-sm" href="{{ route('admin.knowledge.create') }}">新增</a>
                @endcan
            </div>

        </div>
        <div class="layui-card-body">
            <table id="dataTable" lay-filter="dataTable"></table>
            <script type="text/html" id="options">
                <div class="layui-btn-group">
                    @can('v2board.knowledge.edit')
                        <a class="layui-btn layui-btn-sm" lay-event="edit">编辑</a>
                    @endcan
                    @can('v2board.knowledge.destroy')
                        <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="del">删除</a>
                    @endcan
                </div>
            </script>

            <script type="text/html" id="show">
                <input type="checkbox" name="show" data-id="@{{ d.id }}" lay-skin="switch"  lay-filter="show" @{{ d.show ==1?"checked":""}}>
            </script>

        </div>
    </div>
@endsection

@section('script')
    @can('v2board.knowledge')
        <script>
            layui.use(['layer','table','form'],function () {
                var layer = layui.layer;
                var form = layui.form;
                var table = layui.table;
                //用户表格初始化
                var dataTable = table.render({
                    elem: '#dataTable'
                    ,height: 500
                    ,url: "{{ route('admin.knowledge.data') }}" //数据接口
                    ,page: true //开启分页
                    ,cols: [[ //表头
                        {field: 'id', title: '文章ID'}
                        ,{ field: 'show', title: '显示', templet: '#show', style: "text-align:left", align: "center" , totalRow: true }
                        ,{field: 'title', title: '标题'}
                        ,{field: 'category', title: '分类'}
                        ,{field: 'updated_at', title: '更新时间'}
                        ,{fixed: 'right',align:'center', toolbar: '#options'}
                    ]]
                });

                //监听工具条
                table.on('tool(dataTable)', function(obj){ //注：tool是工具条事件名，dataTable是table原始容器的属性 lay-filter="对应的值"
                    var data = obj.data //获得当前行数据
                        ,layEvent = obj.event; //获得 lay-event 对应的值
                    if(layEvent === 'del'){
                        layer.confirm('确认删除吗？', function(index){
                            $.post("{{ route('admin.knowledge.destroy') }}",{_method:'delete',ids:[data.id]},function (result) {
                                if (result.code==0){
                                    obj.del(); //删除对应行（tr）的DOM结构
                                }
                                layer.close(index);
                                layer.msg(result.msg)
                            });
                        });
                    } else if(layEvent === 'edit'){
                        location.href = '/admin/knowledge/'+data.id+'/edit';
                    }
                });

                //监听配送操作
                form.on('switch(show)', function (obj) {
                    var alert_value = this.checked ? '1' : '0'; //设置1为开启，0为关闭
                    // var orgid = $(this).attr("data-orgid");
                    var id = $(this).attr("data-id");
                    // console.log(1111,alert_value,orgid,id);
                    // return false;
                    var url1="{{ route('admin.knowledge') }}"

                    var data11 = {
                            "show" : alert_value,
                            "id" : id
                        }
                    $.get(url1,data11,function (res) {
                        // layer.close(index)
                        layer.msg(res.msg)
                    },'json');
                    return false;//很重要，防止冒泡
                });

            })
        </script>
    @endcan
@endsection