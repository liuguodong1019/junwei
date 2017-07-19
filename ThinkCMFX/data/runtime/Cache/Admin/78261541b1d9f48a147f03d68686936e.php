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
<body class="J_scroll_fixed">
<div class="wrap J_check_wrap">
<ul class="nav nav-tabs">
    <li><a href="<?php echo U('Question/exclusive');?>">密卷管理</a></li>
    <li class="active"><a href="<?php echo U('Question/exclusiveedit');?>"  target="_self">密卷修改</a></li>
</ul>
<form class="form-horizontal js-ajax-form" action="<?php echo U('Question/exclusiveedit_post');?>" method="post">
<div class="col-auto">
    <div class="table_full">
        <table width="100%" cellpadding="2" cellspacing="2">
<tr>
                <th width="80">密卷科目</th>
                <td>
                    <select name="ex_id">
                                        <!--  <option value="">选择分类</option> -->
                                          <?php if(is_array($exc)): $i = 0; $__LIST__ = $exc;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if(($vo["ex_id"]) == $item["ex_id"]): ?><option value="<?php echo ($vo["ex_id"]); ?>" selected ><?php echo ($vo["etitle"]); ?></option>
                                                <?php else: ?>
                                                   <option value="<?php echo ($vo["ex_id"]); ?>" ><?php echo ($vo["etitle"]); ?></option><?php endif; endforeach; endif; else: echo "" ;endif; ?> 
                    </select>
                </td>
            </tr>
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
                <th width="80">题号</th>
                <td>
                    <input type="text" name="no" value="<?php echo ($item["no"]); ?>" style="width: 100px"/>
                </td>
            </tr>
            <tr>
                <th width="80">题干</th>
                <td>
                    <textarea name='question' id='e_answer' style='width:98%;height:150px;'><?php echo ($item["question"]); ?></textarea>
                </td>
            </tr>

            <tr>
                <th width="80">选项</th>
                <td>
                     <?php if(is_array($option)): foreach($option as $key=>$v): ?><input type="text" name="option[]" id="" value="<?php echo ($v["options"]); ?>" style="width:400px;"></br><?php endforeach; endif; ?>
                </td>
            </tr>

            <tr>
                <th width="80">答案</th>
                <td>
                    <input type="text" autocomplete="off" style="width:400px;" name="answer" id="e_result" value="<?php echo ($answer); ?>" style="color:"
                           class="input input_hd J_title_color" placeholder="请输入答案"
                           onkeyup="strlen_verify(this, 'title_len', 160)"/>输入正确的答案单选 如：A多选A,B多个选项用英文逗号隔开 
                </td>

            </tr>
            <tr>
                <th width="80">考点</th>
                <td>
                    <input type="text" autocomplete="off" value="<?php echo ($item["point"]); ?>" name="point" style='width:98%;' />
                </td>
            </tr>
            <tr>
             <th width="80">难易度</th>
                <td>
                    <select name="difficulty" class="normal_select">
                        <option value="0" <?php if($item["difficulty"] == 0): ?>selected<?php endif; ?>>简单</option>
                        <option value="1" <?php if($item["difficulty"] == 1): ?>selected<?php endif; ?>>一般</option>
                        <option value="2" <?php if($item["difficulty"] == 2): ?>selected<?php endif; ?>>困难</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th width="80">试题解析</th>
                <td>
                    <textarea name='parsing' id='parsing' style='width:98%;height:150px;'><?php echo ($item["parsing"]); ?></textarea>
                </td>
            </tr>
            <tr>
                <th width="80">是否为不定项</th>
                <td>
                    <input type="radio" name="ncertain" value="0" <?php if($item["ncertain"] == 0): ?>checked="checked"<?php endif; ?>>否
                    <input type="radio" name="ncertain" value="1" <?php if($item["ncertain"] == 1): ?>checked="checked"<?php endif; ?>>是
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
<script src="/junwei/public/js/common.js"></script>
</body>