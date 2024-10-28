{{csrf_field()}}
<div class="layui-form-item">
    <label for="" class="layui-form-label">套餐名称</label>
    <div class="layui-input-block">
        <input type="text" name="name" value="{{$plan->name??old('name')}}" lay-verify="required" placeholder="请输入套餐名称" class="layui-input" >
    </div>
</div>


<div class="layui-form-item">
    <label for="" class="layui-form-label">套餐描述</label>
    <div class="layui-input-block">
        <textarea name="content"  placeholder="请输入套餐描述，支持HTML" class="layui-textarea">{{$plan->content??old('content')}}</textarea>
        {{--<input type="text" name="name" value="{{$plan->name??old('name')}}" lay-verify="required" placeholder="请输入套餐名称" class="layui-input" >--}}
    </div>
</div>

<div class="layui-form-item">
    <div class="layui-inline">
        <label class="layui-form-label">月付</label>
        <div class="layui-input-inline" style="width: 100px;">
            <input type="number" name="month_price" value="{{isset($plan)?sprintf("%.2f", $plan->month_price/100):''}}" placeholder="￥" autocomplete="off" title="" step="0.001" class="layui-input" >
        </div>
    </div>

    <div class="layui-inline">
        <label class="layui-form-label">季付</label>
        <div class="layui-input-inline" style="width: 100px;">
            <input type="number" name="quarter_price" value="{{isset($plan)?sprintf("%.2f", $plan->quarter_price/100):''}}" autocomplete="off" class="layui-input" placeholder="￥" title="" step="0.001">
        </div>
    </div>

    <div class="layui-inline">
        <label class="layui-form-label">半年</label>
        <div class="layui-input-inline" style="width: 100px;">
            <input type="number" name="half_year_price" value="{{isset($plan)?sprintf("%.2f", $plan->half_year_price/100):''}}" autocomplete="off" class="layui-input" placeholder="￥" title="" step="0.001">
        </div>
    </div>
    <div class="layui-inline">
        <label class="layui-form-label">年付</label>
        <div class="layui-input-inline" style="width: 100px;">
            <input type="number" name="year_price" value="{{isset($plan)?sprintf("%.2f", $plan->year_price/100):''}}" autocomplete="off" class="layui-input" placeholder="￥" title="" step="0.001">
        </div>
    </div>
    <div class="layui-inline">
        <label class="layui-form-label">两年付</label>
        <div class="layui-input-inline" style="width: 100px;">
            <input type="number" name="two_year_price" value="{{isset($plan)?sprintf("%.2f", $plan->two_year_price/100):''}}" autocomplete="off" class="layui-input" placeholder="￥" title="" step="0.001">
        </div>
    </div>
    <div class="layui-inline">
        <label class="layui-form-label">三年付</label>
        <div class="layui-input-inline" style="width: 100px;">
            <input type="number" name="three_year_price" value="{{isset($plan)?sprintf("%.2f", $plan->three_year_price/100):''}}" autocomplete="off" class="layui-input" placeholder="￥" title="" step="0.001">
        </div>
    </div>

    <div class="layui-inline">
        <label class="layui-form-label">一次性</label>
        <div class="layui-input-inline" style="width: 100px;">
            <input type="number" name="onetime_price" value="{{isset($plan)?sprintf("%.2f", $plan->onetime_price/100):''}}" autocomplete="off" class="layui-input" placeholder="￥" title="" step="0.001">
        </div>
    </div>


    <div class="layui-inline">
        <label class="layui-form-label">重置包</label>
        <div class="layui-input-inline" style="width: 100px;">
            <input type="number" name="reset_price" value="{{isset($plan)?sprintf("%.2f", $plan->reset_price/100):''}}" autocomplete="off" class="layui-input" placeholder="￥" title="" step="0.001">
        </div>
    </div>

</div>



<div class="layui-form-item">
    <label for="" class="layui-form-label">套餐流量</label>
    <div class="layui-input-block">
        <input type="number" name="transfer_enable" value="{{$plan->transfer_enable??old('transfer_enable')}}" lay-verify="required" placeholder="请输入套餐流量(GB)" class="layui-input" >
    </div>
</div>


<div class="layui-form-item">
    <label for="" class="layui-form-label">权限组</label>
    <div class="layui-input-block">
        <select name="group_id" lay-search  lay-filter="group_id" lay-verify="required">
            @foreach($serverGroups as $first)
                <option value="{{ $first['id'] }}" @if(isset($plan)&&$plan->group_id==$first['id']) selected @endif>{{ $first['name'] }}</option>
            @endforeach
        </select>
    </div>
</div>


<div class="layui-form-item">
    <label for="" class="layui-form-label">流量重置方式</label>
    <div class="layui-input-block">
        <select name="reset_traffic_method" lay-search  lay-filter="reset_traffic_method">
            @foreach($reset_traffic_method as $kk=>$vv)
                <option value="{{ $kk }}" @if(isset($plan)&&$plan->reset_traffic_method===$kk) selected @endif>{{ $vv }}</option>
            @endforeach
        </select>
    </div>
</div>



<div class="layui-form-item">
    <label for="" class="layui-form-label">最大容纳用户量</label>
    <div class="layui-input-block">
        <input type="number" name="capacity_limit" value="{{$plan->capacity_limit??old('capacity_limit')}}"  placeholder="留空则不限制" class="layui-input" >
    </div>
</div>


<div class="layui-form-item">
    <label for="" class="layui-form-label">限速</label>
    <div class="layui-input-block">
        <input type="number" name="speed_limit" value="{{$plan->speed_limit??old('speed_limit')}}"  placeholder="留空则不限制" class="layui-input" >
    </div>
</div>

<div class="layui-form-item">
    {{--<label for="" class="layui-form-label">限速</label>--}}
    <div class="layui-input-block">
        <input type="checkbox" name="force_update" title="强制更新到用户" lay-skin="primary">

        {{--<input type="number" name="speed_limit" value="{{$plan->speed_limit??old('speed_limit')}}" lay-verify="required" placeholder="留空则不限制" class="layui-input" >--}}
    </div>
</div>


@if(isset($plan))
<input type="hidden" value="{{$plan->id??old('id')}}" name="id">

@endif

<div class="layui-form-item">
    <div class="layui-input-block">
        <button type="submit" class="layui-btn" lay-submit="" lay-filter="formDemo">确 认</button>
        <a  class="layui-btn" href="{{route('admin.plan')}}" >返 回</a>
    </div>
</div>

