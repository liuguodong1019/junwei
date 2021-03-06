<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <!-- Set render engine for 360 browser -->
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- HTML5 shim for IE8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <![endif]-->
    <script src="/junwei/public/simpleboot/jedate/jedate.js"></script>
    <link href="/junwei/public/simpleboot/themes/<?php echo C('SP_ADMIN_STYLE');?>/theme.min.css" rel="stylesheet">
    <link href="/junwei/public/simpleboot/css/simplebootadmin.css" rel="stylesheet">
    <link href="/junwei/public/js/artDialog/skins/default.css" rel="stylesheet" />
    <link href="/junwei/public/simpleboot/font-awesome/4.4.0/css/font-awesome.min.css"  rel="stylesheet" type="text/css">
    <style>
        form .input-order{margin-bottom: 0px;padding:3px;width:40px;}
        .table-actions{margin-top: 5px; margin-bottom: 5px;padding:0px;}
        .table-list{margin-bottom: 0px;}
    </style>
    <!--[if IE 7]>
    <!--<link rel="stylesheet" href="/junwei/public/simpleboot/font-awesome/4.4.0/css/font-awesome-ie7.min.css">-->
    <link  href="/junwei/public/simpleboot/bootstrap/css/bootstrap-responsive.min.css">
    <![endif]-->
    <script type="text/javascript">
        //全局变量
        var GV = {
            ROOT: "/junwei/",
            WEB_ROOT: "/junwei/",
            JS_ROOT: "public/js/",
            APP:'<?php echo (MODULE_NAME); ?>'/*当前应用名*/
        };
    </script>
    <script src="/junwei/public/js/jquery.js"></script>
    <script src="/junwei/public/js/wind.js"></script>
    <script src="/junwei/public/simpleboot/bootstrap/js/bootstrap.min.js"></script>
    <script>
        $(function(){
            $("[data-toggle='tooltip']").tooltip();
        });
    </script>
    <?php if(APP_DEBUG): ?><style>
            #think_page_trace_open{
                z-index:9999;
            }
        </style><?php endif; ?>
    <style>
        body{ padding:50px 0 0 50px;}
        .datainp{ width:200px; height:30px; border:1px #ccc solid;}
        .datep{ margin-bottom:40px;}
    </style>
</html>
<script type="text/javascript">
    //jeDate.skin('gray');
    jeDate({
        dateCell:"#indate",//isinitVal:true,
        format:"YYYY-MM",
        isTime:false, //isClear:false,
        minDate:"2015-10-19 00:00:00",
        maxDate:"2016-11-8 00:00:00"
    })
    jeDate({
        dateCell:"#dateinfo",
        format:"YYYY年MM月DD日 hh:mm:ss",
        isinitVal:true,
        isTime:true, //isClear:false,
        minDate:"2014-09-19 00:00:00",
        okfun:function(val){alert(val)}
    })
</script>
</head>
<body>
<div class="wrap js-check-wrap">
    <ul class="nav nav-tabs">
        <li class="active"><a href="<?php echo U('ClassHour/look');?>">课时信息</a></li>
        <li><a href="<?php echo U('ClassHour/show');?>">课时列表</a></li>
        <li><a href="<?php echo U('ClassHour/create');?>">添加课时</a></li>
    </ul>
    <table class="table table-hover table-bordered table-list">
        <tr>
            <th width="60">ID</th>
            <th width="80">课堂主题</th>
            <th width="80">课时名称</th>
            <th width="80">开始时间</th>
            <th width="80">结束时间</th>
            <th width="80">讲师</th>
            <th width="80">房间号</th>
            <th width="80">课堂ID</th>
            <th width="80">学生口令</th>
            <th width="80">直播状态</th>
            <th width="80">回放地址</th>
        </tr>
        <tr>
            <td><a><?php echo ($data["id"]); ?></a></td>
            <td><?php echo ($data["course_name"]); ?></td>
            <td><?php echo ($data["subject"]); ?></td>
            <td>
                <?php if(($data["startDate"] == '')): echo (date('Y-m-d H:i:s',$data["startdate"])); ?>
                    <?php else: echo ($data["startDate"]); endif; ?>
            </td>
            <td>
                <?php if(($data["invalidDate"] == '')): echo (date('Y-m-d H:i:s',$data["invalidDate"])); ?>
                    <?php else: echo ($data["invalidDate"]); endif; ?>
            </td>
            <td><?php echo ($data["lector"]); ?></td>
            <td><?php echo ($data["number"]); ?></td>
            <td><?php echo ($data["class_id"]); ?></td>
            <td><?php echo ($data["stu_token"]); ?></td>
            <td>
                <?php if(($data["status"] == 1) ): ?>正在直播
                    <?php elseif($data["status"] == 2): ?>
                    直播结束
                    <?php elseif($data["status"] == 3): ?>
                    生成回放
                    <?php else: ?>
                    未开始<?php endif; ?>
            </td>
            <td><?php echo ($data["reply_url"]); ?></td>
        </tr>
    </table>
</div>

</body>
</html>