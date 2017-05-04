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
    <script src="/public/simpleboot/jedate/jedate.js"></script>
	<link href="/public/simpleboot/themes/<?php echo C('SP_ADMIN_STYLE');?>/theme.min.css" rel="stylesheet">
    <link href="/public/simpleboot/css/simplebootadmin.css" rel="stylesheet">
    <link href="/public/js/artDialog/skins/default.css" rel="stylesheet" />
    <link href="/public/simpleboot/font-awesome/4.4.0/css/font-awesome.min.css"  rel="stylesheet" type="text/css">
    <style>
		form .input-order{margin-bottom: 0px;padding:3px;width:40px;}
		.table-actions{margin-top: 5px; margin-bottom: 5px;padding:0px;}
		.table-list{margin-bottom: 0px;}
	</style>
	<!--[if IE 7]>
	<!--<link rel="stylesheet" href="/public/simpleboot/font-awesome/4.4.0/css/font-awesome-ie7.min.css">-->
    <link  href="/public/simpleboot/bootstrap/css/bootstrap-responsive.min.css">
	<![endif]-->
	<script type="text/javascript">
	//全局变量
	var GV = {
	    ROOT: "/",
	    WEB_ROOT: "/",
	    JS_ROOT: "public/js/",
	    APP:'<?php echo (MODULE_NAME); ?>'/*当前应用名*/
	};
	</script>
    <script src="/public/js/jquery.js"></script>
    <script src="/public/js/wind.js"></script>
    <script src="/public/simpleboot/bootstrap/js/bootstrap.min.js"></script>
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
        <li class="active"><a href="<?php echo U('Teaching/index');?>">授课列表</a></li>
        <li><a href="<?php echo U('Teaching/create_teaching');?>">添加授课</a></li>
    </ul>
    <form class="well form-search" method="post" action="<?php echo U('Teaching/index');?>">
        授课名称：
        <input type="text" name="keyword" style="width: 200px;" value="<?php echo ((isset($formget["keyword"]) && ($formget["keyword"] !== ""))?($formget["keyword"]):''); ?>"
               placeholder="请输入关键字...">
        <input type="submit" class="btn btn-primary" value="搜索"/>
        <button type="reset" class="btn btn-danger">重置</button>

    </form>
    <form class="js-ajax-form" method="post">

        <table class="table table-hover table-bordered table-list">
            <thead>
            <tr>
                <th width="16"><label><input type="checkbox" class="js-check-all" data-direction="x"
                                             data-checklist="js-check-x"></label></th>
                <th width="60">ID</th>
                <th width="80">授课名称</th>
                <th width="80"><span>添加时间</span></th>
                <th width="60"><span>修改时间</span></th>
                <th width="125"><?php echo L('ACTIONS');?></th>
            </tr>
            </thead>
            <?php if(is_array($data)): foreach($data as $key=>$vo): ?><tr>
                    <td><input type="checkbox" class="js-check" data-yid="js-check-y" data-xid="js-check-x"
                               name="t_ids[]" value="<?php echo ($vo["t_id"]); ?>"></td>
                    <td><a><?php echo ($vo["t_id"]); ?></a></td>
                    <td><?php echo ($vo["course_name"]); ?></td>
                    <td><?php echo (date("Y-m-d H:i:s",$vo['create_time'] )); ?></td>
                    <td><?php echo (date("Y-m-d H:i:s",$vo['update_time'] )); ?></td>
                    <td>
                        <a href="<?php echo U('Teaching/update_teaching',array('t_id'=>$vo['t_id']));?>"><?php echo L('EDIT');?></a>|
                        <a href="<?php echo U('Teaching/delete',array('t_id'=>$vo['t_id']));?>" class="js-ajax-delete"><?php echo L('DELETE');?></a>
                    </td>
                </tr><?php endforeach; endif; ?>

        </table>
        <div class="table-actions">
            <button class="btn btn-danger btn-small js-ajax-submit" type="submit" data-action="<?php echo U('Teaching/delete');?>" data-subcheck="true" data-msg="你确定删除吗？"><?php echo L('DELETE');?></button>
        </div>
        <div class="pagination"><?php echo ($page); ?></div>
    </form>
</div>
<script src="/public/js/common.js"></script>
<script>
    setCookie('refersh_time', 0);
    function refersh_window() {
        var refersh_time = getCookie('refersh_time');
        if (refersh_time == 1) {
            window.location.reload();
        }
    }
    setInterval(function () {
        refersh_window()
    }, 2000);
</script>
</body>
</html>