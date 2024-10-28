@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <h2>添加支付方式</h2>
        </div>
        <div class="layui-card-body">
            <form class="layui-form" action="{{route('admin.payment.store')}}" method="post">
                @include('admin.payment._form')
            </form>
        </div>
    </div>
@endsection

@section('script')
    @include('admin.payment._js')
@endsection
