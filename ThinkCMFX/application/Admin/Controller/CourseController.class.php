<?php
/**
 * Created by PhpStorm.
 * User: 刘国栋
 * Date: 2017/4/26
 * Time: 11:47
 */

namespace Admin\Controller;

use Common\Controller\AdminbaseController;

use Admin\Controller\ResponseController;

/**
 * Class LiveController
 * @package Admin\Controller
 * 直播系统管理
 */
class CourseController extends AdminbaseController
{
    /**
     * 课堂列表
     */
    public function show()
    {
        $course = M('course');
        $junwei = M('junwei')->find();
        $loginName = $junwei['loginName'];
        $password = $junwei['password'];
        $count = $course->count();
        $Page = new \Think\Page($count, 25);
        $show = $Page->show();
        if (IS_POST) {
            $keyword = I('post.keyword');
            if (!empty($keyword)) {
                $data = $course->where("subject like '%$keyword%'")
                    ->order('startDate')->limit($Page->firstRow . ',' . $Page->listRows)->select();
            } else {
                $this->redirect('show');
            }
        } else {
            $data = $course
                ->join('cmf_people ON cmf_course.people_id = cmf_people.p_id')
                ->join('cmf_lector ON cmf_course.lector_id = cmf_lector.l_id')
                ->join('cmf_book ON cmf_course.book_id = cmf_book.b_id')
                ->order('cmf_course.id')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        }
        $this->assign('loginName', $loginName);
        $this->assign('password', $password);
        $this->assign('data', $data);
        $this->assign('page', $show);
        $this->display();
    }

    /**
     * 查看课堂详细信息
     */
    public function look ()
    {
       if (IS_GET) {
           $id = I('get.id');
           if (!empty($id)) {
                $data = M('course')
                   ->join('cmf_people ON cmf_course.people_id = cmf_people.p_id')
                   ->join('cmf_lector ON cmf_course.lector_id = cmf_lector.l_id')
                   ->join('cmf_book ON cmf_course.book_id = cmf_book.b_id')
                   ->where("id = $id")->find();
           }
       }
       $this->assign('data',$data);
       $this->display();
    }
    /**
     * 创建课堂
     */
    public function create()
    {
        $course = M('course');
        $lector = M('lector');
        $people = M('people');
        $book = M('book');
        $junwei = M('junwei')->find();
        $response = new ResponseController();
        if (IS_POST) {
            $data = I('');
            $subject = I('post.subject');
            $loginName = $junwei['loginname'];
            $password = sp_authcode($junwei['password']);
            $startDate = I('post.startDate');
            $invalidDate = I('post.invalidDate');
            //调用创建实时课堂接口
            $resource = $response::create_course($subject, $loginName, $password, $startDate,$invalidDate);
//            print_r($resource);die;
            $data['number'] = $resource['number'];
            $data['stu_token'] = $resource['studentClientToken'];
            $data['class_id'] = $resource['id'];
            if ($resource['code'] == 0) {
                if ($course->add($data)) {
                    $this->success(L('ADD_SUCCESS'), U("Course/show"));
                    exit();
                } else {
                    $this->error(L('ADD_FAILED'));
                    exit();
                }
            } else {
                $this->error(L('ADD_FAILED'));
                exit();
            }

        }
        $array['lector'] = $lector->select();
        $array['people'] = $people->select();
        $array['book'] = $book->select();
        $this->assign('array', $array);
        $this->display();
    }

    /**
     * 修改课堂信息
     */
    public function update()
    {
        $response = new ResponseController();
        $course = M('course');
        $lector = M('lector');
        $people = M('people');
        $book = M('book');
        $junwei = M('junwei')->find();
        if (IS_GET) {
            $id = I('get.id');
            if (!empty($id)) {
                $data = $course
                    ->join('cmf_people ON cmf_course.people_id = cmf_people.p_id')
                    ->join('cmf_lector ON cmf_course.lector_id = cmf_lector.l_id')
                    ->join('cmf_book ON cmf_course.book_id = cmf_book.b_id')
                    ->where("id = $id")
                    ->find();
                $array['subject'] = $data['subject'];
                $array['class_id'] = $data['class_id'];
                $array['startDate'] = $data['startdate'];
                $lector = $lector->select();
                $people = $people->select();
                $book = $book->select();
                $array['lector'] = $lector;
                $array['people'] = $people;
                $array['book'] = $book;
            }
            $this->assign('id', $id);
            $this->assign('array', $array);
            $this->assign('data', $data);
        }
        if (IS_POST) {
            $id = I('post.id');
            $data = I('');
            $loginName = $junwei['loginname'];
            $password = sp_authcode($junwei['password']);
            $realtime = I('post.realtime');
            $startDate = I('post.startDate');
            $subject = I('post.subject');
            $class_id = I('post.class_id');
            //调用修改实时课堂接口
            $resource = $response::update_course($loginName, $password, $realtime, $startDate, $subject, $class_id);
            $data['update_time'] = date('Y-m-d H:i:s');
            if ($resource['code'] == 0) {
                if ($course->where("id = $id")->save($data)) {
                    $this->success(L('ADD_SUCCESS'), U("Course/show"));
                    exit();
                } else {
                    $this->error(L("ADD_FAILED"));
                    exit();
                }
            }

        }
        $this->display();
    }

