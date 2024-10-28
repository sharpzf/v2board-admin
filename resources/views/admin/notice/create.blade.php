@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <h2>新建公告</h2>
        </div>
        <div class="layui-card-body">
            <form class="layui-form" action="{{route('admin.notice.store')}}" method="post">
                @include('admin.notice._form')
            </form>
        </div>
    </div>
@endsection
