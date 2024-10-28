@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <h2>订单分配</h2>
        </div>
        <div class="layui-card-body">
            <form class="layui-form" action="{{route('admin.order.store')}}" method="post">
                @include('admin.order._form')
            </form>
        </div>
    </div>
@endsection


