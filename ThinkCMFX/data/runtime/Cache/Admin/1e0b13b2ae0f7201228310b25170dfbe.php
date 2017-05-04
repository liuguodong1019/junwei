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
<body class="J_scroll_fixed">
<div class="wrap J_check_wrap">
   <ul class="nav nav-tabs">
      <li class="active"><a href="<?php echo U('Question/itembankadd');?>"  target="_self">讲师列表</a></li>
      <!--<li><a href="#">添加材料试题</a></li>-->
   </ul>
   <form class="well form-search" method="post" action="<?php echo U('Question/itembank');?>">
      名称：
      <input type="text" name="keyword" style="width: 200px;" value="<?php echo ((isset($formget["keyword"]) && ($formget["keyword"] !== ""))?($formget["keyword"]):''); ?>" placeholder="请输入关键字...">
      <input type="submit" class="btn btn-primary" value="搜索" />
      <a class="btn btn-danger" href="<?php echo U('Question/itembank');?>">清空</a>
   </form>
      <div class="col-auto">
         <div class="table_full">
            <table class="table table-hover table-bordered table-list">

               <tr>
                  <th width="16"><label><input type="checkbox" class="js-check-all" data-direction="x" data-checklist="js-check-x"></label></th>
                  <th>ID</th>
                  <th>名称</th>
                  <th>授课类型</th>
                  <th width="125"><?php echo L('ACTIONS');?></th>
               </tr>

               <?php if(is_array($data)): foreach($data as $key=>$vo): ?><tr>
                     <td><input type="checkbox" class="js-check" data-yid="js-check-y" data-xid="js-check-x" name="ids[]" value="<?php echo ($vo["id"]); ?>"></td>
                     <td><?php echo ($vo["id"]); ?></td>
                     <td><?php echo ($vo["name"]); ?></td>
                     <td><?php echo ($vo["range"]); ?></td>
                     <td>
                        <a href="<?php echo U('Question/itembankedit',array('item_id'=>$vo['item_id']));?>"><?php echo L('EDIT');?></a>|
                        <a href="<?php echo U('Question/itembankdelete',array('item_id'=>$vo['item_id']));?>" class="js-ajax-delete"><?php echo L('DELETE');?></a>
                     </td>
                  </tr><?php endforeach; endif; ?>
            </table>
            <div class="table-actions">
               <a href=""  class="btn btn-success" >添加</a>
               <button class="btn btn-danger" type="submit" data-action="<?php echo U('Question/itenbankdelete');?>" data-subcheck="true" data-msg="你确定删除吗？"><?php echo L('DELETE');?></button>
            </div>

         </div>
      </div>

   </form>
   <div class="pagination"><?php echo ($page); ?></div>
</div>

</body>