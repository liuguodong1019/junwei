<?php
namespace Api\Controller;
use Think\Controller;
use Common\Model\CourseModel;
class CacheController extends Controller
{
    /**
     * @param $id
     * @return mixed
     * 某一课程
     */
    public function course ($id)
    {
        $str = new CourseModel();
        $redis = $str::redis();
        $course = json_decode($redis->hGet('course',$id),true);
        return $course;
    }

    /**
     * 公开课 \\ vip课
     */
    public function tax ($is_free)
    {
        $str = new CourseModel();
        $redis = $str::redis();
        $key = $redis->HKEYS('course');
        arsort($key);
        $key1 = array_values($key);
        $length = count($key1);
        for ($f = 0; $f < $length; $f++) {
            $array[] = json_decode($redis->hGet('course',$key1[$f]),true);
        }
        foreach ($array as $val) {
            if ($val['is_free'] == 1) {
                $open[] = $val;
            }else {
                $vip[] = $val;
            }
        }
        if ($is_free == 1) {
            return $open;
        }
        return $vip;
    }

    /**
     * @return array
     * 往期直播
     */
    public function reply ()
    {
        $str = new CourseModel();
        $redis = $str::redis();
        $data = $redis->hVals('course');
        $time = time();
        foreach ($data as $k => $val) {
            $res[] = json_decode($val,true);
            if ($res[$k]['status'] == 3 && strtotime($res[$k]['invalidDate']) < $time) {
                $key[] = $res[$k]['id'];
                $rew[] = json_decode($val,true);
            }
        }
        arsort($key);
        $k = array_values($key);
        $len = count($key);
        for ($a = 0; $a < $len; $a++) {
             $list[] = json_decode($redis->hGet('course',$k[$a]),true);
        }
        return $list;
    }

    /**
     * 课程修改
     */
    public function updateCourse ($array,$data,$id)
    {
        $str = new CourseModel();
        $redis = $str::redis();
        $course = D('course');
        $photo = array_diff_assoc($data,$array);
        if (empty($photo['course_name']) && empty($photo['startDate']) && empty($photo['invalidDate'])) {
            if ($course->where("id = $id")->save($photo)) {
                $res = json_encode($data);
                $redis->hSet('course',$id,$res);
                return true;
            }
            return false;
        }else {
            $junwei = M('junwei')->find();
            $loginName = $junwei['loginname'];
            $password = sp_authcode($junwei['password']);
            $realtime = I('post.realtime');
            $startDate = I('post.startDate');
            $invalidDate = I('post.invaliddate');
            $subject = I('post.course_name');
            $class_id = I('post.class_id');
            $courseware_id = I('post.courseware_id');
            $response = new ResponseController();
            //调用修改课时接口
            $resource = $response::update_course($loginName,$password,$realtime,$startDate,$invalidDate,$subject,$class_id);
            $ter = $response::update_live($courseware_id,$subject,$loginName,$password);
            if ($resource['code'] == 0 || $ter['code'] == 0) {
                if ($course->where("id = $id")->save($data)) {
                    $data['status'] = $array['status'];
                    $data['reply_url'] = $array['reply_url'];
                    $rey = json_encode($data);
                    $redis->hSet('course',$id,$rey);
                    return true;
                } else {
                    return false;
                }
            }else {
                return false;
            }
        }
    }

/*
*  课时结束
*/
    public function live_end ()
    {
        $str = new CourseModel();
        $redis = $str::redis();
        $data = $redis->hVals('live');
        foreach ($data as $value) {
            $rew[] = json_decode($value,true);
            if ($value['status'] == 2) {
                $array[] = $value;
            }
        }
        return $array;
    }

    /**
     * 课时列表
     */
    public function liveList ($id)
    {
        $str = new CourseModel();
        $redis = $str::redis();
        $data = $redis->hVals('live');
        foreach ($data as $k => $val) {
            $rew[] = json_decode($val,true);
            if ($rew[$k]['course_id'] == $id) {
                $array[] = json_decode($val,true);
            }
        }

        foreach ($array as $value) {
            $live_id[] = $value['id'];
            asort($live_id);
            $liveId = array_values($live_id);
        }
        $len = count($liveId);
        for ($k = 0; $k < $len; $k++) {
            $res[] = json_decode($redis->hGet('live',$liveId[$k]),true);
        }
        return $res;
    }

    /**
     * 获取某一课时
     */
    public function live ($res)
    {
        $str = new CourseModel();
        $redis = $str::redis();
        $course_id = $res['course_id'];
        $data = json_decode($redis->hGet('course',$course_id),true);
        $res['course_name'] = $data['course_name'];
        return $res;
    }

    /**
     * 某一课时修改
     */
    public function liveUpdate ($data,$id)
    {
        $str = new CourseModel();
        $redis = $str::redis();
        $rew = json_decode($redis->hGet('live',$id),true);
        $live = D('live');
        unset($data['id']);
        unset($data['realtime']);
        $array = array_diff_assoc($data,$rew);
        if (empty($array['subject']) && empty($array['startDate']) && empty($array['invalidDate'])) {
            $rew['subject'] = !empty($array['subject']) ? $array['subject'] : $data['subject'];
            $rew['startDate'] = !empty($array['startDate']) ? $array['startDate'] : $data['startDate'];
            $rew['invalidDate'] = !empty($array['invalidDate']) ? $array['invalidDate'] : $data['invalidDate'];
            $rew['course_id'] = !empty($array['course_id']) ? $array['course_id'] : $data['course_id'];
            $rew['lector'] = !empty($array['lector']) ? $array['lector'] : $data['lector'];
            if ($live->where("id = '$id'")->save($rew)) {
                $list = json_encode($rew);
                $redis->hSet('live',$id,$list);
             }else {
                return false;
             }
        }else {
            $rew['subject'] = !empty($array['subject']) ? $array['subject'] : $data['subject'];
            $rew['startDate'] = !empty($array['startDate']) ? $array['startDate'] : $data['startDate'];
            $rew['invalidDate'] = !empty($array['invalidDate']) ? $array['invalidDate'] : $data['invalidDate'];
            $rew['course_id'] = !empty($array['course_id']) ? $array['course_id'] : $data['course_id'];
            $rew['lector'] = !empty($array['lector']) ? $array['lector'] : $data['lector'];
            $list = json_encode($rew);
            $redis->hSet('live',$id,$list);
            return $rew;
        }
    }

