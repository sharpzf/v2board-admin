@extends('admin.base')

@section('content')

    <style>
        .box_textarea{
            display: none;
        }
        .box_textarea_tcp{
            display: block;
        }
    </style>
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <h2>编辑节点</h2>
        </div>
        <div class="layui-card-body">
            <form class="layui-form" action="{{route('admin.node.update',['id'=>$node['id']])}}" method="post">
                {{csrf_field()}}

                <div class="layui-form-item">
                    <label for="" class="layui-form-label">节点名称</label>

                    <div class="layui-input-inline email_prefix_box" >
                        <input type="text" name="name" lay-verify="required" value="{{ isset($node)?$node['name']:''}}" placeholder="请输入节点名称" autocomplete="off" class="layui-input">

                    </div>
                    <label for="" class="layui-form-label">倍率</label>
                    <div class="layui-input-inline" style="margin-right: 0px;width: auto;">
                        <input type="number" name="rate"  value="{{ isset($node)?$node['rate']:''}}"  placeholder="请输入节点倍率" autocomplete="off" class="layui-input">
                    </div>
                    <div class="layui-input-inline" >
                        <button class="layui-btn layui-btn-disabled">x</button>
                        {{--<input type="text" name="email_prefix" placeholder="@" class="layui-input layui-btn layui-btn-disabled">--}}
                    </div>

                </div>



                <div class="layui-form-item">
                    <label for="" class="layui-form-label">节点标签</label>
                    <div class="layui-input-block">
                        <input type="text" name="tags" value="{{ isset($node)?$node['tags']:''}}"  placeholder="请输入节点标签(多个用,隔开)" class="layui-input"  autocomplete="off">
                    </div>
                </div>


                <div class="layui-form-item">
                    <label for="" class="layui-form-label">权限组</label>
                    <div class="layui-input-inline" style="width: 50%;">
                        <select name="group_id" xm-select="group_id" lay-verify="required">
                            <option value="">请选择权限组</option>
                            @foreach($groups as $kk=>$vv)
                                <option value="{{ $kk }}" @if(isset($node)&&in_array($kk,$node['group_id'])) selected @endif>{{ $vv }}</option>
                            @endforeach
                        </select>
                    </div>
                    {{--<div class="layui-form-mid layui-word-aux">限制指定订阅可以使用优惠(为空则不限制)</div>--}}
                </div>
                <div class="layui-form-item">
                    <label for="" class="layui-form-label">节点地址</label>
                    <div class="layui-input-inline">
                        <input type="text" name="host" lay-verify="required" value="{{ isset($node)?$node['host']:''}}"  placeholder="地址或ip" class="layui-input"  autocomplete="off">
                    </div>
                    {{--<label for="" class="layui-form-label">TLS</label>--}}
                    {{--<div class="layui-input-inline">--}}
                        {{--<input type="number" name="rate" lay-verify="required"  placeholder="请输入节点倍率" autocomplete="off" class="layui-input">--}}
                        {{--<select name="tls" lay-filter="tls">--}}
                            {{--<option value="0"  @if(isset($node)&&$node['tls']==0) selected @endif>不支持</option>--}}
                            {{--<option value="1"  @if(isset($node)&&$node['tls']==1) selected @endif>支持</option>--}}
                        {{--</select>--}}
                    {{--</div>--}}

                </div>

                <div class="layui-form-item">
                    <label for="" class="layui-form-label">连接端口</label>

                    <div class="layui-input-inline" >
                        <input type="number" name="port" value="{{ isset($node)?$node['port']:''}}" lay-verify="required" placeholder="用户连接端口" autocomplete="off" class="layui-input">

                    </div>

                    <label for="" class="layui-form-label">服务端口</label>
                    <div class="layui-input-inline" >
                        <input type="number" name="server_port" value="{{ isset($node)?$node['server_port']:''}}"  lay-verify="required" placeholder="服务端开放端口" autocomplete="off" class="layui-input">

                    </div>

                    <label for="" class="layui-form-label">允许不安全</label>
                    <div class="layui-input-inline" >
                        {{--<input type="number" name="server_port" lay-verify="required" placeholder="非NAT同连接端口" autocomplete="off" class="layui-input">--}}
                        <select name="insecure" lay-filter="insecure">
                            <option value="0"  @if(isset($node)&&$node['allow_insecure']==0) selected @endif>否</option>
                            <option value="1"  @if(isset($node)&&$node['allow_insecure']==1) selected @endif>是</option>
                        </select>
                    </div>

                </div>


                <div class="layui-form-item">
                    <label for="" class="layui-form-label">服务器名称指示(sni)</label>
                    <div class="layui-input-block">
                        <input type="text" name="server_name" value="{{ isset($node)?$node['server_name']:''}}"  placeholder="当节点地址与证书不一致时用于证书验证" class="layui-input"  autocomplete="off">
                    </div>

                </div>


                <div class="layui-form-item">
                    <label for="" class="layui-form-label">父节点</label>
                    <div class="layui-input-inline" style="width: 50%;">
                        <select name="parent_id">
                            <option value="" @if(isset($node)&&$node['parent_id']==null) selected @endif>无</option>
                            @foreach($parents as $kk=>$vv)
                                <option value="{{ $kk }}" @if(isset($node)&&$node['parent_id']==$kk) selected @endif>{{ $vv }}</option>
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
                                <option value="{{ $kk }}" @if($node['route_id']&&in_array($kk,$node['route_id'])) selected @endif>{{ $vv }}</option>
                            @endforeach
                        </select>
                    </div>
                    {{--<div class="layui-form-mid layui-word-aux">限制指定订阅可以使用优惠(为空则不限制)</div>--}}
                </div>

                <input type="hidden" name="id" value="{{ $node['id'] }}" >
                <input type="hidden" name="type" value="{{ $node['type'] }}" >

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
