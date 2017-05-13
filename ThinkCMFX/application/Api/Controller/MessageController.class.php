<?php
namespace Api\Controller;
use Think\Controller;

class MessageController extends Controller
{
    /**
     * 点播留言
     */
    public function message () {
        $response = new SubmitController();
        $status = C('status');
        $msg = C('msg');
        $message = M('message');

        if (IS_POST) {
            $token = I('post.token');
            $lector_id = I('post.lector_id');
            if (!empty($token)) {
                 $uid = M('users')->field('id')->where("token = $token")->find();
                 $content = $message->field('content')->where("uid = $uid")->find();
                 if (!empty($lector_id)) {
                     if (empty($content)) {
                         echo $response::state(403,'没有留言不能回复');exit();
                     }else {
                         $data['reply_content'] = I('post.reply_content');
                         $data['reply_time'] = time();
                         $data['lector_id'] = $lector_id;
                         if ($message->add($data)) {
                             $this->success('添加成功');
                         }else {
                             $this->error('添加失败');
                         }
                     }
                 }else {
                     $data['id'] = I('post.id');
                     $data['content'] = I('post.content ');
                     $data['create_time'] = time();
                     $data['uid'] = $uid;
                     if ($message->add($data)) {
                         $this->success('添加成功');
                     }else {
                         $this->error('添加失败');
                     }
                 }
                 if ($message->add($data)) {
                     $this->success('添加成功');
                 }else {
                     $this->error('添加失败');
                 }exit();
            }else {
                echo $response::state($status[2],$msg[2]);exit();
            }
        }else {
            echo $response::state($status[3],$msg[3]);exit();
        }
    }
}