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
		  	//print_r($re);
		  	foreach($re as $k1=>$v1)
		  	{ 
		  	  //echo $k1.";";
		  	  $a=trim($v1['ctitle']);
              $b=explode(" ",$a);

               $c[$k][$k1]=$b[0];
               $ti=array("第一章","第二章","第三章","第四章","第五章","第六章","第七章","第八章","第九章","第十章","第十一章"
               	,"第十二章","第十三章","第十四章","第十五章","第十六章","第十七章","第十八章","第十九章","第二十章","第二十一章","第二十二章","第二十三章"
               	,"第二十四章","第二十五章","第二十六章","第二十七章","第二十八章","第二十九章","第三十章","第三十一章","第三十二章","第三十三章","第三十四章","第三十五章"
               	,"第三十六章","第三十七章","第三十八章","第三十九章","第四十章","第四十一章","第四十二章","第四十三章","第四十四章","第四十五章","第四十六章","第四十七章"
               	,"第四十八章","第四十九章","第五十章");
               $huang=array("1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19","20","21","22","23","24"
               	,"25","26","27","28","29","30","31","32","33","34","35","36","37","38","39","40","41","42","43","44","45","46","47","48","49","50");
               //数组替换
               foreach($c as $k2=>$v2)
               {
                 $an=str_replace($ti,$huang,$v2);

               }
               $rec[$k][$k1]=$v1;
               $rec[$k][$k1]['key']=$an[$k1];
		  	}
		  	foreach($rec as $k3=>$v3)
		  	{
		      $sort = array_column($v3,'key');      
              array_multisort($sort, SORT_ASC, $v3);
              $arr[$sid]=$v3;
		  	}
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
			->order('no asc')
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