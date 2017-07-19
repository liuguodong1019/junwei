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
        $str = new CourseModel();
        $redis = $str::redis();
//        $rew = $redis->hVals('course');
//        foreach ($rew as $val) {
//            $res[] = json_decode($val,true);
//        }
        // $redis->hDel('course',27);
       $res = json_decode($redis->hGet('course',29),true);
       // $res['lector'] = '';
//        $res['status'] = 3;
//        $res['reply_url'] = 'http://junwei.gensee.com/training/site/v/43991911';
//        $res['number'] = '43991911';
//        $res['courseware_id'] = 'TXvFMxUYWA';
       // $list = json_encode($res);
       // $redis->hSet('course',29,$list);
       echo '<pre>';
       print_r($res);die;
        $course = D('Course');

        $junwei = M('junwei')->find();
        $loginName = $junwei['loginName'];
        $password = $junwei['password'];
        $count = $course->count();
        $Page = $this->page($count, 20);
        $str = new CourseModel();
        $data = $str->show($Page);

        if (empty($data)) {
            $this->redirect('create');
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
        $str = new CourseModel();
        $redis = $str::redis();
        if (IS_GET) {
            $id = I('get.id');
            $rew = $redis->hGet("course",$id);

            if (!empty($id)) {
                if (empty($rew)) {
                    $data = M('course')
                        ->where("id = $id")->find();
                }else {
                    $data = json_decode($rew,true);
                }
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
        $redis = $str::redis();
        if (IS_GET) {
            $id = I('get.id');
            if (!empty($id)) {
                $rew = $redis->hGet('course',$id);
                if (!empty($rew)) {
                    $data = json_decode($rew,true);
                }else {
                    $data = $course
                        ->field('is_free,num_class,course_name,now_price,old_price,cover,detail_cover,startdate,invaliddate,introduction,lector,people,book,courseware_id')
                        ->where("id = $id")
                        ->find();
                }
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
        $str = new CourseModel();
        $redis = $str::redis();
        if (IS_GET) {
            $id = I('get.id');
            $class_id = I('get.class_id');
            $courseware_id = I('get.courseware_id');
            if (empty($class_id)) {
                if ($course->where("id = '$id'")->delete() !== false) {
                    $redis->hDel('course',$id);
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
                $ter = $response::delete_live($loginName,$password,$courseware_id);
                // print_r($ter);die;
                if ($resourec['code'] == 0 || $ter['code'] == 0) {
                    if ($course->where("id = '$id'")->delete() !== false) {
                        $redis->hDel('course',$id);
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
            $id = $_POST['ids'];
            $ids = join(",", $id);
            $rew = $course->field('class_id,courseware_id')->where("id in ($ids)")->select();
            $junwei = M('junwei')->find();
            $loginName = $junwei['loginname'];
            $password = sp_authcode($junwei['password']);
            foreach ($rew as $value) {
                $class_id[] = $value['class_id'];
                $courseware_id[] = $value['courseware_id'];
            }
            $id = intval(I("get.id"));
            $response::delete($loginName, $password, $class_id);
            $response::delete_live($loginName,$password,$courseware_id);
            if ($course->where("id in ($ids)")->delete() !== false) {
                $len = count($id);
                for ($a = 0; $a < $len; $a++) {
                    $redis->hDel('course',$id[$a]);
                }
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

    /**
     * 手动添加公开课
     */
    public function add ()
    {
        $str = new CourseModel();
        $redis = $str::redis();
        $course = D('course');
        $lector = M('lector');
        $photo = C('upload');
        $teacher = $lector->field('name')->select();
        if (IS_POST) {
            // $data = I('');
            $data['course_name'] = htmlspecialchars(trim(I('post.course_name')));
            $data['is_free'] = htmlspecialchars(trim(I('post.is_free')));
            $data['number'] = htmlspecialchars(trim(I('post.number')));
            $data['stu_token'] = htmlspecialchars(trim(I('post.stu_token')));
            $data['class_id'] = htmlspecialchars(trim(I('post.class_id')));
            $data['courseware_id'] = htmlspecialchars(trim(I('post.courseware_id')));
            $data['introduction'] = htmlspecialchars(trim(I('post.introduction')));
            $data['lector'] = !empty(htmlspecialchars(trim(I('post.lector')))) ? htmlspecialchars(trim(I('post.lector'))): 0;
            // $lector1 = !empty(htmlspecialchars(trim(I('post.lector1')))) ? htmlspecialchars(trim(I('post.lector1'))): 0;
            $data['startDate'] = I('post.startDate').':00';
            $data['invalidDate'] = I('post.invalidDate').':00';
            $data['status'] = !empty(htmlspecialchars(trim(I('post.status')))) ? htmlspecialchars(trim(I('post.status'))): 0;
            $data['reply_url'] = !empty(htmlspecialchars(trim(I('post.reply_url')))) ? htmlspecialchars(trim(I('post.reply_url'))): 0;
            $upload = new \Think\Upload($photo);
            $info = $upload->upload();
            foreach($info as $file){
                $cover = $file['savepath'].$file['savename'];
                $data['cover'] = $cover;
            }
            $redis->incr("id");
            $id = $redis->get("id");
            $data['startDate'] = I('post.startDate').':00';
            $data['invalidDate'] = I('post.invalidDate').':00';
            $data['id'] = $id;
            if ($course->add($data)) {
                $res = json_encode($data);
                $redis->hSet('course',$id,$res);
                $this->success('添加成功');exit();
            }
        }
        $this->assign('teacher',$teacher);
        $this->display();
    }

    /**
     * 管理课程对应的课时
     */
    public function manage ()
    {
        $live = D('live');
        if (IS_GET) {
            $id = I('get.id');
            $data = $live->field('subject,id,startDate,invalidDate,number,stu_token,class_id,status,reply_url')->where("course_id = '$id'")->select();
        }
        $this->assign('data',$data);
        $this->display();
    }
}