<?php
/**
 * 课程科目管理
 */
namespace Admin\Controller;

use Common\Controller\AdminbaseController;

class TypesController extends AdminbaseController
{

   protected $types_model;
	
	public function _initialize() {
		parent::_initialize();
		$this->types_model = D("Common/Types");
		$this->professional_model = D("Common/Professional");
		$this->subjects_model=D("Common/Subjects");
		$this->chapter_model=D("Common/Chapter");
	}
/**
 * --------------------------------分类---------------------------------------------------------------
 */
	/**
	 * 所有分类列表
	 * @return [type] [description]
	 */
   public function index()
   {
   	 //echo time();die;
     $types=$this->types_model->select();
     //var_dump($types);die;
     $this->assign("types",$types);
     $this->display();
   }
   /**
    * 分类添加
    */
   public function add()
   {
   	 $this->display();
   }
   /**
    * 分类添加提交
    */
	public function add_post(){
		if(IS_POST){
			$data['title']=I('title');
			$data['ttime']=time();
			if ($this->types_model->create($data)!==false){
				if ($this->types_model->add($data)!==false) {
					$this->success(L('ADD_SUCCESS'), U("types/index"));
				} else {
					$this->error(L('ADD_FAILED'));
				}
			} else {
				$this->error($this->types_model->getError());
			}
		
		}
	}
   /**
    * 分类编辑
    * @return [type] [description]
    */
	public function edit(){
		$id=I("get.id",0,'intval');
		$types=$this->types_model->where(array('tid'=>$id))->find();
		$this->assign('types',$types);
		$this->display();
	}
   /**
    * 分类编辑提交
    * @return [type] [description]
    */
	public function edit_post(){
		if (IS_POST) {
			$tid=I('tid');
			$data['title']=I('title');
			$data['ttime']=time();
			if ($this->types_model->create()!==false) {
				if ($this->types_model->where("tid=$tid")->save($data)!==false) {
					$this->success("保存成功！", U("Types/index"));
				} else {
					$this->error("保存失败！");
				}
			} else {
				$this->error($this->types_model->getError());
			}
		}
	}