    /**
     * 添加订单
     */
    public function addOrder ($data)
    {
        $status = C('status');
        $msg = C('msg');
        $pay = C('alipay_config');
        $order = D('order');
        $content = array();
        $resource = new SubmitController();
        $str = new CourseModel();
        $redis = $str::redis();
        $order_id = $redis->get('order_id');
        if (empty($order_id)) {
            $redis->set('order_id',100000);
        }
        $course_id = $data['course_id'];
        $uid = $data['uid'];
        $rex = json_decode($redis->hGet('order',$uid),true);
        if (!empty($rex)) {
            unset($rex['id']);
            unset($rex['uid']);
            unset($rex['course_id']);
            unset($rex['subject']);
            unset($rex['boy']);
            unset($rex['total_amount']);
            unset($rex['pay_ways']);
            unset($rex['order_sn']);
            unset($rex['pay_trade_no']);
            unset($rex['pay_notify_id']);
            unset($rex['pay_buyer_email']);
            unset($rex['create_time']);
            unset($rex['pay_time']);

        }else {
            $rex = $order->field('pay_status')->where("uid = '$uid' and course_id = '$course_id' and pay_status = '1'")->find();
        }

        foreach ($rex as $val) {
            if ($val['course_id'] == $course_id && $rex['pay_status'] == 1) {
                echo $resource::state($status[0],'您已经支付过此课程');exit();
            }
        }
        if (is_numeric($course_id) && is_numeric($uid)) {
            foreach ($rex as $k => $value) {
                if ($rex[$k]['course_id'] == $course_id && $rex[$k]['pay_status'] == 2) {
                    $ret = json_decode($value,true);
                }
            }
            if (!empty($ret)) {
                unset($ret['uid']);
                unset($ret['course_id']);
                unset($ret['pay_ways']);
                unset($ret['order_sn']);
                unset($ret['pay_trade_no']);
                unset($ret['pay_notify_id']);
                unset($ret['pay_buyer_email']);
                unset($ret['create_time']);
                unset($ret['pay_time']);
                unset($ret['pay_status']);
                $res = $ret;
            }else {
                $res = $order->field('id,total_amount,subject,boy')->where("course_id = '$course_id' and uid = '$uid' and pay_status = '2'")->find();
            }
            if (!empty($res)) {
                $out_trade_no = $res['id'];
                $total_amount = sprintf('%.2f', $res['total_amount']);
                $subject = $res['subject'];
                $boy = $res['boy'];
            }else {
                $course = json_decode($redis->hGet('course',$course_id),true);
                if (!empty($course)) {
                    unset($course['is_free']);
                    unset($course['num_class']);
                    unset($course['old_price']);
                    unset($course['startDate']);
                    unset($course['invalidDate']);
                    unset($course['lector']);
                    unset($course['people']);
                    unset($course['book']);
                    unset($course['cover']);
                    unset($course['detail_cover']);
                    unset($course['pay_status']);
                    unset($course['type']);
                }else {
                    $course = M('course')->field('now_price,course_name,introduction')->where("id = '$course_id'")->find();
                }
                $data['subject'] = $course['course_name'];
                $data['boy'] = $course['introduction'];
                $data['total_amount'] = $course['now_price'];
                if ($order->add($data)) {
                    $redis->incr('order_id');
                    $orderId = $redis->get('order_id');
                    $data['id'] = $orderId;
                    $data['create_time'] = time();
                    $list = json_encode($data);
                    $redis->hSet('order',$uid,$list);
                    $dat = $order->field('id')->where("course_id = '$course_id' and uid = '$uid'")->find();
                    $out_trade_no = $dat['id'];
                    $total_amount = sprintf('%.2f', $data['total_amount']);
                    $subject = $data['subject'];
                    $boy = $data['boy'];
                } else {
                    echo $resource::state(0, 'fail');exit();
                }
            }
            $content['product_code'] = $pay['product_code'];
            $content['total_amount'] = $total_amount;
            $content['subject'] = $subject;
            $content['body'] = $boy;
            $content['out_trade_no'] = $out_trade_no;
            $con = json_encode($content);//$content是biz_content的值,将之转化成字符串
            return $con;
        }else {
            echo $resource::state(403, $msg[2]);exit();
        }
    }

    /**
     * 获取订单
     */
    public function order ($uid,$id)
    {
        $str = new CourseModel();
        $redis = $str::redis();
        $order = json_decode($redis->hGet('order',$uid.$id),true);
        return $order;
    }

    /**
     * @param $uid
     * @param $id
     * @return mixed
     * 课程收藏
     */
    public function collection ($uid,$id)
    {
        $str = new CourseModel();
        $redis = $str::redis();
        $collection = json_decode($redis->hGet('courseCollection',$uid.$id),true);
        return $collection;
    }
}