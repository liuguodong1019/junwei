<?php

namespace Api\Controller;

use Think\Controller;

use Api\Controller\SubmitController;

class ResponseController extends Controller
{
    /**
     * @param $uid
     * @param $page
     * @return string
     * 直播列表
     */
    public function get_class_list($uid, $page)
    {
        $succ = C('status');
        $mess = C('msg');
        $course = M('course');
        $model = new SubmitController();
        if (is_numeric($page)) {
            $data = $course->table('cmf_course  as  a')
                ->field('a.id,a.course_name,a.startDate,a.invalidDate,a.now_price,a.old_price,b.name,a.cover,a.num_class,a.status,a.is_free,a.introduction,a.number,a.stu_token,a.reply_url')
                ->join('STRAIGHT_JOIN cmf_lector AS b ON a.lector_id = b.l_id')
                ->join('STRAIGHT_JOIN cmf_people AS d ON a.people_id = d.p_id')
                ->join('STRAIGHT_JOIN cmf_book AS e ON a.book_id = e.b_id')
                ->order('a.id DESC')->select();
            $len = count($data);
            for ($c = 0; $c < $len; $c++) {
                $data[$c]['pay_status'] = 0;
                $data[$c]['type'] = 0;
            }
            if (!empty($uid) && is_numeric($uid)) {
                $rew = $this->gotten($uid, $page, $data);
                $is_login = 1;
            } else {
                $rew = $this->page($data, $page);
                $is_login = 0;
            }
            if (!empty($data)) {
                return json_encode([
                    'status' => $succ[0],
                    'msg' => $mess[0],
                    'is_login' => $is_login,
                    'data' => $rew
                ]);
            } else {
                return $model::state($succ[0], $mess[4], $data = null);
            }
        } else {
            return $model::state($succ[2], $mess[2], $data = null);
        }
    }

    /**
     * @param $id
     * @param $uid
     * @return string
     * 课堂详情
     */
    public function get_class($id,$uid)
    {
        $succ = C('status');
        $mess = C('msg');
        $model = new SubmitController();
        if (!empty($uid)) {
            if (!empty($id) && is_numeric($id) && is_numeric($uid)) {
                $order = M('order')->field('pay_status')->where("uid = '$uid' and course_id = '$id'")->find();
                if (empty($order) || $order['pay_status'] == 0 || $order['pay_status'] == 2) {
                    $collection = M('course_collection')->field('type')->where("uid = '$uid' and course_id = '$id'")->find();
                    $rew = M('course')->table('cmf_course as a')
                        ->field('id,course_name,now_price,name,num_class,cover,people,book,introduction')
                        ->join('STRAIGHT_JOIN cmf_lector as b ON a.lector_id = b.l_id')
                        ->join('STRAIGHT_JOIN cmf_people as c ON a.people_id = c.p_id')
                        ->join('STRAIGHT_JOIN cmf_book as d ON a.book_id = d.b_id')
                        ->where("a.id = $id")->find();
                    if (empty($collection) || $collection['type'] == 0) {
                        $rew['type'] = 0;
                    }else {
                        $rew['type'] = 1;
                    }
                }else {
                    $data = M('live')->table('cmf_live as a')
                        ->field('a.id,a.subject,b.introduction,a.status,a.startDate,a.invalidDate,a.reply_url,a.stu_token,a.number')
                        ->join('STRAIGHT_JOIN cmf_course as b ON a.course_id = b.id')
                        ->where("a.course_id = $id")->select();
                    $rew = $this->classHour($uid,$data);
                }
                if (!empty($rew)) {
                    return json_encode([
                        'status' => $succ[0],
                        'msg' => $mess[0],
                        'data' => $rew
                    ]);
                } else {
                    return $model::state($succ[1], $mess[1]);
                }
            }else {
                return $model::state($succ[2], $mess[2]);
            }
        }else {
            return $model::state($succ[0],'暂无登录操作');
        }
    }

