@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <h2>订单信息</h2>
        </div>
        <div class="layui-card-body">
            <div class="layui-form-item">
                <label for="" class="layui-form-label">邮箱:</label>
                <div class="layui-input-block">
                    <div class="layui-form-mid layui-word-aux" style="float: left">{{$order['user']['email']}}</div>
                </div>
            </div>
        </div>
        <div class="layui-card-body">
            <div class="layui-form-item">
                <label for="" class="layui-form-label">订单号:</label>
                <div class="layui-input-block">
                    <div class="layui-form-mid layui-word-aux" style="float: left">{{$order['trade_no']}}</div>
                </div>
            </div>
        </div>

        <div class="layui-card-body">
            <div class="layui-form-item">
                <label for="" class="layui-form-label">订单周期:</label>
                <div class="layui-input-block">
                    <div class="layui-form-mid layui-word-aux" style="float: left">{{$period_arr[$order['period']]}}</div>
                </div>
            </div>
        </div>


        <div class="layui-card-body">
            <div class="layui-form-item">
                <label for="" class="layui-form-label">订单状态:</label>
                <div class="layui-input-block">
                    <div class="layui-form-mid layui-word-aux" style="float: left">{{$order_status[$order['status']]}}</div>
                </div>
            </div>
        </div>



        <div class="layui-card-body">
            <div class="layui-form-item">
                <label for="" class="layui-form-label">订阅计划:</label>
                <div class="layui-input-block">
                    <div class="layui-form-mid layui-word-aux" style="float: left">{{$order['plan']['name']}}</div>
                </div>
            </div>
        </div>

        <div class="layui-card-body">
            <div class="layui-form-item">
                <label for="" class="layui-form-label">回调单号:</label>
                <div class="layui-input-block">
                    <div class="layui-form-mid layui-word-aux" style="float: left">{{$order['callback_no']}}</div>
                </div>
            </div>
        </div>

        <div class="layui-card-body">
            <div class="layui-form-item">
                <label for="" class="layui-form-label">支付金额:</label>
                <div class="layui-input-block">
                    <div class="layui-form-mid layui-word-aux" style="float: left">{{sprintf("%.2f", $order['total_amount']/100)}}</div>
                </div>
            </div>
        </div>


        <div class="layui-card-body">
            <div class="layui-form-item">
                <label for="" class="layui-form-label">余额支付:</label>
                <div class="layui-input-block">
                    <div class="layui-form-mid layui-word-aux" style="float: left">{{sprintf("%.2f", $order['balance_amount']/100)}}</div>
                </div>
            </div>
        </div>


        <div class="layui-card-body">
            <div class="layui-form-item">
                <label for="" class="layui-form-label">优惠金额:</label>
                <div class="layui-input-block">
                    <div class="layui-form-mid layui-word-aux" style="float: left">{{sprintf("%.2f", $order['discount_amount']/100)}}</div>
                </div>
            </div>
        </div>


        <div class="layui-card-body">
            <div class="layui-form-item">
                <label for="" class="layui-form-label">退回金额:</label>
                <div class="layui-input-block">
                    <div class="layui-form-mid layui-word-aux" style="float: left">{{sprintf("%.2f", $order['refund_amount']/100)}}</div>
                </div>
            </div>
        </div>


        <div class="layui-card-body">
            <div class="layui-form-item">
                <label for="" class="layui-form-label">折抵金额:</label>
                <div class="layui-input-block">
                    <div class="layui-form-mid layui-word-aux" style="float: left">{{sprintf("%.2f", $order['surplus_amount']/100)}}</div>
                </div>
            </div>
        </div>


        <div class="layui-card-body">
            <div class="layui-form-item">
                <label for="" class="layui-form-label">创建时间:</label>
                <div class="layui-input-block">
                    <div class="layui-form-mid layui-word-aux" style="float: left">{{date('Y-m-d H:i:s',$order['created_at'])}}</div>
                </div>
            </div>
        </div>


        <div class="layui-card-body">
            <div class="layui-form-item">
                <label for="" class="layui-form-label">更新时间:</label>
                <div class="layui-input-block">
                    <div class="layui-form-mid layui-word-aux" style="float: left">{{date('Y-m-d H:i:s',$order['updated_at'])}}</div>
                </div>
            </div>
        </div>


        <div class="layui-form-item">
            <div class="layui-input-block">
                {{--<button type="submit" class="layui-btn" lay-submit="" lay-filter="formDemo">确 认</button>--}}
                <a  class="layui-btn" href="{{route('admin.order')}}" >返 回</a>
            </div>
        </div>









    </div>


@endsection

