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
    <li><a href="<?php echo U('Question/ture');?>">所有真题</a></li>
    <li class="active"><a href="<?php echo U('Question/truekadd');?>"  target="_self">添加真题</a></li>
    <li><a href="#">添加材料试题</a></li>
</ul>
<form class="form-horizontal js-ajax-form" action="<?php echo U('Question/trueadd_post');?>" method="post">

<div class="col-auto">
    <div class="table_full">
        <table width="100%" cellpadding="2" cellspacing="2">
            <tr>
                <th width="80">试卷名称</th>
                <td>
                    <input type="text" name="eid" value="" style="width: 100px"/>年司国家司法考试试题
                </td>
            </tr>
            <tr>
                <th width="80">试卷类型</th>
                <td>
                    <select name="etid" class="normal_select">
                        <option value="0">卷一</option>
                        <option value="1">卷二</option>
                        <option value="2">卷三</option>
                    </select>
                </td>
            </tr>
             <tr>
                <th width="80">试题类型</th>
                <td>
                    <select name="te_type" class="normal_select">
                        <option value="0">单选题</option>
                        <option value="1">多选题</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th width="80">题干</th>
                <td>
                    <input type="text" name="question" value="" style="width: 500px"/>
                </td>
            </tr>

            <tr>
                <th width="80">选项</th>
                <td>
                    <textarea name='options' id='e_answer' style='width:98%;height:150px;'></textarea>A,B,C,D各个选项用英文';'隔开
                </td>
            </tr>

            <tr>
                <th width="80">答案</th>
                <td>
                    <input type="text" style="width:400px;" name="answer" id="e_result" value="" style="color:"
                           class="input input_hd J_title_color" placeholder="请输入答案"
                           onkeyup="strlen_verify(this, 'title_len', 160)"/>输入正确的答案单选 如：A多选A,B多个选项用英文逗号隔开 
                </td>

            </tr>
            <tr>
                <th width="80">考点</th>
                <td>
                    <input type="text" value="暂无" name="point" />
                </td>
            </tr>
            <tr>
             <th width="80">难易度</th>
                <td>
                    <select name="difficulty" class="normal_select">
                        <option value="0">简单</option>
                        <option value="1">一般</option>
                        <option value="2">困难</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th width="80">是否为不定项</th>
                <td>
                    <input type="radio" name="ncertain" value="0" checked="checked">否
                    <input type="radio" name="ncertain" value="1">是
                </td>
            </tr>
            <tr>
                <th width="80">试题解析</th>
                <td>
                    <textarea name='parsing' id='parsing' style='width:98%;height:150px;'>暂无</textarea>
                </td>
            </tr>
            <tr>
                <th width="80">分值</th>
                <td>
                    <input type="text" value="1" name="score" />
                </td>
            </tr>
			<tr>
                <th width="80">试题描述</th>
                <td>
					<input type="text" id="e_probability" name="info" />
					<input type="hidden" name="type" value="1">
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
<div class="form-actions">
    <button class="btn btn_submit J_ajax_submit_btn" type="submit">提交</button>
    <a class="btn" href="/index.php/Admin/Question">返回</a>
</div>
</form>
</div>
<script src="/public/js/common.js"></script>
</body>