    /**
     * @param $page
     * @param $uid
     * @return string
     * 公开课列表
     */
    public function openClass ($page,$uid)
    {
        $succ = C('status');
        $mess = C('msg');
        $model = new SubmitController();
        $course = M('course');
        if (is_numeric($page)) {
            $data = $course->table('cmf_course as a')
                ->field('a.id,course_name,now_price,old_price,startDate,invalidDate,cover,a.status,a.introduction,b.name')
                ->join('STRAIGHT_JOIN cmf_lector AS b ON a.lector_id = b.l_id')
                ->where("is_free = '1'")->order('a.id DESC')->select();
            $len = count($data);
            for ($c = 0; $c < $len; $c++) {
                $data[$c]['pay_status'] = 0;
                $data[$c]['type'] = 0;
            }
            if (!empty($uid) && is_numeric($uid)) {
                $rew = $this->open_class($uid,$page,$data);
                $is_login = 1;
            } else {
                $rew = $this->page($data,$page);
                $is_login = 0;
            }
            if (!empty($data)) {
                return json_encode([
                    'status' => $succ[0],
                    'msg' => $mess[0],
                    'is_login' => $is_login,
                    'data' => $rew
                ]);
            } else {
                return $model::state($succ[0], $mess[4],$data = null);
            }
        }else {
            return $model::state($succ[2], $mess[2],$data = null);
        }
    }

    /**
     * @param $page
     * @param $uid
     * @return string
     * vip课程列表
     */
    public function vip ($page,$uid)
    {
        $succ = C('status');
        $mess = C('msg');
        $model = new SubmitController();
        $course = M('course');
        if (is_numeric($page)) {
            $data = $course->table('cmf_course as a')
                ->field('a.id,course_name,now_price,old_price,name,startdate,invaliddate,cover,num_class,status,a.introduction')
                ->join('left join cmf_people as b ON a.people_id = b.p_id')
                ->join('left join cmf_lector as d ON a.lector_id = d.l_id')
                ->join('left join cmf_book as e ON a.book_id = e.b_id')
                ->where("a.is_free = '2'")->order('a.id DESC')->select();
            $len = count($data);
            for ($c = 0; $c < $len; $c++) {
                $data[$c]['pay_status'] = 0;
                $data[$c]['type'] = 0;
            }
            if (!empty($uid) && is_numeric($uid)) {
                $rew = $this->gotten($uid,$page,$data);
                $is_login = 1;
            }else {
                $rew = $this->page($uid,$page);
                $is_login = 0;
            }
            if (!empty($data)) {
                return json_encode([
                    'status'   => $succ[0],
                    'msg'      => $mess[0],
                    'is_login' => $is_login,
                    'data'     => $rew
                ]);
            } else {
                return $model::state($succ[0], $mess[4],$data = null);
            }
        } else {
            return $model::state($succ[2], $mess[2]);
        }
    }

    /**
     * @param $page
     * @param $uid
     * @return string
     * 往期直播
     */
    public function past_live ($page,$uid)
    {
        $succ = C('status');
        $mess = C('msg');
        $course = M('course');
        $model = new SubmitController();
        if (!empty($page) && is_numeric($page)) {
            $time = time();
            $where = "a.status = '3'";
            $where .= " and UNIX_TIMESTAMP(a.invaliddate) < '$time'";
            $data = $course->table('cmf_course as a')
                ->field('id,course_name,introduction,num_class,status,is_free,now_price,old_price,number,stu_token,reply_url,class_id,startdate,invaliddate,cover,name')
                ->join('STRAIGHT_JOIN cmf_lector as b ON a.lector_id = b.l_id')
                ->where($where)->order('a.id DESC')->select();
            $len = count($data);
            for ($c = 0; $c < $len; $c++) {
                $data[$c]['pay_status'] = 0;
                $data[$c]['type'] = 0;
            }
            if (!empty($uid) && is_numeric($uid)) {
                $rew = $this->gotten($uid,$page,$data);
                $is_login = 1;
            } else {
                $rew = $this->page($data,$page);
                $is_login = 0;
            }
            if (!empty($data)) {
                return json_encode([
                    'status' => $succ[0],
                    'msg' => $mess[0],
                    'is_login' => $is_login,
                    'data' => $rew
                ]);
            }else {
                return $model::state($succ[1], $mess[4], $data = null);
            }
        }else {
            return $model::state($succ[2], $mess[2], $data = null);
        }
    }

