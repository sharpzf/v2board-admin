{{csrf_field()}}
<div class="layui-form-item">
    <label for="" class="layui-form-label">用户邮箱</label>
    <div class="layui-input-block">
        <input type="text" name="email" value="{{ $user?$user['email']:'' }}" lay-verify="required" placeholder="请输入用户邮箱" class="layui-input" >
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">请选择订阅</label>
    <div class="layui-input-inline">
        <select name="plan_id" lay-filter="plan_id">
            @foreach($plan as $kk=>$first)
                <option value="{{ $kk }}" >{{ $first }}</option>
            @endforeach
        </select>
    </div>
</div>



<div class="layui-form-item">
    <label for="" class="layui-form-label">请选择周期</label>
    <div class="layui-input-inline">
        <select name="period" lay-filter="period">
            @foreach($period_arr as $k1=>$v1)
                <option value="{{ $k1 }}" >{{ $v1 }}</option>
            @endforeach
        </select>
    </div>
</div>



<div class="layui-form-item">
    <label for="" class="layui-form-label">支付金额</label>
    <div class="layui-input-block">
        <input type="number" name="total_amount" value="" lay-verify="required" placeholder="请输入需要支付的金额" class="layui-input" >
    </div>
</div>


<input type="hidden" name="user_id" value="{{ $user?$user['id']:0 }}">


<div class="layui-form-item">
    <div class="layui-input-block">
        <button type="submit" class="layui-btn" lay-submit="" lay-filter="formDemo">确 认</button>
        <a  class="layui-btn" href="{{route('admin.order')}}" >返 回</a>
    </div>
</div>


<style>
    .action_value_hide{
        display: none;
    }
</style>
@section('script')
    <script>
        layui.use(['layer','table','form'],function () {
            var layer = layui.layer;
            var form = layui.form;

            //监听select选择
            form.on('select(action11)', function(data){
                if(data.value=='dns'){
                    $('.action_value').show();
                }else{
                    // alert(222,data.value);
                    $('.action_value').hide();
                }
            });

        })
    </script>
@endsection