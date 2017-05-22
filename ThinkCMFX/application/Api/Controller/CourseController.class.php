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
            if (is_numeric($page)) {
                $data = $course
                    ->field('cmf_course.id,cmf_course.course_name,cmf_course.startDate,cmf_course.invalidDate,cmf_course.now_price,cmf_course.old_price,cmf_lector.name,cmf_course.cover,cmf_course.num_class,cmf_course.status,cmf_course.is_free,is_payment,cmf_course.introduction,cmf_course.number,cmf_course.stu_token,cmf_course.reply_url')
                    ->join('cmf_people ON cmf_course.people_id = cmf_people.p_id')
                    ->join('cmf_lector ON cmf_course.lector_id = cmf_lector.l_id')
                    ->join('cmf_book ON cmf_course.book_id = cmf_book.b_id')
                    ->order('cmf_course.id')->page($page . ',10')->select();

                if (!empty($data)) {
                    echo json_encode([
                        'status' => $succ[0],
                        'msg' => $mess[0],
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
            if (is_numeric($id)  && is_numeric($uid)) {
                $order = M('order')->field('pay_status')->where("uid = $uid")->find();
                if (empty($order) || $order['pay_status'] == 0 || $order['pay_status'] == 2) {
                    $collection = M('course_collection')->field('status')->where("uid = $uid")->find();
                    $data = M('course')
                        ->field('id,course_name,now_price,name,num_class,cover,people,book,introduction')
                        ->join('cmf_people ON cmf_course.people_id = cmf_people.p_id')
                        ->join('cmf_lector ON cmf_course.lector_id = cmf_lector.l_id')
                        ->join('cmf_book ON cmf_course.book_id = cmf_book.b_id')
                        ->where("id = $id")->find();
                    if (empty($collection) || $collection['status'] == 0) {
                        $data['is_collection'] = 0;
                    }else {
                        $data['is_collection'] = 1;
                    }

                }else {
                    $data = M('live')
                        ->field('cmf_live.id,cmf_live.subject,cmf_course.introduction,cmf_live.status,cmf_live.startDate,cmf_live.invalidDate,cmf_live.reply_url,cmf_live.stu_token,cmf_live.number,cmf_live_collection.status')
                        ->join('cmf_course ON cmf_live.course_id = cmf_course.id')
                        ->join('left join cmf_live_collection ON cmf_live.id = cmf_live_collection.live_id')
                        ->where("course_id = $id")->select();
                }
                if (!empty($data)) {
                    echo json_encode([
                        'status' => $succ[0],
                        'msg' => $mess[0],
                        'data' => $data
                    ]);die;
                } else {
                    echo $model::state($succ[2], $mess[2]);die;
                }
            }else {
                echo $model::state($succ[2], $mess[2]);die;
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
            if (is_numeric($page)) {
                $data = $course
                    ->field('id,course_name,now_price,old_price,name,startDate,invalidDate,cover,status,cmf_course.introduction')
                    ->join('cmf_people ON cmf_course.people_id = cmf_people.p_id')
                    ->join('cmf_lector ON cmf_course.lector_id = cmf_lector.l_id')
                    ->join('cmf_book ON cmf_course.book_id = cmf_book.b_id')
                    ->where("is_free = '1'")->order('cmf_course.id')->page($page . ',10')->select();
                if (!empty($data)) {
                    echo json_encode([
                        'status' => $succ[0],
                        'msg' => $mess[0],
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
                if (is_numeric($page)) {
                    $data = $course
                        ->field('id,course_name,now_price,old_price,name,startdate,invaliddate,cover,num_class,status,is_payment,cmf_course.introduction')
                        ->join('cmf_people ON cmf_course.people_id = cmf_people.p_id')
                        ->join('cmf_lector ON cmf_course.lector_id = cmf_lector.l_id')
                        ->join('cmf_book ON cmf_course.book_id = cmf_book.b_id')
                        ->where("is_free = '2'")->order('cmf_course.id')->page($page . ',10')->select();
                    if (!empty($data)) {
                        echo json_encode([
                            'status' => $succ[0],
                            'msg' => $mess[0],
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
            if (is_numeric($page)) {
                $data = $course
                    ->field('id,course_name,introduction,num_class,status,is_free,now_price,old_price,number,stu_token,reply_url,class_id,is_payment,startdate,invaliddate,cover,name,people,book')
                    ->join('cmf_people ON cmf_course.people_id = cmf_people.p_id')
                    ->join('cmf_lector ON cmf_course.lector_id = cmf_lector.l_id')
                    ->join('cmf_book ON cmf_course.book_id = cmf_book.b_id')
                    ->where("cmf_course.status = '2'")->order('cmf_course.id')->page($page . ',10')->select();
                if (!empty($data)) {
                    echo json_encode([
                        'status' => $succ[0],
                        'meg' => $mess[0],
                        'data' => $data
                    ]);die;
                } else {
                    echo $model::state($succ[1], $mess[4],$data = null);die;
                }
            } else {
                echo $model::state($succ[2], $mess[2]);die;
            }
        } else {
            echo $model::state($succ[3], $mess[3]);die;
        }
    }


    public function reply ()
    {
        $course = M('course');
        $data['status'] =  2;
        $course->where("id = 17")->save($data);
//        $course = M('course');
//        $data['status'] = 2;
//        $course->where("id = 37")->save($data);
//        $data = $course->field('id,class_id')->where("status = '2'")->select();
//
//        foreach ($data as $value) {
//            $class_id[] = $value['class_id'];
//            $id[] = $value['id'];
//        }
//        $id = join(",", $id);
//        $junwei = M('junwei')->find();
//        $loginName = $junwei['loginname'];
//        $password = sp_authcode($junwei['password']);
//        $response = new ResponseController();
//        $resource = $response::get_past($loginName,$password,$class_id);
//        $res = $resource['coursewares'][0];
//        if ($resource['code'] == 0) {
//            $data['number'] = $res['number'];
//            $data['reply_url'] = $res['url'];
//            $data['status'] = 2;
//        }
//        $course->where("id in ($id)")->save($data);


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
                        $course->where("id = '$id'")->save($data);
                        break;
                    case 105:
                        $data['status'] = 2;
                        $course->where("id = '$id'")->save($data);
                        break;
                    case 106:
                        $junwei = M('junwei')->find();
                        $loginName = $junwei['loginname'];
                        $password = sp_authcode($junwei['password']);
                        $response = new ResponseController();

                        $resource = $response::get_past($loginName,$password,$class_id);

                        $res = $resource['coursewares'][0];
                        $number = $res['number'];
                        $reply_url = $res['url'];

                        if ($resource['code'] == 0) {
                                $data['number'] = $number;
                                $data['reply_url'] = $reply_url;
                                $data['status'] = 3;
                                $course->where("id = '$id'")->save($data);
                        }
                        break;
                }

            }else {
                $ret = $live->where("class_id = '$class_id'")->find();
                $id = $ret['id'];
                switch ($action) {
                    case 103:
                        $data['status'] = 1;
                    break;
                    case 106:
                        $junwei = M('junwei')->find();
                        $response = new ResponseController();
                        $loginName = $junwei['loginname'];
                        $password = sp_authcode($junwei['password']);
                        $resource = $response::get_past($loginName,$password,$class_id);
                        $res = $resource['coursewares'][0];
                        if ($resource['code'] == 0) {
                            $data['number'] = $res['number'];
                            $data['status'] = 2;
                            $data['reply_url'] = $res['url'];
                        }
                    break;
                }
                        $live->where("id = '$id'")->save($data);

            }
        }
    }
}