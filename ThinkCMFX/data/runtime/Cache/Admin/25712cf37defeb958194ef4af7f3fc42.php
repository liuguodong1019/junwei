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
    <li class="active"><a href="<?php echo U('Live/createLive');?>"  target="_self">创建课堂</a></li>
    <li><a href="#">创建课件</a></li>
    <li ><a href="<?php echo U('People/show');?>"  target="_self">适用人群</a></li>
</ul>
<!--<div class="wrap js-check-wrap">-->
    <form action="<?php echo U('Live/create_live');?>" class="well form-search" method="post" >
    <!--<div class="col-auto">-->
        <!--<div class="table_full">-->
    <div class="box box-default">

        <div class="box-body">
            <table width="100%" cellpadding="2" cellspacing="2" >
                <tr>
                    <th width="80">登录名</th>
                    <td>
                        <input type="text" name="loginName" value="admin@junwei.com" style="width: 300px" placeholder = "admin@junwei.com"/>
                    </td>
                </tr>
                <tr>
                    <th width="80">密码</th>
                    <td>
                        <input type="password" name="password" id="" value = "888888" >
                    </td>
                </tr>
                <tr>
                    <th width="80">课堂类型</th>
                    <td>
                        <label><input name="scene" type="radio" value="0" />&nbsp&nbsp&nbsp大讲堂
                        <input name="scene" type="radio" value="1" />&nbsp&nbsp&nbsp小班课 </label>
                    </td>
                </tr>
                <tr>
                    <th width="80">课时数量</th>
                    <td>
                            <input name="num_class" type="text"  />
                    </td>
                </tr>
                <tr>
                    <th width="80">课程主题</th>
                    <td>
                        <input type="text" name="subject " value="" style="width: 300px"/>
                    </td>
                </tr>
                <tr>
                    <th width="80">现价</th>
                    <td>
                        <input name="now_price" type="text"  />
                    </td>
                </tr>
                <tr>
                    <th width="80">原价</th>
                    <td>
                        <input name="old_price" type="text"  />
                    </td>
                </tr>
                <tr>
                    <th width="80">开始时间</th>
                    <td>
                        <input type="text" name="startDate"  placeholder="格式为2017-04-03 10:20:50">
                    </td>
                </tr>

                <tr>
                    <th width="80">结束时间</th>
                    <td>
                        <input type="text" name="invalidDate" placeholder="格式为2017-04-03 10:20:50">
                    </td>
                </tr>

                <tr>
                    <th width="80">课程简介</th>
                    <td>
                        <textarea name='description ' id='e_answer' style='width:50%;height:100px;'></textarea>
                    </td>
                </tr>
                <tr>
                    <th width="80">讲师</th>
                    <td>
                        <select name="lector_id"  class="normal_select">
                                <option value="请选择">请选择</option>
                            <?php if(is_array($array['lector'])): foreach($array['lector'] as $key=>$vo): ?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["name"]); ?></option><?php endforeach; endif; ?>


                        </select>
                    </td>
                </tr>
                <tr>
                    <th width="80">适用人群</th>
                    <td>
                        <select name="people_id"  class="normal_select">

                                <option value="请选择">请选择</option>

                        </select>
                    </td>
                </tr>
                <tr>
                    <th width="80">配发图书</th>
                    <td>
                        <select name="book_id"  class="normal_select">

                            <option value="请选择">请选择</option>

                        </select>
                    </td>
                </tr>
                </tbody>
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