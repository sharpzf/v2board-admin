@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <div class="layui-btn-group ">
                @can('config.payment.destroy')
                    {{--<button class="layui-btn layui-btn-sm layui-btn-danger" id="listDelete">删 除</button>--}}
                @endcan
                @can('config.payment.create')
                    <a class="layui-btn layui-btn-sm" href="{{ route('admin.payment.create') }}">添加支付方式</a>
                @endcan
                {{--<button class="layui-btn layui-btn-sm" id="searchBtn">搜 索</button>--}}
            </div>
        </div>
        <div class="layui-card-body">
            <table id="dataTable" lay-filter="dataTable"></table>
            <script type="text/html" id="options">
                <div class="layui-btn-group">
                    @can('config.payment.edit')
                        <a class="layui-btn layui-btn-sm" lay-event="edit">编辑</a>
                    @endcan
                    @can('config.payment.destroy')
                        <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="del">删除</a>
                    @endcan
                </div>
            </script>
            {{--<script type="text/html" id="thumb">--}}
                {{--<a href="@{{d.thumb}}" target="_blank" title="点击查看"><img src="@{{d.thumb}}" alt="" width="28" height="28"></a>--}}
            {{--</script>--}}
            {{--<script type="text/html" id="tags">--}}
                {{--@{{#  layui.each(d.tags, function(index, item){ }}--}}
                {{--<button type="button" class="layui-btn layui-btn-sm">@{{ item.name }}</button>--}}
                {{--@{{# }); }}--}}
            {{--</script>--}}
            <script type="text/html" id="group_id">
                @{{ d.group.name }}
            </script>

            <script type="text/html" id="enable">
                <input type="checkbox" name="enable" data-id="@{{ d.id }}" lay-skin="switch"  lay-filter="enable" @{{ d.enable ==1?"checked":""}}>
            </script>

            <script type="text/html" id="renew">
                <input type="checkbox" name="renew" data-id="@{{ d.id }}" lay-skin="switch"  lay-filter="renew" @{{ d.renew ==1?"checked":""}}>
            </script>
        </div>
    </div>
@endsection

@section('script')
    @can('config.payment')
        <script>
            layui.use(['layer','table','form'],function () {
                var layer = layui.layer;
                var form = layui.form;
                var table = layui.table;
                //用户表格初始化
                var dataTable = table.render({
                    elem: '#dataTable'
                    ,height: 500
                    ,url: "{{ route('admin.payment.data') }}" //数据接口
                    ,page: true //开启分页
                    ,cols: [[ //表头
                        // {checkbox: true,fixed: true}
                        // {field: 'id', title: '组ID', sort: true,width:80}
                        {field: 'id', title: 'ID'},
                        // { field: 'show', title: '销售状态', templet: '#show', style: "text-align:left", align: "center" ,sort: true, totalRow: true }
                        { field: 'enable', title: '启用', templet: '#enable', style: "text-align:left", align: "center" , totalRow: true }
                        // ,{ field: 'renew', title: '续费', templet: '#renew', style: "text-align:left", align: "center" , totalRow: true }
                        // ,{field: 'renew', title: '续费'}
                        ,{field: 'name', title: '显示名称'}
                        ,{field: 'payment', title: '支付接口'}
                        ,{field: 'notify_url', width:'40%',title: '通知地址'}
                        ,{fixed: 'right',align:'center', toolbar: '#options'}
                    ]]
                });

                //监听工具条
                table.on('tool(dataTable)', function(obj){ //注：tool是工具条事件名，dataTable是table原始容器的属性 lay-filter="对应的值"
                    var data = obj.data //获得当前行数据
                        ,layEvent = obj.event; //获得 lay-event 对应的值
                    if(layEvent === 'del'){
                        layer.confirm('确认删除吗？', function(index){
                            $.post("{{ route('admin.payment.destroy') }}",{_method:'delete',ids:[data.id]},function (result) {
                                if (result.code==0){
                                    obj.del(); //删除对应行（tr）的DOM结构
                                }
                                layer.close(index);
                                layer.msg(result.msg)
                            });
                        });
                    } else if(layEvent === 'edit'){
                        location.href = '/admin/payment/'+data.id+'/edit';
                    }
                });

                //监听配送操作
                form.on('switch(enable)', function (obj) {
                    var alert_value = this.checked ? '1' : '0'; //设置1为开启，0为关闭
                    // var orgid = $(this).attr("data-orgid");
                    var id = $(this).attr("data-id");
                    // console.log(1111,alert_value,orgid,id);
                    // return false;
                    var url1="{{ route('admin.payment') }}"

                    var data11 = {
                            "enable" : alert_value,
                            "id" : id
                        }
                    $.get(url1,data11,function (res) {
                        // layer.close(index)
                        layer.msg(res.msg)
                    },'json');

                    // $.ajax({
                    //     // url: 'ChangeFilterState',
                    //     url: url,
                    //     data: { "id": id, "show": alert_value },
                    //     beforeSend: function () {
                    //         //layer.msg('正在切换' + branch_name + '达达配送的状态' + ',请稍候!');
                    //     },
                    //     error: function (data) {
                    //         layer.msg("数据异常，操作失败");
                    //     },
                    //     success: function (data) {
                    //         layer.msg(data);
                    //     },
                    //     dataType: 'html'
                    // });
                    return false;//很重要，防止冒泡
                });



                //监听配送操作
                form.on('switch(renew)', function (obj) {
                    var alert_value = this.checked ? '1' : '0'; //设置1为开启，0为关闭
                    // var orgid = $(this).attr("data-orgid");
                    var id = $(this).attr("data-id");
                    // console.log(1111,alert_value,orgid,id);
                    // return false;
                    var url1="{{ route('admin.payment') }}"

                    var data11 = {
                        "renew" : alert_value,
                        "id" : id
                    }
                    $.get(url1,data11,function (res) {
                        // layer.close(index)
                        layer.msg(res.msg)
                    },'json');

                    return false;//很重要，防止冒泡
                });

                @can('config.payment.edit')
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
                            $.post("{{ route('admin.payment.destroy') }}",{_method:'delete',ids:ids},function (result) {
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