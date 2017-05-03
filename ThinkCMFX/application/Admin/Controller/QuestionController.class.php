<?php
/**
 * 题库管理
 */
namespace Admin\Controller;

use Common\Controller\AdminbaseController;

class QuestionController extends AdminbaseController
{
  protected $itembank_model;
	
	public function _initialize() {
		parent::_initialize();
		$this->itembank_model = D("Common/Itembank");
		$this->subjects_model = D("Common/Subjects");
		$this->chapter_model=D("Common/Chapter");
		$this->option_model=D("Common/Option");
		$this->answer_model=D('Common/Answer');
	}

   /**
    *试题列表
    */
   public function itembank()
   {    
   	    if(IS_POST){
   	    	$keyword=I('keyword');
   	    	empty($keyword)?"":$keyword;
	   	    $item=$this->itembank_model->where("'question' like '`%$keyword%`' AND type=0")->select();
			$this->assign("item",$item);
			$this->display();
	    }
	    else
	    {
		    $item=$this->itembank_model->where("type=0")->select();
		    //var_dump($item);die;
			$this->assign("item",$item);
			$this->display();
	    }
   }
   /**
    * 试题添加
    */
  public function itembankadd()
   { 
     $su=$this->subjects_model->select();
     $ch=$this->chapter_model->select();
     $this->assign("su",$su);
     $this->assign("ch",$ch);
   	 $this->display();  
   }
   /**
    * 试题添加提交
    */
  public function itembankadd_post()
  {
  	if(IS_POST){
			$data['te_type']=I('te_type');
			$data['op_type']=I('op_type');
			$data['sid']=I('sid');
			$data['question']=I('question');
			$data['info']=I('info');
			$data['parsing']=I('parsing');
			$data['itime']=time();
			$data['score']=I('score');
			$data['type']=I('type');
			$data['cid']=I('cid');
			$data['point']=I('point');
			$answer=I('answer');
			$data['difficulty']=I('difficultyFpos');
			$an=str_replace(array("A","B","C","D"),array("0","1","2","3"),$answer);
			$option=I('options');
			$op=explode(PHP_EOL,$option);
			$res=$this->itembank_model->add($data);
			//数组去空
			foreach( $op as $k=>$v){  
				    if( !$v )  
				        unset( $op[$k] );  
				}  
			if ($this->itembank_model->create()!==false){
				if ($res!==false) {
					$arr=array();
					$key=array(0,1,2,3);
					foreach($op as $key=>$v)
					{ 
                      $arr['options']=$v;
                      $arr['qid']=$res;
                      $arr['key']=$key;
                      $rec=$this->option_model->add($arr);
					} 
					     if($rec)
					     {   
                            $a=explode(',',$an);
                            $array=array();
                            foreach ($a as $value) 
                            {
                            	$array['qid']=$res;
                            	$array['option_id']=$value;
                            	$re=$this->answer_model->add($array);
                            }
					     	if($re)
						     {   
						     	$this->success(L('ADD_SUCCESS'), U("Question/itembank"));
						     }
						     else
						     {
						     	$this->error(L('ADD_FAILED'));
						     }
					     }
					     else
					     {
					     	$this->error(L('ADD_FAILED'));
					     }
					
				} else {
					$this->error(L('ADD_FAILED'));
				}
			} else {
				$this->error($this->professional_model->getError());
			}
		
		}
	}
		/**
	   * 试题删除
	   */
	  public function itembankdelete()
	  {
	  	  if(isset($_GET['item_id'])){
				$id = intval(I("get.item_id"));
				if ($this->itembank_model->where("item_id=$id")->delete()!==false&&
                    $this->option_model->where("qid=$id")->delete()!==false&&
                    $this->answer_model->where("qid=$id")->delete()!==false
					) {
					$this->success("删除成功！");
				} else {
					$this->error("删除失败！");
				}
			}
			if(isset($_POST['item_ids'])){
				$ids=join(",",$_POST['item_ids']);
				if ($this->itembank_model->where("item_id in ($ids)")->delete()!==false&&
                    $this->option_model->where("qid in ($ids)")->delete()!==false&&
                    $this->answer_model->where("qid in ($ids)")->delete()!==false
					) {
					$this->success("删除成功！");
				} else {
					$this->error("删除失败！");
				}
			}
	  }
	  /**
	   * 试题修改
	   */
	  public function itembankedit()
	  {
	  	$id=I("get.item_id",0,'intval');
		$item=$this->itembank_model->where(array('item_id'=>$id))->find();
		$option=$this->option_model->where(array('qid'=>$id))->select();
		//var_dump($option);die;
		$answer=$this->answer_model->where(array('qid'=>$id))->order('option_id asc')->getField('option_id',true);
		$answ=implode(",",$answer);
		$an=str_replace(array("0","1","2","3"),array("A","B","C","D"),$answ);
		$su=$this->subjects_model->select();
		$this->assign("su",$su);
		$this->assign("option",$option);
   	    $this->assign("item",$item);
   	    $this->assign('answer',$an);
		$this->display();
	  }
	 /**
	  * 试题修改提交
	  */
	 public function itembankedit_post()
	 {
	 	if (IS_POST) {
			$data=I();
			//var_dump($data);die;
			$id=$data['item_id'];
			unset($data['item_id']);
			unset($data['answer']);
			unset($data['options']);
			$answer=I('answer');
			$an=str_replace(array("A","B","C","D"),array("0","1","2","3"),$answer);
			$option=I('option');
			//var_dump($item);
			$data['itime']=time();
		    $re=$this->itembank_model->where("item_id=$id")->save($data);
			if ($this->itembank_model->create()!==false) {
				if ($re!==false) 
				{
					if($this->option_model->where("qid=$id")->delete()!==false&&
                       $this->answer_model->where("qid=$id")->delete()!==false)
					{
					   $key=array(0,1,2,3);
						foreach($option as $v)
						{ 
	                      $arr['options']=$v;
	                      $arr['qid']=$id;
	                      $arr['key']=$key;
	                      $rec=$this->option_model->add($arr);
						} 

					    if($rec)
					     {   
                            $a=explode(',',$an);
                            $array=array();
	                            foreach ($a as $value) 
	                            {
	                            	$array['qid']=$id;
	                            	$array['option_id']=$value;
	                            	$re=$this->answer_model->add($array);
	                            }
						     	if($re)
							     {   
							     	$this->success(L('保存成功！'), U("Question/itembank"));
							     }
							     else
							     {
							     	$this->error(L('保存失败！'));
							    }

						} 
						else 
						{
						    $this->error("保存成功！");
					    }
					 }
					else 
					{
					    $this->error("保存失败！");
				    }
					
				} 
				else {
					$this->error("保存失败！");
				}
			} else {
				$this->error($this->itembank_model->getError());
			}
		}

	 }
/**
 * <---------------------------------------------------真题管理-------------------------------------------------------------------------->
 */
  /**
   * 真题列表
   * @return [type] [description]
   */
   public function true()
   {
   	if(IS_POST){
   	    	$keyword=I('keyword');
   	    	empty($keyword)?"":$keyword;
	   	    $item=$this->itembank_model->where("'question' like '`%$keyword%`' AND type=1")->select();
			$this->assign("item",$item);
			$this->display();
	    }
	    else
	    {
		    $item=$this->itembank_model->where("type=1")->select();
		    //var_dump($item);die;
			$this->assign("item",$item);
			$this->display();
	    }
	 }

