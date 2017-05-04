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
    <li><a href="<?php echo U('Question/itembank');?>">所有试题</a></li>
    <li class="active"><a href="<?php echo U('Question/itembankadd');?>"  target="_self">试题修改</a></li>
</ul>
<form class="form-horizontal js-ajax-form" action="<?php echo U('Question/itembankedit_post');?>" method="post">

<div class="col-auto">
    <div class="table_full">
        <table width="100%" cellpadding="2" cellspacing="2">
            <tr>
                <th width="80">试题类型</th>
                <td>
                    <select name="te_type" class="normal_select">
                        <option value="0" <?php if($item["te_type"] == 0): ?>selected<?php endif; ?> >单选题</option>
                        <option value="1" <?php if($item["te_type"] == 1): ?>selected<?php endif; ?> >多选题</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th width="80">所属科目</th>
                <td>
                   <select name="sid">
                                        <!--  <option value="">选择分类</option> -->
                                          <?php if(is_array($su)): $i = 0; $__LIST__ = $su;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if(($vo["sid"]) == $item["sid"]): ?><option value="<?php echo ($vo["sid"]); ?>" selected ><?php echo ($vo["stitle"]); ?></option>
                                                <?php else: ?>
                                                   <option value="<?php echo ($vo["sid"]); ?>" ><?php echo ($vo["stitle"]); ?></option><?php endif; endforeach; endif; else: echo "" ;endif; ?> 
                    </select>
                   <!--  <select name="sid" onchange="show_class(this.value)" class="normal_select">
                       <?php if(is_array($su)): foreach($su as $key=>$vo): ?><option value="<?php echo ($vo["sid"]); ?>"><?php echo ($vo["stitle"]); ?></option><?php endforeach; endif; ?>                    
                    </select> -->
                </td>
            </tr>

            <tr>
                <th width="80">题干</th>
                <td>
                    <input type="text" name="question" value="<?php echo ($item["question"]); ?>" style="width: 500px"/>
                </td>
            </tr>

            <tr>
                <th width="80">选项</th>
                <td>
                    <textarea name='options' id='e_answer' style='width:98%;height:150px;'><?php echo ($option["0"]); ?>;<?php echo ($option["1"]); ?>;<?php echo ($option["2"]); ?>;<?php echo ($option["3"]); ?>;</textarea>A,B,C,D各个选项用英文';'隔开
                </td>
            </tr>

            <tr>
                <th width="80">答案</th>
                <td>
                    <input type="text" style="width:400px;" name="answer" id="e_result" value="<?php echo ($item["answer"]); ?>" style="color:"
                           class="input input_hd J_title_color" placeholder="请输入答案"
                           onkeyup="strlen_verify(this, 'title_len', 160)"/>输入正确的答案单选 如：A多选A,B多个选项用英文逗号隔开 
                </td>

            </tr>
            <tr>
                <th width="80">试题解析</th>
                <td>
                    <textarea name='parsing' id='parsing' style='width:98%;height:150px;'><?php echo ($item["parsing"]); ?></textarea>
                </td>
            </tr>
            <tr>
                <th width="80">分值</th>
                <td>
                    <input type="text" value="<?php echo ($item["score"]); ?>" name="score" />
                </td>
            </tr>
			<tr>
                <th width="80">试题描述</th>
                <td>
					<input type="text" id="e_probability" name="info" value="<?php echo ($item["info"]); ?>" />
                    <input type="hidden" name="type" value="<?php echo ($item["type"]); ?>">
                    <input type="hidden" name="item_id" value="<?php echo ($item["item_id"]); ?>">
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