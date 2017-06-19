<?php
/**
 * 登录，注册，重置密码，忘记密码接口
 */
namespace Api\Controller;

use Think\Controller;

class UserController extends Controller 
{  
  
  
  /**
   * 注册验证码接口调用
   * @return [type] [description]
   */
     public function rcheck()
  { 
  	    vendor('SMS.CCPRestSmsSDK');
		$to=I('phone');
		$da=rand(100000,999999);
		$time="10分钟";
        $datas=array($da,$time);
	    $tempId='163899';
		$user=M('Users');
		$res=$user->where("mobile=$to")->find();
		//$oldtime=$res['ctime'];
		//$newtime=time();
		//$time=$newtime-$oldtime;
		if($res)
		{   
			$dat['status']=0;
            $dat['msg']="手机号已经注册！";
			echo json_encode($dat);die;
		}
	    else
		{   
				$dt=session("$to");
				$oldtime=$dt['ctime'];
				$newtime=time();
				$time=$newtime-$oldtime;
				if($time>60)
				{
					$this->sendTemplateSMS($to,$datas,$tempId);
				}
				else
				{
					$dat['status']=0;
					$dat['msg']="请勿频繁请求！";
					echo json_encode($dat);die;
				}
			
		}
  } 
	    
     /**
      * 短信验证码接口
      * @param  [type] $to     [description]
      * @param  [type] $datas  [description]
      * @param  [type] $tempId [description]
      * @return [type]         [description]
      */
	  function sendTemplateSMS($to,$datas,$tempId)
	{     
	     global $accountSid,$accountToken,$appId,$serverIP,$serverPort,$softVersion;
	     $accountSid= '8a216da85967959d01597290bf7906db';
		 $accountToken= '7437116bd490437cb14e51b070566c8a';
		 $appId='8a216da85967959d01597290bfc206e0';
		 $serverIP='app.cloopen.com';
		 $serverPort='8883';
		 $softVersion='2013-12-26';
	     $rest = new \REST($serverIP,$serverPort,$softVersion);
	     $rest->setAccount($accountSid,$accountToken);
	     $rest->setAppId($appId);
	    
	     // 发送模板短信
	     //echo "Sending TemplateSMS to $to <br/>";
	     $result = $rest->sendTemplateSMS($to,$datas,$tempId);
	     if($result == NULL ) {
	         echo "result error!";
	         die;
	     }
	     if($result->statusCode!=0) 
		 {    
	         $a=$result->statusMsg;
			 //$xml = simplexml_load_string($a);
			  $re = json_encode($a);
			  $qq = json_decode($re,true);
	     	  $data['status']=0;
	     	  $data['msg']=$qq[0];
	     	  echo json_encode($data);die;
	         // echo "error code :" . $result->statusCode . "<br>";
	         // echo "error msg :" . $result->statusMsg . "<br>";
	         //TODO 添加错误处理逻辑
	     }
		 else
		 {      
				   $user=M('Users');
				   $dat['check']=$datas['0'];
				   $dat['ctime']=time();		
				   session("$to",$dat);
                   $re=$user->add($dat);
                   if($re)
				   {
					   $data['status']=1;
					   $data['msg']="验证码发送成功";
					   $data['id']=$re;
					   echo json_encode($data);die;
				   }				   
				   else
				   {
					   $data['status']=0;
					   $data['msg']="验证码发送失败";
					   echo json_encode($data);die;
				   }
	         // echo "Sendind TemplateSMS success!<br/>";
	         // // 获取返回信息
	         // $smsmessage = $result->TemplateSMS;
	         // echo "dateCreated:".$smsmessage->dateCreated."<br/>";
	         // echo "smsMessageSid:".$smsmessage->smsMessageSid."<br/>";
	         //TODO 添加成功处理逻辑
	     }
	}

	/**
	 * 用户注册
	 */
	public function register () 

