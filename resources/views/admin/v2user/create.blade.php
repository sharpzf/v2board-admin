@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <h2>创建用户</h2>
        </div>
        <div class="layui-card-body">
            <form class="layui-form" action="{{route('admin.v2user.store')}}" method="post">

                {{csrf_field()}}
                <div class="layui-form-item">
                    <label for="" class="layui-form-label">邮箱</label>

                    <div class="layui-input-inline email_prefix_box" style="margin-right: 0px;">
                        <input type="text" name="email_prefix" placeholder="账号（批量生成请留空）" autocomplete="off" class="layui-input email_prefix">

                    </div>
                    <div class="layui-input-inline" style="margin-right: 0px;width: auto;">
                        <button class="layui-btn layui-btn-disabled">@</button>
                        {{--<input type="text" name="email_prefix" placeholder="@" class="layui-input layui-btn layui-btn-disabled">--}}
                    </div>
                    <div class="layui-input-inline">
                        <input type="text" name="email_suffix" lay-verify="required"  placeholder="域" autocomplete="off" class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label for="" class="layui-form-label">密码</label>
                    <div class="layui-input-inline">
                        <input type="text" name="password" placeholder="留空则密码与邮箱相同" class="layui-input" autocomplete="off" >
                    </div>
                </div>


                <div class="layui-form-item">

                    <div class="layui-inline">
                        <label class="layui-form-label">到期时间</label>
                        <div class="layui-input-inline">
                            {{--<input type="text" name="started_at" value="{{isset($coupon)?$coupon['started_at']:''}}" class="layui-input" lay-verify="required" autocomplete="off" id="ID-laydate-start-date-1" placeholder="开始时间">--}}
                            <input type="text" name="expired_at" id="test1" placeholder="留空则密码与邮箱相同" autocomplete="off" class="layui-input" >
                        </div>
                    </div>

                </div>


                <div class="layui-form-item">
                    <label for="" class="layui-form-label">订阅计划</label>
                    <div class="layui-input-inline">
                        <select name="plan_id">
                            <option value="">无</option>
                            @foreach($plan as $kk=>$vv)
                                <option value="{{ $kk }}">{{ $vv }}</option>
                            @endforeach
                        </select>
                    </div>
                    {{--<div class="layui-form-mid layui-word-aux">限制指定订阅可以使用优惠(为空则不限制)</div>--}}
                </div>


                <div class="layui-form-item generate_count_box">
                    <label for="" class="layui-form-label">生成数量</label>
                    <div class="layui-input-inline" style="width: 22%;">
                        <input type="number" name="generate_count" placeholder="如果为批量生成请输入生成数量" class="layui-input generate_count" autocomplete="off" >
                    </div>
                </div>


                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button type="submit" class="layui-btn" lay-submit="" lay-filter="formDemo">确 认</button>
                        <a  class="layui-btn" href="{{route('admin.v2user')}}" >返 回</a>
                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection


@section('script')
    <script>
        layui.use('laydate', function(){
            var laydate = layui.laydate;

            //执行一个laydate实例
            laydate.render({
                elem: '#test1' //指定元素
            });
        });
    </script>

    <script>
        $('.email_prefix').blur(function(){
            if($.trim($(this).val())){
                $('.generate_count_box').hide();
            }else{
                $('.generate_count_box').show();
            }
        });
        $('.generate_count').blur(function(){
            if($.trim($(this).val())){
                $('.email_prefix_box').hide();
            }else{
                $('.email_prefix_box').show();
            }
        });
    </script>

@endsection


