<?php
namespace Api\Controller;
header("Access-Control-Allow-Origin:*");
use Common\Model\CourseModel;
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
            $page = I('get.page');
            $result = $res->get_class($id,$uid,$page);
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
            $data[' '] = I('get.share_way');
            $result = $response->courseShare($data);
            echo $result;exit();
        }else {
            echo json_encode([$status[3],$msg[3]]);exit();
        }
    }

    /**
     * 留言
     */
    public function message ()
    {
        $message = M('message');
        $status = C('status');
        $msg = C('msg');
        $response = new SubmitController();
        if (IS_GET) {
            $lector_id = I('request.lector_id');
            if (!empty($lector_id)) {
                $uid = I('request.uid');
                $live_id = I('request.live_id');
                $data['lector_id'] = $lector_id;
                $data['reply_content'] = I('request.reply_content');
                $data['reply_time'] = time();
                $message->where("uid = '$uid' and live_id = '$live_id'")->save($data);
            }else {
                $data['live_id'] = I('request.live_id');
                $data['uid'] = I('request.uid');
                $data['content'] = I('request.content');
                $data['create_time'] = time();
                $message->add($data);
            }
        }else {
            echo $response::state($status[3],$msg[3]);exit();
        }
    }

    /**
     * 公开课生成回放
     */
    public function reply ()
    {
        $str = new CourseModel();
        $redis = $str::redis();
        $course = M('course');
        $openClass = new CacheController();
        $resw = $openClass->tax($is_free = 1);

        foreach ($resw as $val) {
            if ($val['status'] == 2) {
                $ret[] = $val;
            }
        }

        if (!empty($ret)) {
            $data = $ret;
        }else {
            $data = $course->field('id,class_id')->where("status = '2'")->select();
        }
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
                    $data['courseware_id'] = $res[$k]['id'];
                    $data['number'] = $res[$k]['number'];
                    $data['reply_url'] = $res[$k]['url'];
                    $data['status'] = 3;
                    $tr = json_decode($redis->hGet('course',$id[$k]),true);
                    $tr['courseware_id'] = $data['courseware_id'];
                    $tr['number'] = $data['number'];
                    $tr['reply_url'] = $data['reply_url'];
                    $tr['status'] = 3;
                    $course->where("id = '$id[$k]'")->save($data);
                    $list = json_encode($tr);
                    $redis->hSet('course',$id[$k],$list);
                }
            }
        }

    }


    /**
     * 修改直播状态
     */
    public function live_status ()
    {
        $str = new CourseModel();
        $redis = $str::redis();
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
                        $rew = json_decode($redis->hGet('course',$id),true);
                        $rew['status'] = $data['status'];
                        $list = json_encode($rew);
                        $redis->hSet('course',$id,$list);
                        break;
                    case 105:
                        $data['status'] = 2;
                        $rew = json_decode($redis->hGet('course',$id),true);
                        $rew['status'] = $data['status'];
                        $list = json_encode($rew);
                        $redis->hSet('course',$id,$list);
                        break;
                }
                
                $course->where("id = '$id'")->save($data);
                
            }else {
                $ret = $live->where("class_id = '$class_id'")->find();
                $id = $ret['id'];
                switch ($action) {
                    case 103:
                        $data['status'] = 1;
                        $rew = json_decode($redis->hGet('live',$id),true);
                        $rew['status'] = $data['status'];
                        $list = json_encode($rew);
                        $redis->hSet('live',$id,$list);
                        break;
                    case 105:
                        $data['status'] = 2;
                        $rew = json_decode($redis->hGet('live',$id),true);
                        $rew['status'] = $data['status'];
                        $list = json_encode($rew);
                        $redis->hSet('live',$id,$list);
                        break;
                }
                
                $live->where("id = '$id'")->save($data);
                
            }
        }
    }


    /**
     * 分享页面课堂详情
     */
    public function classDesc()
    {
        $succ = C('status');
        $mess = C('msg');
        $model = new SubmitController();
        $res = new ResponseController();
        $str = new CacheController();
        if (IS_GET) {
            $id = I('get.id');
            $resource = $str->course($id);
            if (!empty($resource)) {
                $result = json_encode($resource);
            }else {
                $result = $res->classDesc($id);
            }
            $jsonp = I('get.callback');
            echo $jsonp."($result)";die;
        } else {
            echo $model::state($succ[3], $mess[3]);die;
        }
    }


    /**
     * 分享页面课时详情
     */
    public function liveDesc ()
    {
        $status = C('status');
        $msg = C('msg');
        $response = new SubmitController();
        $res = new ResponseController();
        if (IS_GET) {
            $id = I('get.id');
            $uid = I('get.uid');
            $jsonp = I('get.callback');
            $result = $res->liveDesc($id,$uid);
            echo $jsonp."($result)";die;
        }else {
            echo $response::state($status[3],$msg[3]);exit();
        }
    }
}