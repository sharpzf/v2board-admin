@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <h2>编辑公告</h2>
        </div>
        <div class="layui-card-body">
            <form class="layui-form" action="{{route('admin.notice.update',['id'=>$notice['id']])}}" method="post">
                {{ method_field('put') }}
                @include('admin.notice._form')
            </form>
        </div>
    </div>
@endsection

