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
	<div class="wrap js-check-wrap">
		<ul class="nav nav-tabs">
	        <li class="active"><a href="#A" data-toggle="tab">章节编辑</a></li>
	    </ul>
		<form class="form-horizontal js-ajax-form" action="<?php echo U('Types/chapteredit_post');?>" method="post">
			<div class="tabbable">
		        <div class="tab-content">
		          <div class="tab-pane active" id="A">
						<fieldset>
						   <div class="control-group">
								<label class="control-label">所属科目</label>
								<div class="controls">
								    <select name="sid">
							            <!--  <option value="">选择分类</option> -->
							              <?php if(is_array($su)): $i = 0; $__LIST__ = $su;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if(($vo["sid"]) == $ch["sid"]): ?><option value="<?php echo ($vo["sid"]); ?>" selected ><?php echo ($vo["stitle"]); ?></option>
							                    <?php else: ?>
							                       <option value="<?php echo ($vo["sid"]); ?>" ><?php echo ($vo["stitle"]); ?></option><?php endif; endforeach; endif; else: echo "" ;endif; ?> 
                                      </select>
									<!-- <select name="tid">
                                      <?php if(is_array($types)): foreach($types as $key=>$vo): if(($vo["tid"]) == $pr["tid"]): ?><option value="<?php echo ($vo["tid"]); ?>" selected ><?php echo ($vo["title"]); ?></option>
							                <?php else: ?>
							                   <option value="<?php echo ($vo["tid"]); ?>" ><?php echo ($vo["title"]); ?></option><?php endif; endforeach; endif; ?>>
									</select> -->
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">章节标题</label>
								<div class="controls">
									<input type="text" name="ctitle" value="<?php echo ($ch["ctitle"]); ?>"><span class="form-required">*</span>
									<input type="hidden" name="cid" value="<?php echo ($ch["cid"]); ?>">
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
	<script type="text/javascript" src="/junwei/public/js/common.js"></script>
</body>
</html>