    /**
     * @param $data
     * 课程分享
     */
    public function courseShare ($data)
    {
        $status = C('status');
        $course_share = M('course_share');
        $uid = $data['uid'];
        $rew = $course_share->field('id,num,share_way,course_id')->where("uid = '$uid'")->find();
        switch ($rew) {
            case 0:
                if ($course_share->add($data)) {
                    return json_encode([$status[0],'分享成功']);
                }else {
                    return json_encode([$status[1],'分享失败']);
                }
                break;
            default:
                if ($data['share_way'] != $rew['share_way']) {
                    if ($course_share->add($data)) {
                        return json_encode([$status[0],'分享成功']);
                    }else {
                        return json_encode([$status[1],'分享失败']);
                    }
                }elseif ($data['course_id'] != $rew['course_id']) {
                    if ($course_share->add($data)) {
                        return json_encode([$status[0],'分享成功']);
                    }else {
                        return json_encode([$status[1],'分享失败']);
                    }
                }
                $dat['num'] = $rew['num']+1;
                $dat['create_time'] = time();
                if ($course_share->where("uid = '$uid'")->save($dat)) {
                    return json_encode([$status[0],'分享成功']);
                }else {
                    return json_encode([$status[1],'分享失败']);
                }
                break;
        }
    }

    /**
     * @param $data
     * @return string
     * 课时分享
     */
    public function liveShare ($data)
    {
        $status = C('status');
        $shareLive = M('live_share');
        $uid = $data['uid'];
        $rew = $shareLive->field('course_id,live_id,share_way,num')->where("uid = '$uid'")->find();
        switch ($rew) {
            case 0:
                switch ($shareLive->add($data)) {
                    case 1:
                        return json_encode([$status[0],'分享成功']);
                        break;
                    default:
                        return json_encode([$status[1],'分享失败']);
                        break;
                }
                break;
            default:
                if ($data['course_id'] != $rew['course_id']) {
                    switch ($shareLive->add($data)) {
                        case 1:
                            return json_encode([$status[0],'分享成功']);
                            break;
                        default:
                            return json_encode([$status[1],'分享失败']);
                            break;
                    }
                }elseif ($data['live_id'] != $rew['live_id']) {
                    switch ($shareLive->add($data)) {
                        case 1:
                            return json_encode([$status[0],'分享成功']);
                            break;
                        default:
                            return json_encode([$status[1],'分享失败']);
                            break;
                    }
                }elseif ($data['share_way'] != $rew['share_way']) {
                    switch ($shareLive->add($data)) {
                        case 1:
                            return json_encode([$status[0],'分享成功']);
                            break;
                        default:
                            return json_encode([$status[1],'分享失败']);
                            break;
                    }
                }else {
                    $dat['num']         = $rew['num'] + 1;
                    $dat['create_time'] = time();
                    switch ($shareLive->where("uid = '$uid'")->save($dat)) {
                        case 1:
                            return json_encode([$status[0],'分享成功']);
                            break;
                        default:
                            return json_encode([$status[1],'分享失败']);
                            break;
                    }
                }
                break;
        }
    }


    /**
     * @param $id
     * @param $uid
     * @param $page
     * @return string
     * 课时列表
     */
    public function classHour_list ($id,$uid)
    {
        $status = C('status');
        $msg = C('msg');
        $live = M('live');
        $response = new SubmitController();
        if (is_numeric($id)) {
            $data = $live
                ->field('id,subject,reply_url,status,startDate,invalidDate,number,stu_token,class_id')
                ->where("course_id = '$id'")->order('cmf_live.id')->select();
            $len = count($data);
            for ($a = 0; $a < $len; $a++) {
                $data[$a]['type'] = 0;
            }
            $rew = $this->classHour($uid,$data);
            if (!empty($data)) {
                return json_encode([
                    'status' => $status[0],
                    'msg' => $msg[0],
                    'data' => $rew
                ]);
            } else {
                return $response::state($status[0], $msg[0], $data = null);
            }
        } else {
            return $response::state($status[2], $msg[2]);
        }
    }

