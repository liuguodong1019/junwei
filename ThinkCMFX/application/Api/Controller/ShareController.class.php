<?php
/**
 * Api����
 */
namespace Api\Controller;

use Think\Controller;

class ShareController extends Controller 
{
	public function share()
	{

		//��ȡ������������ַ 
		$_SERVER['HTTP_HOST']."<br>"; #localhost

		//��ȡ��ҳ��ַ 
		$_SERVER['PHP_SELF']."<br>"; #/blog/testurl.php

		//��ȡ��ַ���� 
		$_SERVER["QUERY_STRING"]."<br>"; #id=5

		//��ȡ�û����� 
		$_SERVER['HTTP_REFERER']."<br>"; 

		//��ȡ������url
		//echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		$url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
		#http://localhost/blog/testurl.php?id=5
		$data['url']=$url;
        echo json_encode($url);die;
		//�����˿ںŵ�����url
		//echo 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"]; 
		#http://localhost:80/blog/testurl.php?id=5

		//ֻȡ·��
		//$url='http://'.$_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"]; 
		//echo dirname($url);
		#http://localhost/blog
	}
}