	{      
		   $user=M('Users');
		   $data['mobile']=I('phone');//手机号
		   $to=$data['mobile'];
		   $id=I("id");
		   $check=I('check'); //短信验证码
		   $re=$user->where("id=$id")->find();
		   $rcheck=$re['check'];
		   $oldtime=$re['ctime'];
		   $newtime=time();
		   $pas=I('password');//密码
           $data['user_pass']=sp_password($pas);
		   $data['token']=sp_random_string(20);
		   $data['create_time']=date('Y-m-d H:i:s',time());
		   //验证码验证
		  $rec=$user->where("mobile=$to")->find();
		  if($newtime-$oldtime>600)
		  {
			             $dat['msg']="验证码已过期，请重新获取！";
						 $dat['status']=0;
						 echo json_encode($dat);die;
		  }
		  else
		  {
			  if($rec)
				  {
						 $dat['msg']="该账号已存在，请重新输有效入手机号！";
						 $dat['status']=0;
						 echo json_encode($dat);die;
				  }
				  else
				  {
					   if($check==$rcheck)
					   {
								  $rec=$user->where("id=$id")->save($data);
								  if($rec)
								  {
									 $dat['msg']="注册成功！";
									 $dat['status']=1;
									 echo json_encode($dat);die;
								  }
								  else
								  {
									 $dat['msg']="注册失败！";
									 $dat['status']=0;
									 echo json_encode($dat);die;
								  }
					   }
					   else
					   {     
							 $dat['msg']="手机号与验证码不符，请重新输入！";
							 $dat['status']=0;
							 echo json_encode($dat);die;
					   }
				  }
		  }
				  
		  
		   

	}

	/**
	 * 用户登陆
	 */
	
   public function login()
   {
   	$user=M('Users');
   	$phone=I('phone');
   	$pas=I('password');
   	$data['token']=sp_random_string(20);
    $token=$data['token'];
   	$password=sp_password($pas);
   	 $re=$user->where("token='$token'")->find();
		   if($re)
		   {
		      $data['token']=sp_random_string(20);
		   }
	     $a=$user->where("mobile=$phone")->save($data);
	     $result=$user->where("mobile='$phone'")->find();
    if(empty($phone)||empty($pas))
	{
	  	                 $dat['msg']="账号密码不能为空！";
						 $dat['status']=0;
						 echo json_encode($dat);die;
	}
	else
	{
			if($result)
		{
			$where="mobile='$phone' and `user_pass`='$password'";
		
			 $res=$user->where($where)->find();
			if($res)
			{            
						 // $dat['last_login_ip']=get_client_ip(0,true);
					  //    $ip=$dat['last_login_ip']; 登陆者IP
						 
						 $dat['msg']="登陆成功！";
						 $dat['data']=$res;
						 $dat['status']=1;
						 echo json_encode($dat);die;
			}
			else
			{
						 $dat['msg']="用户名或密码错误！";
						 $dat['status']=0;
						 echo json_encode($dat);die;
			}
		}
		else
		{
						 $dat['msg']="该账号不存在，请注册！";
						 $dat['status']=0;
						 echo json_encode($dat);die;
		}
	}
    
	     
   }
   /**
    * 密码修改
    */
   public function reset()
   {
   	$user=M('Users');
   	$token=I('token');
   	$oldpas=I('oldpas');
	$oldpas=sp_password($oldpas);
   	$newpas=I('newpas');
	$dat['user_pass']=sp_password($newpas);
	
    $res=$user->where("token='$token'")->find();
    if($res)
    {   
          $re=$user->where("token='$token' and user_pass='$oldpas'")->find();
		  //var_dump($re);die;
      if($re)
	  {
		  $rec=$user->where("token='$token'")->save($dat); 
			if($rec)
			{        
					 $data=$user->where("token='$token'")->find();
					 $dat['msg']="密码修改成功！";
					 $dat['status']=1;
					 echo json_encode($dat);die;
			}
			else
			{
					 $dat['msg']="密码修改失败！";
					 $dat['status']=0;
					 echo json_encode($dat);die;
			} 
	  }
	  else
	  {
		         $dat['msg']="密码错误，请输入正确的密码";
                 $dat['status']=0;
                 echo json_encode($dat);die;
	  }
        
    }
    else
    {
    	         $dat['msg']="身份验证失败!请重新登陆！";
                 $dat['status']=0;
                 echo json_encode($dat);die;
    }
   }
   /**
    *下一步
	*/
	public function next()
	{   
	    $user=M('Users');
		$phone=I('phone');
		$check=I('check');
		$res=$user->where("`mobile`='$phone' and `check`='$check'")->find();
		if($res)
		{
			         $dat['msg']="请求成功！";
	                 $dat['status']=1;
					 $dat['phone']=$phone;
	                 echo json_encode($dat);die;
		}
		else
		{
			        $dat['msg']="请求失败！";
	                 $dat['status']=0;
	                 echo json_encode($dat);die;
		}
	}
   /**
    * 忘记密码
    */
   public function forget()
   {  $user=M('Users');
      $phone=I('phone');
	   //echo json_encode($phone);die;
      $pas=I('password');
      $rpas=I('rpassword');
      //验证码验证
	  
	  if(empty($phone))
	  {
		             $dat['msg']="手机号不能为空！";
					 $dat['status']=0;
					 echo json_encode($dat);die;
	  }
	  else
	  {
		  $resu=$user->where("mobile='$phone'")->find();
		  if($resu)
		  {
			   
				$oldtime=$resu['ctime'];
				$newtime=time();
				$time=$oldtime-$newtime;
				if($time>600)
				{
					 $dat['msg']="验证码已过期，请重新获取！！";
					 $dat['status']=0;
					 echo json_encode($dat);die;
				}
				else
				{
					if($pas==$rpas)
				  {
					 $data['user_pass']=sp_password($pas);
					 $re=$user->where("mobile='$phone'")->save($data);
					 //var_dump($re);die;
					 if($re)
					 {
						 $dat['msg']="密码重置成功！";
						 $dat['status']=1;
						 echo json_encode($dat);die;
					 }
					 else
					 {
						 $dat['msg']="密码已存在，请重新输入！";
						 $dat['status']=0;
						 echo json_encode($dat);die;
					 }
				  }
				  else
				  {
					 $dat['msg']="密码不一致！";
					 $dat['status']=0;
					 echo json_encode($dat);die;
				  }   
				}
				  
		  }
		  else
		  {
					 $dat['msg']="账号不存在！！";
					 $dat['status']=0;
					 echo json_encode($dat);die;
		  }
	  }
	  
		  
   }
 /**
    * 忘记密码短信接口
    */
    public function fcheck()
  { 
  	    vendor('SMS.CCPRestSmsSDK');
		$to=I('phone');
		$da=rand(100000,999999);
		$time="10分钟";
        $datas=array($da,$time);
	    $tempId='163899';
		$user=M('Users');
		$res=$user->where("mobile='$to'")->find();
		if($res)
		{   
	        $oldtime=$res['ctime'];
				$newtime=time();
				$time=$newtime-$oldtime;
				if($time>60)
				{
					$this->sendTemplateSMF($to,$datas,$tempId);
				}
				else
				{
					$dat['status']=0;
					$dat['msg']="请勿频繁请求！";
					echo json_encode($dat);die;
				}	
			
		}
	    else
		{   
			$dat['status']=0;
            $dat['msg']="手机号不存在，注册账号";
			echo json_encode($dat);die;	
			
		}
  } 
	    
