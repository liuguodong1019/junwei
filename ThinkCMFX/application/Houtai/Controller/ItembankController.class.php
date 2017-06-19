<?php
namespace Houtai\Controller;

use Think\Controller;

class ItembankController extends Controller 
{  
    //后台试题
     /**
	   *试题科目章节接口
	   */
	  public function kzjk()
	  { 
        $jsonp=I('get.callback');	  
	  	$subjects=M('subjects');
	  	$chapter=M('chapter');
	  	$res=$subjects
	  	->field('sid,stitle')
	  	->order('sid asc')
	  	->select();
	  	$arr=array();
	  	$data=array();
	  	foreach ($res as $k=>$v)
	  	{
           $sid=$v['sid'];
           $re=$chapter
		  	->field('cid,ctitle')
		  	->where("sid='$sid'")
		  	->order('cid asc')
		  	->select();
            $arr[$sid]=$re;
            $data[$sid]=$v;
	  	}
	  	foreach($data as $k=>$v)
	  	{   $da[$k]=$v;
            $da[$k]["chapter"]=$arr[$k];
	  	}
		 $dac=array_values($da);
	    echo $jsonp.'('.json_encode($dac).')';die; 
	  }
	/**
	  *试题列表请求
	  */
	  
	  public function itembank()
	  {   
		  $jsonp=I('get.callback');
		  $sid=I('get.sid');
		  $cid=I('get.cid');
		  $it=M('Itembank');//实例题库表
		  $su=M('Subjects');//实例科目表
		  $ch=M('Chapter');//实例章节表
		  $item=$it
			->join('cmf_chapter on cmf_itembank.cid = cmf_chapter.cid')
			->join('cmf_subjects on cmf_itembank.sid = cmf_subjects.sid')
			->order('itime asc')
			->where("cmf_itembank.sid='$sid' and cmf_itembank.cid='$cid'")
			->select();
		  $itemb=$jsonp.'('.json_encode($item).')';
		  echo $itemb;die;
	  }
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
	   * 真题试卷名称/类型请求接口
	   */
	public function ztjk()
	{ 
	  $jsonp=I('get.callback');
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
				            $res[$key]['chapter']=$arra;
							$res=array_values($res);	
					}
			}
		}
 

       
        echo $jsonp.'('.json_encode($res).')';die;
	  }
     }
	/**
	  *正题题列表请求
	  */
	  
	  public function trueitembank()
	  {   
		  $jsonp=I('get.callback');
		  $eid=I('get.eid');
		  $etid=I('get.etid');
		  $it=M('Itembank');//实例题库表
		  $item=$it
			->order('no asc')
			->where("eid='$eid' and etid='$etid'")
			->select();
		  $itemb=$jsonp.'('.json_encode($item).')';
		  echo $itemb;die;
	  }
}