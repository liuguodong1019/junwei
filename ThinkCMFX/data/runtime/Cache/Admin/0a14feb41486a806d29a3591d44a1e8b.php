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
<script src="/junwei/public/js/jquery.js"></script>

<script src="/junwei/public/js/js/mobiscroll.core-2.5.2.js" type="text/javascript"></script>
<script src="/junwei/public/js/js/mobiscroll.core-2.5.2-zh.js" type="text/javascript"></script>
<script src="/junwei/public/js/js/mobiscroll.datetime-2.5.1.js" type="text/javascript"></script>
<script src="/junwei/public/js/js/mobiscroll.datetime-2.5.1-zh.js" type="text/javascript"></script>

<!-- S 可根据自己喜好引入样式风格文件 -->
<script src="/junwei/public/js/js/mobiscroll.android-ics-2.5.2.js" type="text/javascript"></script>

<link href="/junwei/public/js/js/mobiscroll.core-2.5.2.css" rel="stylesheet" type="text/css" />
<link href="/junwei/public/js/js/mobiscroll.animation-2.5.2.css" rel="stylesheet" type="text/css" />

<link href="/junwei/public/js/js/mobiscroll.android-ics-2.5.2.css" rel="stylesheet" type="text/css" />

<body>
<ul class="nav nav-tabs">
    <li class="active"><a href="<?php echo U('ClassHour/add');?>"  target="_self">手动创建课时</a></li>
    <li ><a href="<?php echo U('Course/show');?>"  target="_self">课时列表</a></li>

</ul>

<form action="<?php echo U('Course/add');?>" class="well form-search" enctype="multipart/form-data" method="post" >

    <div class="box box-default">
        <div class="box-body">
            <table width="100%" cellpadding="2" cellspacing="2" >
                <tr>
                    <th width="80">是否收费</th>
                    <td>
                        <input name="is_free" type="radio" value="1"  checked/>&nbsp&nbsp&nbsp公开课
                    </td>
                </tr>
                <tr>
                    <th width="80">课名</th>
                    <td>
                        <input type="text" name="course_name" value="" style="width: 300px"/>
                    </td>
                </tr>
                <tr>
                    <th width="80">房间号</th>
                    <td>
                        <input type="text" name="number" value="" style="width: 300px"/>
                    </td>
                </tr>
                <tr>
                    <th width="80">口令</th>
                    <td>
                        <input type="text" name="stu_token" value="" style="width: 300px"/>
                    </td>
                </tr>
                <tr>
                    <th width="80">课堂id</th>
                    <td>
                        <input type="text" name="class_id" value="" style="width: 300px"/>
                    </td>
                </tr>
                <tr>
                    <th width="80">录制件id</th>
                    <td>
                        <input type="text" name="courseware_id" value="" style="width: 300px"/>
                    </td>
                </tr>
                <tr>
                    <th width="80">直播状态</th>
                    <td>
                        <input type="text" name="status" value="" style="width: 300px"/>
                    </td>
                </tr>
                <tr>
                    <th width="80">回放地址</th>
                    <td>
                        <input type="text" name="reply_url" value="" style="width: 300px"/>
                    </td>
                </tr>
                <tr>
                    <th width="80">封面图</th>
                    <td><input type="file" name="cover" ></td>
                </tr>
                <tr>
                    <th width="80">课程简介</th>
                    <td>
                        <textarea name='introduction' id='e_answer' style='width:50%;height:100px;'></textarea>
                    </td>
                </tr>
                <tr>
                    <th width="80">开始时间</th>
                    <td>
                        <input type="text" class="datainp" name="startDate" id="appDateTime" />
                    </td>
                </tr>
                <tr>
                    <th width="80">结束时间</th>
                    <td>
                        <input type="text" class="datainp" name="invalidDate" id="appDateTime1" />
                    </td>
                </tr>
                <tr>
                    <th width="80">讲师</th>
                    <td>
                        <input type="text"  name="lector" />
                    </td>
                </tr>
                <!-- <tr>
                    <th width="80">讲师</th>
                    <td>
                        <select name="lector1"  class="normal_select">
                            <option value="请选择">请选择</option>
                            <?php if(is_array($teacher)): foreach($teacher as $key=>$va): ?><option value="<?php echo ($va["name"]); ?>"><?php echo ($va["name"]); ?></option><?php endforeach; endif; ?>
                        </select>
                    </td>
                </tr> -->
                </tbody>
            </table>
        </div>
    </div>
    <div class="form-actions">
        <button class="btn btn-success" type="submit">提交</button>
        <button type="reset"  class="btn btn-danger">重置</button>
    </div>
</form>
</div>
</body>
<style type="text/css">

    body {
        padding: 0;
        margin: 0;
        font-family: arial, verdana, sans-serif;
        font-size: 12px;
        background: #ddd;
    }
    input, select {
        /*width: 100%;*/
        padding: 5px;
        margin: 5px 0;
        /*border: 1px solid #aaa;*/
        box-sizing: border-box;
        border-radius: 5px;
        -moz-box-sizing: border-box;
        -webkit-box-sizing: border-box;
        -webkit-border-radius: 5px;
    }

</style>
<script>
    $(function () {
        var currYear = (new Date()).getFullYear();
        var opt={};
        opt.date = {preset : 'date'};
        //opt.datetime = { preset : 'datetime', minDate: new Date(2012,3,10,9,22), maxDate: new Date(2014,7,30,15,44), stepMinute: 5  };
        opt.datetime = {preset : 'datetime'};
        opt.time = {preset : 'time'};
        opt.default = {
            theme: 'android-ics light', //皮肤样式
            display: 'modal', //显示方式
            mode: 'scroller', //日期选择模式
            lang:'zh',
            startYear:currYear - 10, //开始年份
            endYear:currYear + 10 //结束年份
        };

        $("#appDate").val('').scroller('destroy').scroller($.extend(opt['date'], opt['default']));
        var optDateTime = $.extend(opt['datetime'], opt['default']);
        var optTime = $.extend(opt['time'], opt['default']);
        $("#appDateTime").mobiscroll(optDateTime).datetime(optDateTime);
        $("#appDateTime1").mobiscroll(optDateTime).datetime(optDateTime);
        $("#appTime").mobiscroll(optTime).time(optTime);
    });

</script>