     /**
      * 短信验证码接口
      * @param  [type] $to     [description]
      * @param  [type] $datas  [description]
      * @param  [type] $tempId [description]
      * @return [type]         [description]
      */
	  function sendTemplateSMF($to,$datas,$tempId)
	{     
	     global $accountSid,$accountToken,$appId,$serverIP,$serverPort,$softVersion;
	     $accountSid= '8a216da85967959d01597290bf7906db';
		 $accountToken= '7437116bd490437cb14e51b070566c8a';
		 $appId='8a216da85967959d01597290bfc206e0';
		 $serverIP='app.cloopen.com';
		 $serverPort='8883';
		 $softVersion='2013-12-26';
	     $rest = new \REST($serverIP,$serverPort,$softVersion);
	     $rest->setAccount($accountSid,$accountToken);
	     $rest->setAppId($appId);
	    
	     // 发送模板短信
	     //echo "Sending TemplateSMS to $to <br/>";
	     $result = $rest->sendTemplateSMS($to,$datas,$tempId);
	     if($result == NULL ) {
	         echo "result error!";
	         die;
	     }
	     if($result->statusCode!=0) 
		 {    
	         $a=$result->statusMsg;
			 //$xml = simplexml_load_string($a);
			  $re = json_encode($a);
			  $qq = json_decode($re,true);
	     	  $data['status']=0;
	     	  $data['msg']=$qq[0];
	     	  echo json_encode($data);die;
	         // echo "error code :" . $result->statusCode . "<br>";
	         // echo "error msg :" . $result->statusMsg . "<br>";
	         //TODO 添加错误处理逻辑
	     }
		 else
		 {      
				   $user=M('Users');
				   $dat['check']=$datas['0'];
				   $dat['ctime']=time();		
                   $re=$user->where("mobile='$to'")->save($dat);
                   if($re)
				   {
					   $data['status']=1;
					   $data['msg']="验证码发送成功";
					   echo json_encode($data);die;
				   }				   
				   else
				   {
					   $data['status']=0;
					   $data['msg']="验证码发送失败";
					   echo json_encode($data);die;
				   }
	         
	     }
	}
	
}