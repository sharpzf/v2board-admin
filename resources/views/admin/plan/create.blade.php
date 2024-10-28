@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <h2>新建订阅</h2>
        </div>
        <div class="layui-card-body">
            <form class="layui-form" action="{{route('admin.plan.store')}}" method="post">
                @include('admin.plan._form')
            </form>
        </div>
    </div>
@endsection

@section('script')
    @include('admin.plan._js')
@endsection
