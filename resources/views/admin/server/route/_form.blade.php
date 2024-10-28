{{csrf_field()}}
<div class="layui-form-item">
    <label for="" class="layui-form-label">备注</label>
    <div class="layui-input-block">
        <input type="text" name="remarks" value="{{!empty($route)?$route['remarks']:''}}" lay-verify="required" placeholder="请输入备注" class="layui-input" >
    </div>
</div>



<div class="layui-form-item">
    <label for="" class="layui-form-label">匹配值</label>
    <div class="layui-input-block">

        @if(isset($route)&&$route['match'])
            <textarea class="layui-textarea" name="match" lay-verify="required" rows="6" cols="30" placeholder="example.com多个用,隔开">{{!empty($route)?$route['match']:''}}</textarea>
        @else
            <textarea class="layui-textarea" name="match" lay-verify="required" rows="6" cols="30" placeholder="example.com多个用,隔开"></textarea>
        @endif


    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">顶部风格</label>
    <div class="layui-input-inline">
        <select name="action" lay-filter="action11">
            <option value="block"  @if(isset($route)&&$route['action']=='block') selected @endif>禁止访问</option>
            <option value="dns"  @if(isset($route)&&$route['action']=='dns') selected @endif>指定DNS服务器进行解析</option>
        </select>
    </div>
</div>


<div class="layui-form-item action_value @if(!isset($route) || $route['action']=='block') action_value_hide @endif">
    <label for="" class="layui-form-label">DNS服务器</label>
    <div class="layui-input-block">
        <input type="text" name="action_value" value="{{!empty($route)?$route['action_value']:''}}"  placeholder="请输入用于解析的DNS服务器地址" class="layui-input" >
    </div>
</div>



<div class="layui-form-item">
    <div class="layui-input-block">
        <button type="submit" class="layui-btn" lay-submit="" lay-filter="formDemo">确 认</button>
        <a  class="layui-btn" href="{{route('admin.route')}}" >返 回</a>
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