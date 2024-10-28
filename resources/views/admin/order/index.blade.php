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
                @can('finance.order.destroy')
                    {{--<button class="layui-btn layui-btn-sm layui-btn-danger" id="listDelete">删 除</button>--}}
                @endcan
                @can('finance.order.create')
                    <a class="layui-btn layui-btn-sm" href="{{ route('admin.order.create',['id'=>0]) }}">添加订单</a>
                @endcan
                    @can('v2board.user')
                        <a class="layui-btn layui-btn-sm" href="{{ route('admin.v2user') }}">回到用户列表</a>
                    @endcan

            </div>

            <div class="layui-form" >
                <div class="layui-input-inline">
                    <select name="field_name" id="field_name" lay-filter="field_name">
                        <option value="trade_no" >订单号</option>
                        <option value="status" >订单状态</option>
                        <option value="commission_status" >佣金状态</option>
                        <option value="user_id" >用户ID</option>
                        <option value="invite_user_id" >邀请人ID</option>
                        <option value="callback_no" >回调单号</option>
                        <option value="commission_balance" >佣金金额</option>
                    </select>
                </div>
                <div class="layui-input-inline">
                    <input type="text" name="field_val" id="field_val" placeholder="请输入搜索内容" class="layui-input" >
                </div>
                <input type="hidden" value="22" name="user_id">
                {{--<button class="layui-btn layui-btn-sm" id="searchBtn">搜 索</button>--}}
                <button class="layui-btn" id="searchBtn">搜 索</button>
                {{--<button class="layui-btn layui-btn-primary" id="searchBtn11">重置</button>--}}
                <a class="layui-btn layui-btn-primary" href="{{ route('admin.order') }}">重置</a>



            </div>

        </div>
        <div class="layui-card-body">
            <table id="dataTable" lay-filter="dataTable"></table>
            <script type="text/html" id="options">
                <div class="layui-btn-group">
                    @can('finance.order.detail')
                        {{--<script type="text/html" id="thumb">--}}
                        {{--<a href="@{{d.thumb}}" target="_blank" title="点击查看"><img src="@{{d.thumb}}" alt="" width="28" height="28"></a>--}}
                        {{--</script>--}}
                        {{--<a class="layui-btn layui-btn-sm" href="{{ route('admin.order.detail',['id'=>@d.id]) }}" >详情</a>--}}
                    @endcan
                </div>
            </script>


        </div>
    </div>
@endsection

@section('script')
    @can('finance.order')
        <script>
            layui.use(['layer','table','form'],function () {
                var layer = layui.layer;
                var form = layui.form;
                var table = layui.table;
                //用户表格初始化
                var dataTable = table.render({
                    elem: '#dataTable'
                    ,height: 500
                    ,url: "{{ route('admin.order.data',['user_id'=>$user_id]) }}" //数据接口
                    {{--,url: "{{ route('admin.order.data?user_id=12') }}" //数据接口--}}
                    ,page: true //开启分页
                    ,cols: [[ //表头
                        ,{field: 'id', title: 'ID',sort: true},
                        {field: 'trade_no',width:'15%', title: '# 订单号',templet: function(d){
                                return '<a  class="layui-btn layui-btn-xs" href="' + d.trade_no_url + '">' + d.trade_no_val + '</a>';
                            }}
                        ,{field: 'type_val',title: '类型'}
                        ,{field: 'plan_name',widht:'10%',  title: '订阅计划'}
                        ,{field: 'period_val',title: '周期'}
                        ,{field: 'total_amount_val',widht:'10%',  title: '支付金额'}
                        ,{field: 'status_val', title: '订单状态',width:'15%',templet: function(d){
                            if(d.status==0){
                                return d.status_val+'<br><a class="diy_cancel" href="' + d.cancel_url + '">标记为取消</a>'+'<br><a class="diy_paid" href="' + d.paid_url + '">标记为已支付</a>';
                            }else{
                                return '' + d.status_val + '';
                            }

                        }}
                        ,{field: 'commission_balance_val',widht:'10%',  title: '佣金金额'}
                        ,{field: 'commission_status_val', title: '佣金状态',width:'15%',templet: function(d){
                                    if(d.status!=0 &&d.status!=2 && d.commission_status==0){
                                        return d.commission_status_val+'<br><a class="diy_cancel" href="' + d.set_invalid + '">标记为无效</a>'+'<br><a class="diy_paid" href="' + d.set_effective + '">标记为有效</a>';
                                    }else if(d.status!=0 &&d.status!=2 && d.commission_status==1){
                                        return d.commission_status_val+'<br><a class="diy_cancel" href="' + d.set_invalid + '">标记为无效</a>'+'<br><a class="diy_confirmed" href="' + d.set_confirmed + '">标记为待确认</a>';
                                    }else if(d.status!=0 &&d.status!=2 && d.commission_status==3){
                                        return d.commission_status_val+'<br><a class="diy_paid" href="' + d.set_effective + '">标记为有效</a>'+'<br><a class="diy_confirmed" href="' + d.set_confirmed + '">标记为待确认</a>';
                                    }else{
                                        return '' + d.commission_status_val + '';
                                     }
                            }}
                        ,{field: 'created_at_val',widht:'20%', title: '创建时间'}
                        // ,{fixed: 'right',widht:'20%',align:'center', toolbar: '#options'}
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
                        location.href = '/admin/order/'+data.id+'/edit';
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
                        layer.confirm('确认删除吗？', function(index){
                            $.post("{{ route('admin.order.set') }}",{_method:'delete',ids:ids},function (result) {
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
                $("#searchBtn,#searchBtn11").click(function (k) {
                    var field_name = $("#field_name option:selected").val()
                    var field_val = $("#field_val").val();
                    if(k.target.id=='searchBtn11'){
                        field_val='';
                        $("#field_val").val('');
                    }

                    dataTable.reload({
                        where:{field_name:field_name,field_val:field_val},
                        page:{curr:1}
                    })
                })
            })
        </script>
    @endcan
@endsection