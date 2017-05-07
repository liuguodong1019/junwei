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
	<link rel="stylesheet" href="/public/simpleboot/font-awesome/4.4.0/css/font-awesome-ie7.min.css">
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
</head>
<body>
<ul class="nav nav-tabs">
    <li class="active"><a href="<?php echo U('Lector/update_lector');?>"  target="_self">修改讲师</a></li>
    <li><a href="<?php echo U('Lector/create_lector');?>">添加讲师</a></li>
    <li><a href="<?php echo U('Lector/lector');?>">讲师列表</a></li>
</ul>

<form action="<?php echo U('Lector/update_lector');?>" method="post">
    <div class="box box-default">
        <div class="box-body">
            <table width="100%" cellpadding="2" cellspacing="2" >
                <tr>
                    <th width="80">名称</th>
                    <td>
                        <input type="text" name="name" value="<?php echo ($list["name"]); ?>" style="width: 200px"/>
                    </td>
                </tr>
                <tr>
                    <th width="80">授课类型</th>
                    <td>
                        <select name="teaching_id" id="">

                                <option value="请选择">请选择</option>
                                <?php if(is_array($data)): foreach($data as $key=>$value): ?><option value="<?php echo ($value["id"]); ?>"><?php echo ($value["course_name"]); ?></option><?php endforeach; endif; ?>
                        </select>
                    </td>
                </tr>
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
</html>