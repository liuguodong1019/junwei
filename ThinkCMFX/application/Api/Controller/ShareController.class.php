<?php
/**
 * Api分享
 */
namespace Api\Controller;

use Think\Controller;

class ShareController extends Controller 
{
	public function share()
	{

		//获取域名或主机地址 
		$_SERVER['HTTP_HOST']."<br>"; #localhost

		//获取网页地址 
		$_SERVER['PHP_SELF']."<br>"; #/blog/testurl.php

		//获取网址参数 
		$_SERVER["QUERY_STRING"]."<br>"; #id=5

		//获取用户代理 
		$_SERVER['HTTP_REFERER']."<br>"; 

		//获取完整的url
		//echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		$url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
		#http://localhost/blog/testurl.php?id=5
		$data['url']=$url;
        echo json_encode($url);die;
		//包含端口号的完整url
		//echo 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"]; 
		#http://localhost:80/blog/testurl.php?id=5

		//只取路径
		//$url='http://'.$_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"]; 
		//echo dirname($url);
		#http://localhost/blog
	}
}