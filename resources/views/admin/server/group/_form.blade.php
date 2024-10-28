{{csrf_field()}}
<div class="layui-form-item">
    <label for="" class="layui-form-label">组名</label>
    <div class="layui-input-block">
        <input type="text" name="name" value="{{$group->name??old('name')}}" lay-verify="required" placeholder="请输入组名" class="layui-input" >
    </div>
</div>


<div class="layui-form-item">
    <div class="layui-input-block">
        <button type="submit" class="layui-btn" lay-submit="" lay-filter="formDemo">确 认</button>
        <a  class="layui-btn" href="{{route('admin.group')}}" >返 回</a>
    </div>
</div>