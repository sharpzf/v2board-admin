
{{csrf_field()}}
<div class="layui-form-item">
    <label for="" class="layui-form-label">名称</label>
    <div class="layui-input-block">
        <input type="text" name="name" value="{{isset($coupon)?$coupon['name']:''}}" lay-verify="required" placeholder="请输入优惠卷名称" class="layui-input" >
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">自定义优惠券码</label>
    <div class="layui-input-block">
        <input type="text" name="code" value="{{isset($coupon)?$coupon['code']:''}}"  placeholder="自定义优惠券码(留空随机生成)" class="layui-input" >
    </div>
</div>

<div class="layui-form">
    {{--<label for="" class="layui-form-label">优惠信息</label>--}}
    <div class="layui-form-item">
        <div class="layui-inline">
            <label class="layui-form-label">优惠信息</label>
            <div class="layui-input-inline">
                <select name="type" lay-filter="type">
                    <option value="1"  @if(isset($coupon)&&$coupon['type']==1) selected @endif >按金额优惠</option>
                    <option value="2" @if(isset($coupon)&&$coupon['type']==2) selected @endif>按比例优惠</option>
                </select>
            </div>
        </div>
        <div class="layui-inline">
            {{--<label class="layui-form-label">输入框</label>--}}
            <div class="layui-input-inline">
                <input type="number" name="value" value="{{isset($coupon)?$coupon['value']:''}}" lay-verify="required" placeholder="请输入值" autocomplete="off" class="layui-input">
            </div>
        </div>
    </div>
</div>




<div class="layui-form">
    {{--<label for="" class="layui-form-label">优惠券有效期</label>--}}
    <div class="layui-form-item">

        <div class="layui-inline" id="ID-laydate-rangeLinked">
            <div class="layui-inline">
                <label class="layui-form-label">优惠券有效期</label>
                <div class="layui-input-inline">
                    <input type="text" name="started_at" value="{{isset($coupon)?$coupon['started_at']:''}}" class="layui-input" lay-verify="required" autocomplete="off" id="ID-laydate-start-date-1" placeholder="开始时间">
                </div>
            </div>

            <div class="layui-inline">
                {{--<label class="layui-form-label"></label>--}}
                <div class="layui-input-inline">
                    <input type="text" name="ended_at" value="{{isset($coupon)?$coupon['ended_at']:''}}" class="layui-input" lay-verify="required" autocomplete="off" id="ID-laydate-end-date-1" placeholder="结束时间">
                </div>
            </div>
        </div>

    </div>
</div>


<div class="layui-form-item">
    <label for="" class="layui-form-label">最大使用次数</label>
    <div class="layui-input-block">
        <input type="number" name="limit_use" value="{{isset($coupon)?$coupon['limit_use']:''}}"  placeholder="限制最大使用次数，用完则无法使用(为空则不限制)" class="layui-input" >
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">每个用户可使用次数</label>
    <div class="layui-input-block">
        <input type="number" name="limit_use_with_user" value="{{isset($coupon)?$coupon['limit_use_with_user']:''}}"  placeholder="限制每个用户可使用次数(为空则不限制)" class="layui-input" >
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">指定订阅</label>
    <div class="layui-input-inline" style="width: 50%;">
        <select name="limit_plan_ids" xm-select="limit_plan_ids">
            @foreach($plan as $kk=>$vv)
                {{--                <option value="{{ $kk }}" @if(isset($coupon)&&$coupon->group_id==$first['id']) selected @endif>{{ $vv }}</option>--}}
                <option value="{{ $kk }}" @if(isset($coupon)&&in_array($kk,$coupon['limit_plan_ids'])) selected @endif>{{ $vv }}</option>
            @endforeach
        </select>
    </div>
    <div class="layui-form-mid layui-word-aux">限制指定订阅可以使用优惠(为空则不限制)</div>
</div>



<div class="layui-form-item">
    <label for="" class="layui-form-label">指定周期</label>
    <div class="layui-input-inline" style="width: 50%;">
        <select name="limit_period" xm-select="limit_period">
            @foreach($limit_period as $kk=>$vv)
                {{--                <option value="{{ $kk }}" @if(isset($coupon)&&$coupon->group_id==$first['id']) selected @endif>{{ $vv }}</option>--}}
                <option value="{{ $kk }}" @if(isset($coupon)&&in_array($kk,$coupon['limit_period'])) selected @endif>{{ $vv }}</option>
            @endforeach
        </select>
    </div>
    <div class="layui-form-mid layui-word-aux">限制指定周期可以使用优惠(为空则不限制)</div>
</div>



<div class="layui-form-item">
    <div class="layui-input-block">
        <button type="submit" class="layui-btn" lay-submit="" lay-filter="formDemo">确 认</button>
        <a  class="layui-btn" href="{{route('admin.coupon')}}" >返 回</a>
    </div>
</div>

