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
<<<<<<< HEAD
    <script src="/junwei/ThinkCMFX/public/simpleboot/jedate/jedate.js"></script>
    <link href="/junwei/ThinkCMFX/public/simpleboot/themes/<?php echo C('SP_ADMIN_STYLE');?>/theme.min.css" rel="stylesheet">
    <link href="/junwei/ThinkCMFX/public/simpleboot/css/simplebootadmin.css" rel="stylesheet">
    <link href="/junwei/ThinkCMFX/public/js/artDialog/skins/default.css" rel="stylesheet" />
    <link href="/junwei/ThinkCMFX/public/simpleboot/font-awesome/4.4.0/css/font-awesome.min.css"  rel="stylesheet" type="text/css">
    <style>
        form .input-order{margin-bottom: 0px;padding:3px;width:40px;}
        .table-actions{margin-top: 5px; margin-bottom: 5px;padding:0px;}
        .table-list{margin-bottom: 0px;}
    </style>
    <!--[if IE 7]>
    <!--<link rel="stylesheet" href="/junwei/ThinkCMFX/public/simpleboot/font-awesome/4.4.0/css/font-awesome-ie7.min.css">-->
    <link  href="/junwei/ThinkCMFX/public/simpleboot/bootstrap/css/bootstrap-responsive.min.css">
    <![endif]-->
    <script type="text/javascript">
        //全局变量
        var GV = {
            ROOT: "/junwei/ThinkCMFX/",
            WEB_ROOT: "/junwei/ThinkCMFX/",
            JS_ROOT: "public/js/",
            APP:'<?php echo (MODULE_NAME); ?>'/*当前应用名*/
        };
    </script>
    <script src="/junwei/ThinkCMFX/public/js/jquery.js"></script>
    <script src="/junwei/ThinkCMFX/public/js/wind.js"></script>
    <script src="/junwei/ThinkCMFX/public/simpleboot/bootstrap/js/bootstrap.min.js"></script>
=======
    <script src="/public/simpleboot/jedate/jedate.js"></script>
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
	<!--<link rel="stylesheet" href="/public/simpleboot/font-awesome/4.4.0/css/font-awesome-ie7.min.css">-->
    <link  href="/public/simpleboot/bootstrap/css/bootstrap-responsive.min.css">
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
>>>>>>> f25310abebdbebd6cc75c2d551f1658e696aeb8e
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
<ul class="nav nav-tabs">
    <li class="active"><a href="<?php echo U('Course/create');?>"  target="_self">创建课堂</a></li>
    <li ><a href="<?php echo U('Course/show');?>"  target="_self">课堂列表</a></li>

</ul>
    <form action="<?php echo U('Course/create');?>" class="well form-search" method="post" >

    <div class="box box-default">

        <div class="box-body">
            <table width="100%" cellpadding="2" cellspacing="2" >
                <tr>
                    <th width="80">课堂类型</th>
                    <td>
                        <label><input name="type" type="radio" value="0" />&nbsp&nbsp&nbsp大讲堂
                        <input name="type" type="radio" value="1" />&nbsp&nbsp&nbsp小班课 </label>
                    </td>
                </tr>
                <tr>
                    <th width="80">收否收费</th>
                    <td>
                        <label><input name="is_free" type="radio" value="1" />&nbsp&nbsp&nbsp公开课
                            <input name="is_free" type="radio" value="2" />&nbsp&nbsp&nbspvip </label>
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
                        <input type="text" name="subject" value="" style="width: 300px"/>
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
                        <input class="datainp" name = "startDate"  id="datebut" type="text" placeholder="双击选择时间"  onClick="jeDate({dateCell:'#datebut',isTime:true,format:'YYYY-MM-DD hh:mm:ss'})">
                    </td>
                </tr>

                <tr>
                    <th width="80">结束时间</th>
                    <td>
                        <input class="datainp" name = "invalidDate"  id="datebut1" type="text" placeholder="双击选择时间"  onClick="jeDate({dateCell:'#datebut1',isTime:true,format:'YYYY-MM-DD hh:mm:ss'})">
                    </td>
                </tr>
                <tr>
                    <th width="80">课程简介</th>
                    <td>
                        <textarea name='introduction' id='e_answer' style='width:50%;height:100px;'></textarea>
                    </td>
                </tr>
                <tr>
                    <th width="80">讲师</th>
                    <td>
                        <select name="lector_id"  class="normal_select">
                                <option value="请选择">请选择</option>
                            <?php if(is_array($array['lector'])): foreach($array['lector'] as $key=>$vo): ?><option value="<?php echo ($vo["l_id"]); ?>"><?php echo ($vo["name"]); ?></option><?php endforeach; endif; ?>


                        </select>
                    </td>
                </tr>
                <tr>
                    <th width="80">适用人群</th>
                    <td>
                        <select name="people_id"  class="normal_select">
                                <option value="请选择">请选择</option>
                                <?php if(is_array($array['people'])): foreach($array['people'] as $key=>$va): ?><option value="<?php echo ($va["p_id"]); ?>"><?php echo ($va["people"]); ?></option><?php endforeach; endif; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th width="80">配发图书</th>
                    <td>
                        <select name="book_id"  class="normal_select">
                            <option value="请选择">请选择</option>
                            <?php if(is_array($array['book'])): foreach($array['book'] as $key=>$value): ?><option value="<?php echo ($value["b_id"]); ?>"><?php echo ($value["book"]); ?></option><?php endforeach; endif; ?>
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