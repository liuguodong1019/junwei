<?php
namespace Api\Controller;

use Common\Model\CourseModel;
use Think\Controller;
use Api\Controller\CacheController;
class ClassHourController extends Controller
{
    /**
     * 课时列表接口
     */
    public function class_hour()
    {
        $status = C('status');
        $msg = C('msg');
        $response = new SubmitController();
        $res = new ResponseController();
        if (IS_GET) {
            $id   = I('get.id');
            $uid  = I('get.uid');
            $page = empty(I('get.page')) ? 1 : I('get.page');
            $result = $res->classHour_list($id,$uid,$page);
            echo $result;die;
        } else {
            echo $response::state($status[3], $msg[3]);
            exit();
        }
    }

    /**
     * vip生成回放
     */
    public function vipReply ()
    {
        $live = M('live');
        $course = M('course');
        $str = new CourseModel();
        $redis = $str::redis();
        $endLive = new CacheController();
        $test = $endLive->live_end();
        if (!empty($test)) {
            $data = $test;
        }else {
            $data = $live->field('id,class_id,course_id')->where("status = '2'")->select();
        }
        
        if (!empty($data)) {
            foreach ($data as $value) {
                $class_id[]  = $value['class_id'];
                $id[]        = $value['id'];
                $course_id[] = $value['course_id'];
            }
            $course_id = join(",", $course_id);
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
                    $data['number']     = $res[$k]['number'];
                    $data['reply_url']  = $res[$k]['url'];
                    $data['status']     = 3;
                    $tr = json_decode($redis->hGet('live',$id[$k]),true);
                    $tr['courseware_id'] = $data['courseware_id'];
                    $tr['number'] = $data['number'];
                    $tr['reply_url'] = $data['reply_url'];
                    $tr['status'] = 3;
                    $live->where("id = '$id[$k]'")->save($data);
                    $list = json_encode($tr);
                    $redis->hSet('live',$id[$k],$list);
                }
            }
        }
    }

    /**
     * @return string
     * 课时分享
     */
    public function share1 ()
    {
        $status = C('status');
        $msg = C('msg');
        $response = new ResponseController();
        switch ($_GET) {
            case 1:
                $data['uid']         = I('get.uid');
                $data['course_id']   = I('get.course_id');
                $data['live_id']     = I('get.live_id');
                $data['share_way']   = I('get.share_way');
                $data['create_time'] = time();
                $result              = $response->liveShare($data);
                echo $result;exit();
                break;
            default:
                return json_encode([$status[3],$msg[3]]);
                break;
        }
    }
}