    /**
     * 删除课堂
     */
    public function delete()
    {
        $course = M("course");
        $response = new ResponseController();
        if (IS_GET) {
            if (isset($_GET['id'])) {
                $class_id = I('class_id');
                $junwei = M('junwei')->find();
                $loginName = $junwei['loginname'];
                $password = sp_authcode($junwei['password']);
                //调用删除实时课堂接口
                $resourec = $response::delete($loginName, $password, $class_id);
                $id = intval(I("get.id"));
                if ($resourec['code'] == 0) {
                    if ($course->where("id = $id")->delete() !== false) {
                        $this->success("删除成功！");
                    } else {
                        $this->error("删除失败！");
                    }
                }

            }
        }
        if (isset($_POST['ids'])) {
            $ids = join(",", $_POST['ids']);
            $rew = $course->where("id in ($ids)")->select();
            $len = count($rew);
            $junwei = M('junwei')->find();
            $loginName = $junwei['loginname'];
            $password = sp_authcode($junwei['password']);
            foreach ($rew as $value) {
                $class_id[] = $value['class_id'];
            }
            //调用删除实时课堂接口批量删除
            $resourec = $response::delete($loginName, $password, $class_id);
            $ids = join(",", $_POST['ids']);
            if ($resourec['code'] == 0) {
                if ($course->where("id in ($ids)")->delete() !== false) {
                    $this->success("删除成功！");
                } else {
                    $this->error("删除失败！");
                }
            } else {
                $this->error("操作有误");
            }
        }
    }

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
                    ->join('cmf_people ON cmf_course.people_id = cmf_people.p_id')
                    ->join('cmf_lector ON cmf_course.lector_id = cmf_lector.l_id')
                    ->join('cmf_book ON cmf_course.book_id = cmf_book.b_id')
                    ->where("status = 1")->order('cmf_course.id')->page($page.',10')->select();
                if (!empty($data)) {
                    echo json_encode([
                        'code' => $succ[0],
                        'mess' => $mess[0],
                        'data' => $data
                    ]);
                } else {
                    echo $model::state($succ[1], $mess[1]);
                }
            } else {
                echo $model::state($succ[2], $mess[2]);
            }
        } else {
            echo $model::state($succ[3], $mess[3]);
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
            $data = M('course')->where("id = $id")->find();
            if (!empty($data)) {
                echo json_encode([
                    'code' => $succ[0],
                    'mess' => $mess[0],
                    'data' => $data
                ]);
                exit();
            } else {
                echo $model::state($succ[2], $mess[2]);
            }
        } else {
            echo $model::state($succ[3], $mess[3]);
        }
    }

    /**
     * 生成回放
     */
    public function playback ()
    {
        $course = M('course');
        if (IS_GET) {
            $id = I('get.id');
            if (is_numeric($id)) {
                $class_id = $course->where("id = $id")->getField('class_id');
                $junwei = M('junwei')->find();
                $response = new ResponseController();
                $loginName = $junwei['loginname'];
                $password = sp_authcode($junwei['password']);
                $resource = $response::get_past($loginName,$password,$class_id);
                if ($resource['code'] == 0) {
                    $res = $resource['coursewares'][0];
                    $data['number'] = $res['number'];
                    $data['status'] = 3;
                    $data['reply_url'] = $res['url'];
                    if ($course->where("id = $id")->save($data)) {
                        $this->success(L('ADD_SUCCESS'), U("Course/show"));exit();
                    }else {
                        $this->error(L('ADD_FAILED'));exit();
                    }
                }
            }
        }
    }
    /**
     * 往期直播
     */
    public function past_live ()
    {
        $succ = C('succ');
        $mess = C('mess');
        $course = M('course');
        $model = new SubmitController();
        if (IS_GET) {
            $page = I('get.page');
            if ($page !== NULL) {
                $data = $course
                    ->join('cmf_people ON cmf_course.people_id = cmf_people.p_id')
                    ->join('cmf_lector ON cmf_course.lector_id = cmf_lector.l_id')
                    ->join('cmf_book ON cmf_course.book_id = cmf_book.b_id')
                    ->where("status = 3")->order('cmf_course.id')->page($page.',10')->select();
                if (!empty($data)) {
                    echo json_encode([
                        'code' => $succ[0],
                        'mess' => $mess[0],
                        'data' => $data
                    ]);
                } else {
                    echo $model::state($succ[1], $mess[1]);
                }
            } else {
                echo $model::state($succ[2], $mess[2]);
            }
        }else {
            echo $model::state($succ[3], $mess[3]);
        }
    }
}