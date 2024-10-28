@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <h2>编辑组</h2>
        </div>
        <div class="layui-card-body">
            <form class="layui-form" action="{{route('admin.group.update',['id'=>$group->id])}}" method="post">
                {{ method_field('put') }}
                @include('admin.server.group._form')
            </form>
        </div>
    </div>
@endsection

@section('script')
    @include('admin.server.group._js')
@endsection
