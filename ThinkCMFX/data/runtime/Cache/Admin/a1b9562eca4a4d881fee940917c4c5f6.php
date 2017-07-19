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
</head>
<body>
<ul class="nav nav-tabs">
    <li class="active"><a href="<?php echo U('Course/update');?>" target="_self">创建课堂</a></li>
    <li><a href="<?php echo U('Course/show');?>" target="_self">课堂列表</a></li>

</ul>
<!--<div class="wrap js-check-wrap">-->
<form action="<?php echo U('Course/update');?>" enctype="multipart/form-data" class="well form-search" method="post">
    <!--<div class="col-auto">-->
    <!--<div class="table_full">-->
    <div class="box box-default">

        <div class="box-body">
            <table width="100%" cellpadding="2" cellspacing="2">
                <!--<tr>-->
                <!--<td>-->
                <!--<input type="hidden" name="course_name"  value = "<?php echo ($array['course_name']); ?>">-->
                <!--<input type="hidden" name="class_id"  value = "<?php echo ($array['class_id']); ?>">-->
                <!--&lt;!&ndash;<input type="hidden" name="startDate" id="" value = "$array['startdate']">&ndash;&gt;-->
                <!--<input type="hidden" name="id"  value = "<?php echo ($id); ?>">-->
                <!--</td>-->
                <!--</tr>-->

                <!--<tr>-->
                <!--<th width="80">课堂类型</th>-->
                <!--<td>-->
                <!--<?php if(($data["is_free"] == 0)): ?>-->
                <!--<label><input name="type" type="radio" value="<?php echo ($data["type"]); ?>" checked/>&nbsp&nbsp&nbsp大讲堂-->
                <!--<input name="type" type="radio" value="<?php echo ($data["type"]); ?>"/>&nbsp&nbsp&nbsp小班课 </label>-->
                <!--<?php else: ?>-->
                <!--<label><input name="type" type="radio" value="<?php echo ($data["type"]); ?>"/>&nbsp&nbsp&nbsp大讲堂-->
                <!--<input name="type" type="radio" value="<?php echo ($data["type"]); ?>" checked/>&nbsp&nbsp&nbsp小班课 </label>-->
                <!--<?php endif; ?>-->
                <!--</td>-->
                <!--</tr>-->
                <tr>
                    <td>
                        <input type="hidden" name="course_name" value="<?php echo ($data["course_name"]); ?>">
                        <input type="hidden" name="class_id" value="<?php echo ($data["class_id"]); ?>">
                        <input type="hidden" name="number" value = "<?php echo ($data["number"]); ?>">
                        <input type="hidden" name="stu_token" value = "<?php echo ($data["stu_token"]); ?>">
                        <input type="hidden" name="id" value="<?php echo ($id); ?>">
                        <input type="hidden" name="courseware_id" value="<?php echo ($data["courseware_id"]); ?>">
                    </td>
                </tr>
                <tr>
                    <th width="80">是否实时</th>
                    <td>
                        <label><input name="realtime" type="radio" value="1" checked/>&nbsp&nbsp&nbsp是
                            <input name="realtime" type="radio" value="0"/>&nbsp&nbsp&nbsp否 </label>
                    </td>
                </tr>
                <tr>
                    <th width="80">收否收费</th>
                    <td>
                        <?php if(($data["is_free"] == 1)): ?><label><input name="is_free" type="radio" value="1" checked/>&nbsp&nbsp&nbsp公开课
                                <input name="is_free" type="radio" value="2"/>&nbsp&nbsp&nbspvip </label>
                            <?php else: ?>
                            <label><input name="is_free" type="radio" value="1"/>&nbsp&nbsp&nbsp公开课
                                <input name="is_free" type="radio" value="2" checked/>&nbsp&nbsp&nbspvip </label><?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th width="80">课时数量</th>
                    <td>
                        <input name="num_class" type="text" value="<?php echo ($data["num_class"]); ?>"/>
                    </td>
                </tr>
                <tr>
                    <th width="80">课程主题</th>
                    <td>
                        <input type="text" name="course_name" value="<?php echo ($data["course_name"]); ?>" style="width: 300px"/>
                    </td>
                </tr>
                <tr>
                    <th width="80">现价</th>
                    <td>
                        <input name="now_price" type="text" value="<?php echo ($data["now_price"]); ?>"/>
                    </td>
                </tr>
                <tr>
                    <th width="80">原价</th>
                    <td>
                        <input name="old_price" type="text" value="<?php echo ($data["old_price"]); ?>"/>
                    </td>
                </tr>
                <tr>
                    <th width="80">封面图</th>
                    <td>
                        <input type="file" name="cover" value = "<?php echo ($data["cover"]); ?>">
                    </td>
                </tr>
                <tr>
                    <th width="80">课程详情封面图</th>
                    <td>
                        <input type="file" name="detail_cover" value = "<?php echo ($data["detail_cover"]); ?>">
                    </td>
                </tr>
                <tr>
                    <th width="80">开始时间</th>
                    <td>
                        <?php if(($data["startDate"] == '')): ?><input type="text" class="datainp"  value = "<?php echo ($data["startdate"]); ?>" name="startDate" id="appDateTime" />
                            <?php else: ?>
                            <input type="text" class="datainp"  value = "<?php echo ($data["startDate"]); ?>" name="startDate" id="appDateTime2" /><?php endif; ?>
                        <!--<input type="text" class="datainp" name="startDate" id="appDateTime" />-->
                    </td>
                </tr>

                <tr>
                    <th width="80">结束时间</th>
                    <td>
                        <?php if(($data["invalidDate"] == '')): ?><input type="text" class="datainp" value="<?php echo ($data["invaliddate"]); ?>" name="invalidDate" id="appDateTime1" />
                            <?php else: ?>
                            <input type="text" class="datainp" value="<?php echo ($data["invalidDate"]); ?>" name="invalidDate" id="appDateTime3" /><?php endif; ?>
                        <!--<input type="text" class="datainp" name="invalidDate" id="appDateTime1" />-->
                    </td>
                </tr>
                <tr>
                    <th width="80">课程简介</th>
                    <td>
                        <textarea name='introduction' id='e_answer'
                                  style='width:50%;height:100px;'><?php echo ($data["introduction"]); ?></textarea>
                    </td>
                </tr>
                <tr>
                    <th width="80">讲师</th>
                    <td>
                        <select name="lector" class="normal_select">
                            <option value="<?php echo ($data["lector"]); ?>"><?php echo ($data["lector"]); ?></option>
                            <?php if(is_array($array['lector'])): foreach($array['lector'] as $key=>$vo): ?><option value="<?php echo ($vo["name"]); ?>"><?php echo ($vo["name"]); ?></option><?php endforeach; endif; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th width="80">适用人群</th>
                    <td>
                        <!--<?php if(is_array($array['people'])): foreach($array['people'] as $key=>$va): ?>-->
                            <!--<?php echo ($va["people"]); ?>&nbsp;&nbsp;&nbsp;<input type="checkbox" name="people[]" value="<?php echo ($va["people"]); ?>">&nbsp;&nbsp;&nbsp;-->
                        <!--<?php endforeach; endif; ?>-->
                        <?php if(is_array($array['people'])): foreach($array['people'] as $key=>$va): echo ($va["people"]); ?>&nbsp;&nbsp;&nbsp;<input type="checkbox"   name="people[]"  value="<?php echo ($va["people"]); ?>">&nbsp;&nbsp;&nbsp;<?php endforeach; endif; ?>
                    </td>
                </tr>
                <tr>
                    <th width="80">配发图书</th>
                    <td>
                        <?php if(is_array($array['book'])): foreach($array['book'] as $key=>$value): echo ($value["book"]); ?>&nbsp;&nbsp;&nbsp;<input type="checkbox" name="book[]" value="<?php echo ($value["book"]); ?>">&nbsp;&nbsp;&nbsp;<?php endforeach; endif; ?>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="form-actions">
        <button class="btn btn-success" type="submit">提交</button>
        <button type="reset" class="btn btn-danger">重置</button>
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
        $("#appDateTime2").mobiscroll(optDateTime).datetime(optDateTime);
        $("#appDateTime3").mobiscroll(optDateTime).datetime(optDateTime);
        $("#appTime").mobiscroll(optTime).time(optTime);
    });

</script>
</html>