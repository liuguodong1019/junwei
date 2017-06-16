<?php
/**
 * Created by PhpStorm.
 * User: 刘国栋
 * Date: 2017/6/16
 * Time: 10:06
 */
namespace Admin\Controller;
use Common\Controller\AdminbaseController;

class OrderController extends AdminbaseController
{
    /**
     * 订单列表
     */
    public function show () {
        $order = M('order');
        $count = $order->count();
        $Page = $this->page($count, 20);
        if (IS_POST) {
            $keyword = I('post.keyword');
            if (!empty($keyword)) {
                $data = $order->table('cmf_order as a')
                    ->field('a.id,user_nicename,b.course_name,total_amount,pay_ways,pay_time,pay_status')
                    ->join('cmf_course as b ON a.course_id = b.id')
                    ->join('cmf_users as c ON a.uid = c.id')
                    ->where("user_nicename like '%$keyword%' or a.id like '%$keyword%' or b.course_name like '%$keyword%'")
                    ->order('a.id DESC')->limit($Page->firstRow . ',' . $Page->listRows)->select();
            } else {
                $this->redirect('show');
            }
        } else {
            $data = $order->table('cmf_order as a')
                ->field('a.id,user_nicename,b.course_name,total_amount,pay_ways,pay_time,pay_status')
                ->join('cmf_course as b ON a.course_id = b.id')
                ->join('cmf_users as c ON a.uid = c.id')
                ->order('a.id DESC')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        }

        $this->assign('page', $Page->show('Admin'));
        $this->assign('data',$data);
        $this->display();
    }
}