@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <h2>编辑支付方式</h2>
        </div>
        <div class="layui-card-body">
            <form class="layui-form" action="{{route('admin.payment.update',['id'=>$payment['id']])}}" method="post">
                {{ method_field('put') }}
                @include('admin.payment._form')
            </form>
        </div>
    </div>
@endsection

@section('script')
    @include('admin.payment._js')
@endsection
