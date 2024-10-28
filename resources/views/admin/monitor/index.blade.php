{{--@extends('admin.base')--}}


<style>
    .text-danger,.text-success {
        display: none;
    }
</style>
{{--{{csrf_field()}}--}}
{{--@section('content')--}}
{{--<link rel="stylesheet" href="/theme/v2board/assets/umi.css?v=1.7.4.1681103823832">--}}
{{--<link href="/theme/v2board/assets/umi.css" rel="stylesheet">--}}

<link rel="stylesheet" href="/assets/admin/components.chunk.css?v=1.7.4.1681103823832">
<link rel="stylesheet" href="/assets/admin/umi.css?v=1.7.4.1681103823832">
{{--<link rel="stylesheet" href="/assets/admin/custom.css?v=1.7.4.1681103823832">--}}

<div class="p-0 p-lg-4">
    <div class="ant-spin-nested-loading">
        <div class="ant-spin-container">
            <div class="block block-rounded ">
                <div class="block-header block-header-default"><h3 class="block-title">总览</h3></div>
                <div class="block-content p-0">
                    <div class="row no-gutters">
                        <div class="col-lg-6 col-xl-3 border-right p-4 border-bottom">
                            <div>
                                <div>当前作业量</div>
                                <div class="mt-4 font-size-h3 jobsPerMinute"></div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-xl-3 border-right p-4 border-bottom">
                            <div>
                                <div>近一小时处理量</div>
                                <div class="mt-4 font-size-h3 recentJobs"></div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-xl-3 border-right p-4 border-bottom">
                            <div>
                                <div>7日内报错数量</div>
                                <div class="mt-4 font-size-h3 failedJobs"></div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-xl-3 p-4 border-bottom overflow-hidden">
                            <div>
                                <div>状态</div>
                                <div class="mt-4 font-size-h3 job_status"></div>
                                <i class="si si-check text-success"
                                   style="position: absolute; font-size: 100px; right: -20px; bottom: -20px;"></i>

                                <i class="si si-close text-danger"
                                   style="position: absolute; font-size: 100px; right: -20px; bottom: -20px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="ant-spin-nested-loading">
        <div class="ant-spin-container">
            <div class="block block-rounded ">
                <div class="block-header block-header-default"><h3 class="block-title">当前作业详情</h3></div>
                <div class="block-content p-0">
                    <div class="ant-table-wrapper">
                        <div class="ant-spin-nested-loading">
                            <div class="ant-spin-container">
                                <div class="ant-table ant-table-default ant-table-scroll-position-left">
                                    <div class="ant-table-content">
                                        <div class="ant-table-body">
                                            <table class="">
                                                <colgroup>
                                                    <col>
                                                    <col>
                                                    <col>
                                                    <col>
                                                </colgroup>
                                                <thead class="ant-table-thead">
                                                <tr>
                                                    <th class=""><span class="ant-table-header-column"><div><span
                                                                        class="ant-table-column-title">队列名称</span><span
                                                                        class="ant-table-column-sorter"></span></div></span>
                                                    </th>
                                                    <th class=""><span class="ant-table-header-column"><div><span
                                                                        class="ant-table-column-title">作业量</span><span
                                                                        class="ant-table-column-sorter"></span></div></span>
                                                    </th>
                                                    <th class=""><span class="ant-table-header-column"><div><span
                                                                        class="ant-table-column-title">任务量</span><span
                                                                        class="ant-table-column-sorter"></span></div></span>
                                                    </th>
                                                    <th class="ant-table-align-right ant-table-row-cell-last"
                                                        style="text-align: right;"><span
                                                                class="ant-table-header-column"><div><span
                                                                        class="ant-table-column-title">占用时间</span><span
                                                                        class="ant-table-column-sorter"></span></div></span>
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody class="ant-table-tbody" id="box">

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="/static/admin/layuiadmin/jquery.js" type="text/javascript" charset="utf-8"></script>
<link href="/static/admin/layuiadmin/layui2.9.18.css" rel="stylesheet">
<script src="/static/admin/layuiadmin/layui2.9.18.js" type="text/javascript" charset="utf-8"></script>
<script>


    $(document).ready(function () {
        // 设置定时器
        var timer = setInterval(function () {
            getQueueWorkload();
            // console.log("这个函数每隔一秒钟会被调用一次");
        }, 3000);

        // 设置定时器
        var timer2 = setInterval(function () {
            getQueueStats();
            // console.log("这个函数每隔一秒钟会被调用一次");
        }, 3000);

        // 在需要的时候取消定时器
        // setTimeout(function () {
        //     console.log("取消定时器");
        //     clearInterval(timer);
        // }, 2000); // 5秒后取消定时器
    });

    function getQueueWorkload() {
        $.ajax({
            url: "{{ route('admin.monitor.getQueueWorkload') }}",
            type: 'get',
            {{--data: {--}}
                    {{--'message': this_val,--}}
                    {{--'id': id,--}}
                    {{--'_token':'{{csrf_token()}}'--}}
                    {{--},--}}
            async: false,
            dataType: 'json',
            success: function (res) {
                var html='';
                var len=res.data.length;



                // for(var i=0;i++;i<len){
                    for(var i=0; i<len; i++){

                        // console.log(22,i,res.data[i]);
                    var temp_name1=res.data[i]['name'];


                    if(temp_name1!='default'){
                        var temp_name=res.job_arr[temp_name1];
                        var processes=res.data[i]['processes'];
                        var len1=res.data[i]['length'];
                        var wait=res.data[i]['wait']+'s';


                        html+='<tr class="ant-table-row ant-table-row-level-0"\n' +
                            '                                                    data-row-key="0">\n' +
                            '                                                    <td class="">'+temp_name+'</td>\n' +
                            '                                                    <td class="">'+processes+'</td>\n' +
                            '                                                    <td class="">'+len1+'</td>\n' +
                            '                                                    <td class="" style="text-align: right;">'+wait+'</td>\n' +
                            '                                                </tr>';
                    }
                }


                // console.log(23333,html);
                $('#box').html(html);

            }
        });
    }


    function getQueueStats() {
        $.ajax({
            url: "{{ route('admin.monitor.getQueueStats') }}",
            type: 'get',
            {{--data: {--}}
                    {{--'message': this_val,--}}
                    {{--'id': id,--}}
                    {{--'_token':'{{csrf_token()}}'--}}
                    {{--},--}}
            async: false,
            dataType: 'json',
            success: function (res) {

                $('.jobsPerMinute').text(res.data.jobsPerMinute);
                $('.recentJobs').text(res.data.recentJobs);
                $('.failedJobs').text(res.data.failedJobs);
                if(res.data.status){
                    $('.job_status').text('运行中');
                    $('.text-danger').hide();
                    $('.text-success').show();
                }else{
                    $('.job_status').text('未启动');
                    $('.text-danger').show();
                    $('.text-success').hide();
                }

            }
        });
    }


</script>