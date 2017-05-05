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
	<link rel="stylesheet" href="/ThinkCMFX/public/simpleboot/font-awesome/4.4.0/css/font-awesome-ie7.min.css">
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
</head>
<body>
<div class="wrap js-check-wrap">
    <ul class="nav nav-tabs">
        <li class="active"><a href="<?php echo U('Course/show');?>">课堂列表</a></li>
        <li><a href="<?php echo U('Course/show');?>">课堂列表</a></li>
        <li><a href="<?php echo U('Course/create');?>">添加课堂</a></li>
    </ul>
    <table class="table table-hover table-bordered table-list">
        <tr>
            <th width="60">ID</th>
            <th width="80">课堂主题</th>
            <th width="80">现价</th>
            <th width="80">原价</th>
            <th width="80">讲师</th>
            <th width="80">课时数量</th>
            <th width="80">课堂类型</th>
            <th width="80">直播状态</th>
            <th width="80">是否收费</th>
        </tr>
        <tr>
            <td><a><?php echo ($data["id"]); ?></a></td>
            <td><?php echo ($data["subject"]); ?></td>
            <td><?php echo ($data["now_price"]); ?></td>
            <td><?php echo ($data["old_price"]); ?></td>
            <td><?php echo ($data["name"]); ?></td>
            <td><?php echo ($data["num_class"]); ?></td>
            <td><?php echo ($data["type"]); ?></td>
            <td><?php echo ($data["status"]); ?></td>
            <td><?php echo ($data["is_free"]); ?></td>
        </tr>
        <tr>
            <th width="80">课堂编号</th>
            <th width="80">学生口令</th>
            <th width="80">课堂ID</th>
            <th width="80">回放地址</th>
            <th width="80">回放口令</th>
            <th width="80">适用人群</th>
            <th width="80">配发图书</th>
            <th width="80">开始时间</th>
            <th width="80">结束时间</th>
        </tr>
        <tr>
            <td><?php echo ($data["number"]); ?></td>
            <td><?php echo ($data["stu_token"]); ?></td>
            <td><?php echo ($data["class_id"]); ?></td>
            <td><?php echo ($data["reply_url"]); ?></td>
            <td><?php echo ($data["token"]); ?></td>
            <td><?php echo ($data["people"]); ?></td>
            <td><?php echo ($data["book"]); ?></td>
            <td><?php echo ($data["startdate"]); ?></td>
            <td><?php echo ($data["invaliddate"]); ?></td>
        </tr>

    </table>
    <table class="table table-hover table-bordered table-list">
        <tr>
            <th width="80">课程简介</th>
        </tr>
        <tr>
            <td><textarea style='width:50%;height:100px;'><?php echo ($data["introduction"]); ?></textarea></td>
        </tr>
    </table>

</div>

</body>
</html>