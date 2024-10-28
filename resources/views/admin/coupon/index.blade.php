@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <div class="layui-btn-group ">
                @can('finance.coupon.destroy')
                    {{--<button class="layui-btn layui-btn-sm layui-btn-danger" id="listDelete">删 除</button>--}}
                @endcan
                @can('finance.coupon.create')
                    <a class="layui-btn layui-btn-sm" href="{{ route('admin.coupon.create') }}">添加优惠卷</a>
                @endcan
                {{--<button class="layui-btn layui-btn-sm" id="searchBtn">搜 索</button>--}}
            </div>

        </div>
        <div class="layui-card-body">
            <table id="dataTable" lay-filter="dataTable"></table>
            <script type="text/html" id="options">
                <div class="layui-btn-group">
                    @can('finance.coupon.edit')
                        <a class="layui-btn layui-btn-sm" lay-event="edit">编辑</a>
                    @endcan
                    @can('finance.coupon.destroy')
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
    @can('finance.coupon')
        <script>
            layui.use(['layer','table','form'],function () {
                var layer = layui.layer;
                var form = layui.form;
                var table = layui.table;
                //用户表格初始化
                var dataTable = table.render({
                    elem: '#dataTable'
                    ,height: 500
                    ,url: "{{ route('admin.coupon.data') }}" //数据接口
                    ,page: true //开启分页
                    ,cols: [[ //表头
                        { field: 'id', title: '#' }
                        ,{ field: 'show', title: '启用', templet: '#show', style: "text-align:left", align: "center" }
                        ,{field: 'name', title: '券名称'}
                        ,{field: 'type', title: '类型'}
                        ,{field: 'code', title: '券码'}
                        ,{field: 'limit_use', title: '剩余次数'}
                        ,{field: 'period_of_validity', width:'30%',title: '有效期'}
                        ,{fixed: 'right',align:'center', toolbar: '#options'}
                    ]]
                });

                //监听工具条
                table.on('tool(dataTable)', function(obj){ //注：tool是工具条事件名，dataTable是table原始容器的属性 lay-filter="对应的值"
                    var data = obj.data //获得当前行数据
                        ,layEvent = obj.event; //获得 lay-event 对应的值
                    if(layEvent === 'del'){
                        layer.confirm('确认删除吗？', function(index){
                            $.post("{{ route('admin.coupon.destroy') }}",{_method:'delete',ids:[data.id]},function (result) {
                                if (result.code==0){
                                    obj.del(); //删除对应行（tr）的DOM结构
                                }
                                layer.close(index);
                                layer.msg(result.msg)
                            });
                        });
                    } else if(layEvent === 'edit'){
                        location.href = '/admin/coupon/'+data.id+'/edit';
                    }
                });

                //监听配送操作
                form.on('switch(show)', function (obj) {
                    var alert_value = this.checked ? '1' : '0'; //设置1为开启，0为关闭
                    // var orgid = $(this).attr("data-orgid");
                    var id = $(this).attr("data-id");
                    // console.log(1111,alert_value,orgid,id);
                    // return false;
                    var url1="{{ route('admin.coupon') }}"

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



                @can('finance.coupon.edit')
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
                @endcan

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
                            $.post("{{ route('admin.coupon.destroy') }}",{_method:'delete',ids:ids},function (result) {
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
                    var catId = $("#category_id").val()
                    var title = $("#title").val();
                    dataTable.reload({
                        where:{category_id:catId,title:title},
                        page:{curr:1}
                    })
                })
            })
        </script>
    @endcan
@endsection