<?php
namespace Api\Controller;

use Think\Controller;
use Api\Controller\ResponseController;
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
        if (IS_GET) {
            $course = M('course');
            $page = I('get.page');
            $uid = I('get.uid');
            if (is_numeric($page)) {
                $where = "a.is_up = '0'";
                if (!empty($uid) && is_numeric($uid)) {
                    $data = M()->table('cmf_course  as  a')
                        ->field('a.id,a.course_name,a.startDate,a.invalidDate,a.now_price,a.old_price,b.name,a.cover,a.num_class,a.status,a.is_free,a.introduction,a.number,a.stu_token,a.reply_url,c.pay_status')
//                        ->join('STRAIGHT_JOIN cmf_people ON cmf_course.people_id = cmf_people.p_id')
                        ->join('left join cmf_order AS c ON a.id = c.course_id')
                        ->where("c.uid = '$uid'")
                        ->join('STRAIGHT_JOIN cmf_lector AS b ON a.lector_id = b.l_id')
//                        ->join('STRAIGHT_JOIN cmf_book ON cmf_course.book_id = cmf_book.b_id')
                        ->where($where)->order('a.id')->page($page . ',10')->select();
                }else {
                    $data = $course
                        ->field('cmf_course.id,cmf_course.course_name,cmf_course.startDate,cmf_course.invalidDate,cmf_course.now_price,cmf_course.old_price,cmf_lector.name,cmf_course.cover,cmf_course.num_class,cmf_course.status,cmf_course.is_free,cmf_course.introduction,cmf_course.number,cmf_course.stu_token,cmf_course.reply_url')
                        ->join('STRAIGHT_JOIN cmf_people ON cmf_course.people_id = cmf_people.p_id')
                        ->join('STRAIGHT_JOIN cmf_lector ON cmf_course.lector_id = cmf_lector.l_id')
                        ->join('STRAIGHT_JOIN cmf_book ON cmf_course.book_id = cmf_book.b_id')
                        ->where($where)->order('cmf_course.id')->page($page . ',10')->select();
                }
                if (!empty($data)) {
                    if (!empty($uid)) {
                        echo json_encode([
                            'status'   => $succ[0],
                            'msg'      => $mess[0],
                            'is_login' => 1,
                            'data'     => $data
                        ]);die;
                    }else {
                        echo json_encode([
                            'status'   => $succ[0],
                            'msg'      => $mess[0],
                            'is_login' => 0,
                            'data'     => $data
                        ]);die;
                    }

                } else {
                    echo $model::state($succ[0], $mess[4],$data = null);die;
                }
            } else {
                echo $model::state($succ[2], $mess[2]);die;
            }
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
        if (IS_GET) {
            $id = I('get.id');
            $uid = I('get.uid');
            if (!empty($uid)) {
                if (is_numeric($id)  && is_numeric($uid)) {
                    $order = M('order')->field('pay_status')->where("uid = '$uid' and course_id = '$id'")->find();
                    if (empty($order) || $order['pay_status'] == 0 || $order['pay_status'] == 2) {
                        $collection = M('course_collection')->field('type')->where("uid = $uid")->find();
                        $data = M('course')
                            ->field('id,course_name,now_price,name,num_class,cover,people,book,introduction')
                            ->join('STRAIGHT_JOIN cmf_people ON cmf_course.people_id = cmf_people.p_id')
                            ->join('STRAIGHT_JOIN cmf_lector ON cmf_course.lector_id = cmf_lector.l_id')
                            ->join('STRAIGHT_JOIN cmf_book ON cmf_course.book_id = cmf_book.b_id')
                            ->where("id = $id")->find();
                        if (empty($collection) || $collection['type'] == 0) {
                            $data['type'] = 0;
                        }else {
                            $data['type'] = 1;
                        }
                    }else {
                        $data = M('live')
                            ->field('cmf_live.id,cmf_live.subject,cmf_course.introduction,cmf_live.status,cmf_live.startDate,cmf_live.invalidDate,cmf_live.reply_url,cmf_live.stu_token,cmf_live.number,cmf_live_collection.type')
                            ->join('STRAIGHT_JOIN cmf_course ON cmf_live.course_id = cmf_course.id')
                            ->join('left join cmf_live_collection ON cmf_live.id = cmf_live_collection.live_id')
                            ->where("cmf_live.course_id = $id")->select();
                    }
                    if (!empty($data)) {
                        echo json_encode([
                            'status' => $succ[0],
                            'msg' => $mess[0],
                            'data' => $data
                        ]);exit();
                    } else {
                        echo $model::state($succ[1], $mess[1]);exit();
                    }
                }else {
                    echo $model::state($succ[2], $mess[2]);die;
                }
            }else {
                echo $model::state($succ[0],'暂无登录操作');die;
            }
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
        if (IS_GET) {
            $course = M('course');
            $page = I('get.page');
            $uid = I('get.uid');
            if (!empty($uid)) {
                if (is_numeric($page) && is_numeric($uid)) {
                    $data = $course
                        ->field('cmf_course.id,course_name,now_price,old_price,startDate,invalidDate,cover,cmf_course.status,cmf_course.introduction,cmf_course_collection.type')
                        ->join('left join cmf_course_collection ON cmf_course.id = cmf_course_collection.course_id')
                        ->where("is_free = '1'")->order('cmf_course.id')->page($page . ',10')->select();
                    if (!empty($data)) {
                        echo json_encode([
                            'status' => $succ[0],
                            'msg' => $mess[0],
                            'is_login' => 1,
                            'data' => $data
                        ]);die;
                    } else {
                        echo $model::state($succ[0], $mess[4],$data = null);die;
                    }
                } else {
                    echo $model::state($succ[2], $mess[2]);die;
                }
            }else {
                $data = $course
                    ->field('cmf_course.id,course_name,now_price,old_price,startDate,invalidDate,cover,cmf_course.status,cmf_course.introduction')
                    ->where("is_free = '1'")->order('cmf_course.id')->page($page . ',10')->select();
                if (!empty($data)) {
                    echo json_encode([
                        'status'   => $succ[0],
                        'msg'      => $mess[0],
                        'is_login' => 0,
                        'data'     => $data
                    ]);die;
                } else {
                    echo $model::state($succ[0], $mess[4],$data = null);die;
                }
            }
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
        if (IS_GET) {
            $course = M('course');
            $page = I('get.page');
            $uid = I('get.uid');
                if (is_numeric($page)) {
                    $where = "cmf_course.is_free = '2'";
                    $where .= "and cmf_course.is_up = '0'";
                    if (!empty($uid) && is_numeric($uid)) {
                        $data = $course
                            ->field('cmf_course.id,course_name,now_price,old_price,name,startdate,invaliddate,cover,num_class,status,cmf_course.introduction,cmf_order.pay_status')
                            ->join('left join cmf_people ON cmf_course.people_id = cmf_people.p_id')
                            ->join('left join cmf_lector ON cmf_course.lector_id = cmf_lector.l_id')
                            ->join('left join cmf_order ON cmf_course.id = cmf_order.course_id')
                            ->where("cmf_order.uid = '$uid'")
                            ->join('left join cmf_book ON cmf_course.book_id = cmf_book.b_id')
                            ->where($where)->order('cmf_course.id')->page($page . ',10')->select();
                    }else {
                        $data = $course
                            ->field('id,course_name,now_price,old_price,name,startdate,invaliddate,cover,num_class,status,cmf_course.introduction')
                            ->join('STRAIGHT_JOIN cmf_people ON cmf_course.people_id = cmf_people.p_id')
                            ->join('STRAIGHT_JOIN cmf_lector ON cmf_course.lector_id = cmf_lector.l_id')
                            ->join('STRAIGHT_JOIN cmf_book ON cmf_course.book_id = cmf_book.b_id')
                            ->where($where)->order('cmf_course.id')->page($page . ',10')->select();
                    }
                    if (!empty($data)) {
                        if (!empty($uid)) {
                            echo json_encode([
                                'status'   => $succ[0],
                                'msg'      => $mess[0],
                                'is_login' => 1,
                                'data'     => $data
                            ]);die;
                        }
                        echo json_encode([
                            'status' => $succ[0],
                            'msg' => $mess[0],
                            'is_login' => 0,
                            'data' => $data
                        ]);die;
                    } else {
                        echo $model::state($succ[0], $mess[4],$data = null);die;
                    }
                } else {
                    echo $model::state($succ[2], $mess[2]);die;
                }
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
        $course = M('course');
        $model = new SubmitController();
        if (IS_GET) {
            $page = I('get.page');
            $uid = I('get.uid');
            if (!empty($page) && is_numeric($page)) {
                $time = time();
                $where = "cmf_course.status = '3'";
                $where .= " and cmf_course.is_up = '0'";
                $where .= " and UNIX_TIMESTAMP(cmf_course.invaliddate) < '$time'";
                        if (!empty($uid) && is_numeric($uid)) {
                            $data = $course
                                ->field('cmf_course.id,course_name,introduction,num_class,status,is_free,now_price,old_price,number,stu_token,reply_url,class_id,startdate,invaliddate,cover,name,cmf_course_collection.type,cmf_order.pay_status')
                                ->join('left join cmf_lector ON cmf_course.lector_id = cmf_lector.l_id')
                                ->join('LEFT JOIN cmf_order ON cmf_course.id = cmf_order.course_id')
                                ->where("cmf_order.uid = '$uid'")
                                ->join('left join cmf_course_collection  ON cmf_course.id = cmf_course_collection.course_id')
                                ->where($where)
                                ->order('cmf_course.id')->page($page . ',10')->select();
                        } else {
                            $data = $course
                                ->field('id,course_name,introduction,num_class,status,is_free,now_price,old_price,number,stu_token,reply_url,class_id,startdate,invaliddate,cover,name,cmf_course_collection.type')
                                ->join('STRAIGHT_JOIN cmf_lector ON cmf_course.lector_id = cmf_lector.l_id')
                                ->join('left join cmf_course_collection  ON cmf_course.id = cmf_course_collection.course_id')
                                ->where($where)->order('cmf_course.id')->page($page . ',10')->select();
                        }
                    if (!empty($data)) {
                        if (!empty($uid)) {
                            echo json_encode([
                                'status' => $succ[0],
                                'meg' => $mess[0],
                                'is_login' => 1,
                                'data' => $data
                            ]);
                            die;
                        }
                        echo json_encode([
                            'status' => $succ[0],
                            'meg' => $mess[0],
                            'is_login' => 0,
                            'data' => $data
                        ]);
                        die;
                    }else {
                        echo $model::state($succ[1], $mess[4], $data = null);die;
                    }
                }else {
                echo $model::state($succ[2], $mess[2], $data = null);die;
               }
        }else {
            echo $model::state($succ[3], $mess[3], $data = null);die;
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
                $live->where("id = '$id'")->save($data);

            }
        }
    }
}