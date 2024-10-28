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
        <input type="text" name="tags" value="{{ isset($node)?$node['tags']:''}}"  placeholder="请输入节点标签(多个用,隔开)" class="layui-input" >
    </div>
</div>


<div class="layui-form-item">
    <label for="" class="layui-form-label">权限组</label>
    <div class="layui-input-inline" style="width: 50%;">
        <select name="group_id" xm-select="group_id" lay-verify="required">
            <option value="">请选择权限组</option>
            @foreach($groups as $kk=>$vv)
                <option value="{{ $kk }}" @if(isset($node)&&in_array($kk,$node['limit_plan_ids'])) selected @endif>{{ $vv }}</option>
            @endforeach
        </select>
    </div>
    {{--<div class="layui-form-mid layui-word-aux">限制指定订阅可以使用优惠(为空则不限制)</div>--}}
</div>



<div class="layui-form-item">
    <label for="" class="layui-form-label">节点地址</label>

    <div class="layui-input-inline email_prefix_box"  >
        <input type="text" name="host" placeholder="地址或ip" autocomplete="off" class="layui-input email_prefix">

    </div>
    <label for="" class="layui-form-label" style="margin-left: 0px;width: auto;">TLS</label>
    <div class="layui-input-inline" style="margin-right: 0px;width: 8%;">
        {{--<input type="number" name="rate" lay-verify="required"  placeholder="请输入节点倍率" autocomplete="off" class="layui-input">--}}
        <select name="tls" lay-filter="tls">
            <option value="0"  @if(isset($node)&&$node['tls']==0) selected @endif>不支持</option>
            <option value="1"  @if(isset($node)&&$node['tls']==1) selected @endif>支持</option>
        </select>
    </div>


    <label for="" class="layui-form-label" style="margin-left: 0px;width: auto;">Server Name</label>
    <div class="layui-input-inline" style="margin-right: 0px">
        <input type="text" name="serverName" value=""  placeholder="不使用请留空" autocomplete="off" class="layui-input">
    </div>

    <label for="" class="layui-form-label" style="margin-left: 0px;width: auto;">Allow Insecure</label>
    <div class="layui-input-inline">
        <input type="checkbox" name="allowInsecure" lay-skin="switch"  @if(isset($node['tlsSettings']['allowInsecure'])&&$node['tlsSettings']['allowInsecure']==1) checked @endif >
    </div>
</div>




<div class="layui-form-item">
    <label for="" class="layui-form-label">连接端口</label>

    <div class="layui-input-inline" >
        <input type="number" name="port" placeholder="用户链接端口" autocomplete="off" class="layui-input">

    </div>

    <label for="" class="layui-form-label">服务端口</label>
    <div class="layui-input-inline" >
        <input type="number" name="server_port" placeholder="服务端开放端口" autocomplete="off" class="layui-input">

    </div>


    <label for="" class="layui-form-label">允许不安全</label>
    <div class="layui-input-inline" style="margin-right: 0px;width: auto;">
        {{--<input type="number" name="rate" lay-verify="required"  placeholder="请输入节点倍率" autocomplete="off" class="layui-input">--}}
        <select name="insecure" lay-filter="insecure">
            <option value="0"  @if(isset($node)&&$node['insecure']==0) selected @endif>否</option>
            <option value="1"  @if(isset($node)&&$node['insecure']==1) selected @endif>是</option>
        </select>
    </div>

</div>



<div class="layui-form-item cipher">
    <label for="" class="layui-form-label">加密算法</label>
    <div class="layui-input-inline">
        <select name="cipher">
                <option value="aes-128-gcm" @if(isset($node)&&$node['cipher']=='aes-128-gcm') selected @endif >aes-128-gcm</option>
                <option value="aes-128-gcm" @if(isset($node)&&$node['cipher']=='aes-192-gcm') selected @endif >aes-192-gcm</option>
                <option value="aes-128-gcm" @if(isset($node)&&$node['cipher']=='aes-256-gcm') selected @endif >aes-256-gcm</option>
                <option value="aes-128-gcm" @if(isset($node)&&$node['cipher']=='chacha20-ietf-poly1305') selected @endif >chacha20-ietf-poly1305</option>
                <option value="aes-128-gcm" @if(isset($node)&&$node['cipher']=='2022-blake3-aes-128-gcm') selected @endif >2022-blake3-aes-128-gcm</option>
                <option value="aes-128-gcm" @if(isset($node)&&$node['cipher']=='2022-blake3-aes-256-gcm') selected @endif >2022-blake3-aes-256-gcm</option>
        </select>
    </div>
    {{--<div class="layui-form-mid layui-word-aux">全局流量重置方式，默认每月1号。可以在订阅管理为订阅单独设置</div>--}}
</div>


<div class="layui-form-item obfs">
    <label for="" class="layui-form-label">混淆</label>
    <div class="layui-input-inline">
        <select name="cipher">
            <option value="aes-128-gcm" @if(isset($node)&&$node['cipher']==null) selected @endif >无</option>
            <option value="aes-128-gcm" @if(isset($node)&&$node['cipher']=='http') selected @endif >HTTP</option>
        </select>
    </div>
    {{--<div class="layui-form-mid layui-word-aux">全局流量重置方式，默认每月1号。可以在订阅管理为订阅单独设置</div>--}}
</div>


<div class="layui-form-item">
    <div class="layui-input-inline" >
        <input type="text" name="path" placeholder="路径" autocomplete="off" class="layui-input">
    </div>
    <div class="layui-input-inline" >
        <input type="text" name="host" placeholder="Host" autocomplete="off" class="layui-input">
    </div>
</div>





<div class="layui-form-item">
    <div class="layui-input-block">
        <button type="submit" class="layui-btn" lay-submit="" lay-filter="formDemo">确 认</button>
        <a  class="layui-btn" href="{{route('admin.node')}}" >返 回</a>
    </div>
</div>