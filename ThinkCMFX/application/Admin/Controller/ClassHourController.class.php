<?php
/**
 * Created by PhpStorm.
 * User: ������
 * Date: 2017/4/26
 * Time: 11:47
 */

namespace Admin\Controller;

use Api\Controller\CacheController;
use Common\Controller\AdminbaseController;
use Api\Controller\ResponseController;
use Common\Model\CourseModel;
use Common\Model\LiveModel;

/**
 * Class LiveController
 * @package Admin\Controller
 *
 */
class ClassHourController extends AdminbaseController
{
    /**
     * 课时列表
     */
    public function show()
    {
       $str = new CourseModel();
       $redis = $str::redis();
//        $id = $redis->get('live_id');
       // $rew = json_decode($redis->hGet('live',11),true);
       // $rew['subject'] = '理论法3';
       // $rew['startDate'] = '2017-06-28 19:00:00';
       // $rew['invalidDate'] = '2017-06-28 21:00:00';
       // $rew['course_id'] = '14';
       // $rew['lector'] = '0';
       // $rew['id'] = '11';
       // $data['status'] = 3;
       // $rew['status'] = $data['status'];
       // $rew['number'] = '85429448';
       // $rew['stu_token'] = '58eaecb94aed8';
       // $rew['reply_url'] = 'http://junwei.gensee.com/training/site/v/43362436';
       // $rew['class_id'] = 'P9xJu4Fu4d';

       // $list = json_encode($rew);
       // $redis->hSet('live',11,$list);
       // $list = json_encode($rew);
//        unset($res['book']);
//        unset($res['people']);
//        $list = $res;
//        $redis->hSet('live',1,$list);
       // $res = $redis->hVals('live');
       // foreach ($res as $val) {
       //     $rew[] = json_decode($val,true);
       // }
       // echo '<pre>';
       // print_r($rew);die;
        $live = D('live');
        $junwei = M('junwei')->find();
        $loginName = $junwei['loginName'];
        $password = $junwei['password'];
        $count = $live->count();
        $page = $this->page($count,20);
        $resource = new LiveModel();
        $data = $resource->show($page);
        // echo "<pre>";
        // print_r($data);die;
        if (empty($data)) {
            $this->redirect('create');
        }
        $this->assign('loginName', $loginName);
        $this->assign('password', $password);
        $this->assign('data', $data);
        $this->assign('page', $page->show('Admin'));
        $this->display();
    }

    /**
     * 查看课时
     */
    public function look()
    {
        $str = new CourseModel();
        $redis = $str::redis();
        $look = new CacheController();
        if (IS_GET) {
            $id = I('get.id');
            $live = D('live');
            if (!empty($id)) {
                $res = json_decode($redis->hGet('live',$id),true);
                if (!empty($res)) {
                    $rew = $look->live($res);
                    $data = $rew;
                }else {
                    $data = $live->table('cmf_live as a')
                        ->field('a.subject,a.id,a.startDate,a.invalidDate,a.number,a.stu_token,a.class_id,b.course_name,a.lector,a.status,a.reply_url')
                        ->join('cmf_course as b ON a.course_id = b.id')
                        ->where("a.id = '$id'")->find();
                }
            }
        }
        $this->assign('data', $data);
        $this->display();
    }

    /**
     * 创建课时
     */
    public function create()
    {
        $course = D('course')->field('id,course_name')->where("is_free = 2")->select();
        $lector = M('lector')->select();
        $create = new LiveModel();
        if (IS_POST) {
            $data = I('');
            $type = $create->create($data);
            if ($type != false) {
                $this->success(L('ADD_SUCCESS'), U("ClassHour/show"));exit();
            }else {
                $this->error(L('ADD_FAILED'));exit();
            }
        }
        $this->assign('course', $course);
        $this->assign('lector',$lector);
        $this->display();
    }

