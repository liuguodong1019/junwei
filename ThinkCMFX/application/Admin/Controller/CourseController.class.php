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
        $Page = $this->page($count, 20);
        if (IS_POST) {
            $keyword = I('post.keyword');
            if (!empty($keyword)) {
                $data = $course->where("course_name like '%$keyword%'")
                    ->order('startDate')->limit($Page->firstRow . ',' . $Page->listRows)->select();
            } else {
                $this->redirect('show');
            }
        } else {
            $data = $course
                ->join('cmf_people ON cmf_course.people_id = cmf_people.p_id')
                ->join('cmf_lector ON cmf_course.lector_id = cmf_lector.l_id')
                ->join('cmf_book ON cmf_course.book_id = cmf_book.b_id')
                ->order('cmf_course.id DESC')->limit($Page->firstRow . ',' . $Page->listRows)->select();
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
        $photo = C('upload');
        $upload = new \Think\Upload($photo);// 实例化上传类
        if (IS_POST) {
            $data = I('');
            $info = $upload->upload();
            foreach($info as $file){
                $cover = $file['savepath'].$file['savename'];
                $data['cover'] = $cover;
            }
            $is_free = I('post.is_free');
            if ($is_free == 1) {
                $junwei = M('junwei')->find();
                $loginName = $junwei['loginname'];
                $password = sp_authcode($junwei['password']);
                $startDate = I('post.startDate');
                $invalidDate = I('post.invalidDate');
                $subject = I('post.course_name');
                $response = new ResponseController();
                //调用课时创建接口
                $resource = $response::create_course($subject,$loginName,$password,$startDate,$invalidDate);
                $data['stu_token'] = $resource['studentClientToken'];
                $data['number'] = $resource['number'];
                $data['class_id'] = $resource['id'];
                if ($resource['code'] == 0) {
                    if ($course->add($data)) {
                        $this->success(L('ADD_SUCCESS'), U("Course/show"));
                        exit();
                    }else {
                        $this->error(L('ADD_FAILED'));
                        exit();
                    }
                }else {
                    $this->error(L('ADD_FAILED'));
                    exit();
                }
            }else {
                if ($course->add($data)) {
                    $this->success(L('ADD_SUCCESS'), U("Course/show"));
                    exit();
                } else {
                    $this->error(L('ADD_FAILED'));
                    exit();
                }
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
        $course = M('course');
        $lector = M('lector');
        $people = M('people');
        $book = M('book');
        $photo = C('upload');
        $upload = new \Think\Upload($photo);// 实例化上传类
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
            $is_free = I('post.is_free');
            if ($is_free == 1) {
                $id = I('post.id');
                $data = I('');
                $info = $upload->upload();
                foreach($info as $file){
                    $cover = $file['savepath'].$file['savename'];
                }
                $data['cover'] = $cover;
                $junwei = M('junwei')->find();
                $loginName = $junwei['loginname'];
                $password = sp_authcode($junwei['password']);
                $realtime = I('post.realtime');
                $startDate = I('post.startDate');
                $invalidDate = I('post.invaliddate');
                $subject = I('post.course_name');
                $class_id = I('post.class_id');
                $response = new ResponseController();
                //调用修改课时接口
                $resource = $response::update_course($loginName,$password,$realtime,$startDate,$invalidDate,$subject,$class_id);
                if ($resource['code'] == 0) {
                    if ($course->where("id = $id")->save($data)) {
                        $this->success(L('ADD_SUCCESS'), U("Course/show"));
                        exit();
                    } else {
                        $this->error(L("ADD_FAILED"));
                        exit();
                    }
                }else {
                    $this->error(L("ADD_FAILED"));
                    exit();
                }
            }else {
                $id = I('post.id');
                $data = I('');
                $info = $upload->upload();
                foreach($info as $file){
                    $cover = $file['savepath'].$file['savename'];
                }
                $data['cover'] = $cover;
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
            $class_id = I('get.class_id');
            if (empty($class_id)) {
                if (isset($_GET['id'])) {
                    $id = intval(I("get.id"));
                    if ($course->where("id = $id")->delete() !== false) {
                        $this->success("删除成功！");
                    } else {
                        $this->error("删除失败！");
                    }
                }
            }else {
                $class_id = I('class_id');
                $junwei = M('junwei')->find();
                $loginName = $junwei['loginname'];
                $password = sp_authcode($junwei['password']);
                //调用课时删除接口
                $resourec = $response::delete($loginName,$password,$class_id);
                $id = intval(I("get.id"));
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
            $rew = $course->where("id in ($ids)")->select();
            $junwei = M('junwei')->find();
            $loginName = $junwei['loginname'];
            $password = sp_authcode($junwei['password']);
            foreach ($rew as $value) {
                $class_id[] = $value['class_id'];
                $is_free[] = $value['is_free'];
            }
            if (empty($class_id)) {
                $ids = join(",", $_POST['ids']);
                if ($course->where("id in ($ids)")->delete() !== false) {
                    $this->success("删除成功！");
                } else {
                    $this->error("删除失败！");
                }
            }else {
                $resourec = $response::delete($loginName, $password, $class_id);
                $ids = join(",", $_POST['ids']);
                if ($resourec['code'] == 0) {
                    if ($course->where("id in ($ids)")->delete() !== false) {
                        $this->success('删除成功');exit();
                    } else {
                        $this->error('删除失败');exit();
                    }
                } else {
                    $this->error('删除失败');exit();
                }
            }
        }
    }

    /**
     * 已结束课程
     */
    public function end ()
    {
        $time = time();
        $course = M('course');
        $where = "UNIX_TIMESTAMP(invaliddate) < '$time'";
        $count = $course->where($where)->count();
        $page = $this->page($count,20);
        $data = $course->where($where)->limit($page->firstRow . ',' . $page->listRows)->select();
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
        $course = M('course');
        $where = "is_free = 1";
        $count = $course->where($where)->count();
        $page = $this->page($count,20);
        $data = $course->where($where)->limit($page->firstRow . ',' . $page->listRows)->select();
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
        $course = M('course');
        $where = "is_free = 2";
        $count = $course->where($where)->count();
        $page = $this->page($count,20);
        $data = $course->where($where)->limit($page->firstRow . ',' . $page->listRows)->select();
        $this->assign("page", $page->show('Admin'));
        $this->assign('data',$data);
        $url = "{:U('Course/vip')}";
        $describe = "vip课";
        $this->assign('url',$url);
        $this->assign('describe',$describe);
        $this->display("Course/end");
    }
}