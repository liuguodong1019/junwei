<?php
namespace Admin\Controller;

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
        $Page       = new \Think\Page($count,25);
        $show = $Page->show();
        if (IS_POST) {
            $keyword = I('post.keyword');
            if (!empty($keyword)) {
                $list = $lector
                    ->join('cmf_teaching ON cmf_lector.teaching_id = cmf_teaching.t_id')
                    ->where("name like '%$keyword%'")
                    ->order('create_time')->limit($Page->firstRow.','.$Page->listRows)
                    ->select();
            }
        }else {
            $list = $lector
                ->join('cmf_teaching ON cmf_lector.teaching_id = cmf_teaching.t_id')
                ->order('l_id')->limit($Page->firstRow.','.$Page->listRows)
                ->select();
        }

        if (empty($list)) {
            $this->redirect(U('Lector/create_lector'));
        }
        $this->assign('list', $list);
        $this->assign('page',$show);
        $this->display('lector');
    }

    /**
     * 添加讲师
     */
    public function create_lector()
    {
        $model = M('teaching');
        $lector = M('lector');
        $list = $model->select();
        $this->assign('list', $list);
        if (IS_POST) {
            $data['name'] = I('post.name');
            $data['teaching_id'] = I('post.teaching_id');
            $data['create_time'] = date('Y-m-d H:i:s');

            if ($lector->add($data)) {
                $this->success(L('ADD_SUCCESS'), U("Lector/lector"));exit();
            } else {
                $this->error(L('ADD_FAILED'));exit();
            }
        }
        $this->assign('list', $list);
        $this->display();
    }

    /**
     * 修改讲师信息
     */
    public function update_lector ()
    {
        $lector = M('lector');
        $teaching = M('teaching');

        $id = I('get.id');
        if (!empty($id)) {
            $list = $lector
                ->join('cmf_teaching ON cmf_lector.teaching_id = cmf_teaching.t_id')
                ->where("cmf_lector.id = $id")
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