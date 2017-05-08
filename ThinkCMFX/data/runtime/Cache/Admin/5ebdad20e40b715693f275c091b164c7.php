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
    <script src="/ThinkCMFX/public/simpleboot/jedate/jedate.js"></script>
    <link href="/ThinkCMFX/public/simpleboot/themes/<?php echo C('SP_ADMIN_STYLE');?>/theme.min.css" rel="stylesheet">
    <link href="/ThinkCMFX/public/simpleboot/css/simplebootadmin.css" rel="stylesheet">
    <link href="/ThinkCMFX/public/js/artDialog/skins/default.css" rel="stylesheet" />
    <link href="/ThinkCMFX/public/simpleboot/font-awesome/4.4.0/css/font-awesome.min.css"  rel="stylesheet" type="text/css">
    <style>
        form .input-order{margin-bottom: 0px;padding:3px;width:40px;}
        .table-actions{margin-top: 5px; margin-bottom: 5px;padding:0px;}
        .table-list{margin-bottom: 0px;}
    </style>
    <!--[if IE 7]>
    <!--<link rel="stylesheet" href="/ThinkCMFX/public/simpleboot/font-awesome/4.4.0/css/font-awesome-ie7.min.css">-->
    <link  href="/ThinkCMFX/public/simpleboot/bootstrap/css/bootstrap-responsive.min.css">
    <![endif]-->
    <script type="text/javascript">
        //全局变量
        var GV = {
            ROOT: "/ThinkCMFX/",
            WEB_ROOT: "/ThinkCMFX/",
            JS_ROOT: "public/js/",
            APP:'<?php echo (MODULE_NAME); ?>'/*当前应用名*/
        };
    </script>
    <script src="/ThinkCMFX/public/js/jquery.js"></script>
    <script src="/ThinkCMFX/public/js/wind.js"></script>
    <script src="/ThinkCMFX/public/simpleboot/bootstrap/js/bootstrap.min.js"></script>
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
<ul class="nav nav-tabs">
    <li class="active"><a href="<?php echo U('ClassHour/create');?>" target="_self">修改课时</a></li>
    <li><a href="<?php echo U('ClassHour/show');?>" target="_self">课时列表</a></li>

</ul>
<form action="<?php echo U('ClassHour/update');?>" class="well form-search" method="post">

    <div class="box box-default">
        <div class="box-body">
            <table width="100%" cellpadding="2" cellspacing="2">
                <tr>
                    <td>
                        <input type="hidden" name="subject"  value = "<?php echo ($array['subject']); ?>">
                        <input type="hidden" name="class_id"  value = "<?php echo ($array['class_id']); ?>">
                        <!--<input type="hidden" name="startDate" id="" value = "$array['startdate']">-->
                        <input type="hidden" name="id"  value = "<?php echo ($id); ?>">
                    </td>
                </tr>
                <tr>
                    <th width="80">课时名称</th>
                    <td>
                        <input type="text" name="subject" value="<?php echo ($data["subject"]); ?>" style="width: 300px"/>
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
                    <th width="80">开始时间</th>
                    <td>
                        <input class="datainp" value="<?php echo ($data["startdate"]); ?>" name="startDate" id="datebut" type="text"
                               placeholder="双击选择时间"
                               onClick="jeDate({dateCell:'#datebut',isTime:true,format:'YYYY-MM-DD hh:mm:ss'})">
                    </td>
                </tr>

                <tr>
                    <th width="80">结束时间</th>
                    <td>
                        <input class="datainp" value="<?php echo ($data["invaliddate"]); ?>" name="invalidDate" id="datebut1" type="text"
                               placeholder="双击选择时间"
                               onClick="jeDate({dateCell:'#datebut1',isTime:true,format:'YYYY-MM-DD hh:mm:ss'})">
                    </td>
                </tr>
                <tr>
                    <th width="80">课堂名称</th>
                    <td>
                        <select name="course_id" class="normal_select">
                            <option value="<?php echo ($data["course_id"]); ?>">
                                    <?php echo ($data["course_name"]); ?>
                            </option>
                            <?php if(is_array($array['course'])): foreach($array['course'] as $key=>$va): ?><option value="<?php echo ($va["id"]); ?>"><?php echo ($va["course_name"]); ?></option><?php endforeach; endif; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th width="80">讲师名称</th>
                    <td>
                        <select name="lector_id" class="normal_select">
                            <option value="<?php echo ($data["lector_id"]); ?>">
                                    <?php echo ($data["name"]); ?>
                            </option>
                            <?php if(is_array($array['lector'])): foreach($array['lector'] as $key=>$val): ?><option value="<?php echo ($val["l_id"]); ?>"><?php echo ($val["name"]); ?></option><?php endforeach; endif; ?>
                        </select>
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