@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <h2>用户管理</h2>
        </div>

        <div class="layui-card-body">

            <form class="layui-form" action="{{route('admin.v2user.update',['id'=>$user['id']])}}" method="post">
                {{ method_field('put') }}
                {{csrf_field()}}
                <div class="layui-form-item">
                    <label for="" class="layui-form-label">邮箱</label>
                    <div class="layui-input-inline">
                        <input type="text" name="email" value="{{!empty($user)?$user['email']:''}}"
                               lay-verify="required" placeholder="请输入邮箱" class="layui-input">
                    </div>
                </div>


                <div class="layui-form-item">
                    <label for="" class="layui-form-label">邀请人邮箱</label>
                    <div class="layui-input-inline">
                        <input type="text" name="invite_user_email" value="{{!empty($user)?$user['invite_user_id']:''}}"
                               placeholder="请输入邀请人邮箱" class="layui-input">
                    </div>
                </div>


                <div class="layui-form-item">
                    <label for="" class="layui-form-label">密码</label>
                    <div class="layui-input-inline">
                        <input type="text" name="password" value="" placeholder="如需修改密码请输入" class="layui-input">
                    </div>
                </div>


                <div class="layui-form-item">
                    <label for="" class="layui-form-label">余额</label>
                    <div class="layui-input-inline" style="margin-right: 0px;">
                        <input type="number" name="balance" value="{{!empty($user)?$user['balance']:''}}"
                               placeholder="余额" autocomplete="off" class="layui-input" step="0.001">
                    </div>
                    <div class="layui-input-inline" style="margin-right: 0px;width: auto;">
                        <button class="layui-btn layui-btn-disabled">¥</button>
                    </div>

                    <label for="" class="layui-form-label">推广佣金</label>
                    <div class="layui-input-inline" style="margin-right: 0px;">
                        <input type="number" name="commission_balance"
                               value="{{!empty($user)?$user['commission_balance']:''}}" placeholder="推广佣金"
                               autocomplete="off" class="layui-input" step="0.001">
                    </div>
                    <div class="layui-input-inline" style="margin-right: 0px;width: auto;">
                        <button class="layui-btn layui-btn-disabled">¥</button>
                    </div>
                </div>


                <div class="layui-form-item">
                    <label for="" class="layui-form-label">已用上行</label>
                    <div class="layui-input-inline" style="margin-right: 0px;">
                        <input type="number" name="d" value="{{!empty($user)?$user['d']:''}}" placeholder="已用上行"
                               autocomplete="off" class="layui-input" step="0.001">
                    </div>
                    <div class="layui-input-inline" style="margin-right: 0px;width: auto;">
                        <button class="layui-btn layui-btn-disabled">¥</button>
                    </div>

                    <label for="" class="layui-form-label">已用下行</label>
                    <div class="layui-input-inline" style="margin-right: 0px;">
                        <input type="number" name="u" value="{{!empty($user)?$user['u']:''}}" placeholder="已用下行"
                               autocomplete="off" class="layui-input" step="0.001">
                    </div>
                    <div class="layui-input-inline" style="margin-right: 0px;width: auto;">
                        <button class="layui-btn layui-btn-disabled">¥</button>
                    </div>
                </div>

                <div class="layui-form-item">
                    <label for="" class="layui-form-label">流量</label>
                    <div class="layui-input-inline" style="margin-right: 0px;">
                        <input type="number" name="transfer_enable"
                               value="{{!empty($user)?$user['transfer_enable']:''}}" placeholder="请输入流量"
                               class="layui-input" step="0.001">
                    </div>
                    <div class="layui-input-inline" style="margin-right: 0px;width: auto;">
                        <button class="layui-btn layui-btn-disabled">GB</button>
                    </div>
                </div>

                <div class="layui-form-item">

                    <div class="layui-inline">
                        <label class="layui-form-label">到期时间</label>
                        <div class="layui-input-inline">
                            <input type="text" name="expired_at" value="{{!empty($user)?$user['expired_at']:''}}"
                                   id="test2" placeholder="长期有效" autocomplete="off" class="layui-input">
                        </div>
                    </div>

                </div>


                <div class="layui-form-item">
                    <label for="" class="layui-form-label">订阅计划</label>
                    <div class="layui-input-inline">
                        <select name="plan_id">
                            <option value="">无</option>
                            @foreach($plan as $kk=>$vv)
                                <option value="{{ $kk }}"
                                        @if(isset($user)&&$user['plan_id']==$kk) selected @endif>{{ $vv }}</option>
                            @endforeach
                        </select>
                    </div>
                    {{--<div class="layui-form-mid layui-word-aux">限制指定订阅可以使用优惠(为空则不限制)</div>--}}
                </div>


                <div class="layui-form-item">
                    <label for="" class="layui-form-label">账户状态</label>
                    <div class="layui-input-inline">
                        <select name="banned">
                            <option value="0" @if(isset($user)&&$user['banned']==0) selected @endif>正常</option>
                            <option value="1" @if(isset($user)&&$user['banned']!=0) selected @endif>封禁</option>
                        </select>
                    </div>
                    {{--<div class="layui-form-mid layui-word-aux">限制指定订阅可以使用优惠(为空则不限制)</div>--}}
                </div>


                <div class="layui-form-item">
                    <label for="" class="layui-form-label">推荐返利类型</label>
                    <div class="layui-input-inline">
                        <select name="commission_type">
                            <option value="0" @if(isset($user)&&$user['commission_type']==0) selected @endif>跟随系统设置
                            </option>
                            <option value="1" @if(isset($user)&&$user['commission_type']==1) selected @endif>循环返利
                            </option>
                            <option value="2" @if(isset($user)&&$user['commission_type']==2) selected @endif>首次返利
                            </option>
                        </select>
                    </div>
                    {{--<div class="layui-form-mid layui-word-aux">限制指定订阅可以使用优惠(为空则不限制)</div>--}}
                </div>


                <div class="layui-form-item">
                    <label for="" class="layui-form-label">推荐返利比例</label>
                    <div class="layui-input-inline" style="margin-right: 0px;width: 35%;">
                        <input type="number" name="commission_rate"
                               value="{{!empty($user)?$user['commission_rate']:''}}"
                               placeholder="请输入推荐返利比例(为空则跟随站点设置返利比例)" autocomplete="off" class="layui-input" step="0.001">
                    </div>
                    <div class="layui-input-inline" style="margin-right: 0px;width: auto;">
                        <button class="layui-btn layui-btn-disabled">%</button>
                    </div>

                </div>


                <div class="layui-form-item">
                    <label for="" class="layui-form-label">专享折扣比例</label>
                    <div class="layui-input-inline" style="margin-right: 0px;">
                        <input type="number" name="discount" value="{{!empty($user)?$user['discount']:''}}"
                               placeholder="请输入专享折扣比例" autocomplete="off" class="layui-input" step="0.001">
                    </div>
                    <div class="layui-input-inline" style="margin-right: 0px;width: auto;">
                        <button class="layui-btn layui-btn-disabled">%</button>
                    </div>
                </div>


                <div class="layui-form-item">
                    <label for="" class="layui-form-label">限速</label>
                    <div class="layui-input-inline" style="margin-right: 0px;">
                        <input type="number" name="speed_limit" value="{{!empty($user)?$user['speed_limit']:''}}"
                               placeholder="留空则不限制" autocomplete="off" class="layui-input" step="0.001">
                    </div>
                    <div class="layui-input-inline" style="margin-right: 0px;width: auto;">
                        <button class="layui-btn layui-btn-disabled">Mbps</button>
                    </div>
                </div>


                <div class="layui-form-item">
                    <label for="" class="layui-form-label">是否管理员</label>
                    <div class="layui-input-inline">
                        <input type="checkbox" name="is_admin" lay-skin="switch"
                               @if(isset($user)&&$user['is_admin']==1) checked @endif >
                    </div>
                    {{--<div class="layui-form-mid layui-word-aux">限制指定订阅可以使用优惠(为空则不限制)</div>--}}
                </div>


                <div class="layui-form-item">
                    <label for="" class="layui-form-label">是否员工</label>
                    <div class="layui-input-inline">
                        <input type="checkbox" name="is_staff" lay-skin="switch"
                               @if(isset($user)&&$user['is_staff']==1) checked @endif >
                    </div>
                    {{--<div class="layui-form-mid layui-word-aux">限制指定订阅可以使用优惠(为空则不限制)</div>--}}
                </div>


                <div class="layui-form-item">
                    <label for="" class="layui-form-label">备注</label>
                    <div class="layui-input-block">
                        <textarea class="layui-textarea" name="remarks" rows="6" cols="30"
                                  placeholder="请在这里记录..">{{!empty($user)?$user['remarks']:''}}</textarea>
                    </div>
                </div>

                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button type="submit" class="layui-btn" lay-submit="" lay-filter="formDemo">确 认</button>
                        <a class="layui-btn" href="{{route('admin.v2user')}}">返 回</a>
                    </div>
                </div>

            </form>

        </div>
        @endsection



        @section('script')
            <script>
                layui.use('laydate', function () {
                    var laydate = layui.laydate;

                    //执行一个laydate实例
                    laydate.render({
                        elem: '#test2' //指定元素
                    });
                });
            </script>



@endsection

