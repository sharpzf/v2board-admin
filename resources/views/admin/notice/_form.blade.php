{{csrf_field()}}
<div class="layui-form-item">
    <label for="" class="layui-form-label">标题</label>
    <div class="layui-input-block">
        <input type="text" name="title" value="{{$notice?$notice['title']:''}}" lay-verify="required" placeholder="请输入公告标题" class="layui-input" >
    </div>
</div>


<div class="layui-form-item">
    <label for="" class="layui-form-label">公告内容</label>
    <div class="layui-input-block">
        <textarea name="content"  placeholder="请输入公告内容" lay-verify="required" class="layui-textarea">{{$notice?$notice['content']:''}}</textarea>
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">公告标签</label>
    <div class="layui-input-block">
        <input type="text" name="tags" value="{{$notice?$notice['tags']:''}}"  placeholder="请输入公告标签(多个用,隔开)" class="layui-input" >
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">图片URL</label>
    <div class="layui-input-block">
        <input type="text" name="img_url" value="{{$notice?$notice['img_url']:''}}"  placeholder="请输入图片URL" class="layui-input" >
    </div>
</div>


<div class="layui-form-item">
    <div class="layui-input-block">
        <button type="submit" class="layui-btn" lay-submit="" lay-filter="formDemo">确 认</button>
        <a  class="layui-btn" href="{{route('admin.notice')}}" >返 回</a>
    </div>
</div>

