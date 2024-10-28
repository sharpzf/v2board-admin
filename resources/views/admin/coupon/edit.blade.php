@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <h2>编辑优惠券</h2>
        </div>
        <div class="layui-card-body">
            <form class="layui-form" action="{{route('admin.coupon.update',['id'=>$coupon['id']])}}" method="post">
                {{ method_field('put') }}
                @include('admin.coupon._form')
            </form>
        </div>
    </div>
@endsection


@section('script')
    @include('admin.coupon._js')
@endsection
