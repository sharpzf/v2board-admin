@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <h2>编辑订阅</h2>
        </div>
        <div class="layui-card-body">
            <form class="layui-form" action="{{route('admin.plan.update',['id'=>$plan->id])}}" method="post">
                {{ method_field('put') }}
                @include('admin.plan._form')
            </form>
        </div>
    </div>
@endsection

@section('script')
    @include('admin.plan._js')
@endsection
