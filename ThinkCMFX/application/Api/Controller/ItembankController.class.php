<?php
/**
 * Api题库接口
 */
namespace Api\Controller;

use Think\Controller;

class ItembankController extends Controller 
{  
     /**
    * 数组去重方法
    */
   public function uniqid($arr)
   {
   	 foreach ($arr as $key=>$v){
	  $v=join(',',$v); //降维,也可以用implode,将一维数组转换为用逗号连接的字符串
	  $temp[]=$v;
	 }
	 $temp=array_unique($temp); //去掉重复的字符串,也就是重复的一维数组
	 foreach ($temp as $k => $v){
	  $temp[$k]=explode(',',$v); //再将拆开的数组重新组装
	 }
	 return $temp;die;
   }
   /**
    * 分页方法
    * $p：获取的页码，
    * $pageSize:每一页所显示数据条数。
    */
   public function page($arr,$p,$pageSize) {
            $count = count($arr);//总记录数
            $pagecount = ceil($rows/$pagesize);//总页数
            $Page = new \Think\Page($count,$pageSize);
            $start=($p- 1) *$pageSize;
            $length= $pageSize;//每页显示条数
            $cut_qa=  array_slice($arr, $start, $length, true);//返回的数据
            $page = $Page->show();
            
            $res=array(
                'list'=>$cut_qa,
                'pagecount'=>$count
            );
            return $res;
    }
	/**
	 * 历年真题答题试卷列表/试卷类型
   * 
	 * @return [type] [description]
	 */
	public function list()
	{ 
	  $item=M("Itembank");
	  $list=$item->where("type=1")->field("eid")->order("eid desc")->select();//查询所有试卷标题
	  if($list)
	  { 
        $li=$this->uniqid($list);//试卷标题去重
        foreach ($li as $key=>$value)
        {
             foreach ($value as $k=>$v)
             {  
                $arr[$key]['eid']=$v;//试卷标题
             }        
        }
		$array=array_values($arr);
		foreach($array as$key=>$v)
		{   
			foreach($v as $value)
			{
				$re=$item->where("type='1' and eid='$value'")->field("etid")->order("etid asc")->select();//查询所有试卷类型
				$re=$this->uniqid($re);//试卷类型去重
				$re=array_values($re);
				foreach($re as$n=>$e)
				{
					foreach($e as $m)
					{
						$abc[$n]['etid']=$m;
						
					}
				}
				foreach ($abc as $a=>$b)
					{     
						 foreach ($b as $c)
						 { 
							$rec=$item->where("eid='$value' and etid='$c'")->count();//获取试卷下的试卷条数
							$arra[$a]=$b;
							$arra[$a]['count']=$rec;
							
						 }        
				            $res[$key]=$v;
				            $res[$key]['sub_items']=$arra;
							$res=array_values($res);	
					}
			}
		}
        $data['status']=1;
        $data['data']=$res;
        $data['msg']="请求成功";
        echo json_encode($data);die;
	  }
	  else
	  {
	  	$data['status']=0;
        $data['msg']="请求失败";
        echo json_encode($data);die;
	  }
     }
     /**
      * 法考科目试卷科目列表/章节列表
      */
    public function kmlist()
  { 
    $subjects=M('subjects');
	$chapter=M('chapter');
	$item=M('itembank');
	$res=array();
    $res=$subjects->field("sid,stitle")->order("sid asc")->select();//科目

	foreach($res as $k=>$v)
	{       
		    $sid=$v['sid'];
            $stitle=$v['stitle'];			
			$re=$chapter->where("sid='$sid'")->field("cid,ctitle")->order("cid asc")->select();//章节
			$arr[$k]['sid']=$sid;
			$arr[$k]['stitle']=$stitle;
			$arr[$k]['cid']=$re;
			
	}
	  foreach($arr as $key=>$value)
	  {
		$sid=$value['sid'];
		$stitle=$value['stitle'];
		$data=$value['cid'];
		foreach($data as $k1=>$v1)
		{
		   $cid=$v1['cid'];
		   $rec=$item->where("sid='$sid' and cid='$cid'")->count();
		   //$ace[$key]['sid']=$sid;
		   //$ace[$key]['stitle']=$stitle;
		   $ace[$key][$k1]=$v1;
		   $ace[$key][$k1]['count']=$rec;
		   $data=array_values($ace[$key]);
		   
		   
		} 
        $resu[$key]['sid']=$sid;
        $resu[$key]['stitle']=$stitle;
        $resu[$key]['sub_items']=$data;		
	  }
    if($ace)
    {
        $dat['data']=$resu;
		$dat['status']=1;
        $dat['msg']="请求成功";
        echo json_encode($dat);die;
    }
    else
    {
        $data['status']=0;
        $data['msg']="请求失败";
        echo json_encode($data);die;
    }
  }
   /**
    * 真题题目请求
    */
   public function zttimu()
   { 
     //($page == "")? $page = 1 : $page = I('page'); //初始化页码
      $eid=I('eid');
      $etid=I('etid');
	  $uid=I('uid');
	  switch ($etid)
		{
		case 0:
		  $name='卷一';
		  break;
		case 1:
		  $name='卷二';
		  break;
		case 2:
		  $name='卷三';
		  break;
		}
	  $name=$eid."年国家司法考试".$name."试题";
	  $sheet=M('sheet');
	  $ic=M('item_collection');//实例化收藏表
	  $shou=$ic->where("eid='$eid' and etid='$etid' and uid='$uid'and status=1")->getField("status");
	  if(empty($shou)||$shou==0)
	  {
		$dat['collection_type']=0;  
	  }
	  else
	  {
		$dat['collection_type']=1;  
	  }
     $where="eid='$eid' and etid='$etid'";
     $list=$this->timu($where);
     if($list)
        {
		  $r=$sheet->where("eid='$eid' and etid='$etid'and uid='$uid'")->select();//查询记录表
		  if($r)
		  {  
	         
			 $time=time();
		     $time=date('Y-m-d H:i:s'); 
			 $data['sheet_time']=$time;
			 $result=$sheet->where("eid='$eid' and etid='$etid'and uid='$uid'")->save($data);
		  }
		  else
		  {   
	          $data['eid']=$eid;
			  $data['etid']=$etid;
			  $data['uid']=$uid;
			  $time=time();
		      $time=date('Y-m-d H:i:s');
		      $data['sheet_time']=$time;
	          $result=$sheet->add($data);
		  }
		  
		  if($result)
		  {
			  $dat['data']=$list;
			  $dat['status']=1;
			  $dat['name']=$name;
			  $dat['msg']="请求成功";
			  //print_r($dat);die;
			  echo json_encode($dat);die;  
		  }
		  else
		  {
			  $dat['status']=0;
			  $dat['msg']="咿呀！好像出问题了( •̀ ω •́ )！";
			  echo json_encode($dat);die;   
		  }
        }
        else
        {
          $dat['status']=0;
          $dat['msg']="暂无试题";
          echo json_encode($dat);die;
        }

   }
   /**
    * 法考科目题目请求
    */
   public function kmtimu()
   { 
    //($page == "")? $page = 1 : $page = I('page'); //初始化页码
	  $subjects=M('subjects');//实例化科目表
	  $chapter=M('chapter');  //实例化章节表	  
	  $sheet=M('sheet');  //实例化记录表
	  $ic=M('item_collection');//实例化收藏表
      $sid=I('sid');
      $cid=I('cid');
	  $uid=I('uid');
	  $shou=$ic->where("sid='$sid' and cid='$cid'and uid='$uid'and status=1")->getField("status");
	  if(empty($shou)||$shou==0)
	  {
		$dat['collection_type']=0;  
	  }
	  else
	  {
		$dat['collection_type']=1;  
	  }
	  $stitle=$subjects->where("sid='$sid'")->getField("stitle");
	  $ctitle=$chapter->where("cid='$cid'")->getField("ctitle");
	  $name=$ctitle."试题";

     $where="sid='$sid' and cid='$cid'";
     $list=$this->timu($where);
     if($list)
        { 
		  $r=$sheet->where("sid='$sid' and cid='$cid'and uid='$uid'")->select();//查询记录表
		  if($r)
		  {
			 $time=time();
		     $time=date('Y-m-d H:i:s'); 
			 $data['sheet_time']=$time;
			 $result=$sheet->where("sid='$sid' and cid='$cid'and uid='$uid'")->save($data);
		  }
		  else
		  {   
	          $data['sid']=$sid;
		      $data['cid']=$cid;
		      $data['uid']=$uid;
			  $time=time();
		      $time=date('Y-m-d H:i:s');
		      $data['sheet_time']=$time;
	          $result=$sheet->add($data);
		  }
		  if($result)
		  {
			  $dat['data']=$list;
			  $dat['name']=$name;
			  $dat['status']=1;
			  $dat['msg']="请求成功";
			  echo json_encode($dat);die;  
		  }
		  else
		  {
			  $dat['status']=0;
			  $dat['msg']="咿呀！好像出问题了( •̀ ω •́ )！";
			  echo json_encode($dat);die;   
		  }
          
        }
        else
        {
          $dat['status']=0;
          $dat['msg']="暂无试题";
          echo json_encode($dat);die;
        }

   }
	 /**
	  *题目列表
	  */
	 public function timu($where)
	 {  
	 	$item=M("Itembank");
	 	$option=M("Option");
	 	$material=M("material");
        $ans=M('Answer');
        $list=$item
        ->field('item_id,question,ncertain,no,material_id,point,parsing,te_type')
        ->where($where)
        ->order("no asc")
        ->select();
        foreach($list as $k=>$v)
        { 
          $id=$v['item_id'];
          $mid=$v['material_id'];
          $nc=$v['ncertain'];
          $ty=$v['te_type'];
          if($mid!==null)
          {
           $content=$material
            ->field('content')
            ->where("material_id='$mid'")
            ->find();
            $content=$content['content'];
            $array[$k]=$v;
            $array[$k]['content']=$content;
          }
          else
          {
            $array[$k]=$v;
            $array[$k]['content']='';
          }
          $options=$option
            ->field('option_id,options,key')
	        ->where("qid='$id'")
	        ->order("option_id asc")
	        ->select();
	        $array[$k]['option']=$options;
	        if($nc==1)
	        {
	          $array[$k]['part_type']=2;
	        }
	        elseif($nc==0 && $ty==1)
	        {
              $array[$k]['part_type']=1;
	        }
	         elseif($nc==0 && $ty==0)
	        {
              $array[$k]['part_type']=0;
	        }

          $answer=$ans
            ->where("qid='$id'")
            ->order('option_id asc')
            ->getField('option_id',true);
              $answ=implode(",",$answer);
              $an=str_replace(array("0","1","2","3"),array("A","B","C","D"),$answ);
              $array[$k]['answer']=$an;
         
        }
        return $array;
        
	 }
	/**
	 * 试题提交试题提交情况
	 */
	public function submit()
	{
		$data=I();
        foreach($data as $k=>$v)
        {
           foreach ($v as $key=>$value)
           {  
             $answer=$value['answer'];
             if(empty($answer))
             {
                $array[$k]=$v;
                $array[$k]['type']=0;
             }
             else
             {
                $array[$k]=$v;
                $array[$k]['type']=1;
             }
           }
        }
        if($array)
        {
          $dat['data']=$array;
          $dat['status']=1;
          $dat['msg']="请求成功!";
          echo json_encode($dat);die;
        }
        else
        {
          $dat['status']=0;
          $dat['msg']="请求失败！";
          echo json_encode($dat);die;
        }
        
	}
  /**
   * 答案校对
   */
  public function check()
  {
     $answer=I('post.answer');
	// $ceshi=M('ceshi');
	// $data['content']=$answer;
	// $re=$ceshi->add($data);
	//echo json_encode($re);die;
	$str=html_entity_decode($answer,ENT_QUOTES); //html 单双引号符号转译
    //print_r($str);die;
	$data=json_decode($str,true);
    //print_r($data);die;
	foreach($data as $k=>$v)
	{   
		foreach($v as $key=>$value)
		{
			foreach($value as $ke=>$va)
			{
				$ans=M('Answer');
				$id=$ke;
				$answ1=$va;
				$answer2=$ans
					->where("qid='$id'")
					->order('option_id asc')
					->getField('option_id',true);
					  $answ2=trim(implode("",$answer2));
					  $an=str_replace(array("0","1","2","3"),array("A","B","C","D"),$answ2);
				if($answ1==$an)
				   { 

					 $array[$ke]=1;//正确
				   }
				   elseif($answ1=='0')
				   {  

					  $array[$ke]=0;//没答
				   }
				   elseif($answ1=='0'||$answ1!=$an)
				   {

					  $array[$ke]=2;//错误  
				   }
			}
		}
	}
	 $ar=array_count_values($array);
	 $arr['kong']=$ar['0'];
	 $arr['dui']=$ar['1'];
	 $arr['cuo']=$ar['2'];
	 $count=count($array);
	 $dui=$arr['dui'];
	 $lv=$dui/$count;
	 $arra=array_values($array);//试题对错
     if($array)
        {
          $dat['data']=$arra;
          $dat['status']=1;
          $dat['msg']="请求成功!";
		  $dat['result']=$arr;
		  $dat['lv']=$lv;//正确率
          echo json_encode($dat);die;
        }
        else
        {
          $dat['status']=0;
          $dat['msg']="请求失败！";
          echo json_encode($dat);die;
        }
  }
}    