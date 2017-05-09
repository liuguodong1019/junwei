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
	<div class="wrap js-check-wrap">
		<ul class="nav nav-tabs">
			<li class="active"><a href="<?php echo U('menu/index');?>"><?php echo L('ADMIN_MENU_INDEX');?></a></li>
			<li><a href="<?php echo U('menu/add');?>"><?php echo L('ADMIN_MENU_ADD');?></a></li>
			<li><a href="<?php echo U('menu/lists');?>"><?php echo L('ADMIN_MENU_LISTS');?></a></li>
		</ul>
		<form class="js-ajax-form" action="<?php echo U('Menu/listorders');?>" method="post">
			<div class="table-actions">
				<button class="btn btn-primary btn-small js-ajax-submit" type="submit"><?php echo L('SORT');?></button>
			</div>
			<table class="table table-hover table-bordered table-list" id="menus-table">
				<thead>
					<tr>
						<th width="80"><?php echo L('SORT');?></th>
						<th width="50">ID</th>
						<th><?php echo L('APP');?></th>
						<th><?php echo L('NAME');?></th>
						<th width="80"><?php echo L('STATUS');?></th>
						<th width="180"><?php echo L('ACTIONS');?></th>
					</tr>
				</thead>
				<tbody>
					<?php echo ($categorys); ?>
				</tbody>
				<tfoot>
					<tr>
						<th width="80"><?php echo L('SORT');?></th>
						<th width="50">ID</th>
						<th><?php echo L('APP');?></th>
						<th><?php echo L('NAME');?></th>
						<th width="80"><?php echo L('STATUS');?></th>
						<th width="180"><?php echo L('ACTIONS');?></th>
					</tr>
				</tfoot>
			</table>
			<div class="table-actions">
				<button class="btn btn-primary btn-small js-ajax-submit" type="submit"><?php echo L('SORT');?></button>
			</div>
		</form>
	</div>
	<script src="/ThinkCMFX/public/js/common.js"></script>
	<script>
		$(document).ready(function() {
			Wind.css('treeTable');
			Wind.use('treeTable', function() {
				$("#menus-table").treeTable({
					indent : 20
				});
			});
		});

		setInterval(function() {
			var refersh_time = getCookie('refersh_time_admin_menu_index');
			if (refersh_time == 1) {
				reloadPage(window);
			}
		}, 1000);
		setCookie('refersh_time_admin_menu_index', 0);
	</script>
</body>
</html>