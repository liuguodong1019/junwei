<?php

 namespace Admin\Controller;

 use Common\Controller\AdminbaseController;

 class TeachingController extends AdminbaseController
 {
     /**
      * 授课列表
      */
     public function index ()
     {
         $teaching = M("teaching");
         $count      = $teaching->count();
         $Page       = new \Think\Page($count,25);
         $show = $Page->show();
         if (IS_POST) {
             $keyword = I('post.keyword');
             if (!empty($keyword)) {
                 $data = $teaching->where("course_name like '%$keyword%'")
                 ->order('create_time')->limit($Page->firstRow.','.$Page->listRows)->select();
             }
         }else {
             $data = $teaching->order('create_time')->limit($Page->firstRow.','.$Page->listRows)->select();
         }

         $this->assign('data',$data);
         $this->assign('page',$show);
         $this->display();
     }

     /**
      * 添加授课类型
      */
     public function create_teaching()
     {
         $teaching = M('teaching');
         if (IS_POST) {
             $data['course_name'] = I('post.course_name');
             $data['create_time'] = time();
             if ($teaching->add($data)) {
                 $this->success(L('ADD_SUCCESS'), U("Teaching/index"));
                 exit();
             } else {
                 $this->error(L('ADD_FAILED'));
             }
         }
         $this->display();
     }

     /**
      * 修改授课类型
      */
     public function update_teaching ()
     {
         $teaching = M('teaching');
         if (IS_POST) {
             $data['course_name'] = I("post.course_name");
             $data['update_time'] = time();
             $id = I("post.t_id");
             if (!empty($data['course_name'])) {
                 if ($teaching->where("t_id = $id")->save($data)) {
                     $this->success(L('ADD_SUCCESS'), U("Teaching/index"));exit();
                 }else {
                     $this->error(L('ADD_FAILED'));exit();
                 }
             }
         }
         if (IS_GET) {
             $id = I('get.t_id');
             if (!empty($id)) {
                 $data = $teaching->where("t_id = $id")->find();
             }
         }
         $this->assign('data',$data);
         $this->display();
     }

     /**
      * 删除授课信息
      */
     public function delete ()
     {
         $teaching = M("teaching");
         if (IS_GET) {
             if(isset($_GET['t_id'])){
                 $id = intval(I("get.t_id"));
                 if ($teaching->where("t_id = $id")->delete()!==false) {
                     $this->success("删除成功！");
                 } else {
                     $this->error("删除失败！");
                 }
             }
         }
         if(isset($_POST['t_ids'])){
             $ids=join(",",$_POST['t_ids']);
//             print_r($ids);die;
             if ($teaching->where("t_id in ($ids)")->delete()!==false) {
                 $this->success("删除成功！");
             } else {
                 $this->error("删除失败！");
             }
         }
     }
 }