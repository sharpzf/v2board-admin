@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <h2>新建优惠券</h2>
        </div>
        <div class="layui-card-body">
            <form class="layui-form" action="{{route('admin.coupon.store')}}" method="post">
                @include('admin.coupon._form')
            </form>
        </div>
    </div>
@endsection



@section('script')
    @include('admin.coupon._js')
@endsection
