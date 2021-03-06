<?php

namespace Api\Controller;

use Think\Controller;
use Common\Model\CourseModel;
use Api\Controller\CacheController;
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
        $str = new CourseModel();
        $redis = $str::redis();
        if (is_numeric($page)) {
            $res = $redis->hVals('course');
            if (!empty($res)) {
                $key = $redis->HKEYS('course');
                arsort($key);
                $key1 = array_values($key);
                $length = count($key1);
                for ($f = 0; $f < $length; $f++) {
                    $array[] = json_decode($redis->hGet('course',$key1[$f]),true);
                }
                $data = $array;
            }else {
                $data = $course->table('cmf_course  as  a')
                    ->field('a.id,a.course_name,a.startDate,a.invalidDate,a.now_price,a.old_price,a.cover,a.num_class,a.status,a.is_free,a.introduction,a.number,a.stu_token,a.reply_url,people,book,lector')
                    ->order('a.id DESC')->select();
            }
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
        $getClass = new CacheController();
        if (!empty($uid)) {
            if (!empty($id) && is_numeric($id) && is_numeric($uid)) {
                    $courseCollection = $getClass->collection($uid,$id);
                    if (!empty($courseCollection)) {
                        $collection = $courseCollection;
                    }else {
                        $collection = M('course_collection')->field('type')->where("uid = '$uid' and course_id = '$id'")->find();
                    }
                    $course = $getClass->course($id);
                    if (!empty($course)) {
                        $rew = $course;
                    }else {
                        $rew = M('course')->table('cmf_course as a')
                            ->field('id,course_name,now_price,num_class,cover,people,book,lector,introduction,detail_cover')
                            ->where("a.id = $id")->find();
                    }
                    if (empty($collection) || $collection['type'] == 0) {
                        $rew['type'] = 0;
                    }else {
                        $rew['type'] = 1;
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
        $openClass = new CacheController();
        $str = new CourseModel();
        $redis = $str::redis();
        $course = M('course');
        if (is_numeric($page)) {
            $res = $openClass->tax($is_free = 1);
            if (!empty($res)) {
                $data = $res;
            }else {
                $data = $course->table('cmf_course as a')
                    ->field('a.id,a.course_name,a.startDate,a.invalidDate,a.cover,a.status,a.is_free,a.introduction,a.number,a.stu_token,a.reply_url,people,lector')
                    ->where("is_free = '1'")->order('a.id DESC')->select();
            }
            $len = count($data);
            for ($c = 0; $c < $len; $c++) {
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
        $vipClass = new CacheController();
        $course = M('course');
        if (is_numeric($page)) {
            $res = $vipClass->tax($is_free = 2);
            if (!empty($res)) {
                $data = $res;
            }else {
                $data = $course
                    ->field('id,course_name,startDate,invalidDate,now_price,old_price,cover,num_class,status,is_free,introduction,number,stu_token,reply_url,people,book,lector')
                    ->where("is_free = '2'")->order('id DESC')->select();
            }
            $len = count($data);
            for ($c = 0; $c < $len; $c++) {
                $data[$c]['pay_status'] = 0;
                $data[$c]['type'] = 0;
            }
            if (!empty($uid) && is_numeric($uid)) {
                $rew = $this->gotten($uid,$page,$data);
                $is_login = 1;
            }else {
                $rew = $this->page($data,$page);
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
        $reply = new CacheController();
        if (!empty($page) && is_numeric($page)) {
            $res = $reply->reply();
            if (!empty($res)) {
                $data = $res;
            }else {
                $time = time();
                $where = "a.status = '3'";
                $where .= " and UNIX_TIMESTAMP(a.invaliddate) < '$time'";
                $data = $course->table('cmf_course as a')
                    ->field('id,course_name,introduction,num_class,status,is_free,now_price,old_price,number,stu_token,reply_url,class_id,startdate,invaliddate,cover,lector')
                    ->where($where)->order('a.id DESC')->select();
            }
            $len = count($data);
            for ($c = 0; $c < $len; $c++) {
                $data[$c]['pay_status'] = 0;
                $data[$c]['type'] = 0;
            }
            if (!empty($uid) && is_numeric($uid)) {
                $rew = $this->pastLive($uid,$page,$data);
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
    public function classHour_list ($id,$uid,$page)
    {
        $status = C('status');
        $msg = C('msg');
        $live = M('live');
        $response = new SubmitController();
        $liveList = new CacheController();
        if (is_numeric($id)) {
            $res = $liveList->liveList($id);
            if (!empty($res)) {
                $data = $res;
            }else {
                $data = $live->table('cmf_live as a')
                    ->field('a.id,a.subject,a.reply_url,a.status,a.startDate,a.invalidDate,a.number,a.stu_token,a.class_id')
                    ->where("a.course_id = '$id'")->order('a.id')->select();
            }
            $len = count($data);
            for ($a = 0; $a < $len; $a++) {
                $data[$a]['type'] = 0;
            }
            
            if (!empty($uid) && is_numeric($uid)) {
                $rew = $this->classHour($uid,$data,$page);
                $is_login = 1;
            } else {
                $rew = $this->page($data,$page);
                $is_login = 0;
            }
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
        $pageSize = 20;
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
        $c = array_values($c);
        $len3 = count($c);
        if ($len3 == 0) {
            for ($x = 0; $x < $len3; $x++) {
                $data[$c[$x]]['type'] = 0;
            }
        }else {
            for ($x = 0; $x < $len3; $x++) {
                $data[$c[$x]]['type'] = 1;
            }
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
        $v = array_values($v);
        $len1 = count($v);
        if ($len1 == 0) {
            for ($j = 0; $j < $len1; $j++) {
                $data[$v[$j]]['pay_status'] = 0;
            }
        }else {
            for ($j = 0; $j < $len1; $j++) {
                $data[$v[$j]]['pay_status'] = 1;
            }
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

        foreach ($c as $key => $value) {
            if (!is_numeric($value)) {
                unset($c[$key]);
            }
        }
        $c = array_values($c);
        $len3 = count($c);
        if ($len3 == 0) {
            for ($x = 0; $x < $len3; $x++) {
                $data[$c[$x]]['type'] = 0;
            }
        }else {
            for ($x = 0; $x < $len3; $x++) {
                $data[$c[$x]]['type'] = 1;
            }
        }

        $a = $this->page($data,$page);
        return $a;
    }


    /**
     * @param $uid
     * @param $page
     * @param $data
     * @return array|int
     * 往期直播列表
     */
    public function pastLive ($uid,$page,$data)
    {
        foreach ($data as $va) {
            $id[] = $va['id'];
        }

        $res = M('course_collection')->field('course_id')->where("uid = '$uid' and type = '1'")->select();
        $rew = M('order')->field('course_id')->where("uid = '$uid' and pay_status = '1'")->select();
        foreach ($res as $value) {
            $course_id[] = $value['course_id'];
        }
        $len = count($id);
        for ($a = 0; $a < $len; $a++) {
            $c[] = array_search($course_id[$a],$id);
        }

        foreach ($c as $k => $val) {
            if (!is_numeric($val)) {
                unset($c[$k]);
            }
        }
        $c = array_values($c);
        $len2 = count($c);
        if ($len2 == 0) {
            for ($b = 0; $b < $len2; $b++) {
                $data[$c[$b]]['type'] = 0;
            }
        }else {
            for ($b = 0; $b < $len2; $b++) {
                $data[$c[$b]]['type'] = 1;
            }
        }

        foreach ($rew as $valu) {
            $cid[] = $valu['course_id'];
        }
        for ($a = 0; $a < $len; $a++) {
            $d[] = array_search($cid[$a],$id);
        }

        foreach ($d as $k1 => $tem) {
            if (!is_numeric($tem)) {
                unset($d[$k1]);
            }
        }
        $d = array_values($d);
        $len4 = count($d);
        if ($len4 == 0) {
            for ($e = 0; $e < $len4; $e++) {
                $data[$d[$e]]['pay_status'] = 0;
            }
        }else {
            for ($e = 0; $e < $len4; $e++) {
                $data[$d[$e]]['pay_status'] = 1;
            }
        }
        $a = $this->page($data,$page);
        return $a;
    }

    /**
     * @param $uid
     * @param $data
     * 课时拼接数据
     */
    public function classHour ($uid,$data,$page)
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
        $c = array_values($c);
        $len2 = count($c);
        if ($len2 == 0) {
            for ($b = 0;$b < $len2; $b++) {
                $data[$c[$b]]['type'] = 0;
            }
        }else {
            for ($b = 0;$b < $len2; $b++) {
                $data[$c[$b]]['type'] = 1;
            }
        }
        // $len3 = count($id);
        // for ($m = 0; $m < $len3; $m++) {
        //     $data[$m]['startdate'] = date('Y-m-d H:i:s',$data[$m]['startdate']);
        //     $data[$m]['invaliddate'] = date('Y-m-d H:i:s',$data[$m]['invaliddate']);
        // }
        $a = $this->page($data,$page);
        return $a;
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
            'invalidDate' => $invalidDate,
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
        $model = new SubmitController();
        $url = "http://junwei.gensee.com/integration/site/training/room/deleted";
        $len = count($class_id);
        if ($len > 1) {
            for ($k = 0; $k < $len; $k++) {
                $data[] = array(
                    'loginName' => $loginName,
                    'password' => $password,
                    'roomId' => $class_id[$k]
                );
            }
            for ($a = 0; $a < $len; $a++) {
                $result = $model->post($url, $data[$a]);
            }
        }else {
            $data = array(
                'loginName' => $loginName,
                'password' => $password,
                'roomId' => $class_id
            );
            if (is_array($data['roomId'])) {
                $data['roomId'] = $class_id[0];
            }
            $result = $model->post($url, $data);
        }
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
    
    /*
    *修改课件
    */
    public static function update_live ($courseware_id,$subject,$loginName,$password)
    {
        $url = 'http://junwei.gensee.com/integration/site/training/courseware/modify';
        $model = new SubmitController();
        $data = array(
            'id' => $courseware_id,
            'subject'  => $subject,
            'loginName' => $loginName,
            'password' => $password,
        );

        $result = $model->post($url, $data);
        $result = json_decode($result, true);
        return $result;
    }



    /**
     * 删除课件
     */
    public static function delete_live ($loginName,$password,$coursewareId)
    {
        $url = 'http://junwei.gensee.com/integration/site/training/courseware/deleted';
        $model = new SubmitController();
        $len = count($coursewareId);
        if ($len > 1) {
            for ($i=0; $i < $len; $i++) { 
                $data[] = array(
                    'loginName' => $loginName,
                    'password' => $password,
                    'coursewareId' => $coursewareId[$i]
                );
            }
            for ($a = 0; $a < $len; $a++) {
                $result = $model->post($url, $data[$a]);
            }
        }else {
            $data = array(
                'loginName' => $loginName,
                'password' => $password,
                'coursewareId' => $coursewareId
            );
            if (is_array($data['coursewareId'])) {
                $data['coursewareId'] = $class_id[0];
            }
            $result = $model->post($url, $data);
        }
        $result = json_decode($result, true);
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

    /**
     * @return array
     * 6位随机字符串
     */
    public function randNum ()
    {
        $array= array();
        for($i=1;$i<=500000;$i++){
            $string = substr(md5(uniqid($i.'x'.$i.'f'.$i.'s'.$i.rand(), true)),-6);
            array_push($array,$string);
        }
        $result = array_unique($array);
        $len = count($result);
        $max = $len-1;
        $k=mt_rand(0,$max);
        $result = $result[$k];
        return $result;
    }

    /**
     * @param $id
     * @param $uid
     * @return string
     * 分享页面课堂详情
     */
    public function classDesc($id)
    {
        $status = C('status');
        $msg = C('msg');
        $response = new SubmitController();
        if (is_numeric($id)) {
            $rew = M('course')->table('cmf_course as a')
                ->field('a.id,a.course_name,a.now_price,a.num_class,cover,people,book,introduction,detail_cover,lector,startDate,invalidDate,is_free,number,stu_token,class_id')
                ->where("a.id = $id")->find();
            return $response::state($status[3],$msg[3],$rew);
        }else {
            return $response::state($status[3],$msg[3]);
        }

    }

    /**
     * @param $id
     * @param $uid
     * @return string
     * 分享页面课时详情
     */
    public function liveDesc ($id,$uid)
    {

        $status = C('status');
        $msg = C('msg');
        $order = M('order');
        $response = new SubmitController();
        if (is_numeric($id) && is_numeric($uid)) {
            $rew = M('live')->table('cmf_live as a')
                ->field('a.id,a.subject,a.status,a.startDate,a.invalidDate,a.reply_url,a.stu_token,a.class_id,b.introduction,a.number,a.course_id')
                ->join('cmf_course as b ON a.course_id = b.id')
                ->where("a.id = '$id'")->find();
            $course_id = $rew['course_id'];
            if (!empty($course_id)) {
                $data = $order->where("uid = '$uid' and course_id = '$course_id' and pay_status = '1'")->find();
                if (!empty($data)) {
                    return json_encode(['status' => $status[0],'msg' => $msg[0],'data' => $rew]);
                }else {
                    return $response::state($status[0],'此课程属于vip课，请先购买后观看');
                }
            }else {
                return $response::state($status[2],$msg[2]);
            }
        }else {
            return $response::state($status[2],$msg[2]);
        }
    }
}