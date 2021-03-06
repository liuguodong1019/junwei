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
<ul class="nav nav-tabs">
    <li class="active"><a href="<?php echo U('Order/add');?>"  target="_self">手动录入订单信息</a></li>
    <li ><a href="<?php echo U('Order/show');?>"  target="_self">订单列表</a></li>

</ul>
<form action="<?php echo U('Order/add');?>" enctype="multipart/form-data" class="well form-search" method="post" >

    <div class="box box-default">
        <div class="box-body">
            <table width="100%" cellpadding="2" cellspacing="2" >
                <tr>
                    <th width="80">课程</th>
                    <td>
                        <select name="course_id">
                            <option value="请选择">请选择</option>
                            <?php if(is_array($course)): foreach($course as $key=>$vo): ?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["course_name"]); ?></option><?php endforeach; endif; ?>

                        </select>
                    </td>
                </tr>
                <tr>
                    <th width="80">用户</th>
                    <td>
                        <select name="uid">
                            <option value="请选择">请选择</option>
                            <?php if(is_array($user)): foreach($user as $key=>$va): ?><option value="<?php echo ($va["id"]); ?>"><?php echo ($va["user_nicename"]); ?></option><?php endforeach; endif; ?>

                        </select>
                    </td>
                </tr>
                <tr>
                    <th width="80">支付状态</th>
                    <td>
                        <select name="pay_status">
                            <option value="请选择">请选择</option>
                            <option value="1">支付成功</option>
                            <option value="2">待支付</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th width="80">支付方式</th>
                    <td>
                        <select name="pay_ways">
                            <option value="请选择">请选择</option>
                            <option value="1">支付宝</option>
                            <option value="2">微信</option>
                        </select>
                    </td>
                </tr>

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