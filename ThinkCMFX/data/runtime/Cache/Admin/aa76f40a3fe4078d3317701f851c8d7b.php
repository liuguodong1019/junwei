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
	<div class="wrap">
		<ul class="nav nav-tabs">
			<li class="active"><a>上传设置</a></li>
		</ul>
		<form method="post" class="form-horizontal js-ajax-form" action="<?php echo U('setting/upload_post');?>">
			<fieldset>
				<div class="control-group">
					<label class="control-label">图片文件</label>
					<div class="controls">
						<input type="text" name="image[upload_max_filesize]" title="允许上传大小KB,1M=1024KB" placeholder="允许上传大小KB,1M=1024KB" value="<?php echo ((isset($image["upload_max_filesize"]) && ($image["upload_max_filesize"] !== ""))?($image["upload_max_filesize"]):10240); ?>">
						<input type="text" name="image[extensions]" title="扩展名,以英文逗号分隔" placeholder="扩展名,以英文逗号分隔" value="<?php echo ((isset($image["extensions"]) && ($image["extensions"] !== ""))?($image["extensions"]):'jpg,jpeg,png,gif,bmp'); ?>">
						<span class="form-required">*</span>
						<span class="help-block">允许上传大小默认为10240KB,1M=1024KB，允许上传格式默认为jpg,jpeg,png,gif,bmp</span>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">视频文件</label>
					<div class="controls">
						<input type="text" name="video[upload_max_filesize]" title="允许上传大小KB,1M=1024KB" placeholder="允许上传大小KB,1M=1024KB" value="<?php echo ((isset($video["upload_max_filesize"]) && ($video["upload_max_filesize"] !== ""))?($video["upload_max_filesize"]):10240); ?>">
						<input type="text" name="video[extensions]" title="扩展名,以英文逗号分隔" placeholder="扩展名,以英文逗号分隔" value="<?php echo ((isset($video["extensions"]) && ($video["extensions"] !== ""))?($video["extensions"]):'mp4,avi,wmv,rm,rmvb,mkv'); ?>">
						<span class="form-required">*</span>
						<span class="help-block">允许上传大小默认为102400KB,1M=1024KB，允许上传格式默认为mp4,avi,wmv,rm,rmvb,mkv</span>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">音频文件</label>
					<div class="controls">
						<input type="text" name="audio[upload_max_filesize]" title="允许上传大小KB,1M=1024KB" placeholder="允许上传大小KB,1M=1024KB" value="<?php echo ((isset($audio["upload_max_filesize"]) && ($audio["upload_max_filesize"] !== ""))?($audio["upload_max_filesize"]):10240); ?>">
						<input type="text" name="audio[extensions]" title="扩展名,以英文逗号分隔" placeholder="扩展名,以英文逗号分隔" value="<?php echo ((isset($audio["extensions"]) && ($audio["extensions"] !== ""))?($audio["extensions"]):'mp3,wma,wav'); ?>">
						<span class="form-required">*</span>
						<span class="help-block">允许上传大小默认为10240KB,1M=1024KB，允许上传格式默认为mp3,wma,wav</span>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">附件</label>
					<div class="controls">
						<input type="text" name="file[upload_max_filesize]" title="允许上传大小KB,1M=1024KB" placeholder="允许上传大小KB,1M=1024KB" value="<?php echo ((isset($file["upload_max_filesize"]) && ($file["upload_max_filesize"] !== ""))?($file["upload_max_filesize"]):10240); ?>">
						<input type="text" name="file[extensions]" title="扩展名,以英文逗号分隔" placeholder="扩展名,以英文逗号分隔" value="<?php echo ((isset($file["extensions"]) && ($file["extensions"] !== ""))?($file["extensions"]):'txt,pdf,doc,docx,xls,xlsx,ppt,pptx,zip,rar'); ?>">
						<span class="form-required">*</span>
						<span class="help-block">允许上传大小默认为10240KB,1M=1024KB，允许上传格式默认为除以上文档类型以外的其它常用文件,如：txt,pdf,doc,docx,xls,xlsx,ppt,pptx,zip,rar</span>
					</div>
				</div>
			</fieldset>
			<div class="form-actions">
				<button type="submit" class="btn btn-primary js-ajax-submit"><?php echo L('SAVE');?></button>
			</div>
		</form>
	</div>
	<script src="/junwei/public/js/common.js"></script>
</body>
</html>