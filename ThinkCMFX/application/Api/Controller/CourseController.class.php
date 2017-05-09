<?php
namespace Api\Controller;

use Think\Controller;

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
                    ->field('cmf_course.id,cmf_course.course_name,cmf_course.startDate,cmf_course.invalidDate,cmf_course.now_price,cmf_course.old_price,cmf_lector.name,cmf_course.cover,cmf_course.num_class,cmf_course.status,cmf_course.is_free,is_payment')
                    ->join('cmf_people ON cmf_course.people_id = cmf_people.p_id')
                    ->join('cmf_lector ON cmf_course.lector_id = cmf_lector.l_id')
                    ->join('cmf_book ON cmf_course.book_id = cmf_book.b_id')
                    ->order('cmf_course.id')->page($page.',10')->select();
                if (!empty($data)) {
                    echo json_encode([
                        'status' => $succ[0],
                        'msg' => $mess[0],
                        'data' => $data
                    ]);die;
                } else {
                    echo $model::state($succ[0], $mess[0],$data = null);die;
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
            if (is_numeric($id)) {
                $data = M('course')
                    ->field('id,course_name,now_price,name,num_class,cover,people,book,introduction')
                    ->join('cmf_people ON cmf_course.people_id = cmf_people.p_id')
                    ->join('cmf_lector ON cmf_course.lector_id = cmf_lector.l_id')
                    ->join('cmf_book ON cmf_course.book_id = cmf_book.b_id')
                    ->where("id = $id")->find();
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
                    ->field('id,course_name,now_price,old_price,name,startDate,invalidDate,cover,num_class,status')
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
                    echo $model::state($succ[0], $mess[0],$data = null);die;
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
                        ->field('id,course_name,now_price,old_price,name,startdate,invaliddate,cover,num_class,status,is_payment')
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
                        echo $model::state($succ[0], $mess[0],$data = null);die;
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
                    ->field('id,course_name,now_price,old_price,name,startdate,invaliddate,cover,num_class,status,is_free,is_payment')
                    ->join('cmf_people ON cmf_course.people_id = cmf_people.p_id')
                    ->join('cmf_lector ON cmf_course.lector_id = cmf_lector.l_id')
                    ->join('cmf_book ON cmf_course.book_id = cmf_book.b_id')
                    ->where("status = '3'")->order('cmf_course.id')->page($page . ',10')->select();
                if (!empty($data)) {
                    echo json_encode([
                        'status' => $succ[0],
                        'meg' => $mess[0],
                        'data' => $data
                    ]);die;
                } else {
                    echo $model::state($succ[0], $mess[0],$data = null);die;
                }
            } else {
                echo $model::state($succ[2], $mess[2]);die;
            }
        } else {
            echo $model::state($succ[3], $mess[3]);die;
        }
    }
}