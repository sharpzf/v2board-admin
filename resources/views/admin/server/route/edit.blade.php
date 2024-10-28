@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <h2>编辑路由</h2>
        </div>
        <div class="layui-card-body">
            <form class="layui-form" action="{{route('admin.route.update',['id'=>$route['id']])}}" method="post">
                {{ method_field('put') }}
                @include('admin.server.route._form')
            </form>
        </div>
    </div>
@endsection

@section('script')
    @include('admin.server.route._js')
@endsection
