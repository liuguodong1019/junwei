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
			$data['options']=I('options');
			//$option=explode(';',$data['options']);
			$data['answer']=I('answer');
			$data['info']=I('info');
			$data['parsing']=I('parsing');
			$data['itime']=time();
			$data['score']=I('score');
			$data['type']=I('type');
			if ($this->itembank_model->create($data)!==false){
				if ($this->itembank_model->add($data)!==false) {
					$this->success(L('ADD_SUCCESS'), U("Question/itembank"));
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
				if ($this->itembank_model->where("item_id=$id")->delete()!==false) {
					$this->success("删除成功！");
				} else {
					$this->error("删除失败！");
				}
			}
			if(isset($_POST['item_ids'])){
				$ids=join(",",$_POST['item_ids']);
				if ($this->itembank_model->where("item_id in ($ids)")->delete()!==false) {
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
		$su=$this->subjects_model->select();
		$option=explode(';', $item['options']);
		$this->assign("su",$su);
		$this->assign("option",$option);
   	    $this->assign("item",$item);
		$this->display();
	  }
	 /**
	  * 试题修改提交
	  */
	 public function itembankedit_post()
	 {
	 	if (IS_POST) {
			$data=I();
			$id=$data['item_id'];
			unset($data['item_id']);
			$data['itime']=time();
			if ($this->itembank_model->create()!==false) {
				if ($this->itembank_model->where("item_id=$id")->save($data)!==false) {
					$this->success("保存成功！", U("Question/itembank"));
				} else {
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
				if ($this->itembank_model->create($data)!==false){
					if ($this->itembank_model->add($data)!==false) {
						$this->success(L('ADD_SUCCESS'), U("Question/true"));
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
				if ($this->itembank_model->where("item_id=$id")->delete()!==false) {
					$this->success("删除成功！");
				} else {
					$this->error("删除失败！");
				}
			}
			if(isset($_POST['item_ids'])){
				$ids=join(",",$_POST['item_ids']);
				if ($this->itembank_model->where("item_id in ($ids)")->delete()!==false) {
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
		$option=explode(';', $item['options']);
		$this->assign("option",$option);
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
			$id=$data['item_id'];
			unset($data['item_id']);
			$data['itime']=time();
			// echo $id;
   //           var_dump($data);die;
			if ($this->itembank_model->create()!==false) {
				if ($this->itembank_model->where("item_id=$id")->save($data)!==false) {
					$this->success("保存成功！", U("Question/true"));
				} else {
					$this->error("保存失败！");
				}
			} else {
				$this->error($this->itembank_model->getError());
			}
		}

	 }

	 /**
	  * 测试答题
	  */
   
    public function da()
    {       
 
    	    $item=$this->itembank_model->select();
    	    $array=array();
    	    foreach ($item as $value) {
    	    	$array=explode(';',$value['options']);
    	    	unset($array['4']);
    	    	// unset($value['options']);
    	    	$value['options']=array();
    	    	$value['options']=$array;
    	    	//shuffle($value['options']);
    	    	//var_dump($value);
    	    }
             //var_dump($item);die;
			$this->assign("item",$item);
			$this->display();
    }
  }
  
