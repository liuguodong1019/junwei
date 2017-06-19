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
use Common\Model\CourseModel;

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
        $course = D('Course');
        $junwei = M('junwei')->find();
        $loginName = $junwei['loginName'];
        $password = $junwei['password'];
        $count = $course->count();
        $Page = $this->page($count, 20);
        $str = new CourseModel();
        $data = $str->show($Page);
        if (empty($data)) {
            $this->redirect('show');
        }
        $this->assign('loginName', $loginName);
        $this->assign('password', $password);
        $this->assign('data', $data);
        $this->assign('page', $Page->show('Admin'));
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
                    ->join('left join cmf_lector ON cmf_course.lector_id = cmf_lector.l_id')
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
        $lector = M('lector');
        $people = M('people');
        $book = M('book');
        $photo = C('upload');
        $str = new CourseModel();
        if (IS_POST) {
            $data = I('');
            $data = $str->create($data,$photo);
            if ($data != false) {
                $this->success(L('ADD_SUCCESS'), U("Course/show"));exit();
            }else {
                $this->error(L('ADD_FAILED'));exit();
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
        $course = D('Course');
        $lector = M('lector');
        $people = M('people');
        $book = M('book');
        $str = new CourseModel();
        if (IS_GET) {
            $id = I('get.id');
            if (!empty($id)) {
                $data = $course
                    ->join('left join cmf_lector ON cmf_course.lector_id = cmf_lector.l_id')
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
            $is_free = I('post.is_free');
            $res = $str->update($is_free);
            if ($res != false) {
                $this->success(L('ADD_SUCCESS'), U("Course/show"));exit();
            }else {
                $this->error(L("ADD_FAILED"));exit();
            }
        }
        $this->display();
    }

    /**
     * 删除课堂
     */
    public function delete()
    {
        $course = D('Course');
        $response = new ResponseController();
        if (IS_GET) {
            $class_id = I('get.class_id');
            $id = intval(I("get.id"));
            if (empty($class_id)) {
                if ($course->where("id = $id")->delete() !== false) {
                    $this->success("删除成功！");
                } else {
                    $this->error("删除失败！");
                }
            }else {
                $junwei = M('junwei')->find();
                $loginName = $junwei['loginname'];
                $password = sp_authcode($junwei['password']);
                //调用课时删除接口
                $resourec = $response::delete($loginName,$password,$class_id);
                if ($resourec['code'] == 0) {
                    if ($course->where("id = $id")->delete() !== false) {
                        $this->success('删除成功');exit();
                    } else {
                        $this->error('删除失败');exit();
                    }
                }else {
                    $this->error('删除失败');exit();
                }
            }
        }
        if (isset($_POST['ids'])) {
            $ids = join(",", $_POST['ids']);
            $rew = $course->field('class_id')->where("id in ($ids)")->select();
            $junwei = M('junwei')->find();
            $loginName = $junwei['loginname'];
            $password = sp_authcode($junwei['password']);
            foreach ($rew as $value) {
                $class_id[] = $value['class_id'];
            }
            $response::delete($loginName, $password, $class_id);
            if ($course->where("id in ($ids)")->delete() !== false) {
                $this->success('删除成功');exit();
            } else {
                $this->error('删除失败');exit();
            }
        }
    }

    /**
     * 已结束课程
     */
    public function end ()
    {
        $time = time();
        $course = D('Course');
        $where = "UNIX_TIMESTAMP(invaliddate) < '$time'";
        $count = $course->where($where)->count();
        $page = $this->page($count,20);
        $data = $course->where($where)->limit($page->firstRow . ',' . $page->listRows)->order('id desc')->select();
        $this->assign("page", $page->show('Admin'));
        $this->assign('data',$data);
        $url = "{:U('Course/end')}";
        $describe = "已结束";
        $this->assign('url',$url);
        $this->assign('describe',$describe);
        $this->display();
    }

    /**
     * 公开课
     */
    public function openClass ()
    {
        $course = D('Course');
        $where = "is_free = 1";
        $count = $course->where($where)->count();
        $page = $this->page($count,20);
        $data = $course->where($where)->limit($page->firstRow . ',' . $page->listRows)->order('id desc')->select();
        $this->assign("page", $page->show('Admin'));
        $this->assign('data',$data);
        $url = "{:U('Course/openClass')}";
        $describe = "公开课";
        $this->assign('url',$url);
        $this->assign('describe',$describe);
        $this->display("Course/end");
    }

    /**
     * vip课程
     */
    public function vip ()
    {
        $course = D('Course');
        $where = "is_free = 2";
        $count = $course->where($where)->count();
        $page = $this->page($count,20);
        $data = $course->where($where)->limit($page->firstRow . ',' . $page->listRows)->order('id desc')->select();
        $this->assign("page", $page->show('Admin'));
        $this->assign('data',$data);
        $url = "{:U('Course/vip')}";
        $describe = "vip课";
        $this->assign('url',$url);
        $this->assign('describe',$describe);
        $this->display("Course/end");
    }
}