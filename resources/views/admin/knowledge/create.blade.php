@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <h2>新增知识</h2>
        </div>
        <div class="layui-card-body">
            <form class="layui-form" action="{{route('admin.knowledge.store')}}" method="post">
                @include('admin.knowledge._form')
            </form>
        </div>
    </div>
@endsection

@section('script')
    <!-- 实例化编辑器 -->
    <script type="text/javascript">
        var ue = UE.getEditor('container');
        ue.ready(function() {
            ue.execCommand('serverparam', '_token', '{{ csrf_token() }}');//此处为支持laravel5 csrf ,根据实际情况修改,目的就是设置 _token 值.
        });
    </script>
@endsection