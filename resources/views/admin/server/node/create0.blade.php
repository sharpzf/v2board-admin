@extends('admin.base')

@section('content')

    <style>
        .obfs_box{
            display: none;
        }
    </style>
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <h2>创建节点</h2>
        </div>
        <div class="layui-card-body">
            <form class="layui-form" action="{{route('admin.node.store')}}" method="post">
                {{csrf_field()}}

                <div class="layui-form-item">
                    <label for="" class="layui-form-label">节点名称</label>

                    <div class="layui-input-inline email_prefix_box" >
                        <input type="text" name="name" lay-verify="required" value="" placeholder="请输入节点名称" autocomplete="off" class="layui-input">

                    </div>
                    <label for="" class="layui-form-label">倍率</label>
                    <div class="layui-input-inline" style="margin-right: 0px;width: auto;">
                        <input type="number" name="rate"  value="1"  placeholder="请输入节点倍率" autocomplete="off" class="layui-input">
                    </div>
                    <div class="layui-input-inline" >
                        <button class="layui-btn layui-btn-disabled">x</button>
                        {{--<input type="text" name="email_prefix" placeholder="@" class="layui-input layui-btn layui-btn-disabled">--}}
                    </div>

                </div>



                <div class="layui-form-item">
                    <label for="" class="layui-form-label">节点标签</label>
                    <div class="layui-input-block">
                        <input type="text" name="tags" value=""  placeholder="请输入节点标签(多个用,隔开)" class="layui-input"  autocomplete="off">
                    </div>
                </div>


                <div class="layui-form-item">
                    <label for="" class="layui-form-label">权限组</label>
                    <div class="layui-input-inline" style="width: 50%;">
                        <select name="group_id" xm-select="group_id" lay-verify="required">
                            <option value="">请选择权限组</option>
                            @foreach($groups as $kk=>$vv)
                                <option value="{{ $kk }}">{{ $vv }}</option>
                            @endforeach
                        </select>
                    </div>
                    {{--<div class="layui-form-mid layui-word-aux">限制指定订阅可以使用优惠(为空则不限制)</div>--}}
                </div>

                <div class="layui-form-item">
                    <label for="" class="layui-form-label">节点地址</label>
                    <div class="layui-input-block">
                        <input type="text" name="host" lay-verify="required" value=""  placeholder="地址或ip" class="layui-input"  autocomplete="off">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label for="" class="layui-form-label">连接端口</label>

                    <div class="layui-input-inline" >
                        <input type="number" name="port" lay-verify="required" placeholder="用户连接端口" autocomplete="off" class="layui-input">

                    </div>

                    <label for="" class="layui-form-label">服务端口</label>
                    <div class="layui-input-inline" >
                        <input type="number" name="server_port" lay-verify="required" placeholder="服务端开放端口" autocomplete="off" class="layui-input">

                    </div>

                </div>



                <div class="layui-form-item cipher">
                    <label for="" class="layui-form-label">加密算法</label>
                    <div class="layui-input-inline">
                        <select name="cipher">
                            <option value="aes-128-gcm">aes-128-gcm</option>
                            <option value="aes-192-gcm">aes-192-gcm</option>
                            <option value="aes-256-gcm">aes-256-gcm</option>
                            <option value="chacha20-ietf-poly1305">chacha20-ietf-poly1305</option>
                            <option value="2022-blake3-aes-128-gcm">2022-blake3-aes-128-gcm</option>
                            <option value="2022-blake3-aes-256-gcm">2022-blake3-aes-256-gcm</option>
                        </select>
                    </div>
                    {{--<div class="layui-form-mid layui-word-aux">全局流量重置方式，默认每月1号。可以在订阅管理为订阅单独设置</div>--}}
                </div>


                <div class="layui-form-item">
                    <label for="" class="layui-form-label">混淆</label>
                    <div class="layui-input-inline">
                        <select name="obfs" class="obfs" lay-filter="obfs">
                            <option value="" >无</option>
                            <option value="http">HTTP</option>
                        </select>
                    </div>
                    {{--<div class="layui-form-mid layui-word-aux">全局流量重置方式，默认每月1号。可以在订阅管理为订阅单独设置</div>--}}
                </div>


                {{--<div class="layui-form-ite obfs">--}}
                    {{--<label for="" class="layui-form-label"></label>--}}
                    {{--<div class="layui-input-inline" >--}}
                        {{--<input type="text" name="path" placeholder="路径" autocomplete="off" class="layui-input">--}}
                    {{--</div>--}}
                    {{--<label for="" class="layui-form-label"></label>--}}
                    {{--<div class="layui-input-inline" >--}}
                        {{--<input type="text" name="host" placeholder="Host" autocomplete="off" class="layui-input">--}}
                    {{--</div>--}}
                {{--</div>--}}


                <div class="layui-form-item obfs_box">
                    <label for="" class="layui-form-label"></label>

                    <div class="layui-input-inline" >
                        <input type="text" name="path1"  placeholder="路径" autocomplete="off" class="layui-input">
                    </div>
                    <label for="" class="layui-form-label"></label>
                    <div class="layui-input-inline" >
                        <input type="text" name="host1"  placeholder="Host" autocomplete="off" class="layui-input">

                    </div>

                </div>


                <div class="layui-form-item">
                    <label for="" class="layui-form-label">父节点</label>
                    <div class="layui-input-inline" style="width: 50%;">
                        <select name="parent_id">
                            <option value="">无</option>
                            @foreach($parents as $kk=>$vv)
                                <option value="{{ $kk }}">{{ $vv }}</option>
                            @endforeach
                        </select>
                    </div>
                    {{--<div class="layui-form-mid layui-word-aux">限制指定订阅可以使用优惠(为空则不限制)</div>--}}
                </div>



                <div class="layui-form-item">
                    <label for="" class="layui-form-label">路由组</label>
                    <div class="layui-input-inline" style="width: 50%;">
                        <select name="route_id" xm-select="route_id">
                            <option value="">请选择路由组</option>
                            @foreach($routes as $kk=>$vv)
                                <option value="{{ $kk }}">{{ $vv }}</option>
                            @endforeach
                        </select>
                    </div>
                    {{--<div class="layui-form-mid layui-word-aux">限制指定订阅可以使用优惠(为空则不限制)</div>--}}
                </div>

                <input type="hidden" name="type" value="{{ $type }}" >

                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button type="submit" class="layui-btn" lay-submit="" lay-filter="formDemo">确 认</button>
                        <a  class="layui-btn" href="{{route('admin.node')}}" >返 回</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    @include('admin.server.node._js')
@endsection

