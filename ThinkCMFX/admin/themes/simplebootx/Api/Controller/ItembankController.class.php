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
	 * 历年真题答题试卷列表
   * 
	 * @return [type] [description]
	 */
	public function list()
	{ 
	  $item=M("Itembank");
	  $list=$item->where("type=1")->field("eid")->order("eid desc")->select();
	  if($list)
	  { 
        $li=$this->uniqid($list);
        foreach ($li as $key=>$value)
        {
             foreach ($value as $v)
             {
                $arr[$key]=$v;
                
             }        
        }
        $data['status']=1;
        $data['data']=$arr;
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
   * 历年真题试卷类型
   */
  
     public function sjlist()
  { 
    $eid=I('eid');
    $item=M("Itembank");
    $list=$item->where("type='1' and eid='$eid'")->field("etid")->order("etid asc")->select();
    if($list)
    { 
        $li=$this->uniqid($list);
        foreach ($li as $key=>$value)
        {
             foreach ($value as $v)
             {
                $arr[$key]=$v;
                $arr[$key]['eid']=$eid;
                
             }        
        }
        $data['status']=1;
        $data['data']=$arr;
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
      * 法考科目试卷科目列表
      */
    public function kmlist()
  { 
    $subjects=M('subjects');
    $res=$subjects->select();
    if($res)
    {
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
   * 法考章节列表
   */
  public function zjlist()
  {
    $sid=I('sid');
    $chapter=M('chapter');
    $res=$chapter->where("sid='$sid'")->select();
    if($res)
    {
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
    * 真题题目请求
    */
   public function zttimu()
   { 
     ($page == "")? $page = 1 : $page = I('page'); //初始化页码
      $eid=2016;//I('eid');
      $etid=0;//I('etid');
     $where="eid='$eid' and etid='$etid' and type='1'";
     $array=$this->timu($where,$page);
	 $res=$this->page($array,$page,1);
     $list=$res['list'];
     if($list)
        {
          $dat['data']=$res;
          $dat['status']=1;
          $dat['msg']="请求成功";
          echo json_encode($dat);die;
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
    ($page == "")? $page = 1 : $page = I('page'); //初始化页码
      $sid=I('sid');
      $cid=I('cid');
     $where="sid='$sid' and cid='$cid'";
     $res=$this->timu($where,$page);
     $list=$res['list'];
     if($list)
        {
          $dat['data']=$res;
          $dat['status']=1;
          $dat['msg']="请求成功";
          echo json_encode($dat);die;
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
            $array[$k]=$v;
            $array[$k]['content']=$content;
          }
          else
          {
            $array[$k]=$v;
            $array[$k]['content']=array();
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
          $dat['status']=1;
          $dat['msg']="请求失败！";
          echo json_encode($dat);die;
        }
        
	}
  /**
   * 答案校对
   */
  public function check()
  {
    $data=I();
    foreach ($data as $k => $v) {
      foreach ($v as $key => $value)
      { 
        $ans=M('Answer');
        $id=$value_['item_id'];
        $answer1=$value['answer'];
        $answ1=trim(implode(",",$answer));
        $answer2=$ans
            ->where("qid='$id'")
            ->order('option_id asc')
            ->getField('option_id',true);
              $answ2=trim(implode(",",$answer));
       if($answ1==$answ2)
       { 
         $array[$k]=$v;
         $array[$k]['type']=1;
       }
       else
       {  
          $array[$k]=$v;
          $array[$k]['type']=0;
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
          $dat['status']=1;
          $dat['msg']="请求失败！";
          echo json_encode($dat);die;
        }
  }
}    