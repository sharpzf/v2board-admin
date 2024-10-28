@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <h2>创建组</h2>
        </div>
        <div class="layui-card-body">
            <form class="layui-form" action="{{route('admin.group.store')}}" method="post">
                @include('admin.server.group._form')
            </form>
        </div>
    </div>
@endsection

@section('script')
    @include('admin.server.group._js')
@endsection