    /**
     * 数组分页
     */
    public function page ($data,$page)
    {
        $pageSize = 10;
        $start=($page- 1) *$pageSize;
        $show=array_slice($data,$start,$pageSize);
        return $show;
    }

    /**
     * 直播列表拼接数据
     */
    public function gotten ($uid,$page,$data)
    {
        foreach ($data as $va) {
            $id[] = $va['id'];
        }

        //是否收藏
        $res = M('course_collection')->field('type,course_id')->where("uid = '$uid' and type = '1'")->select();
        foreach ($res as $value) {
            $course_id[] = $value['course_id'];
        }
        $len2 = count($course_id);
        for ($a =0; $a < $len2; $a++) {
            $c[] = array_search($course_id[$a],$id);
        }

        foreach ($c as $k => $aa) {
            if (!is_numeric($aa)) {
                unset($c[$k]);
            }
        }
        $len3 = count($c);
        for ($x = 0; $x < $len3; $x++) {

            $data[$c[$x]]['type'] = 1;
        }
        //是否支付
        $rew = M('order')->field('course_id,pay_status')->where("uid = '$uid' and pay_status = '1'")->select();
        foreach ($rew as $val) {
            $cid[] = $val['course_id'];
        }
        $len = count($cid);

        for ($k =0; $k < $len; $k++) {
            $v[] = array_search($cid[$k],$id);
        }
        foreach ($v as $k1 => $a2) {
            if (!is_numeric($a2)) {
                unset($v[$k1]);
            }
        }
        $len1 = count($v);
        for ($j = 0; $j < $len1; $j++) {
            $data[$v[$j]]['pay_status'] = 1;
        }

        $rew = $this->page($data,$page);
        return $rew;
    }

    /**
     * @param $uid
     * @param $page
     * @param $data
     * @return array|int
     * 公开课列表
     */
    public function open_class ($uid,$page,$data)
    {
        $res = M('course_collection')->field('type,course_id')->where("uid = '$uid' and type = '1'")->select();

        foreach ($res as $value) {
            $course_id[] = $value['course_id'];
        }
        foreach ($data as $va) {
            $id[] = $va['id'];
        }
        $len2 = count($course_id);
        for ($a =0; $a < $len2; $a++) {
            $c[] = array_search($course_id[$a],$id);
        }
        $len3 = count($c);
        for ($x = 0; $x < $len3; $x++) {
            if (!is_numeric($c[$x])) {
                $a = $this->page($data,$page);
                return $a;
            }
            $data[$c[$x]]['type'] = 1;
        }
        $a = $this->page($data,$page);
        return $a;
    }


//    /**
//     * @param $uid
//     * @param $page
//     * @param $data
//     * @return array|int
//     * 往期直播列表
//     */
//    public function pastLive ($uid,$page,$data)
//    {
//        foreach ($data as $va) {
//            $id[] = $va['id'];
//        }
//        $res = M('course_collection')->field('course_id')->where("uid = '$uid' and type = '1'")->select();
//        $rew = M('order')->field('course_id')->where("uid = '$uid' and pay_status = '1'")->select();
//        foreach ($res as $value) {
//            $course_id[] = $value['course_id'];
//        }
//        $len = count($id);
//        for ($a = 0; $a < $len; $a++) {
//            $c[] = array_search($course_id[$a],$id);
//        }
//
//        foreach ($c as $k => $val) {
//            if (!is_numeric($val)) {
//                unset($c[$k]);
//            }
//        }
//        $len2 = count($c);
//        for ($b = 0; $b < $len2; $b++) {
//            $data[$c[$b]]['type'] = 1;
//        }
//        foreach ($rew as $valu) {
//            $cid[] = $valu['course_id'];
//        }
//        for ($a = 0; $a < $len; $a++) {
//            $d[] = array_search($cid[$a],$id);
//        }
//
//        foreach ($d as $k1 => $tem) {
//            if (!is_numeric($tem)) {
//                unset($d[$k1]);
//            }
//        }
//
//        $len4 = count($d);
//        for ($e = 0; $e < $len4; $e++) {
//            $data[$d[$e]]['pay_status'] = 1;
//        }
//        $a = $this->page($data,$page);
//        return $a;
//    }