	   /**
	    * 真题添加
	    */
	  public function trueadd()
	   { 
	     $su=$this->subjects_model->select();
	     $ch=$this->chapter_model->select();
	     $this->assign("su",$su);
	     $this->assign("ch",$ch);
	   	 $this->display();  
	   }
	   /**
	    * 真题添加提交
	    */
	  public function trueadd_post()
	  {
	  	if(IS_POST){
				$data=I();
				$data['itime']=time();
				unset($data['answer']);
			    unset($data['options']);
			    $answer=I('answer');
				$an=str_replace(array("A","B","C","D"),array("0","1","2","3"),$answer);
				$option=I('options');
				$op=explode(PHP_EOL,$option);
				$res=$this->itembank_model->add($data);
				//数组去空
				foreach( $op as $k=>$v){  
					    if( !$v )  
					        unset( $op[$k] );  
					}  
				var_dump($op);die; 
				if ($this->itembank_model->create()!==false){
					if ($res!==false) {
						$arr=array();
						$key=array(0,1,2,3);
						foreach($op as $key=>$v)
						{ 
	                      $arr['options']=$v;
	                      $arr['qid']=$res;
	                      $arr['key']=$key;
	                      $rec=$this->option_model->add($arr);
						} 
						     if($rec)
						     {   
	                            $a=explode(',',$an);
	                            $array=array();
	                            foreach ($a as $value) 
	                            {
	                            	$array['qid']=$res;
	                            	$array['option_id']=$value;
	                            	$re=$this->answer_model->add($array);
	                            }
						     	if($re)
							     {   
							     	$this->success(L('ADD_SUCCESS'), U("Question/itembank"));
							     }
							     else
							     {
							     	$this->error(L('ADD_FAILED'));
							     }
						     }
						     else
						     {
						     	$this->error(L('ADD_FAILED'));
						     }
						
					} else {
						$this->error(L('ADD_FAILED'));
					}
				} else {
					$this->error($this->professional_model->getError());
				}
			}
		}
	/**
	   * 真题删除
	   */
	  public function truedelete()
	  {
	  	   if(isset($_GET['item_id'])){
				$id = intval(I("get.item_id"));
				if ($this->itembank_model->where("item_id=$id")->delete()!==false&&
                    $this->option_model->where("qid=$id")->delete()!==false&&
                    $this->answer_model->where("qid=$id")->delete()!==false
					) {
					$this->success("删除成功！");
				} else {
					$this->error("删除失败！");
				}
			}
			if(isset($_POST['item_ids'])){
				$ids=join(",",$_POST['item_ids']);
				if ($this->itembank_model->where("item_id in ($ids)")->delete()!==false&&
                    $this->option_model->where("qid in ($ids)")->delete()!==false&&
                    $this->answer_model->where("qid in ($ids)")->delete()!==false
					) {
					$this->success("删除成功！");
				} else {
					$this->error("删除失败！");
				}
			}
	  }
	  /**
	   * 真题修改
	   */
	  public function trueedit()
	  {
	  	$id=I("get.item_id",0,'intval');
		$item=$this->itembank_model->where(array('item_id'=>$id))->find();
		$option=$this->option_model->where(array('qid'=>$id))->select();
		//var_dump($option);die;
		$answer=$this->answer_model->where(array('qid'=>$id))->order('option_id asc')->getField('option_id',true);
		$answ=implode(",",$answer);
		$an=str_replace(array("0","1","2","3"),array("A","B","C","D"),$answ);
		$this->assign("answer",$an);
		$this->assign("options",$option);
   	    $this->assign("item",$item);
		$this->display();
	  }
	 /**
	  * 真题修改提交
	  */
	 public function trueedit_post()
	 {
	 	if (IS_POST) {
			$data=I();
			//var_dump($data);die;
			$id=$data['item_id'];
			unset($data['item_id']);
			unset($data['answer']);
			unset($data['options']);
			$answer=I('answer');
			$an=str_replace(array("A","B","C","D"),array("0","1","2","3"),$answer);
			$option=I('option');
			//var_dump($item);
			$data['itime']=time();
		    $re=$this->itembank_model->where("item_id=$id")->save($data);
			if ($this->itembank_model->create()!==false) {
				if ($re!==false) 
				{
					if($this->option_model->where("qid=$id")->delete()!==false&&
                       $this->answer_model->where("qid=$id")->delete()!==false)
					{
					   $key=array(0,1,2,3);
						foreach($option as $v)
						{ 
	                      $arr['options']=$v;
	                      $arr['qid']=$id;
	                      $arr['key']=$key;
	                      $rec=$this->option_model->add($arr);
						} 

					    if($rec)
					     {   
                            $a=explode(',',$an);
                            $array=array();
	                            foreach ($a as $value) 
	                            {
	                            	$array['qid']=$id;
	                            	$array['option_id']=$value;
	                            	$re=$this->answer_model->add($array);
	                            }
						     	if($re)
							     {   
							     	$this->success(L('保存成功！'), U("Question/itembank"));
							     }
							     else
							     {
							     	$this->error(L('保存失败！'));
							    }

						} 
						else 
						{
						    $this->error("保存成功！");
					    }
					 }
					else 
					{
					    $this->error("保存失败！");
				    }
					
				} 
				else {
					$this->error("保存失败！");
				}
			} else {
				$this->error($this->itembank_model->getError());
			}
		}

	 }
/**
 * 试题材料题添加
 */
	  public function mater()
	  {
	  	 $su=$this->subjects_model->select();
	     $ch=$this->chapter_model->select();
	     $this->assign("su",$su);
	     $this->assign("ch",$ch);
	   	 $this->display();
	  }
/**
 * 材料题添加提交
 */
public function materadd_post()
	  {
	  	if(IS_POST){
	  		//试题选项处理
	  		 $options=I('options');
             $op=array();
             foreach($options as $ke=>$va)
             {
               $op[]=explode(PHP_EOL,$va);
             }
             //var_dump($op);die;
             //数组去空
             $opt=array();
             foreach ($op as $ky=>$vu)
             {  
             	foreach( $vu as $kl=>$vc){  
					    if( empty($vc) )  
					        unset( $vu[$kl] );  
					} 
					$opt[]=$vu; //去空以后的选项  
             }
             //试题答案处理
			 $answer=I('answer');
			 $an=array();
             foreach ($answer as $ky => $vl) 
             {
             	$an[]=str_replace(array("A","B","C","D"),array("0","1","2","3"),$vl);
             }
			 $data['content']=I('content');//试题材料
             $mate=M("material");
             $m_id=$mate->add($data);

			 $te_type=I('te_type');
			 $sid=I('sid');
			 $cid=I('cid');
			 $question=I('question');
			 $parsing=I('parsing');
			 $point=I('point');
			 $difficulty=I('difficulty');
			 $score=I('score');
			 $info=I('info');
			 $type=I('type');
			 $ncertain=I('ncertain');
			 $fen=array();
			 $jian=array("te_type","sid","cid","question","parsing","point","difficulty","score","info","type","ncertain","material_id","itiem");
            foreach ($te_type as $k=>$v)
            {
               $fen[]=array($v,$sid[$k],$cid[$k],$question[$k],$parsing[$k],$point[$key],$difficulty[$k],$score[$k],
               	            $info[$k],$type[$k],$ncertain[$k],$res,time());
               
            }
              foreach ($fen as $key => $value) {
              	$newarr=array_combine($jian,$value);
              	$newarr['material_id']=
              	var_dump($newarr);
              }

             
			 }
		}
  }
  
