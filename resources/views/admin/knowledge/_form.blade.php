{{csrf_field()}}



<div class="layui-form-item">
    <label for="" class="layui-form-label">标题</label>
    <div class="layui-input-block">
        <input type="text" name="title" value="{{$knowledge?$knowledge['title']:''}}" lay-verify="required" placeholder="请输入知识标题" class="layui-input" >
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">分类</label>
    <div class="layui-input-block">
        <input type="text" name="category" value="{{$knowledge?$knowledge['category']:''}}" lay-verify="required" placeholder="请输入分类，分类将会自动归集" class="layui-input" >
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">语言</label>
    <div class="layui-input-block">
        {{--<select name="language">--}}
            {{--@foreach($lang_arr as $key=>$val)--}}
                {{--<option value="{{ $key }}" @if($knowledge&&$knowledge['language']==$key) selected @endif>{{ $val }}</option>--}}
            {{--@endforeach--}}
        {{--</select>--}}
        @foreach($lang_arr as $key=>$val)
            @if($knowledge)
                <input type="radio" name="language" value="{{ $key }}" title="{{ $val }}" @if($knowledge&&$knowledge['language']==$key) checked @endif>
            @else
                <input type="radio" name="language" value="{{ $key }}" title="{{ $val }}" @if($key=='en-US') checked @endif>
            @endif

        {{--<input type="radio" name="AAA" value="2" title="选中" checked>--}}
        {{--<input type="radio" name="AAA" value="3" title="禁用" disabled>--}}
        @endforeach
    </div>
</div>

@include('UEditor::head');
<div class="layui-form-item">
    <label for="" class="layui-form-label">内容</label>
    <div class="layui-input-block">
        <script id="container" name="body" type="text/plain">
            {!! $knowledge['body']??old('body') !!}
        </script>
    </div>
</div>




<div class="layui-form-item">
    <div class="layui-input-block">
        <button type="submit" class="layui-btn" lay-submit="" lay-filter="formDemo">确 认</button>
        <a  class="layui-btn" href="{{route('admin.knowledge')}}" >返 回</a>
    </div>
</div>

