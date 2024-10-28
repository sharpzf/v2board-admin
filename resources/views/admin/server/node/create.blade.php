@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <h2>创建节点</h2>
        </div>
        <div class="layui-card-body">
            <form class="layui-form" action="{{route('admin.node.store')}}" method="post">
                @include('admin.server.node._form')
            </form>
        </div>
    </div>
@endsection

@section('script')
    @include('admin.server.node._js')
@endsection
