<?php
namespace Api\Controller;

use Think\Controller;
use Api\Controller\ResponseController;
use Think\Page;
use Think\Think;

class CourseController extends Controller
{

    /**
     * 获取课堂列表
     */
    public function get_class_list()
    {
        $succ = C('status');
        $mess = C('msg');
        $model = new SubmitController();
        $res = new ResponseController();
        if (IS_GET) {
            $page = I('get.page');
            $uid = I('get.uid');
            $result = $res->get_class_list($uid,$page);
            echo $result;die;
        } else {
            echo $model::state($succ[3], $mess[3]);die;
        }
    }

    /**
     * 获取课堂信息
     */
    public function get_class()
    {
        $succ = C('status');
        $mess = C('msg');
        $model = new SubmitController();
        $res = new ResponseController();
        if (IS_GET) {
            $id = I('get.id');
            $uid = I('get.uid');
            $result = $res->get_class($id,$uid);
            echo $result;die;
        } else {
            echo $model::state($succ[3], $mess[3]);die;
        }
    }

    /**
     * 公开课
     */
    public function open_class ()
    {
        $succ = C('status');
        $mess = C('msg');
        $model = new SubmitController();
        $res = new ResponseController();
        if (IS_GET) {
            $page = I('get.page');
            $uid = I('get.uid');
            $result = $res->openClass($page,$uid);
            echo $result;die;
        } else {
            echo $model::state($succ[3], $mess[3]);die;
        }
    }

    /**
     * vip课程
     */
    public function vip ()
    {
        $succ = C('status');
        $mess = C('msg');
        $model = new SubmitController();
        $res = new ResponseController();
        if (IS_GET) {
            $page = I('get.page');
            $uid = I('get.uid');
            $result = $res->vip($page,$uid);
            echo $result;die;
        } else {
            echo $model::state($succ[3], $mess[3]);die;
        }
    }


    /**
     * 往期直播
     */
    public function past_live()
    {
        $succ = C('status');
        $mess = C('msg');
        $model = new SubmitController();
        $res = new ResponseController();
        if (IS_GET) {
            $page = I('get.page');
            $uid = I('get.uid');
            $result = $res->past_live($page,$uid);
            echo $result;die;
        }else {
            echo $model::state($succ[3], $mess[3], $data = null);die;
        }
    }

    /**
     * 课程分享
     */
    public function share ()
    {
        $status = C('status');
        $msg = C('msg');
        $response = new ResponseController();
        if ($_GET) {
            $data['uid'] = I('get.uid');
            $data['course_id'] = I('get.id');
            $data['create_time'] = time();
            $data['share_way'] = I('get.share_way');
            $result = $response->courseShare($data);
            echo $result;exit();
        }else {
            echo json_encode([$status[3],$msg[3]]);exit();
        }
    }

    /**
     * 公开课生成回放
     */
    public function reply ()
    {
        $course = M('course');
        $data = $course->field('id,class_id')->where("status = '2'")->select();
        if (!empty($data)) {
            foreach ($data as $value) {
                $class_id[] = $value['class_id'];
                $id[] = $value['id'];
            }
            $junwei = M('junwei')->find();
            $loginName = $junwei['loginname'];
            $password = sp_authcode($junwei['password']);
            $response = new ResponseController();

            $resource = $response::get_past($loginName,$password,$class_id);
            $len = count($resource);
            for ($k = 0; $k < $len; $k++) {
                $rew[] = json_decode($resource[$k],true);
                $res[] = $rew[$k]['coursewares'][0];
                if ($rew[$k]['code'] == 0) {
                    $data['number'] = $res[$k]['number'];
                    $data['reply_url'] = $res[$k]['url'];
                    $data['status'] = 3;
                }
                $course->where("id = '$id[$k]'")->save($data);
            }
        }

    }


    /**
     * 修改直播状态
     */
    public function live_status ()
    {
        if (IS_GET) {
            $course = M('course');
            $live = M('live');
            $class_id = I('get.ClassNo');
            $action = I('get.Action');
            $rew = $course->where("class_id = '$class_id'")->find();
            if (!empty($rew)) {
                $id = $rew['id'];
                switch ($action)
                {
                    case 103:
                        $data['status'] = 1;
                        break;
                    case 105:
                        $data['status'] = 2;
                        break;
                }
                $course->where("id = '$id'")->save($data);
            }else {
                $ret = $live->where("class_id = '$class_id'")->find();
                $id = $ret['id'];
                switch ($action) {
                    case 103:
                        $data['status'] = 1;
                    break;
                    case 105:
                        $data['status'] = 2;
                        break;
                }
                if ($live->where("id = '$id'")->save($data)) {
                   
                    $rew = $live->field('course_id')->where("id = '$id'")->find();
                    $course_id = $rew['course_id'];
                    $res = $course->field('status')->where("id = 'course_id'")->find();
                    $status = $res['status'];
                    if (empty($status != 1 && $status != 2 && $status != 3)) {
                        $course->where("id = '$course_id'")->save($data);
                    }
                }
            }
        }
    }
}