	/**
	 * 分类删除
	 * @return [type] [description]
	 */
	public function delete(){
		if(isset($_GET['tid'])){
			$id = intval(I("get.tid"));
			if ($this->types_model->where("tid=$id")->delete()!==false) {
				$this->success("删除成功！");
			} else {
				$this->error("删除失败！");
			}
		}
		if(isset($_POST['tids'])){
			$ids=join(",",$_POST['tids']);
			if ($this->types_model->where("tid in ($ids)")->delete()!==false) {
				$this->success("删除成功！");
			} else {
				$this->error("删除失败！");
			}
		}
	}
/**
 * --------------------------------------------------------专业--------------------------------------------------------
 */
/**
 * 专业列表
 */
	public function major()
	{
		$major=M('professional')->query('select pid,ptitle,ptime,title from cmf_professional as a left join cmf_types as b 
			on a.tid=b.tid ');
		$this->assign("major",$major);
        $this->display();
	}
/**
    * 专业添加
    */
   public function majoradd()
   { 
   	 $types=$this->types_model->select();
   	 $this->assign("types",$types);
   	 $this->display();
   }

/**
 * 专业添加提交
 */
public function majoradd_post(){
		if(IS_POST){
			$data['ptitle']=I('ptitle');
			$data['ptime']=time();
			$data['tid']=I('tid');
			if ($this->professional_model->create($data)!==false){
				if ($this->professional_model->add($data)!==false) {
					$this->success(L('ADD_SUCCESS'), U("types/major"));
				} else {
					$this->error(L('ADD_FAILED'));
				}
			} else {
				$this->error($this->professional_model->getError());
			}
		
		}
	}
/**
 * 专业移除删除
 * @return [type] [description]
 */
	public function majordelete(){
		if(isset($_GET['pid'])){
			$id = intval(I("get.pid"));
			if ($this->professional_model->where("pid=$id")->delete()!==false) {
				$this->success("删除成功！");
			} else {
				$this->error("删除失败！");
			}
		}
		if(isset($_POST['pids'])){
			$ids=join(",",$_POST['pids']);
			if ($this->professional_model->where("pid in ($ids)")->delete()!==false) {
				$this->success("删除成功！");
			} else {
				$this->error("删除失败！");
			}
		}
	}
/**
    * 专业编辑
    * @return [type] [description]
    */
	public function majoredit(){
		$id=I("get.pid",0,'intval');
		$pr=$this->professional_model->where(array('pid'=>$id))->find();
		$types=$this->types_model->select();
   	    $this->assign("types",$types);
		$this->assign('pr',$pr);
		$this->display();
	}
   /**
    * 专业编辑提交
    * @return [type] [description]
    */
	public function majoredit_post(){
		if (IS_POST) {
			$pid=I('pid');
			$data['tid']=I('tid');
			$data['ptitle']=I('ptitle');
			$data['ptime']=time();
			if ($this->professional_model->create()!==false) {
				if ($this->professional_model->where("pid=$pid")->save($data)!==false) {
					$this->success("保存成功！", U("Types/major"));
				} else {
					$this->error("保存失败！");
				}
			} else {
				$this->error($this->professional_model->getError());
			}
		}
	}
	/**
	 * ------------------------------------------------------------科目----------------------------------------------
	 */
	/**
 * 科目列表
 */
	public function classindex()
	{
		$class=$this->subjects_model->select();
		
		//->join('cmf_types on cmf_subjects.tid = cmf_types.tid')
        //->join('cmf_professional on cmf_subjects.pid = cmf_professional.pid')
        
		$this->assign("class",$class);
        $this->display();
	}
/**
    * 科目添加
    */
   public function classadd()
   { 
   	 //$pr=$this->professional_model->select();
   	 //$this->assign("pr",$pr);
   	 $this->display();
   }
   /**
 * 科目添加提交
 */
public function classadd_post(){
		if(IS_POST){
			$data['stitle']=I('stitle');
			$data['stime']=time();
			$data['pid']=0;
			//$pid=$data['pid'];
			//$data['tid']=$this
							//->professional_model
							//->where("pid=$pid")
							//->getField('tid');
			$data['tid']=0;
			if ($this->subjects_model->create($data)!==false){
				if ($this->subjects_model->add($data)!==false) {
					$this->success(L('ADD_SUCCESS'), U("types/classindex"));
				} else {
					$this->error(L('ADD_FAILED'));
				}
			} else {
				$this->error($this->subjects_model->getError());
			}
		
		}
	}
/**
 * 科目移除删除
 * @return [type] [description]
 */
	public function classdelete(){
		if(isset($_GET['sid'])){
			$id = intval(I("get.sid"));
			if ($this->subjects_model->where("sid=$id")->delete()!==false) {
				$this->success("删除成功！");
			} else {
				$this->error("删除失败！");
			}
		}
		if(isset($_POST['sids'])){
			$ids=join(",",$_POST['sids']);
			if ($this->subjects_model->where("sid in ($ids)")->delete()!==false) {
				$this->success("删除成功！");
			} else {
				$this->error("删除失败！");
			}
		}
	}
/**
    * 科目编辑
    * @return [type] [description]
    */
	public function classedit(){
		$id=I("get.sid",0,'intval');
		$su=$this->subjects_model->where(array('sid'=>$id))->find();
		//$pr=$this->professional_model->select();
   	    $this->assign("su",$su);
		//$this->assign('pr',$pr);
		$this->display();
	}
   /**
    * 专业编辑提交
    * @return [type] [description]
    */
	public function classedit_post(){
		if (IS_POST) {
			$sid=I('sid');
			$data['pid']=I('pid');
			$data['stitle']=I('stitle');
			$data['ptime']=time();
			$pid=0;
			$data['tid']=0;
			//$data['tid']=$this
							//->professional_model
							//->where("pid=$pid")
							//->getField('tid');

			if ($this->subjects_model->create()!==false) {
				if ($this->subjects_model->where("sid=$sid")->save($data)!==false) {
					$this->success("保存成功！", U("Types/classindex"));
				} else {
					$this->error("保存失败！");
				}
			} else {
				$this->error($this->subjects_model->getError());
			}
		}
	}
	/**
	 * <------------------------------------------
	 * 章节管理-------------------------------------------------------------------------->
	 */
	/**
	 * 章节列表
	 * @return [type] [description]
	 */
	public function chapter()
	{
      $chapter=$this
		->chapter_model
		->join('cmf_subjects on cmf_subjects.sid = cmf_chapter.sid')
        ->select();
		$this->assign("chapter",$chapter);
        $this->display();
	}
	/**
	 * 章节添加
	 */
	public function chapteradd()
	{
		 $su=$this->subjects_model->select();
	   	 $this->assign("su",$su);
	   	 $this->display();
	}
	/**
	 * 章节添加提交
	 */
    public function chapteradd_post()
    {
    	if(IS_POST){
			$data['ctitle']=I('ctitle');
			$data['ctime']=time();
			$data['sid']=I('sid');
			//var_dump($data);die;
			if ($this->chapter_model->create($data)!==false){
				if ($this->chapter_model->add($data)!==false) {
					$this->success(L('ADD_SUCCESS'), U("types/chapter"));
				} else {
					$this->error(L('ADD_FAILED'));
				}
			} else {
				$this->error($this->chapter_model->getError());
			}
		
		}
    }
  /**
   * 章节移除
   */
  public function chapterdelete()
  {
		if(isset($_GET['id'])){
			$id = intval(I("get.id"));
			if ($this->chapter_model->where("cid=$id")->delete()!==false) {
				$this->success("删除成功！");
			} else {
				$this->error("删除失败！");
			}
		}
		if(isset($_POST['cids'])){
			$ids=join(",",$_POST['cids']);
			//var_dump($cids);die;
			if ($this->chapter_model->where("cid in ($ids)")->delete()!==false) {
				$this->success("删除成功！");
			} else {
				$this->error("删除失败！");
			}
		}
	}
 /**
  * 章节编辑
  */
 public function chapteredit()
 {
        $id=I("get.id",0,'intval');
		$ch=$this->chapter_model->where(array('cid'=>$id))->find();
		$su=$this->subjects_model->select();
   	    $this->assign("su",$su);
		$this->assign('ch',$ch);
		$this->display();
 }
 /**
  * 章节编辑提交
  */
 public function chapteredit_post()
 {
      if (IS_POST) {
			$cid=I('cid');
			$data['sid']=I('sid');

			$data['ctitle']=I('ctitle');
			$data['ptime']=time();
			if ($this->chapter_model->create()!==false) {
				if ($this->chapter_model->where("cid=$cid")->save($data)!==false) {
					$this->success("保存成功！", U("Types/chapter"));
				} else {
					$this->error("保存失败！");
				}
			} else {
				$this->error($this->chapter_model->getError());
			}
		}
 }
//--------------------------------------------------独家密卷-----------------------------------------------------------
 /**
	 * 密卷列表
	 * @return [type] [description]
	 */
	public function exclusive()
	{  
	  $exclusive=M('exclusive');
      $exclusive=$exclusive
        ->order('ex_id asc')
        ->select();
		$this->assign("exclusive",$exclusive);
        $this->display();
	}
	 /**
    * 密卷添加
    */
   public function exclusiveadd()
   {
   	 $this->display();
   }
   /**
    * 密卷添加提交
    */
	public function exclusiveadd_post(){
		if(IS_POST){
			$exclusive=M('exclusive');
			$data=I();
			$data['etime']=time();
			if ($exclusive->create($data)!==false){
				if ($exclusive->add($data)!==false) {
					$this->success(L('ADD_SUCCESS'), U("types/exclusive"));
				} else {
					$this->error(L('ADD_FAILED'));
				}
			} else {
				$this->error($exclusive->getError());
			}
		
		}
	}
	 /**
   * 密卷移除
   */
  public function exclusivedelete()
  {     
  	    $exclusive=M('exclusive');
		if(isset($_GET['id'])){
			$id = intval(I("get.id"));
			if ($exclusive->where("ex_id=$id")->delete()!==false) {
				$this->success("删除成功！");
			} else {
				$this->error("删除失败！");
			}
		}
		if(isset($_POST['ex_ids'])){
			$ids=join(",",$_POST['ex_ids']);
			if ($exclusive->where("ex_id in ($ids)")->delete()!==false) {
				$this->success("删除成功！");
			} else {
				$this->error("删除失败！");
			}
		}
	}
 /**
  * 密卷编辑
  */
 public function exclusiveedit()
 {      
 	    $exclusive=M('exclusive');
        $id=I("get.id",0,'intval');
		$ex=$exclusive->where(array('ex_id'=>$id))->find();
   	    $this->assign("ex",$ex);
		$this->display();
 }
 /**
  * 密卷编辑提交
  */
 public function exclusiveedit_post()
 {    
 	  $exclusive=M('exclusive');
      if (IS_POST) {
			$ex_id=I('ex_id');
			$data=I();
			$data['etime']=time();
			if ($exclusive->create()!==false) {
				if ($exclusive->where("ex_id=$ex_id")->save($data)!==false) {
					$this->success("保存成功！", U("Types/exclusive"));
				} else {
					$this->error("保存失败！");
				}
			} else {
				$this->error($this->chapter_model->getError());
			}
		}
 }
}