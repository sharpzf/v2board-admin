@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <div class="layui-btn-group ">
                @can('server.node.create')
                    <a class="layui-btn layui-btn-sm" href="{{ route('admin.node.create',['type'=>0]) }}" style="margin-right: 5px;">shadowsocks添加</a>
                    <a class="layui-btn layui-btn-sm" href="{{ route('admin.node.create',['type'=>1]) }}" style="margin-right: 5px;">vmess添加</a>
                    <a class="layui-btn layui-btn-sm" href="{{ route('admin.node.create',['type'=>2]) }}" style="margin-right: 5px;">trojan添加</a>
                    <a class="layui-btn layui-btn-sm" href="{{ route('admin.node.create',['type'=>3]) }}" style="margin-right: 5px;">hysteria添加</a>
                @endcan

            </div>
            <div class="layui-form" >
                <div class="layui-input-inline">
                    <select name="type" id="type">
                        <option value="-1">请选择节点类型</option>
                        @foreach($node_arr as $kk=>$vl)
                            <option value="{{ $kk }}" >{{ $vl }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="layui-input-inline">
                    <input type="text" name="name" id="name" placeholder="节点名称" class="layui-input">
                </div>

                <button class="layui-btn" id="searchBtn">搜 索</button>
                <a class="layui-btn layui-btn-primary" href="{{ route('admin.node') }}" >重置</a>
            </div>
        </div>
        <div class="layui-card-body">
            <table id="dataTable" lay-filter="dataTable"></table>
            <script type="text/html" id="options">
                <div class="layui-btn-group">
                    @can('server.node.edit')
                        <a class="layui-btn layui-btn-sm" lay-event="edit">编辑</a>
                    @endcan
                    @can('server.node.destroy')
                        <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="del">删除</a>
                    @endcan
                        @can('server.node.copy')
                            <a class="layui-btn layui-btn-sm layui-btn-blue" lay-event="copy">复制</a>
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
    @can('server.node')
        <script>
            layui.use(['layer','table','form'],function () {
                var layer = layui.layer;
                var form = layui.form;
                var table = layui.table;
                //用户表格初始化
                var dataTable = table.render({
                    elem: '#dataTable'
                    ,height: 500
                    ,url: "{{ route('admin.node.data') }}" //数据接口
                    ,page: true //开启分页
                    ,cols: [[ //表头
                        {field: 'id_show', title: '节点ID'}
                        ,{ field: 'show', title: '显隐', templet: '#show', style: "text-align:left", align: "center" , totalRow: true }
                        ,{field: 'name', title: '节点',templet:function(d){
                            if(d.available_status==0){
                                return '<span class="layui-badge-dot layui-bg-red"></span> '+d.name;
                            }else if(d.available_status==1){
                                return '<span class="layui-badge-dot layui-bg-orange"></span> '+d.name;
                            }else{
                                return '<span class="layui-badge-dot layui-bg-blue"></span> '+d.name;
                            }

                            }}
                        ,{field: 'address', title: '地址'}
                        ,{field: 'online', title: '人数'}
                        ,{field: 'rate', title: '倍率'}
                        ,{field: 'group_name', title: '权限组'}
                        ,{fixed: 'right',align:'center',width:'20%', toolbar: '#options'}
                    ]]
                });

                //监听工具条
                table.on('tool(dataTable)', function(obj){ //注：tool是工具条事件名，dataTable是table原始容器的属性 lay-filter="对应的值"
                    var data = obj.data //获得当前行数据
                        ,layEvent = obj.event; //获得 lay-event 对应的值
                    if(layEvent === 'del'){
                        layer.confirm('确认删除吗？', function(index){
                            $.post("{{ route('admin.node.destroy') }}",{_method:'delete',ids:[data.id]},function (result) {
                                if (result.code==0){
                                    obj.del(); //删除对应行（tr）的DOM结构
                                }
                                layer.close(index);
                                layer.msg(result.msg)
                            });
                        });
                    } else if(layEvent === 'edit'){
                        location.href = '/admin/node/'+data.id+'/edit';
                    }else if(layEvent === 'copy'){
                        layer.confirm('确认复制吗？', function(index){
                            $.get("{{ route('admin.node.copy') }}",{_method:'get',ids:[data.id]},function (result) {
                                // if (result.code==0){
                                //     obj.del(); //删除对应行（tr）的DOM结构
                                // }
                                layer.close(index);
                                layer.msg(result.msg);
                                setTimeout(function () {
                                    location.href = '/admin/node';
                                }, 2000);
                            });
                        });
                    }
                });


                //监听配送操作
                form.on('switch(show)', function (obj) {
                    var alert_value = this.checked ? '1' : '0'; //设置1为开启，0为关闭
                    // var orgid = $(this).attr("data-orgid");
                    var id = $(this).attr("data-id");
                    // console.log(1111,alert_value,orgid,id);
                    // return false;
                    var url1="{{ route('admin.node') }}"

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
                        layer.confirm('确认删除吗？', function(index){
                            $.post("{{ route('admin.node.destroy') }}",{_method:'delete',ids:ids},function (result) {
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
                })

                //搜索
                $("#searchBtn").click(function () {
                    var type = $("#type").val()
                    var name = $.trim($("#name").val());
                    dataTable.reload({
                        where:{type:type,name:name},
                        page:{curr:1}
                    })
                })
            })
        </script>
    @endcan
@endsection