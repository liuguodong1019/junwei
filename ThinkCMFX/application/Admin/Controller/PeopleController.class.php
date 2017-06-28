<?php
/**
 * Created by PhpStorm.
 * User: 刘国栋
 * Date: 2017/4/28
 * Time: 9:38
 */
namespace Admin\Controller;

use Common\Controller\AdminbaseController;

class PeopleController extends AdminbaseController
{
    /**
     * 适用人群列表
     */
    public function show ()
    {
        $people = M('people');
        $count      = $people->count();
        $page = $this->page($count,20);
        if (IS_POST) {
            $keyword = I('post.keyword');
            if (!empty($keyword)) {
                $data = $people->where("people like '%$keyword%'")
                    ->order('create_time')->limit($page->firstRow.','.$page->listRows)->select();
            }
        }else {
            $data = $people->order('create_time')->limit($page->firstRow.','.$page->listRows)->select();
        }
        $this->assign('data',$data);
        $this->assign('page',$page->show('Admin'));
        $this->display();
    }

    /**
     * 添加适用人群
     */
    public function create ()
    {
        $people = M('people');
        if (IS_POST) {
            $data['people'] = I('post.people');
            $data['create_time'] = time();
            if ($people->add($data)) {
                $this->success(L('ADD_SUCCESS'), U("people/show"));exit();
            }else {
                $this->error(L('ADD_FAILED'));exit();
            }
        }
        $this->display();
    }

    /**
     * 修改适用人群
     */
    public function update ()
    {
        $people = M('people');
        if (IS_GET) {
            $id = I('get.id');
            if (!empty($id)) {
                $people = $people->where("p_id = '$id'")->getField('people');;
            }
            $this->assign('id',$id);
            $this->assign('people',$people);
        }
        if (IS_POST) {
            $id = I('post.id');
        
            $data['people'] = I('post.people');

            $data['update_time'] = time();
            
            if ($people->where("p_id = '$id'")->save($data)) {
                $this->success(L('ADD_SUCCESS'),U("People/show"));exit();
            }else {
                $this->error(L("ADD_FAILED"));exit();
            }
        }
        $this->display();
    }

    /**
     * 删除适用人群
     */
    public function delete ()
    {
        $people = M("people");
        if (IS_GET) {
            if(isset($_GET['id'])){
                $id = intval(I("get.id"));
                if ($people->where("p_id = $id")->delete()!==false) {
                    $this->success("删除成功！");
                } else {
                    $this->error("删除失败！");
                }
            }
        }
        if(isset($_POST['p_ids'])){
            $ids=join(",",$_POST['p_ids']);
            if ($people->where("p_id in ($ids)")->delete()!==false) {
                $this->success("删除成功！");
            } else {
                $this->error("删除失败！");
            }
        }
    }
}