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
                ->field('a.id,c.user_nicename,b.course_name,a.total_amount,a.pay_ways,a.pay_time,a.pay_status')
                ->join('cmf_course as b ON a.course_id = b.id')
                ->join('cmf_users as c ON a.uid = c.id')
                ->order('a.id DESC')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        }

        $this->assign('page', $Page->show('Admin'));
        $this->assign('data',$data);
        $this->display();
    }

    /**
     * 手动录入用户信息
     */
    public function create ()
    {
        $user = M('users');
        if (IS_POST) {
            $data['user_nicename'] = htmlspecialchars(trim(I('post.user_nicename')));
            $data['mobile'] = htmlspecialchars(trim(I('post.mobile')));
            $data['user_pass'] = sp_password(123456);
            $data['avatar'] = '/upload/avatar/2017-07-03/1499048720.jpg';
            $data['create_time'] = time();
            if ($user->add($data)) {
                $this->success('创建成功');exit();
            }else {
                $this->error('fail');exit();
            }
        }
        $this->display();
    }

    /**
     * 录入订单信息
     */
    public function add ()
    {
        $user = M('users')->field('id,user_nicename')->order('id desc')->select();
        $course = D('course')->field('id,course_name')->where("is_free = '2'")->select();
        if (IS_POST) {
            $data = I('');
            $course_id = $data['course_id'];
            $rew = D('course')->field('now_price,introduction,course_name')->where("id = '$course_id'")->find();
            $data['subject'] = $rew['course_name'];
            $data['total_amount'] = $rew['now_price'];
            $data['boy'] = $rew['introduction'];
            $data['create_time'] = time();

            if (M('order')->add($data)) {
                $this->success('创建成功');exit();
            }else {
                $this->error('fail');exit();
            }
        }
        $this->assign('user',$user);
        $this->assign('course',$course);
        $this->display();
    }
}