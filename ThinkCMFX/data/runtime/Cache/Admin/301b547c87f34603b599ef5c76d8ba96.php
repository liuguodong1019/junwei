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
	<div class="wrap js-check-wrap">
		<ul class="nav nav-tabs">
	        <li class="active"><a href="#A" data-toggle="tab">科目编辑</a></li>
	    </ul>
		<form class="form-horizontal js-ajax-form" action="<?php echo U('Types/classedit_post');?>" method="post">
			<div class="tabbable">
		        <div class="tab-content">
		          <div class="tab-pane active" id="A">
						<fieldset>
						   <div class="control-group">
								<label class="control-label">所属专业</label>
								<div class="controls">
								      <select name="pid">
							             <option value="">选择分类</option>
							              <?php if(is_array($pr)): $i = 0; $__LIST__ = $pr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if(($vo["pid"]) == $su["pid"]): ?><option value="<?php echo ($vo["pid"]); ?>" selected ><?php echo ($vo["ptitle"]); ?></option>
							                    <?php else: ?>
							                       <option value="<?php echo ($vo["pid"]); ?>" ><?php echo ($vo["ptitle"]); ?></option><?php endif; endforeach; endif; else: echo "" ;endif; ?> 
                                      </select>

									<!-- <select name="pid">
                                      <?php if(is_array($pr)): foreach($pr as $key=>$vo): ?><option value="<?php echo ($vo["pid"]); ?>" 
										    {if "<?php echo ($vo["pid"]); ?>" eq "<?php echo ($su["pid"]); ?>"}
                                              selected
										    {/if}
										><?php echo ($vo["ptitle"]); ?></option><?php endforeach; endif; ?>
									</select> -->
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">科目标题</label>
								<div class="controls">
									<input type="text" name="stitle" value="<?php echo ($su["stitle"]); ?>"><span class="form-required">*</span>
									<input type="hidden" name="sid" value="<?php echo ($su["sid"]); ?>">
								</div>
							</div>
						</fieldset>
		          </div>
		    <div class="form-actions">
		     	<button class="btn btn-primary js-ajax-submit"type="submit">保存</button>
		      	<a class="btn" href="javascript:history.back(-1);"><?php echo L('BACK');?></a>
		    </div>
		</form>
	</div>
	<script type="text/javascript" src="/public/js/common.js"></script>
</body>
</html>