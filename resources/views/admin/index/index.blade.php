@extends('admin.base')

@section('content')
    <link href="/theme/v2board/assets/umi.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/admin/components.chunk.css?v=1.7.5.2685.0000">
    <link rel="stylesheet" href="/assets/admin/umi.css?v=1.7.5.2685.0000">
    {{--<link rel="stylesheet" href="/assets/admin/custom.css?v=1.7.5.2685.0000">--}}
    <link rel="stylesheet" href="/assets/admin/theme/default.css">





    <div class="p-0 p-lg-4">

        @if($arr['status']!='running')
            <div class="row">
                <div class="col-lg-12">
                    <div class="alert alert-danger" role="alert"><p class="mb-0">当前队列服务运行异常，可能会导致业务无法使用。</p></div>
                </div>
            </div>
        @endif

        @if($arr['ticket_pending_total']>0)
            <div class="alert alert-danger" role="alert">
                <p class="mb-0">有 {{ $arr['ticket_pending_total'] }} 条工单等待处理
                    <a class="alert-link" href="{{ route('admin.ticket') }}">立即处理</a>
                </p>
            </div>
        @endif


        @if($arr['commission_pending_total']>0)
            <div class="alert alert-danger" role="alert"><p class="mb-0">有 {{ $arr['commission_pending_total'] }}
                    笔佣金等待确认 <a class="alert-link"
                               href="{{ route('admin.order') }}">立即处理</a>
                </p></div>
        @endif


        <div class="mb-0 block border-bottom js-classic-nav d-none d-sm-block">
            <div class="block-content block-content-full">
                <div class="row no-gutters border">
                    <div class="col-sm-6 col-xl-3 js-appear-enabled animated" data-toggle="appear"><a
                                class="block block-bordered block-link-pop text-center mb-0"
                                href="{{ route('admin.config') }}">
                            <div class="block-content block-content-full text-center"><i
                                        class="fa-2x si si-equalizer text-primary d-none d-sm-inline-block mb-3"></i>
                                <div class="font-w600 text-uppercase">系统设置</div>
                            </div>
                        </a></div>
                    <div class="col-sm-6 col-xl-3 js-appear-enabled animated" data-toggle="appear"><a
                                class="block block-bordered block-link-pop text-center mb-0"
                                href="{{ route('admin.order') }}">
                            <div class="block-content block-content-full text-center"><i
                                        class="fa-2x si si-list text-primary d-none d-sm-inline-block mb-3"></i>
                                <div class="font-w600 text-uppercase">订单管理</div>
                            </div>
                        </a></div>
                    <div class="col-sm-6 col-xl-3 js-appear-enabled animated" data-toggle="appear"><a
                                class="block block-bordered block-link-pop text-center mb-0"
                                href="{{ route('admin.plan') }}">
                            <div class="block-content block-content-full text-center"><i
                                        class="fa-2x si si-bag text-primary d-none d-sm-inline-block mb-3"></i>
                                <div class="font-w600 text-uppercase">订阅管理</div>
                            </div>
                        </a></div>
                    <div class="col-sm-6 col-xl-3 js-appear-enabled animated" data-toggle="appear"><a
                                class="block block-bordered block-link-pop text-center mb-0"
                                href="{{ route('admin.v2user') }}">
                            <div class="block-content block-content-full text-center"><i
                                        class="fa-2x si si-users text-primary d-none d-sm-inline-block mb-3"></i>
                                <div class="font-w600 text-uppercase">用户管理</div>
                            </div>
                        </a></div>
                </div>
            </div>
        </div>
        <div class="row no-gutters">
            <div class="col-lg-12 js-appear-enabled animated" data-toggle="appear">
                <div class="block border-bottom mb-0">
                    <div class="block-content">
                        <div class="px-sm-3 clearfix"><i class="fa fa-chart-line fa-2x text-gray-light float-right"></i>
                            <p class="text-muted w-75 mb-1">今日收入</p>
                            <p class="display-4 text-black font-w300 mb-2">{{ $arr['day_income'] }}<span
                                        class="font-size-h5 font-w600 text-muted">CNY</span></p></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 js-appear-enabled animated" data-toggle="appear">
                <div class="block border-bottom mb-0 v2board-stats-bar">
                    <div class="block-content block-content-full">
                        <div class="d-flex align-items-center">
                            <div class="pr-4 pr-sm-5 pl-0 pl-sm-3"><p
                                        class="fs-3 text-dark mb-0">{{ $arr['month_income'] }} CNY</p>
                                <p class="text-muted mb-0">本月收入</p></div>
                            <div class="px-4 px-sm-5 border-start"><p
                                        class="fs-3 text-dark mb-0">{{ $arr['last_month_income'] }} CNY</p>
                                <p class="text-muted mb-0">上月收入</p></div>
                            <div class="px-4 px-sm-5 border-start"><p
                                        class="fs-3 text-dark mb-0">{{ $arr['commission_last_month_payout'] }} CNY</p>
                                <p class="text-muted mb-0">上月佣金支出</p></div>
                            <div class="px-4 px-sm-5 border-start"><p
                                        class="fs-3 text-dark mb-0">{{ $arr['month_register_total'] }}</p>
                                <p class="text-muted mb-0">本月新增用户</p></div>
                        </div>
                    </div>
                </div>
            </div>

            {{--<div style="width: 100%;height: 300px;" id="shopping"></div>--}}


            <div class="col-lg-12 js-appear-enabled animated" data-toggle="appear">
                <div class="block border-bottom mb-0">

                    {{--<div style="width: 100%;height: 300px;" id="shopping"></div>--}}



                    <div class="px-sm-3 pt-sm-3 py-3 clearfix" id="orderChart"
                         style="height: 400px; position: relative;" _echarts_instance_="ec_1729761850754">

                        <div style="width: 100%;height:100%;" id="shopping"></div>

                        {{--<div style="position: relative; overflow: hidden; width: 782px; height: 368px; cursor: default;">--}}
                            {{--<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"--}}
                                 {{--version="1.1" baseProfile="full" width="782" height="368"--}}
                                 {{--style="position:absolute;left:0;top:0;user-select:none">--}}
                                {{--<rect width="782" height="368" x="0" y="0" id="0" fill="none" fill-opacity="1"></rect>--}}
                                {{--<g>--}}
                                    {{--<path d="M46.8571 337.5L774.18 337.5" fill="none" stroke="#E0E6F1"></path>--}}
                                    {{--<path d="M46.8571 291.5L774.18 291.5" fill="none" stroke="#E0E6F1"></path>--}}
                                    {{--<path d="M46.8571 244.5L774.18 244.5" fill="none" stroke="#E0E6F1"></path>--}}
                                    {{--<path d="M46.8571 198.5L774.18 198.5" fill="none" stroke="#E0E6F1"></path>--}}
                                    {{--<path d="M46.8571 152.5L774.18 152.5" fill="none" stroke="#E0E6F1"></path>--}}
                                    {{--<path d="M46.8571 106.5L774.18 106.5" fill="none" stroke="#E0E6F1"></path>--}}
                                    {{--<path d="M46.8571 60.5L774.18 60.5" fill="none" stroke="#E0E6F1"></path>--}}
                                    {{--<path d="M46.8571 337.5L774.18 337.5" fill="none" stroke="#6E7079"--}}
                                          {{--stroke-linecap="round"></path>--}}
                                    {{--<path d="M47.5 336.96L47.5 341.96" fill="none" stroke="#6E7079"></path>--}}
                                    {{--<path d="M774.5 336.96L774.5 341.96" fill="none" stroke="#6E7079"></path>--}}
                                    {{--<text dominant-baseline="central" text-anchor="end"--}}
                                          {{--style="font-size:12px;font-family:Microsoft YaHei;"--}}
                                          {{--transform="translate(38.8571 336.96)" fill="#6E7079">0--}}
                                    {{--</text>--}}
                                    {{--<text dominant-baseline="central" text-anchor="end"--}}
                                          {{--style="font-size:12px;font-family:Microsoft YaHei;"--}}
                                          {{--transform="translate(38.8571 290.8)" fill="#6E7079">1,000--}}
                                    {{--</text>--}}
                                    {{--<text dominant-baseline="central" text-anchor="end"--}}
                                          {{--style="font-size:12px;font-family:Microsoft YaHei;"--}}
                                          {{--transform="translate(38.8571 244.64)" fill="#6E7079">2,000--}}
                                    {{--</text>--}}
                                    {{--<text dominant-baseline="central" text-anchor="end"--}}
                                          {{--style="font-size:12px;font-family:Microsoft YaHei;"--}}
                                          {{--transform="translate(38.8571 198.48)" fill="#6E7079">3,000--}}
                                    {{--</text>--}}
                                    {{--<text dominant-baseline="central" text-anchor="end"--}}
                                          {{--style="font-size:12px;font-family:Microsoft YaHei;"--}}
                                          {{--transform="translate(38.8571 152.32)" fill="#6E7079">4,000--}}
                                    {{--</text>--}}
                                    {{--<text dominant-baseline="central" text-anchor="end"--}}
                                          {{--style="font-size:12px;font-family:Microsoft YaHei;"--}}
                                          {{--transform="translate(38.8571 106.16)" fill="#6E7079">5,000--}}
                                    {{--</text>--}}
                                    {{--<text dominant-baseline="central" text-anchor="end"--}}
                                          {{--style="font-size:12px;font-family:Microsoft YaHei;"--}}
                                          {{--transform="translate(38.8571 60)" fill="#6E7079">6,000--}}
                                    {{--</text>--}}
                                    {{--<text dominant-baseline="central" text-anchor="middle"--}}
                                          {{--style="font-size:12px;font-family:Microsoft YaHei;" y="6"--}}
                                          {{--transform="translate(46.8571 344.96)" fill="#6E7079">09-23--}}
                                    {{--</text>--}}
                                    {{--<text dominant-baseline="central" text-anchor="middle"--}}
                                          {{--style="font-size:12px;font-family:Microsoft YaHei;" y="6"--}}
                                          {{--transform="translate(774.18 344.96)" fill="#6E7079">10-14--}}
                                    {{--</text>--}}
                                    {{--<g clip-path="url(#zr0-c0)">--}}
                                        {{--<path d="M46.8571 183.1087C46.8571 183.1087 774.18 335.4367 774.18 335.4367"--}}
                                              {{--fill="none" stroke="#5470c6" stroke-width="2"--}}
                                              {{--stroke-linejoin="bevel"></path>--}}
                                    {{--</g>--}}
                                    {{--<g clip-path="url(#zr0-c1)">--}}
                                        {{--<path d="M46.8571 334.9087C46.8571 334.9087 774.18 336.9397 774.18 336.9397"--}}
                                              {{--fill="none" stroke="#91cc75" stroke-width="2"--}}
                                              {{--stroke-linejoin="bevel"></path>--}}
                                    {{--</g>--}}
                                    {{--<g clip-path="url(#zr0-c2)">--}}
                                        {{--<path d="M46.8571 80.5412C46.8571 80.5412 774.18 334.4212 774.18 334.4212"--}}
                                              {{--fill="none" stroke="#fac858" stroke-width="2"--}}
                                              {{--stroke-linejoin="bevel"></path>--}}
                                    {{--</g>--}}
                                    {{--<g clip-path="url(#zr0-c3)">--}}
                                        {{--<path d="M46.8571 333.883C46.8571 333.883 774.18 336.9295 774.18 336.9295"--}}
                                              {{--fill="none" stroke="#ee6666" stroke-width="2"--}}
                                              {{--stroke-linejoin="bevel"></path>--}}
                                    {{--</g>--}}
                                    {{--<path d="M1 0A1 1 0 1 1 1 -0.0001" transform="matrix(2,0,0,2,46.8571,183.1087)"--}}
                                          {{--fill="#fff" stroke="#5470c6"></path>--}}
                                    {{--<path d="M1 0A1 1 0 1 1 1 -0.0001" transform="matrix(2,0,0,2,774.18,335.4367)"--}}
                                          {{--fill="#fff" stroke="#5470c6"></path>--}}
                                    {{--<path d="M1 0A1 1 0 1 1 1 -0.0001" transform="matrix(2,0,0,2,46.8571,334.9087)"--}}
                                          {{--fill="#fff" stroke="#91cc75"></path>--}}
                                    {{--<path d="M1 0A1 1 0 1 1 1 -0.0001" transform="matrix(2,0,0,2,774.18,336.9397)"--}}
                                          {{--fill="#fff" stroke="#91cc75"></path>--}}
                                    {{--<path d="M1 0A1 1 0 1 1 1 -0.0001" transform="matrix(2,0,0,2,46.8571,80.5412)"--}}
                                          {{--fill="#fff" stroke="#fac858"></path>--}}
                                    {{--<path d="M1 0A1 1 0 1 1 1 -0.0001" transform="matrix(2,0,0,2,774.18,334.4212)"--}}
                                          {{--fill="#fff" stroke="#fac858"></path>--}}
                                    {{--<path d="M1 0A1 1 0 1 1 1 -0.0001" transform="matrix(2,0,0,2,46.8571,333.883)"--}}
                                          {{--fill="#fff" stroke="#ee6666"></path>--}}
                                    {{--<path d="M1 0A1 1 0 1 1 1 -0.0001" transform="matrix(2,0,0,2,774.18,336.9295)"--}}
                                          {{--fill="#fff" stroke="#ee6666"></path>--}}
                                    {{--<path d="M-5 -5l444.0313 0l0 23.2l-444.0312 0Z" transform="translate(5 5)"--}}
                                          {{--fill="rgb(0,0,0)" fill-opacity="0" stroke="#ccc" stroke-width="0"></path>--}}
                                    {{--<path d="M0 7L25 7" transform="translate(6 4.6)" fill="#000" stroke="#5470c6"--}}
                                          {{--stroke-width="2" stroke-linecap="butt" stroke-miterlimit="10"></path>--}}
                                    {{--<path d="M18.1 7A5.6 5.6 0 1 1 18.1 6.9994" transform="translate(6 4.6)" fill="#fff"--}}
                                          {{--stroke="#5470c6" stroke-width="2" stroke-linecap="butt"--}}
                                          {{--stroke-miterlimit="10"></path>--}}
                                    {{--<text dominant-baseline="central" text-anchor="start"--}}
                                          {{--style="font-size:12px;font-family:Microsoft YaHei;" x="30" y="7"--}}
                                          {{--transform="translate(6 4.6)" fill="#333">佣金笔数(已发放)--}}
                                    {{--</text>--}}
                                    {{--<path d="M0 7L25 7" transform="translate(139.0156 4.6)" fill="#000" stroke="#91cc75"--}}
                                          {{--stroke-width="2" stroke-linecap="butt" stroke-miterlimit="10"></path>--}}
                                    {{--<path d="M18.1 7A5.6 5.6 0 1 1 18.1 6.9994" transform="translate(139.0156 4.6)"--}}
                                          {{--fill="#fff" stroke="#91cc75" stroke-width="2" stroke-linecap="butt"--}}
                                          {{--stroke-miterlimit="10"></path>--}}
                                    {{--<text dominant-baseline="central" text-anchor="start"--}}
                                          {{--style="font-size:12px;font-family:Microsoft YaHei;" x="30" y="7"--}}
                                          {{--transform="translate(139.0156 4.6)" fill="#333">佣金金额(已发放)--}}
                                    {{--</text>--}}
                                    {{--<path d="M0 7L25 7" transform="translate(272.0313 4.6)" fill="#000" stroke="#fac858"--}}
                                          {{--stroke-width="2" stroke-linecap="butt" stroke-miterlimit="10"></path>--}}
                                    {{--<path d="M18.1 7A5.6 5.6 0 1 1 18.1 6.9994" transform="translate(272.0313 4.6)"--}}
                                          {{--fill="#fff" stroke="#fac858" stroke-width="2" stroke-linecap="butt"--}}
                                          {{--stroke-miterlimit="10"></path>--}}
                                    {{--<text dominant-baseline="central" text-anchor="start"--}}
                                          {{--style="font-size:12px;font-family:Microsoft YaHei;" x="30" y="7"--}}
                                          {{--transform="translate(272.0313 4.6)" fill="#333">收款笔数--}}
                                    {{--</text>--}}
                                    {{--<path d="M0 7L25 7" transform="translate(361.0313 4.6)" fill="#000" stroke="#ee6666"--}}
                                          {{--stroke-width="2" stroke-linecap="butt" stroke-miterlimit="10"></path>--}}
                                    {{--<path d="M18.1 7A5.6 5.6 0 1 1 18.1 6.9994" transform="translate(361.0313 4.6)"--}}
                                          {{--fill="#fff" stroke="#ee6666" stroke-width="2" stroke-linecap="butt"--}}
                                          {{--stroke-miterlimit="10"></path>--}}
                                    {{--<text dominant-baseline="central" text-anchor="start"--}}
                                          {{--style="font-size:12px;font-family:Microsoft YaHei;" x="30" y="7"--}}
                                          {{--transform="translate(361.0313 4.6)" fill="#333">收款金额--}}
                                    {{--</text>--}}
                                {{--</g>--}}
                                {{--<defs>--}}
                                    {{--<clipPath id="zr0-c0">--}}
                                        {{--<path d="M45 59l729 0l0 278.96l-729 0Z" fill="#000"></path>--}}
                                    {{--</clipPath>--}}
                                    {{--<clipPath id="zr0-c1">--}}
                                        {{--<path d="M45 59l729 0l0 278.96l-729 0Z" fill="#000"></path>--}}
                                    {{--</clipPath>--}}
                                    {{--<clipPath id="zr0-c2">--}}
                                        {{--<path d="M45 59l729 0l0 278.96l-729 0Z" fill="#000"></path>--}}
                                    {{--</clipPath>--}}
                                    {{--<clipPath id="zr0-c3">--}}
                                        {{--<path d="M45 59l729 0l0 278.96l-729 0Z" fill="#000"></path>--}}
                                    {{--</clipPath>--}}
                                {{--</defs>--}}
                            {{--</svg>--}}
                        {{--</div>--}}
                        {{--<div class=""--}}
                             {{--style="position: absolute; display: block; border-style: solid; white-space: nowrap; z-index: 9999999; box-shadow: rgba(0, 0, 0, 0.2) 1px 2px 10px; transition: opacity 0.2s cubic-bezier(0.23, 1, 0.32, 1), visibility 0.2s cubic-bezier(0.23, 1, 0.32, 1), transform 0.4s cubic-bezier(0.23, 1, 0.32, 1); background-color: rgb(255, 255, 255); border-width: 1px; border-radius: 4px; color: rgb(102, 102, 102); font: 14px / 21px &quot;Microsoft YaHei&quot;; padding: 10px; top: 0px; left: 0px; transform: translate3d(801px, 124px, 0px); border-color: rgb(255, 255, 255); pointer-events: none; visibility: hidden; opacity: 0;">--}}
                            {{--<div style="margin: 0px 0 0;line-height:1;">--}}
                                {{--<div style="margin: 0px 0 0;line-height:1;">--}}
                                    {{--<div style="font-size:14px;color:#666;font-weight:400;line-height:1;">10-14</div>--}}
                                    {{--<div style="margin: 10px 0 0;line-height:1;">--}}
                                        {{--<div style="margin: 0px 0 0;line-height:1;">--}}
                                            {{--<div style="margin: 0px 0 0;line-height:1;"><span--}}
                                                        {{--style="display:inline-block;margin-right:4px;border-radius:10px;width:10px;height:10px;background-color:#5470c6;"></span><span--}}
                                                        {{--style="font-size:14px;color:#666;font-weight:400;margin-left:2px">佣金笔数(已发放)</span><span--}}
                                                        {{--style="float:right;margin-left:20px;font-size:14px;color:#666;font-weight:900">33</span>--}}
                                                {{--<div style="clear:both"></div>--}}
                                            {{--</div>--}}
                                            {{--<div style="clear:both"></div>--}}
                                        {{--</div>--}}
                                        {{--<div style="margin: 10px 0 0;line-height:1;">--}}
                                            {{--<div style="margin: 0px 0 0;line-height:1;"><span--}}
                                                        {{--style="display:inline-block;margin-right:4px;border-radius:10px;width:10px;height:10px;background-color:#91cc75;"></span><span--}}
                                                        {{--style="font-size:14px;color:#666;font-weight:400;margin-left:2px">佣金金额(已发放)</span><span--}}
                                                        {{--style="float:right;margin-left:20px;font-size:14px;color:#666;font-weight:900">0.44</span>--}}
                                                {{--<div style="clear:both"></div>--}}
                                            {{--</div>--}}
                                            {{--<div style="clear:both"></div>--}}
                                        {{--</div>--}}
                                        {{--<div style="margin: 10px 0 0;line-height:1;">--}}
                                            {{--<div style="margin: 0px 0 0;line-height:1;"><span--}}
                                                        {{--style="display:inline-block;margin-right:4px;border-radius:10px;width:10px;height:10px;background-color:#fac858;"></span><span--}}
                                                        {{--style="font-size:14px;color:#666;font-weight:400;margin-left:2px">收款笔数</span><span--}}
                                                        {{--style="float:right;margin-left:20px;font-size:14px;color:#666;font-weight:900">55</span>--}}
                                                {{--<div style="clear:both"></div>--}}
                                            {{--</div>--}}
                                            {{--<div style="clear:both"></div>--}}
                                        {{--</div>--}}
                                        {{--<div style="margin: 10px 0 0;line-height:1;">--}}
                                            {{--<div style="margin: 0px 0 0;line-height:1;"><span--}}
                                                        {{--style="display:inline-block;margin-right:4px;border-radius:10px;width:10px;height:10px;background-color:#ee6666;"></span><span--}}
                                                        {{--style="font-size:14px;color:#666;font-weight:400;margin-left:2px">收款金额</span><span--}}
                                                        {{--style="float:right;margin-left:20px;font-size:14px;color:#666;font-weight:900">0.66</span>--}}
                                                {{--<div style="clear:both"></div>--}}
                                            {{--</div>--}}
                                            {{--<div style="clear:both"></div>--}}
                                        {{--</div>--}}
                                        {{--<div style="clear:both"></div>--}}
                                    {{--</div>--}}
                                    {{--<div style="clear:both"></div>--}}
                                {{--</div>--}}
                                {{--<div style="clear:both"></div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    </div>
                </div>
            </div>


        </div>
        <div class="row no-gutters mt-xl-3">
            <div class="col-lg-12 js-appear-enabled animated" data-toggle="appear">
                <div class="block border-bottom mb-0">
                    <div class="block-header block-header-default"><h3 class="block-title">昨日节点流量排行</h3></div>
                    <div class="block-content">
                        <div class="px-sm-3 pt-sm-3 py-3 clearfix" id="serverRankChart"
                             style="height: 400px; position: relative;" _echarts_instance_="ec_1729754736177">


                            <div style="width: 100%;height:100%;" id="shopping11"></div>

                            {{--<div style="position: relative; overflow: hidden; width: 697px; height: 368px; cursor: pointer;">--}}
                                {{--<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"--}}
                                     {{--version="1.1" baseProfile="full" width="697" height="368"--}}
                                     {{--style="position:absolute;left:0;top:0;user-select:none">--}}
                                    {{--<rect width="697" height="368" x="0" y="0" id="0" fill="none"--}}
                                          {{--fill-opacity="1"></rect>--}}
                                    {{--<g>--}}
                                        {{--<path d="M73.5 3.68L73.5 336.96" fill="none" stroke="#E0E6F1"></path>--}}
                                        {{--<path d="M176.5 3.68L176.5 336.96" fill="none" stroke="#E0E6F1"></path>--}}
                                        {{--<path d="M279.5 3.68L279.5 336.96" fill="none" stroke="#E0E6F1"></path>--}}
                                        {{--<path d="M382.5 3.68L382.5 336.96" fill="none" stroke="#E0E6F1"></path>--}}
                                        {{--<path d="M484.5 3.68L484.5 336.96" fill="none" stroke="#E0E6F1"></path>--}}
                                        {{--<path d="M587.5 3.68L587.5 336.96" fill="none" stroke="#E0E6F1"></path>--}}
                                        {{--<path d="M690.5 3.68L690.5 336.96" fill="none" stroke="#E0E6F1"></path>--}}
                                        {{--<path d="M73.5 336.96L73.5 3.68" fill="none" stroke="#6E7079"--}}
                                              {{--stroke-linecap="round"></path>--}}
                                        {{--<path d="M73.4759 337.5L68.4759 337.5" fill="none" stroke="#6E7079"></path>--}}
                                        {{--<path d="M73.4759 170.5L68.4759 170.5" fill="none" stroke="#6E7079"></path>--}}
                                        {{--<path d="M73.4759 3.5L68.4759 3.5" fill="none" stroke="#6E7079"></path>--}}
                                        {{--<text dominant-baseline="central" text-anchor="end"--}}
                                              {{--style="font-size:12px;font-family:Microsoft YaHei;"--}}
                                              {{--transform="translate(65.4759 253.64)" fill="#6E7079">undefined--}}
                                        {{--</text>--}}
                                        {{--<text dominant-baseline="central" text-anchor="end"--}}
                                              {{--style="font-size:12px;font-family:Microsoft YaHei;"--}}
                                              {{--transform="translate(65.4759 87)" fill="#6E7079">44--}}
                                        {{--</text>--}}
                                        {{--<text dominant-baseline="central" text-anchor="middle"--}}
                                              {{--style="font-size:12px;font-family:Microsoft YaHei;" y="6"--}}
                                              {{--transform="translate(73.4759 344.96)" fill="#6E7079">0--}}
                                        {{--</text>--}}
                                        {{--<text dominant-baseline="central" text-anchor="middle"--}}
                                              {{--style="font-size:12px;font-family:Microsoft YaHei;" y="6"--}}
                                              {{--transform="translate(176.2349 344.96)" fill="#6E7079">0.05--}}
                                        {{--</text>--}}
                                        {{--<text dominant-baseline="central" text-anchor="middle"--}}
                                              {{--style="font-size:12px;font-family:Microsoft YaHei;" y="6"--}}
                                              {{--transform="translate(278.9939 344.96)" fill="#6E7079">0.1--}}
                                        {{--</text>--}}
                                        {{--<text dominant-baseline="central" text-anchor="middle"--}}
                                              {{--style="font-size:12px;font-family:Microsoft YaHei;" y="6"--}}
                                              {{--transform="translate(381.7529 344.96)" fill="#6E7079">0.15--}}
                                        {{--</text>--}}
                                        {{--<text dominant-baseline="central" text-anchor="middle"--}}
                                              {{--style="font-size:12px;font-family:Microsoft YaHei;" y="6"--}}
                                              {{--transform="translate(484.512 344.96)" fill="#6E7079">0.2--}}
                                        {{--</text>--}}
                                        {{--<text dominant-baseline="central" text-anchor="middle"--}}
                                              {{--style="font-size:12px;font-family:Microsoft YaHei;" y="6"--}}
                                              {{--transform="translate(587.271 344.96)" fill="#6E7079">0.25--}}
                                        {{--</text>--}}
                                        {{--<text dominant-baseline="central" text-anchor="middle"--}}
                                              {{--style="font-size:12px;font-family:Microsoft YaHei;" y="6"--}}
                                              {{--transform="translate(690.03 344.96)" fill="#6E7079">0.3--}}
                                        {{--</text>--}}
                                        {{--<path d="M73.4759 196.1492l17.01 0l0 114.9816l-17.01 0Z" fill="#5470c6"></path>--}}
                                        {{--<path d="M73.4759 29.5092l547.5139 0l0 114.9816l-547.5139 0Z"--}}
                                              {{--fill="#5470c6"></path>--}}
                                    {{--</g>--}}
                                {{--</svg>--}}
                            {{--</div>--}}
                            {{--<div class=""--}}
                                 {{--style="position: absolute; display: block; border-style: solid; white-space: nowrap; z-index: 9999999; box-shadow: rgba(0, 0, 0, 0.2) 1px 2px 10px; transition: opacity 0.2s cubic-bezier(0.23, 1, 0.32, 1), visibility 0.2s cubic-bezier(0.23, 1, 0.32, 1), transform 0.4s cubic-bezier(0.23, 1, 0.32, 1); background-color: rgb(255, 255, 255); border-width: 1px; border-radius: 4px; color: rgb(102, 102, 102); font: 14px / 21px &quot;Microsoft YaHei&quot;; padding: 10px; top: 0px; left: 0px; transform: translate3d(394px, 143px, 0px); border-color: rgb(255, 255, 255); pointer-events: none; visibility: hidden; opacity: 0;">--}}
                                {{--0.26640673354268074 GB--}}
                            {{--</div>--}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="/assets/admin/vendors.async.js?v=1.7.5.2685.0000"></script>
    <script src="/assets/admin/components.async.js?v=1.7.5.2685.0000"></script>
    <script src="/assets/admin/umi.js?v=1.7.5.2685.0000"></script>
    <script src="/static/admin/layuiadmin/layui2.9.18.js"></script>
@endsection




@section('script')




    <script>
        // layui.use(['index', 'console']);

        layui.config({
            version: 1,
            base: '/package/dist/' //这个就是你放Echart.js的目录
        }).use(['element', 'echarts', 'carousel'], function () {
            var element = layui.element,
                $ = layui.jquery,
                carousel = layui.carousel,
                echarts = layui.echarts;

            var shopping = echarts.init(document.getElementById('shopping'));
            var shopping11 = echarts.init(document.getElementById('shopping11'));


            var type_date="<?=implode(',',$arr['order_list']['type_date'])?>";
            type_date=type_date.split(',');

            var type1_arr="<?=implode(',',$arr['order_list']['type1_arr'])?>";
            type1_arr=type1_arr.split(',');

            var type2_arr="<?=implode(',',$arr['order_list']['type2_arr'])?>";
            type2_arr=type2_arr.split(',');

            var type3_arr="<?=implode(',',$arr['order_list']['type3_arr'])?>";
            type3_arr=type3_arr.split(',');

            var type4_arr="<?=implode(',',$arr['order_list']['type4_arr'])?>";
            type4_arr=type4_arr.split(',');

            var node_name="<?=implode(',',$arr['last_rank']['node_name'])?>";
            node_name=node_name.split(',');

            var node_total="<?=implode(',',$arr['last_rank']['node_total'])?>";
            node_total=node_total.split(',');


            var optionShopping = {
                title: {
                    text: ''
                },
                tooltip: {},
                legend: { //顶部显示 与series中的数据类型的name一致
                    data: ['佣金笔数(已发放)', '佣金金额(已发放)', '收款笔数', '收款金额']
                },
                xAxis: {
                    // type: 'category',
                    // boundaryGap: false, //从起点开始
                    // data: ['周一', '周二', '周三', '周四', '周五', '周六', '周日']
                    // data: ['周一', '周日']
                    data: type_date
                },
                yAxis: {
                    type: 'value'
                },
                series: [{
                    name: '佣金笔数(已发放)',
                    type: 'line', //线性
                    // data: [145, 230, 701, 734, 1090, 1130, 1120],
                    // data: [145, 1120],
                    data: type1_arr,
                }, {
                    name: '佣金金额(已发放)',
                    type: 'line', //线性
                    // data: [720, 832, 801, 834, 1190, 1230, 1220],
                    // data: [720, 1220],
                    data: type2_arr,
                }, {
                    smooth: true, //曲线 默认折线
                    name: '收款笔数',
                    type: 'line', //线性
                    // data: [820, 932, 901, 934, 1290, 1330, 1320],
                    // data: [820, 1320],
                    data: type3_arr,
                }, {
                    smooth: true, //曲线
                    name: '收款金额',
                    type: 'line', //线性
                    // data: [220, 332, 401, 534, 690, 730, 820],
                    // data: [220, 820],
                    data: type4_arr,
                }]
            };


            var optionShopping11= {
                title: {
                    text: ''
                },
                tooltip: {},
                legend: {
                    data: ['']
                },
                yAxis: {
                    // data: ['衬衫', '羊毛衫', '雪纺衫', '裤子', '高跟鞋', '袜子'],
                    data: node_name,
                    // position: 'left' // 将x轴放置在左侧
                },
                xAxis: {type: 'value'},
                series: [
                    {
                        name: '流量(GB)',
                        type: 'bar',//柱状
                        // data: [5, 20, 36, 10, 10, 20],
                        data: node_total,
                        itemStyle: {
                            normal: {//柱子颜色
                                // color: 'rdb(84,112,198)'
                                color: '#0665d0'
                            }
                        }
                    }
                ]
            };

            // 使用刚指定的配置项和数据显示图表。
            shopping.setOption(optionShopping);
            shopping11.setOption(optionShopping11);

        });

    </script>


@endsection