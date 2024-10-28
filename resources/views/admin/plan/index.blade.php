@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <div class="layui-btn-group ">
                @can('finance.plan.destroy')
                    {{--<button class="layui-btn layui-btn-sm layui-btn-danger" id="listDelete">删 除</button>--}}
                @endcan
                @can('finance.plan.create')
                    <a class="layui-btn layui-btn-sm" href="{{ route('admin.plan.create') }}">添 加</a>
                @endcan
                {{--<button class="layui-btn layui-btn-sm" id="searchBtn">搜 索</button>--}}
            </div>
            {{--<div class="layui-form" >--}}
                {{--<div class="layui-input-inline">--}}
                    {{--<select name="category_id" lay-verify="required" id="category_id">--}}
                        {{--<option value="">请选择分类</option>--}}
                        {{--@foreach($categorys as $category)--}}
                            {{--<option value="{{ $category->id }}" >{{ $category->name }}</option>--}}
                            {{--@if(isset($category->allChilds)&&!$category->allChilds->isEmpty())--}}
                                {{--@foreach($category->allChilds as $child)--}}
                                    {{--<option value="{{ $child->id }}" >&nbsp;&nbsp;&nbsp;┗━━{{ $child->name }}</option>--}}
                                    {{--@if(isset($child->allChilds)&&!$child->allChilds->isEmpty())--}}
                                        {{--@foreach($child->allChilds as $third)--}}
                                            {{--<option value="{{ $third->id }}" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;┗━━{{ $third->name }}</option>--}}
                                        {{--@endforeach--}}
                                    {{--@endif--}}
                                {{--@endforeach--}}
                            {{--@endif--}}
                        {{--@endforeach--}}
                    {{--</select>--}}
                {{--</div>--}}
                {{--<div class="layui-input-inline">--}}
                    {{--<input type="text" name="title" id="title" placeholder="请输入文章标题" class="layui-input">--}}
                {{--</div>--}}
            {{--</div>--}}
        </div>
        <div class="layui-card-body">
            <table id="dataTable" lay-filter="dataTable"></table>
            <script type="text/html" id="options">
                <div class="layui-btn-group">
                    @can('finance.plan.edit')
                        <a class="layui-btn layui-btn-sm" lay-event="edit">编辑</a>
                    @endcan
                    @can('finance.plan.destroy')
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

            <script type="text/html" id="show">
                <input type="checkbox" name="show" data-id="@{{ d.id }}" lay-skin="switch"  lay-filter="show" @{{ d.show ==1?"checked":""}}>
            </script>

            <script type="text/html" id="renew">
                <input type="checkbox" name="renew" data-id="@{{ d.id }}" lay-skin="switch"  lay-filter="renew" @{{ d.renew ==1?"checked":""}}>
            </script>
        </div>
    </div>
@endsection

@section('script')
    @can('finance.plan')
        <script>
            layui.use(['layer','table','form'],function () {
                var layer = layui.layer;
                var form = layui.form;
                var table = layui.table;
                //用户表格初始化
                var dataTable = table.render({
                    elem: '#dataTable'
                    ,height: 500
                    ,url: "{{ route('admin.plan.data') }}" //数据接口
                    ,page: true //开启分页
                    ,cols: [[ //表头
                        // {checkbox: true,fixed: true}
                        // {field: 'id', title: '组ID', sort: true,width:80}
                        // {field: 'show', title: '销售状态'}
                        // { field: 'show', title: '销售状态', templet: '#show', style: "text-align:left", align: "center" ,sort: true, totalRow: true }
                        { field: 'show', title: '销售状态', templet: '#show', style: "text-align:left", align: "center" , totalRow: true }
                        ,{ field: 'renew', title: '续费', templet: '#renew', style: "text-align:left", align: "center" , totalRow: true }
                        // ,{field: 'renew', title: '续费'}
                        ,{field: 'name', title: '名称'}
                        ,{field: 'transfer_enable', title: '流量'}
                        ,{field: 'month_price', title: '月付'}
                        ,{field: 'quarter_price', title: '季付'}
                        ,{field: 'half_year_price', title: '半年付'}
                        ,{field: 'year_price', title: '年付'}
                        ,{field: 'two_year_price', title: '两年付'}
                        ,{field: 'three_year_price', title: '三年付'}
                        ,{field: 'onetime_price', title: '一次性'}
                        ,{field: 'reset_price', title: '重置包'}
                        ,{field: 'group_id', title: '权限组',toolbar:'#group_id'}
                        ,{fixed: 'right',align:'center', toolbar: '#options'}
                    ]]
                });

                //监听工具条
                table.on('tool(dataTable)', function(obj){ //注：tool是工具条事件名，dataTable是table原始容器的属性 lay-filter="对应的值"
                    var data = obj.data //获得当前行数据
                        ,layEvent = obj.event; //获得 lay-event 对应的值
                    if(layEvent === 'del'){
                        layer.confirm('确认删除吗？', function(index){
                            $.post("{{ route('admin.plan.destroy') }}",{_method:'delete',ids:[data.id]},function (result) {
                                if (result.code==0){
                                    obj.del(); //删除对应行（tr）的DOM结构
                                }
                                layer.close(index);
                                layer.msg(result.msg)
                            });
                        });
                    } else if(layEvent === 'edit'){
                        location.href = '/admin/plan/'+data.id+'/edit';
                    }
                });

                //监听配送操作
                form.on('switch(show)', function (obj) {
                    var alert_value = this.checked ? '1' : '0'; //设置1为开启，0为关闭
                    // var orgid = $(this).attr("data-orgid");
                    var id = $(this).attr("data-id");
                    // console.log(1111,alert_value,orgid,id);
                    // return false;
                    var url1="{{ route('admin.plan') }}"

                    var data11 = {
                            "show" : alert_value,
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
                    var url1="{{ route('admin.plan') }}"

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

                @can('finance.plan.edit')
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
                            $.post("{{ route('admin.plan.destroy') }}",{_method:'delete',ids:ids},function (result) {
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