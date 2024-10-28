{{--@extends('admin.base')--}}

<style>

    #chat_box {
        overflow-y: scroll; /* 使得内容可以垂直滚动 */
        height: 400px; /* 设置一个固定高度 */
    }
</style>

{{--{{csrf_field()}}--}}
{{--@section('content')--}}
{{--<link rel="stylesheet" href="/theme/v2board/assets/umi.css?v=1.7.4.1681103823832">--}}
<link href="/theme/v2board/assets/umi.css" rel="stylesheet">
<div class="layui-card">
    {{--<div class="layui-card-header layuiadmin-card-header-auto">--}}
    {{--<h2>1111</h2>--}}
    {{--</div>--}}
    <div class="layui-card-body">


        <div id="root">
            <div>
                <div class="block-content-full bg-gray-lighter p-3"><span
                            class="tag___12_9H">{{ $ticket['subject'] }}</span>
                    <a href="{{route('admin.ticket')}}" style="float: right;color: blue;">返回工单管理</a></div>
                <div class="bg-white js-chat-messages block-content block-content-full text-wrap-break-word overflow-y-auto content___DW5w1" id="chat_box">

                    @foreach($ticket['message'] as $key=>$val)
                        @if($val['is_me']==0)
                            <div>
                                <div class="font-size-sm text-muted my-2 text-right">{{ $val['created_at'] }}</div>
                                <!--<div class="font-size-sm text-muted my-2">2024/10/17 18:27</div>-->
                                <div class="text-right ml-4">
                                    <!--<div class="ml-4">-->
                                    <div class="d-inline-block bg-gray-lighter px-3 py-2 mb-2 mw-100 rounded text-left">{{ $val['message'] }}</div>
                                </div>
                            </div>
                        @else
                            <div>
                                <div class="font-size-sm text-muted my-2">{{ $val['created_at'] }}</div>
                                <div class="mr-4">
                                    <div class="d-inline-block bg-success-lighter px-3 py-2 mb-2 mw-100 rounded text-left">{{ $val['message'] }}</div>
                                </div>
                            </div>

                        @endif

                    @endforeach
                </div>


                <div class="js-chat-form block-content p-2 bg-body-dark input___1j_ND">
                    <input type="text" name="message" id="message" autocomplete="off" data-id="{{ $ticket['id'] }}" class="js-chat-input bg-body-dark border-0 form-control form-control-alt message"
                           placeholder="输入内容回复工单...">
                </div>
            </div>
        </div>

    </div>
</div>

<script src="/static/admin/layuiadmin/jquery.js" type="text/javascript" charset="utf-8"></script>
<link href="/static/admin/layuiadmin/layui2.9.18.css" rel="stylesheet">
<script src="/static/admin/layuiadmin/layui2.9.18.js" type="text/javascript" charset="utf-8"></script>
<script>

    scrollToBottom();

    function scrollToBottom() {
        var chatContainer = document.getElementById('chat_box');
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }

    $("#message").keypress(function (even) {
        if (even.which == 13) {
            var this_val = $.trim($(this).val());
            var id = $(this).attr('data-id');
            if (!this_val) {
                layer.msg('内容不能为空');
                return false;
            }

            $.ajax({
                url: "{{ route('admin.ticket.reply') }}",
                type: 'post',
                data: {
                    'message': this_val,
                    'id': id,
                    '_token':'{{csrf_token()}}'
                },
                async: false,
                dataType: 'json',
                success: function (res) {
                    layer.msg(res.msg);
                    if(res.code==1){
                        return false;
                    }

                    var add_html='    <div>' +
                        '                                <div class="font-size-sm text-muted my-2 text-right">'+res.data.time+'</div>' +
                        '                                <div class="text-right ml-4">' +
                        '                                    <div class="d-inline-block bg-gray-lighter px-3 py-2 mb-2 mw-100 rounded text-left">'+res.data.message+'</div>' +
                        '                                </div>' +
                        '                            </div>';

                $('#chat_box').append(add_html);
                $('#message').val('');
                scrollToBottom();

                }
            });

        }
    });
</script>