    /**
     * 课时修改
     */
    public function update()
    {
        $live = D('live');
        $lector = M('lector');
        $course = D('course');
        $update = new LiveModel();
        $str = new CourseModel();
        $rew = new CacheController();
        $redis = $str::redis();
        if (IS_GET) {
            $id = I('get.id');
            if (!empty($id)) {
                $res = json_decode($redis->hGet('live',$id),true);
                if (!empty($res)) {
                    $test = $rew->live($res);
                    $data = $test;
                }else {
                    $data = $live->table('cmf_live as a')
                        ->field('b.id as course_id,b.course_name,a.subject,a.reply_url,a.is_free,a.lector,a.startdate as startDate,a.invaliddate as invalidDate,a.class_id,a.courseware_id')
                        ->join('cmf_course as b ON a.course_id = b.id')
                        ->where("a.id = $id")
                        ->find();
                }

                $array['course'] = $course->field('id,course_name')->where("is_free = '2'")->select();
                $array['lector'] = $lector->field('l_id,name')->select();
            }
            $this->assign('id', $id);
            $this->assign('array', $array);
            $this->assign('data', $data);
        }
        if (IS_POST) {
            $id = I('post.id');
            $data = I('');
            $type = $update->update($data,$id);
            if ($type != false) {
                $this->success(L('ADD_SUCCESS'), U("ClassHour/show"));exit();
            }else {
                $this->error(L("ADD_FAILED"));exit();
            }
        }
        $this->display();
    }

    /**
     * 课时删除
     */
    public function delete()
    {
        $live = M("live");
        $response = new ResponseController();
        $str = new CourseModel();
        $redis = $str::redis();
        if (IS_GET) {
            if (isset($_GET['id'])) {
                $class_id = I('class_id');
                $courseware_id = I('courseware_id');
                $junwei = M('junwei')->find();
                $loginName = $junwei['loginname'];
                $password = sp_authcode($junwei['password']);
                //调用课时删除接口
                $resourec = $response::delete($loginName,$password,$class_id);
                $test = $response::delPast($loginName, $password, $courseware_id);
                $id = intval(I("get.id"));
                if ($resourec['code'] == 0 && $test['code'] == 0) {
                    if ($live->where("id = $id")->delete() !== false) {
                        $redis->hDel('live',$id);
                        $this->success('删除成功');exit();
                    } else {
                        $this->error('删除失败');exit();
                    }
                }

            }
        }
        if (isset($_POST['ids'])) {
            $id = $_POST['ids'];
            $ids = join(",", $id);
            $rew = $live->field('class_id,courseware_id')->where("id in ($ids)")->select();
        
            $junwei = M('junwei')->find();
            $loginName = $junwei['loginname'];
            $password = sp_authcode($junwei['password']);
            foreach ($rew as $value) {
                $class_id[] = $value['class_id'];
                $courseware_id = $value['courseware_id']; 
            }
            $resourec = $response::delete($loginName, $password, $class_id);
            $response::delete_live($loginName, $password, $courseware_id);
            if ($live->where("id in ($ids)")->delete() !== false) {
                $len = count($id);
                for ($k = 0; $k < $len; $k++) {
                    $redis->hDel('live',$id[$k]);
                }
                $this->success('删除成功');exit();
            } else {
                $this->error('删除失败');exit();
            }  
        }
    }


    /**
     * 正在直播
     */
    public function play ()
    {
        $live = M('live');
        $where = "a.status = '1'";
        $count = $live->table('cmf_live as a')->where($where)->count();
        $page = $this->page($count,20);
        $data = $live->table('cmf_live as a')
            ->field('a.id,a.subject,b.course_name,a.startDate,a.invalidDate,a.class_id')
            ->join('left join cmf_course as b ON a.course_id = b.id')
            ->where($where)
            ->order('a.id DESC')->limit($page->firstRow . ',' . $page->listRows)->select();
        if (empty($data)) {
            $this->redirect('create');
        }
        $this->assign("page", $page->show('Admin'));
        $this->assign('data',$data);
        $this->display('ClassHour/show');
    }

    /**
     * 点播
     */
    public function reply ()
    {
        $live = M('live');
        $where = "a.status = '3'";
        $count = $live->table('cmf_live as a')->where($where)->count();
        $page = $this->page($count,20);
        $data = $live->table('cmf_live as a')
            ->field('a.id,a.subject,b.course_name,a.startDate,a.invalidDate,a.class_id')
            ->join('left join cmf_course as b ON a.course_id = b.id')
            ->where($where)
            ->order('a.id DESC')->limit($page->firstRow . ',' . $page->listRows)->select();
        if (empty($data)) {
            $this->redirect('create');
        }
        $this->assign("page", $page->show('Admin'));
        $this->assign('data',$data);
        $this->display('ClassHour/show');
    }
}