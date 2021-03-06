<?php
namespace Admin\Controller;

use Api\Controller\ResponseController;
use Common\Controller\AdminbaseController;

class LectorController extends AdminbaseController
{
    /**
     * 讲师列表
     */
    public function lector()
    {
        $lector = M('lector');
        $count      = $lector->count();
        $page = $this->page($count,20);
        if (IS_POST) {
            $keyword = I('post.keyword');
            if (!empty($keyword)) {
                $list = $lector
                    ->where("name like '%$keyword%'")
                    ->order('create_time')->limit($page->firstRow.','.$page->listRows)
                    ->select();
            }
        }else {
            $list = $lector
                ->order('l_id')->limit($page->firstRow.','.$page->listRows)
                ->select();
            if (empty($list)) {
                $this->error('暂时还没有数据',U('Lector/create_lector'));
            }
        }

        $this->assign('list', $list);
        $this->assign('page',$page->show('Admin'));
        $this->display('lector');
    }

    /**
     * 添加讲师
     */
    public function create_lector()
    {
        $model = M('teaching');
        $lector = M('lector');
        $response = new ResponseController();
        $list = $model->select();
        $this->assign('list', $list);
        $junWei = M('junwei')->field('loginName,password')->find();
        if (IS_POST) {
            // $data = I('');

            // $data['password'] = sp_authencode(I('post.password'));
            $data['create_time'] = date('Y-m-d H:i:s');
            // $loginName = $junWei['loginname'];
            // $password = sp_authcode($junWei['password']);
            $data['name'] = I('post.name').'老师';
            // $teacherLoginName = I('post.login_name');
            // $teacherPassword = I('post.password');
            //调用创建老师接口
            // $resource = $response::create_lector($loginName,$password,$name,$teacherLoginName,$teacherPassword);
            // if ($resource['code'] == 0) {
                if ($lector->add($data)) {
                    $this->success(L('ADD_SUCCESS'), U("Lector/lector"));exit();
                } else {
                    $this->error(L('ADD_FAILED'));exit();
                }
            // }
        }
        $this->assign('list', $list);
        $this->display();
    }

    /**
     * 修改讲师信息
     */
    public function update ()
    {
        $lector = M('lector');
        $teaching = M('teaching');

        $id = I('get.id');
        if (!empty($id)) {
            $list = $lector
                ->where("cmf_lector.l_id = $id")
                ->find();
        }else {
            if (IS_POST) {
                $data['name'] = I('post.name');
                $data['teaching_id'] = I('post.teaching_id');
                $data['update_time'] = date('Y-m-d H:i:s');
            }
            if ($lector->where("l_id = $id")->save($data)) {
                $this->success(L('ADD_SUCCESS'), U("Lector/lector"));
                exit();
            }else {
                $this->error(L('ADD_FAILED'));exit();
            }
        }
        $data = $teaching->select();
        $this->assign('list',$list);
        $this->assign('data',$data);
        $this->display();
    }

    /**
     * 删除讲师信息
     */
    public function delete ()
    {
        $lector = M("lector");
        if (IS_GET) {
            if(isset($_GET['id'])){
                $id = intval(I("get.id"));
                if ($lector->where("l_id = $id")->delete()!==false) {
                    $this->success("删除成功！");
                } else {
                    $this->error("删除失败！");
                }
            }
        }
        if(isset($_POST['l_ids'])){
            $ids=join(",",$_POST['l_ids']);
            if ($lector->where("l_id in ($ids)")->delete()!==false) {
                $this->success("删除成功！");
            } else {
                $this->error("删除失败！");
            }
        }
    }
}