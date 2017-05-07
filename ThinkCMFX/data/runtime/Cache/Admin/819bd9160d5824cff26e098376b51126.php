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
			<li><a href="<?php echo U('setting/site');?>">网站信息</a></li>
			<li class="active"><a href="<?php echo U('route/index');?>">URL美化</a></li>
			<li><a href="<?php echo U('route/add');?>">添加URL规则</a></li>
		</ul>
		<form class="js-ajax-form" action="<?php echo U('route/listorders');?>" method="post">
			<div class="table-actions">
				<button type="submit" class="btn btn-primary btn-small js-ajax-submit">排序</button>
			</div>
			<table class="table table-hover table-bordered table-list">
				<thead>
					<tr>
						<th width="50">排序</th>
						<th width="50">ID</th>
						<th>原始网址</th>
						<th>显示网址</th>
						<th>状态</th>
						<th width="120">操作</th>
					</tr>
				</thead>
				<tbody>
					<?php $statuses=array('0'=>"已禁用","1"=>"已启用"); ?>
					<?php if(is_array($routes)): foreach($routes as $key=>$vo): ?><tr>
						<td><input name='listorders[<?php echo ($vo["id"]); ?>]'
							class="input input-order mr5" type='text' size='3'
							value='<?php echo ($vo["listorder"]); ?>'></td>
						<td><?php echo ($vo["id"]); ?></td>
						<td><?php echo ($vo["full_url"]); ?></td>
						<td><?php echo ($vo["url"]); ?></td>
						<td><?php echo ($statuses[$vo['status']]); ?></td>
						<td>
							<a href="<?php echo U('route/edit',array('id'=>$vo['id']));?>">修改</a>|
							<a href="<?php echo U('route/open',array('id'=>$vo['id']));?>" class="js-ajax-dialog-btn" data-msg="确定启用吗？">启用</a>|
							<a href="<?php echo U('route/ban',array('id'=>$vo['id']));?>" class="js-ajax-dialog-btn" data-msg="确定禁用吗？">禁用</a>|
							<a href="<?php echo U('route/delete',array('id'=>$vo['id']));?>" class="js-ajax-delete">删除</a>
						</td>
					</tr><?php endforeach; endif; ?>
				</tbody>
				<tfoot>
					<tr>
						<th width="50">排序</th>
						<th width="50">ID</th>
						<th>原始网址</th>
						<th>显示网址</th>
						<th>状态</th>
						<th width="120">操作</th>
					</tr>
				</tfoot>
			</table>
			<div class="table-actions">
				<button type="submit" class="btn btn-primary btn-small js-ajax-submit">排序</button>
			</div>
		</form>
	</div>
	<script type="text/javascript" src="/ThinkCMFX/public/js/common.js"></script>
</body>
</html>