    /**
     * @param $uid
     * @param $data
     * 课时拼接数据
     */
    public function classHour ($uid,$data)
    {
        foreach ($data as $va) {
            $id[] = $va['id'];
        }
        $res = M('live_collection')->field('type,live_id')->where("uid = '$uid' and type = '1'")->select();

        foreach ($res as $va) {
            $live_id[] = $va['live_id'];
        }

        $len = count($live_id);
        for ($k = 0; $k < $len; $k++) {
            $c[] = array_search($live_id[$k],$id);
        }

            foreach ($c as $k => $va) {
                if (!is_numeric($va)) {
                    unset($c[$k]);
                }
            }
        $len2 = count($c);
        for ($b = 0;$b < $len2; $b++) {
            $data[$c[$b]]['type'] = 1;
        }
        return $data;
    }
    /**
     * 创建实时课堂
     */
    public static function create_course($subject, $loginName, $password, $startDate, $invalidDate)
    {
        $url = "http://junwei.gensee.com/integration/site/training/room/created";
        $data = array(
            'loginName' => $loginName,
            'password' => $password,
            'subject' => $subject,
            'startDate' => $startDate,
            'invalidDate' => $invalidDate
        );
        $model = new SubmitController();
        $result = $model->post($url, $data);
        $result = json_decode($result, true);
        return $result;
    }

    /**
     * 修改实时课堂
     */
    public static function update_course($loginName, $password, $realtime, $startDate, $invalidDate, $subject, $class_id)
    {
        $url = "http://junwei.gensee.com/integration/site/training/room/modify";
        $data = array(
            'loginName' => $loginName,
            'password' => $password,
            'subject' => $subject,
            'startDate' => $startDate,
            'realtime' => $realtime,
            'invalidDate' => $invalidDate,
            'id' => $class_id
        );
        $model = new SubmitController();
        $result = $model->post($url, $data);
        $result = json_decode($result, true);
        return $result;
    }

    /**
     * 删除实时课堂
     */
    public static function delete($loginName, $password, $class_id)
    {
        $url = "http://junwei.gensee.com/integration/site/training/room/deleted";
        $len = count($class_id);
        if ($len > 1) {
            for ($k = 0; $k < $len; $k++) {
                $data = array(
                    'loginName' => $loginName,
                    'password' => $password,
                    'roomId' => $class_id[$k]
                );
            }
        } else {
            $data = array(
                'loginName' => $loginName,
                'password' => $password,
                'roomId' => $class_id
            );
        }

        $model = new SubmitController();
        $result = $model->post($url, $data);
        $result = json_decode($result, true);
        return $result;
    }

    /**
     * 获取录制的课件
     */
    public static function get_past($loginName, $password, $class_id)
    {
        $url = "http://junwei.gensee.com/integration/site/training/courseware/list";
        $model = new SubmitController();
        $len = count($class_id);
        for ($a = 0; $a < $len; $a++) {
            $data[] = array(
                'loginName' => $loginName,
                'password' => $password,
                'roomId' => $class_id[$a]
            );
        }
        $length = count($data);
        for ($j = 0; $j < $length; $j++) {
            $result[] = $model->post($url, $data[$j]);
        }
        return $result;
    }

    /**
     * 创建讲师接口
     */
    public static function create_lector($loginName,$password,$name,$teacherLoginName,$teacherPassword)
    {
        $url = 'http://junwei.gensee.com/integration/site/training/teacher/created';
        $model = new SubmitController();
        $data = array(
            'loginName' => $loginName,
            'password' => $password,
            'name' => $name,
            'teacherLoginName' => $teacherLoginName,
            'teacherPassword' => $teacherPassword,
        );
        $result = $model->post($url, $data);
        $result = json_decode($result, true);
        return $result;
    }
}