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
        $succ = C('succ');
        $mess = C('mess');
        $model = new SubmitController();
        if (IS_GET) {
            $course = M('course');
            $page = I('get.page');
            if ($page !== NULL) {
                $data = $course
                    ->field('id,subject,startDate,invalidDate,now_price,old_price,name,cover,num_class,status,is_free,number,stu_token')
                    ->join('cmf_people ON cmf_course.people_id = cmf_people.p_id')
                    ->join('cmf_lector ON cmf_course.lector_id = cmf_lector.l_id')
                    ->join('cmf_book ON cmf_course.book_id = cmf_book.b_id')
                    ->order('cmf_course.id')->page($page,',10')->select();
                if (!empty($data)) {
                    echo json_encode([
                        'code' => $succ[0],
                        'mess' => $mess[0],
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
        $succ = C('succ');
        $mess = C('mess');
        $model = new SubmitController();
        if (IS_GET) {
            $id = I('get.id');

            $data = M('course')
                ->field('id,subject,now_price,name,num_class,cover,people,book,introduction')
                ->join('cmf_people ON cmf_course.people_id = cmf_people.p_id')
                ->join('cmf_lector ON cmf_course.lector_id = cmf_lector.l_id')
                ->join('cmf_book ON cmf_course.book_id = cmf_book.b_id')
                ->where("id = $id")->find();

            if (!empty($data)) {
                echo json_encode([
                    'code' => $succ[0],
                    'mess' => $mess[0],
                    'data' => $data
                ]);die;

            } else {
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
        $succ = C('succ');
        $mess = C('mess');
        $model = new SubmitController();
        if (IS_GET) {
            $course = M('course');
            $page = I('get.page');
            if ($page !== NULL) {
                $data = $course
                    ->field('id,subject,now_price,old_price,name,startdate,invaliddate,cover,num_class,status,number,stu_token')
                    ->join('cmf_people ON cmf_course.people_id = cmf_people.p_id')
                    ->join('cmf_lector ON cmf_course.lector_id = cmf_lector.l_id')
                    ->join('cmf_book ON cmf_course.book_id = cmf_book.b_id')
                    ->where("is_free = 1")->order('cmf_course.id')->page($page . ',10')->select();
                if (!empty($data)) {
                    echo json_encode([
                        'code' => $succ[0],
                        'mess' => $mess[0],
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
        $succ = C('succ');
        $mess = C('mess');
        $model = new SubmitController();
        if (IS_GET) {
            $course = M('course');
            $page = I('get.page');
            if ($page !== NULL) {
                $data = $course
                    ->field('id,subject,now_price,old_price,name,startdate,invaliddate,cover,num_class,status,number,stu_token')
                    ->join('cmf_people ON cmf_course.people_id = cmf_people.p_id')
                    ->join('cmf_lector ON cmf_course.lector_id = cmf_lector.l_id')
                    ->join('cmf_book ON cmf_course.book_id = cmf_book.b_id')
                    ->where("is_free = 2")->order('cmf_course.id')->page($page . ',10')->select();
                if (!empty($data)) {
                    echo json_encode([
                        'code' => $succ[0],
                        'mess' => $mess[0],
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
     * 直播结束
     */
    public function live_end ()
    {
        $succ = C('succ');
        $mess = C('mess');
        $model = new SubmitController();
        if (IS_GET) {
            $id = I('get.id');
            if (is_numeric($id)) {
                $course = M('course');
                $rew = $course->where("id = $id")->getField('class_id');
                if ($rew) {
                    $course->status = 2;
                    if ($course->where("id = $id")->save()) {
                        echo json_encode([
                            'code' => $succ[0],
                            'mess' => $mess[4],
                        ]);die;
                    }
                }else {
                    echo $model::state($succ[2], $mess[2]);die;
                }
            }else {
                echo $model::state($succ[2], $mess[2]);die;
            }
        }else {
            echo $model::state($succ[3], $mess[3]);die;
        }
    }
    /**
     * 往期直播
     */
    public function past_live()
    {
        $succ = C('succ');
        $mess = C('mess');
        $course = M('course');
        $model = new SubmitController();
        if (IS_GET) {
            $page = I('get.page');
            if ($page !== NULL) {
                $data = $course
                    ->field('id,subject,now_price,old_price,name,startdate,invaliddate,cover,num_class,status,is_free,number,stu_token,reply_url')
                    ->join('cmf_people ON cmf_course.people_id = cmf_people.p_id')
                    ->join('cmf_lector ON cmf_course.lector_id = cmf_lector.l_id')
                    ->join('cmf_book ON cmf_course.book_id = cmf_book.b_id')
                    ->where("status = 3")->order('cmf_course.id')->page($page . ',10')->select();
                if (!empty($data)) {
                    echo json_encode([
                        'code' => $succ[0],
                        'mess' => $mess[0],
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