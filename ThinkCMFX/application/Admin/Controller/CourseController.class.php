<?php
/**
 * Created by PhpStorm.
 * User: 刘国栋
 * Date: 2017/4/26
 * Time: 11:47
 */

namespace Admin\Controller;

use Common\Controller\AdminbaseController;
use Api\Controller\ResponseController;

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
        $Page = new \Think\Page($count, 10);
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
                ->order('UNIX_TIMESTAMP(startdate)')->limit($Page->firstRow . ',' . $Page->listRows)->select();
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
    public function look()
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
        $this->assign('data', $data);
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
            if ($course->add($data)) {
                $this->success(L('ADD_SUCCESS'), U("Course/show"));
                exit();
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
        if (IS_GET) {
            $id = I('get.id');
            if (!empty($id)) {
                $data = $course
                    ->join('cmf_people ON cmf_course.people_id = cmf_people.p_id')
                    ->join('cmf_lector ON cmf_course.lector_id = cmf_lector.l_id')
                    ->join('cmf_book ON cmf_course.book_id = cmf_book.b_id')
                    ->where("id = $id")
                    ->find();
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
            if ($course->where("id = $id")->save($data)) {
                $this->success(L('ADD_SUCCESS'), U("Course/show"));
                exit();
            } else {
                $this->error(L("ADD_FAILED"));
                exit();
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
                $id = intval(I("get.id"));
                    if ($course->where("id = $id")->delete() !== false) {
                        $this->success("删除成功！");
                    } else {
                        $this->error("删除失败！");
                    }
            }
        }
        if (isset($_POST['ids'])) {
            $ids = join(",", $_POST['ids']);
            if ($course->where("id in ($ids)")->delete() !== false) {
                $this->success("删除成功！");
            } else {
                $this->error("删除失败！");
            }
        }
    }
}