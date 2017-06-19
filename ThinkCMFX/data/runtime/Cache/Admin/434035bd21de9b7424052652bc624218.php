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
    <li><a href="<?php echo U('Question/itembank');?>">所有试题</a></li>
    <li><a href="<?php echo U('Question/itembankadd');?>"  >添加试题</a></li>
    <li><a href="<?php echo U('Question/mater');?>">添加材料试题</a></li>
    <li class="active"><a href="<?php echo U('Question/subjective');?>"target="_self">添加主观试题</a></li>
</ul>
<form class="form-horizontal js-ajax-form" action="<?php echo U('Question/subjectiveadd');?>" method="post">
    <div>
         <table width="100%" cellpadding="2" cellspacing="2">
          <tr>
                <th width="80">试题材料</th>
                <td>
                    <textarea name='content' style='width:98%;height:100px;'></textarea>
                </td>
            </tr>
         </table>
    </div>

<div id="shiti-box" style="">
    <div class="table_full" style=" padding-top:10px;   padding-bottom: 20px;border-bottom: 1px solid #ebebeb;">
        <table width="100%" cellpadding="2" cellspacing="2">
            <tr>
                <th width="80">所属科目</th>
                <td>
                    <select name="sid[]"  class="normal_select oneji">
                      <option value="">请选择</option>             
					</select>
                </td>
            </tr>
             <tr>
                <th width="80">所属章节</th>
                <td>
                    <select name="cid[]" class="normal_select twoji">
                       <option value="">请选择</option>
					</select>
                </td>
            </tr>
			<tr>
                <th width="80">题号</th>
                <td>
                    <input type="text" autocomplete="off" name="no[]"  style="width: 100px"/>
                </td>
            </tr>
            <tr>
                <th width="80">题干</th>
                <td>
                    <textarea name='question[]' id='e_answer' style='width:98%;height:150px;'></textarea>
                </td>
            </tr>

            <tr>
                <th width="80">答案</th>
                <td>
                    <textarea name='answer[]' autocomplete="off" id='options' style='width:98%;height:150px;'></textarea>
                </td>
            </tr>
			<tr>
                <th width="80">考点</th>
                <td>
                    <input type="text" autocomplete="off" value="" name="point[]" />
                </td>
            </tr>
            <tr>
                <th width="80">试题解析</th>
                <td>
                    <textarea name='parsing[]' id='parsing[]' style='width:98%;height:150px;'></textarea>
                </td>
            </tr>
             <tr>
                <th width="80">关键字</th>
                <td>
                    <input type="text" autocomplete="off" style="width:400px;" name="kword[]" id="kword[]" value="" style="color:"
                           class="input input_hd J_title_color" placeholder="请输入答案"
                           onkeyup="strlen_verify(this, 'title_len', 160)"/> 多个关键字用英文逗号隔开 
                </td>

            </tr>
            <tr>
             <th width="80">难易度</th>
                <td>
                    <select name="difficulty[]" class="normal_select">
                        <option value="0">简单</option>
                        <option value="1">一般</option>
                        <option value="2">困难</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th width="80">分值</th>
                <td>
                    <input type="text" value="1" name="score[]" />
                </td>
            </tr>
            <tr>
                <th width="80">试题描述</th>
                <td>
                    <input type="text" autocomplete="off" id="e_probability" name="info[]" />
                    <input type="hidden" name="type[]" value="0">
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
</div>
<div class="form-actions">
    <button class="btn btn_submit J_ajax_submit_btn" type="submit">提交</button>
    <a class="btn" href="/index.php/Admin/Question">返回</a>
    <a class="btn" id="addshiti" href="#">试题添加</a>
</div>
</form>
</div>
<script src="/junwei/public/js/common.js"></script>
<script src="/junwei/public/js/jquery.js"></script>
<script>
$(document).ready(function(){
			var add=$("#addshiti");
			var shitibox=$("#shiti-box");
			var shiti=document.getElementById("shiti-box").getElementsByTagName("div")[0].innerHTML;
			//alert (shiti);
			function addshiti(){
				var index="list1";
				var newst=document.createElement("div");
				newst.innerHTML=shiti;
				$("#shiti-box").append(newst);
				
			}
			add.click(function(){
				addshiti();
				var oneji=$("#shiti-box>div:last-child").find(".oneji");
				var twoji=$("#shiti-box>div:last-child").find(".twoji");
				getdata(oneji,twoji);	
			})
	function getdata(one,two){
			$.ajax({
				type:"get",	
				url:"http://kf.junweiedu.cn/junwei/index.php?g=houtai&m=itembank&a=kzjk",
				datatype:"jsonp",
                jsonp:"jsonpCallback",
                jsonpCallback:"successCallback",
				success:function(data){
					var jsondata=eval(data); 
					var onejihtml='<option value="">请选择</option>';
					
			        for(i in jsondata){
						onejihtml+="<option value="+jsondata[i].sid+">"+jsondata[i].stitle+"</option>";		
					}
					one.html(onejihtml);	
				}
			});
			one.change(function(){
				var oneval=$(this).val();
				$.ajax({
				type:"get",	
				url:"http://kf.junweiedu.cn/junwei/index.php?g=houtai&m=itembank&a=kzjk",
				datatype:"jsonp",
                jsonp:"jsonpCallback",
                jsonpCallback:"successCallback",
				success:function(data){
					var jsondata=eval(data); 
					var twojihtml='<option value="">请选择</option>';
			        for(i in jsondata){
					var onejiname=jsondata[i].sid;
					if(onejiname==oneval){
						$.each(jsondata[i].chapter, function(k,v) {
						 twojihtml+="<option value="+jsondata[i].chapter[k].cid+">"+jsondata[i].chapter[k].ctitle+"</option>";
					});
					two.html(twojihtml);				
					}		
					}	
			}
	});
})
};
var oneji=$(".oneji");
var twoji=$(".twoji");
getdata(oneji,twoji);	
})
</script>
</body>
<style>
    .table_full{
        
        padding-bottom: 20px; 
        margin: 20px 0;
    }
    #shiti-box{
        
        padding-bottom: 20px; 
        margin: 20px 0px;
    }
</style>