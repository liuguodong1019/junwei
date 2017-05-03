<?php
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
		$to=I('post.phone');
		$da=rand(100000,999999);
		$time="1分钟";
        $datas=array($da,$time);
	    $tempId='163899';
	    $this->sendTemplateSMS($to,$datas,$tempId);
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
	     if($result->statusCode!=0) {
	     	  $data['status']=0;
	     	  $data['msg']=$result->statusMsg;
	     	  return json_encode($data);
	         // echo "error code :" . $result->statusCode . "<br>";
	         // echo "error msg :" . $result->statusMsg . "<br>";
	         //TODO 添加错误处理逻辑
	     }else{
	     	  $dat['mobile']=$to;
	     	  $dat['check']=$datas['0'];;
	     	  $user=M('Users');
	     	  $res=$user->add($dat);
	     	  if($res)
	     	  {
		     	  $data['status']=1;
		     	  $data['msg']="验证码发送成功";
		     	  return json_encode($data);
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
		   $phone=$data['mobile'];
		   $check=I('check'); //短信验证码
		   $pas=I('password');//密码
           $data['user_pass']=sp_password($pas);
		   $data['token']=sp_random_string(20);
		   $token=$data['token'];
		   $data['create_time']=date('Y-m-d H:i:s',time());
		   $res=$user->where("mobile")->find();
		   //token防止重复
		   //验证码验证
		   $res=$user->where("mobile=$phone AND check=$check")->find();
		   if($res)
		   {
			   	if($re)
			   {
	              $data['token']=sp_random_string(20);
			   }
			   else
			   {
		              $res=$user->where("mobile=$phone")->find();
		              if($res)
		              {
		                 $dat['msg']="手机号重复，请重新输入！";
		                 $dat['status']=0;
		                 return json_encode($dat);
		              }
		              $rec=$user->add($data);
		              if($rec)
		              {
		                 $dat['msg']="注册成功！";
		                 $dat['status']=1;
		                 return json_encode($dat);
		              }
		              else
		              {
		              	 $dat['msg']="注册失败！";
		                 $dat['status']=0;
		                 return json_encode($dat);
		              }
			   }
		   }
		   else
		   {
   	             $dat['msg']="验证码错误，请重新输入！";
                 $dat['status']=0;
                 return json_encode($dat);
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
   	 $re=$user->where("token=$token")->find();
		   if($re)
		   {
		      $data['token']=sp_random_string(20);
		   }
	$user->where("mobile=$phone AND user_pass=$password")->save($data);
   	$res=$user->where("mobile=$phone AND user_pass=$password")->find();
   	if($res)
   	{            
		         // $dat['last_login_ip']=get_client_ip(0,true);
	          //    $ip=$dat['last_login_ip']; 登陆者IP
                 $dat['msg']="登陆成功！";
                 $dat['token']=$res['token'];
                 $dat['status']=1;
                 return json_encode($dat);
   	}
   	else
   	{
   		         $dat['msg']="登陆失败！";
                 $dat['status']=0;
                 return json_encode($dat);
   	}
   }
   /**
    * 密码重置
    */
   public function reset()
   {
   	$user=M('Users');
   	$token=I('token');
   	$oldpas=I('oldpas');
   	$newpas=I('nwepas');
    $res=$user->where("token=$token AND user_pass=$oldpas")->find();
    if($res)
    {   $dat['user_pass']=$newpas;
        $rec=$user->where("token=$token")->save($dat); 
        if($rec)
        {
                 $dat['msg']="重置成功！";
                 $dat['status']=1;
                 return json_encode($dat);
        }
        else
        {
        	     $dat['msg']="重置失败！";
                 $dat['status']=0;
                 return json_encode($dat);
        } 
    }
    else
    {
    	         $dat['msg']="用户不存在";
                 $dat['status']=0;
                 return json_encode($dat);
    }
   }
   /**
    * 忘记密码
    */
   public function forget()
   {  $user=M('Users');
      $phone=I('phone');
      $check=I('check'); //短信验证码
      $pas=I('password');
      $rpas=I('rpassword');
      //验证码验证
		   $res=$user->where("mobile=$phone AND check=$check")->find();
		if($res)
		{
              if($pas==$rpas)
              {
                 $data['user_pass']=sp_password($pas);
                 $rec=$use->where("mobile=$phone")->save($data);
                 if($rec)
                 {
                   $dat['msg']="密码重置成功！";
	                 $dat['status']=1;
	                 return json_encode($dat);
                 }
                 else
                 {
                 	 $dat['msg']="密码重置失败！";
	                 $dat['status']=0;
	                 return json_encode($dat);
                 }
              }
              else
              {
              	 $dat['msg']="密码不一致！";
                 $dat['status']=0;
                 return json_encode($dat);
              }   
		}
		else
		{
                 $dat['msg']="验证失败！";
                 $dat['status']=0;
                 return json_encode($dat);
		}
   }
   /**
   * 忘记密码验证码接口调用
   * @return [type] [description]
   */
     public function fcheck()
  {
  	    vendor('SMS.CCPRestSmsSDK');
		$to=I('post.phone');
		$da=rand(100000,999999);
		$time="1分钟";
        $datas=array($da,$time);
	    $tempId='163899';
	    $this->senTemplateSMS($to,$datas,$tempId);
  } 
	    
     /**
      * 短信验证码接口
      * @param  [type] $to     [description]
      * @param  [type] $datas  [description]
      * @param  [type] $tempId [description]
      * @return [type]         [description]
      */
	  function senTemplateSMS($to,$datas,$tempId)
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
	     if($result->statusCode!=0) {
	     	  $data['status']=0;
	     	  $data['msg']=$result->statusMsg;
	     	  return json_encode($data);
	         // echo "error code :" . $result->statusCode . "<br>";
	         // echo "error msg :" . $result->statusMsg . "<br>";
	         //TODO 添加错误处理逻辑
	     }else{
	     	  $mobile=$to;
	     	  $dat['check']=$datas['0'];;
	     	  $user=M('Users');
	     	  $res=$user->save("mobile=$mobile")->($dat);
	     	  if($res)
	     	  {
		     	  $data['status']=1;
		     	  $data['msg']="验证码发送成功";
		     	  return json_encode($data);
	     	  }
	     	  
	         // echo "Sendind TemplateSMS success!<br/>";
	         // // 获取返回信息
	         // $smsmessage = $result->TemplateSMS;
	         // echo "dateCreated:".$smsmessage->dateCreated."<br/>";
	         // echo "smsMessageSid:".$smsmessage->smsMessageSid."<br/>";
	         //TODO 添加成功处理逻辑
	     }
	}
}