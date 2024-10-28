<style>
    #layui-upload-box li{
        width: 120px;
        height: 100px;
        float: left;
        position: relative;
        overflow: hidden;
        margin-right: 10px;
        border:1px solid #ddd;
    }
    #layui-upload-box li img{
        width: 100%;
    }
    #layui-upload-box li p{
        width: 100%;
        height: 22px;
        font-size: 12px;
        position: absolute;
        left: 0;
        bottom: 0;
        line-height: 22px;
        text-align: center;
        color: #fff;
        background-color: #333;
        opacity: 0.6;
    }
    #layui-upload-box li i{
        display: block;
        width: 20px;
        height:20px;
        position: absolute;
        text-align: center;
        top: 2px;
        right:2px;
        z-index:999;
        cursor: pointer;
    }
    #ID-laydate-end-date-1{
        margin-bottom: 20px;
    }
</style>
<link rel="stylesheet" type="text/css" href="/static/admin/layuiadmin/formSelects-v4.css"/>
<script src="/static/admin/layuiadmin/jquery.js" type="text/javascript" charset="utf-8"></script>
<script src="/static/admin/layuiadmin/formSelects-v4.js" type="text/javascript" charset="utf-8"></script>

<link href="/static/admin/layuiadmin/layui2.9.18.css" rel="stylesheet">
<script src="/static/admin/layuiadmin/layui2.9.18.js" type="text/javascript" charset="utf-8"></script>

<script>
    layui.use(['upload'],function () {
        var upload = layui.upload;
        // var laydate = layui.laydate;
        //普通图片上传
        var uploadInst = upload.render({
            elem: '#uploadPic'
            ,url: '{{ route("uploadImg") }}'
            ,multiple: false
            ,data:{"_token":"{{ csrf_token() }}"}
            ,before: function(obj){
                //预读本地文件示例，不支持ie8
                /*obj.preview(function(index, file, result){
                 $('#layui-upload-box').append('<li><img src="'+result+'" /><p>待上传</p></li>')
                 });*/
                obj.preview(function(index, file, result){
                    $('#layui-upload-box').html('<li><img src="'+result+'" /><p>上传中</p></li>')
                });

            }
            ,done: function(res){
                //如果上传失败
                if(res.code == 0){
                    $("#thumb").val(res.url);
                    $('#layui-upload-box li p').text('上传成功');
                    return layer.msg(res.msg);
                }
                return layer.msg(res.msg);
            }
        });

    })
</script>


<script>
    layui.use(function(){
        var laydate = layui.laydate;
        // 日期范围 - 左右面板独立选择模式
        laydate.render({
            elem: '#ID-laydate-range',
            range: ['#ID-laydate-start-date', '#ID-laydate-end-date']
        });
        // 日期范围 - 左右面板联动选择模式
        laydate.render({
            elem: '#ID-laydate-rangeLinked',
            range: ['#ID-laydate-start-date-1', '#ID-laydate-end-date-1'],
            rangeLinked: true, // 开启日期范围选择时的区间联动标注模式 ---  2.8+ 新增
            fullPanel: true,
            type: 'datetime',
        });

        // 年范围
        laydate.render({
            elem: '#ID-laydate-range-year',
            type: 'year',
            range: true
        });

        // 年月范围
        laydate.render({
            elem: '#ID-laydate-range-month',
            type: 'month',
            range: true
        });

        // 时间范围
        laydate.render({
            elem: '#ID-laydate-range-time',
            type: 'time',
            range: true
        });

        // 日期时间范围
        laydate.render({
            elem: '#ID-laydate-range-datetime',
            type: 'datetime',
            range: true
        });

    });
</script>