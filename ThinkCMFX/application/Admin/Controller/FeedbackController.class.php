<?php
/**
 * Created by PhpStorm.
 * User: 刘国栋
 * Date: 2017/4/26
 * Time: 11:47
 */

namespace Admin\Controller;

use Common\Controller\AdminbaseController;

use Admin\Controller\ResponseController;

/**
 * Class LiveController
 * @package Admin\Controller
 * 意见反馈
 */
class FeedbackController extends AdminbaseController{
    public function show(){
        $model = M("feedback");

        $where = "1=1";
        $keyword = $_REQUEST['keyword'];
        if(!empty($keyword)){
            $where .= " and f_desc like '%$keyword%'";
            $this->assign("keyword",$keyword);
        }

        $count = $model->join("cmf_users ON cmf_users.id = cmf_feedback.uid")->where($where)->count();

        $Page = new \Think\Page($count, 25);

        foreach($keyword as $key=>$val) {
            $Page->parameter   .=   "$key=".urlencode($val).'&';
        }


        $list = $model->join("cmf_users ON cmf_users.id = cmf_feedback.uid")->where($where)->order('f_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();

        $page = $Page->show();
        $this->assign("data",$list);
        $this->assign("page",$page);
        $this->display();
    }



    /*
     * 删除系统消息
     * */
    public function  feeddel()
    {
        if (isset($_GET['id'])) {
            $id = I("id");
            $res = M("feedback")->where("f_id = $id")->delete();
            if ($res) {
                $this->success("删除成功");
            } else {
                $this->error("删除失败");
            }
        }

        if(isset($_POST['ids'])){
            $ids = join(",", $_POST['ids']);
            $res = M("feedback")->where("f_id in ($ids)")->delete();
            if ($res) {
                $this->success("删除成功");
            } else {
                $this->error("删除失败");
            }
        }else{
            $this->error("请选择要删除的信息");
        }

    }




}