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
<style>
		.kemumenu{ width: 100%; padding:10px  0px;float:left; border:#cbcbcb solid 1px;border-radius:4px; margin-bottom:10px;}
		.kemumenu span{padding: 5px 20px;cursor: pointer;font-size: 14px;font-family: "微软雅黑"; line-height: 20px;color:#636363;display: block;float: left;}
		.kemumenu span:hover{color: #333333;}
		.kemu-active{ color:red!important;font-weight: bold;}
	</style>
</head>
<body>
	<div class="wrap js-check-wrap">
		<ul class="nav nav-tabs">
			<li class="active"><a href="<?php echo U('Question/true');?>">真题管理</a></li>
			<li><a href="<?php echo U('Question/trueadd');?>">真题添加</a></li>
			<li><a href="<?php echo U('Question/truesubjectivelist');?>">主观题列表</a></li>
		</ul>
		<div class="kemumenu" id="one-menu"></div>
		<div class="kemumenu" id="two-menu"></div>
		<form class="js-ajax-form" method="post">
			<div class="table-actions">
				<button class="btn btn-danger btn-small js-ajax-submit" type="submit" data-action="<?php echo U('Question/turedelete');?>" data-subcheck="true" data-msg="<?php echo L('DELETE_CONFIRM_MESSAGE');?>"><?php echo L('DELETE');?></button>
			</div>
			<table class="table table-hover table-bordered table-list">
				<thead>
					<tr>
						<th width="16"><label><input type="checkbox" class="js-check-all" data-direction="x" data-checklist="js-check-x"></label></th>
						<th width="50">题号</th>
						<th>题干</th>
						<!-- <th>点击量</th> -->
						<th width="150">卷名</th>
						<th width="80"><span>添加时间</span></th>
						<th width="60"><span>考题类型</span></th>
						<th width="125"><?php echo L('ACTIONS');?></th>
					</tr><q>			</q>
				</thead>

				<tr>
					<td><input type="checkbox" class="js-check" data-yid="js-check-y" data-xid="js-check-x" name="item_ids[]" value="<?php echo ($vo["item_id"]); ?>"></td>
					<td><a><?php echo ($vo["no"]); ?></a></td>
					<td><a href="#"><?php echo ($vo["question"]); ?></a></td>
					<td><?php echo ($vo["eid"]); ?>年国家司法考试<a href="#"><?php if($vo["etid"] == 0): ?>卷一 
						<?php elseif($vo["etid"] == 1): ?> 卷二<?php elseif($vo["etid"] == 2): ?>卷三<?php endif; ?></a>试题</td>
					<td><?php echo (date('Y-m-d H:i:s',$vo["itime"])); ?></td>
					<td>
						<?php if($vo["te_type"] == 1): ?>多选题 
						<?php elseif($vo["te_type"] == 0): ?> 单选题<?php endif; ?>
					</td>
					<td>
						<a href="<?php echo U('Question/trueedit',array('item_id'=>$vo['item_id']));?>"><?php echo L('EDIT');?></a>|
						<a href="<?php echo U('Question/truedelete',array('item_id'=>$vo['item_id']));?>" class="js-ajax-delete"><?php echo L('DELETE');?></a>
					</td>
				</tr>

				<tfoot>
					<tr>
						<th width="16"><label><input type="checkbox" class="js-check-all" data-direction="x" data-checklist="js-check-x"></label></th>
						<th width="50">题号</th>
						<th>题干</th>
						<!-- <th>点击量</th> -->
						<th width="150">卷名</th>
						<th width="80"><span>添加时间</span></th>
						<th width="60"><span>考题类型</span></th>
						<th width="125"><?php echo L('ACTIONS');?></th>
					</tr>
				</tfoot>
			</table>
			<div class="table-actions">
				<button class="btn btn-danger btn-small js-ajax-submit" type="submit" data-action="<?php echo U('Question/truedelete');?>" data-subcheck="true" data-msg="你确定删除吗？"><?php echo L('DELETE');?></button>
			</div>
			<div class="pagination"><?php echo ($page); ?></div>
		</form>
	</div>
	<script src="/junwei/public/js/common.js"></script>
	<script>
		setCookie('refersh_time', 0);
		function refersh_window() {
			var refersh_time = getCookie('refersh_time');
			if (refersh_time == 1) {
				window.location.reload();
			}
		}
		setInterval(function() {
			refersh_window()
		}, 2000);
	</script>


	<script type="text/javascript">
	function getLocalTime(nS) {
      return new Date(parseInt(nS) * 1000).toLocaleString().substr(0,17)
	  }; 
$(document).ready(function(){
	var onemenu="";
	var twomenu="";
		$.ajax({
            type: "get",
            url: "http://kf.junweiedu.cn/junwei/index.php?g=houtai&m=itembank&a=ztjk",
            dataType:"jsonp",
            jsonp: "callback",
            jsonpCallback:"successCallback", 
            success:function(data) {
            	var json=eval(data);
            	for(i in json){
            		//console.log(json[i]);
            		onemenu+="<span>"+json[i].eid+"年国家司法考试</span>";
            	}
            	$("#one-menu").html(onemenu);
                
               
            },
            error:function(st){
            	alert ("系统错误，错误码"+st.status);　
            }
            
        });
        
    $("#one-menu").on("click","span",function(){
    	var thistexts=$(this).text();
    	var thistext=thistexts.substring(0,4);
    	//alert (thistext);


    	$(this).addClass("kemu-active").siblings().removeClass("kemu-active");
    	//alert ($(this).text());
    	var twomenu="";
    	$.ajax({
            type: "get",
            url: "http://kf.junweiedu.cn/junwei/index.php?g=houtai&m=itembank&a=ztjk",
            dataType:"jsonp",
            jsonp: "callback",
            jsonpCallback:"successCallback", 
            success:function(data) {
            	var json=eval(data);
            	var res="";
            	for(i in json){
            		if(thistext==json[i].eid){
            			for(m in json[i].chapter){
            				//console.log(json[i].chapter[m]);
            				
            				 if(json[i].chapter[m].etid==0)
								  {
								    res='卷一';
								  }
								  else if(json[i].chapter[m].etid==1)
								  {
								   res='卷二';
								  }
								  else if(json[i].chapter[m].etid==2)
								  {
								   res='卷三';
								  };
            				twomenu+="<span>"+res+"</span>";
            			}
            				
            		}
            		
            		
            	}
            	$("#two-menu").html(twomenu);
                
               
            },
            error:function(st){
            	alert ("系统错误，错误码"+st.status);　
            }
            
        });
    	 	
    })
    


 $("#two-menu").on("click","span",function(){
            var eid="";
            var etid="";
            var this2text=$(this).text();
        	$(this).addClass("kemu-active").siblings().removeClass("kemu-active");
            //alert (this2text);


$.ajax({
            type: "get",
            url: "http://kf.junweiedu.cn/junwei/index.php?g=houtai&m=itembank&a=ztjk",
            dataType:"jsonp",
            jsonp: "callback",
            jsonpCallback:"successCallback", 
            success:function(data) {
                var json=eval(data);
                var res="";
                console.log(json);
                for(i in json){
		                    
                        for(m in json[i].chapter){

                        		if(json[i].chapter[m].etid==0)
								  {
								    res='卷一';
								  }
								  else if(json[i].chapter[m].etid==1)
								  {
								   res='卷二';
								  }
								  else if(json[i].chapter[m].etid==2)
								  {
								   res='卷三';
								  };

                            if (this2text==res) {
                               eid=json[i].eid;
                               etid=json[i].chapter[m].etid;
                               senddata(eid,etid);

                            }
                        }
                     
                }
               
                


               
            },
            error:function(st){
                alert ("系统错误，错误码"+st.status);　
            }
            
        });

  function senddata(eid,etid){
    console.log("sid:"+eid+"cid:"+etid);

    $.ajax({
            type: "get",
            url: "http://kf.junweiedu.cn/junwei/index.php?g=houtai&m=itembank&a=trueitembank&eid="+eid+"&etid="+etid,
            dataType:"jsonp",
            //jsonp: "callback",
            //jsonpCallback:"successCallback", 
            success:function(data) {
                var fhdata=eval(data);
                var tabledatahtml="";
                var re;
				var time;
				var id;
				var url;
				var res;
                for(i=0;i<data.length;i++){
                    console.log(data[i]);
				  if(data[i].te_type==1)
				  {
				    re='多选';
				  }
				  else if(data[i].te_type==0)
				  {
				   re='单选';
				  };

				  if(data[i].etid==0)
				  {
				    res='卷一';
				  }
				  else if(data[i].etid==1)
				  {
				   res='卷二';
				  }
				  else if(data[i].etid==2)
				  {
				   res='卷三';
				  };
				 id=data[i].item_id;
				 delurl="Question/truedelete?item_id="+id;
				 editurl="Question/trueedit?item_id="+id;
				 //alert(url);
				 time=getLocalTime(data[i].itime);
                 
                 // tabledatahtml+="<tr><td><input type='checkbox' class='js-check' data-yid='js-check-y' data-xid='js-check-x' name='item_ids[]'' value="+data[i].item_id+"></td><td><a>"+data[i].no+"</a></td><td><a href='#'>"+data[i].question+"</a></td><td>"+data[i].eid+"年国家司法考试"+res+"试题</td><td>"+time+"</td><td>"+re+"</td><td><a href='"+editurl+"' class='js-ajax-delete'><?php echo L('EDIT');?></a>|<a href='"+delurl+"' class='js-ajax-delete'><?php echo L('DELETE');?></a></td></tr>"

                 tabledatahtml+="<tr><td><input type='checkbox' class='js-check' data-yid='js-check-y' data-xid='js-check-x' name='item_ids[]'' value="+data[i].item_id+"></td><td><a>"+data[i].no+"</a></td><td><a href='#'>"+data[i].question+"</a></td><td>"+data[i].eid+"年国家司法考试"+res+"试题</td><td>"+time+"</td><td>"+re+"</td><td><a href='"+editurl+"' class='js-ajax-delete'><?php echo L('EDIT');?></a>|<a href='"+delurl+"' class='js-ajax-delete'><?php echo L(DELETE);?></a></td></tr>"




 
			   }
                $("tbody").html(tabledatahtml);

               
               
            },
            error:function(st){
                alert ("发送数据失败，错误码"+st.status);　
            }
            
        });





  }

})        
})
</script>
</body>
</html>