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
class ClassHourController extends AdminbaseController
{
    /**
     * 课时列表
     */
    public function show()
    {
        $live = M('live');
        $junwei = M('junwei')->find();
        $loginName = $junwei['loginName'];
        $password = $junwei['password'];
        $count = $live->count();
        $Page = new \Think\Page($count, 10);
        $show = $Page->show();
        if (IS_POST) {
            $keyword = I('post.keyword');
            if (!empty($keyword)) {
                $data = $live->where("subject like '%$keyword%'")
                    ->order('startDate')->limit($Page->firstRow . ',' . $Page->listRows)->select();
            } else {
                $this->redirect('show');
            }
        } else {
            $data = $live
                ->field('cmf_live.id,subject,course_name,cmf_live.startDate,cmf_live.invalidDate,cmf_live.class_id')
                ->join('cmf_course ON cmf_live.course_id = cmf_course.id')
                ->order('cmf_live.id')->limit($Page->firstRow . ',' . $Page->listRows)->select();
            if (empty($data)) {
                $this->redirect('create');
            }
        }
        $this->assign('loginName', $loginName);
        $this->assign('password', $password);
        $this->assign('data', $data);
        $this->assign('page', $show);
        $this->display();
    }

    /**
     * 查看课时详细信息
     */
    public function look()
    {
        if (IS_GET) {
            $id = I('get.id');
            $live = M('live');
            if (!empty($id)) {
                $data = $live
                    ->join('cmf_course ON cmf_live.course_id = cmf_course.id')
                    ->join('cmf_lector ON cmf_live.lector_id = cmf_lector.l_id')
                    ->where("cmf_live.id = $id")->find();
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
        $live = M('live');
        $course = M('course')->field('id,course_name')->select();
        $junwei = M('junwei')->find();
        $lector = M('lector')->select();
        $response = new ResponseController();
        if (IS_POST) {
            $data = I('');
            $data['startDate'] = strtotime(I('post.startDate'));
            $data['invalidDate'] = strtotime(I('post.invalidDate'));
            $subject = I('post.subject');

            $loginName = $junwei['loginname'];
            $password = sp_authcode($junwei['password']);
            $startDate = I('post.startDate');
            $invalidDate = I('post.invalidDate');
            //调用创建实时课堂接口
            $resource = $response::create_course($subject,$loginName,$password,$startDate,$invalidDate);
            $data['number'] = $resource['number'];
            $data['stu_token'] = $resource['studentClientToken'];
            $data['class_id'] = $resource['id'];
            if ($resource['code'] == 0) {
                if ($live->add($data)) {
                    $this->success(L('ADD_SUCCESS'), U("ClassHour/show"));
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
        $this->assign('course', $course);
        $this->assign('lector',$lector);
        $this->display();
    }

    /**
     * 修改课时信息
     */
    public function update()
    {
        $response = new ResponseController();
        $live = M('live');
        $lector = M('lector');
        $course = M('course');
        $junwei = M('junwei')->find();
        if (IS_GET) {
            $id = I('get.id');
            if (!empty($id)) {
                $data = $live
                    ->join('cmf_course ON cmf_live.course_id = cmf_course.id')
                    ->join('cmf_lector ON cmf_live.lector_id = cmf_lector.l_id')
                    ->where("cmf_live.id = $id")
                    ->find();
                $array['subject'] = $data['subject'];
                $array['class_id'] = $data['class_id'];
                $array['course'] = $course->field('id,course_name')->select();
                $array['lector'] = $lector->field('l_id,name')->select();
            }
            $this->assign('id', $id);
            $this->assign('array', $array);
            $this->assign('data', $data);
        }
        if (IS_POST) {
            $id = I('post.id');
            $data = I('');
            $data['startDate'] = strtotime($data['startDate']);
            $data['invalidDate'] = strtotime($data['invalidDate']);
            $loginName = $junwei['loginname'];
            $password = sp_authcode($junwei['password']);
            $realtime = I('post.realtime');
            $startDate = I('post.startDate');
            $invalidDate = I('post.invaliddate');
            $subject = I('post.subject');
            $class_id = I('post.class_id');
            //调用修改实时课堂接口
            $resource = $response::update_course($loginName,$password,$realtime,$startDate,$invalidDate,$subject,$class_id);
            if ($resource['code'] == 0) {
                if ($live->where("id = $id")->save($data)) {
                    $this->success(L('ADD_SUCCESS'), U("ClassHour/show"));
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
     * 删除课时
     */
    public function delete()
    {
        $live = M("live");
        $response = new ResponseController();
        if (IS_GET) {
            if (isset($_GET['id'])) {
                $class_id = I('class_id');
                $junwei = M('junwei')->find();
                $loginName = $junwei['loginname'];
                $password = sp_authcode($junwei['password']);
                //调用删除实时课堂接口
                $resourec = $response::delete($loginName,$password,$class_id);
                $id = intval(I("get.id"));
                if ($resourec['code'] == 0) {
                    if ($live->where("id = $id")->delete() !== false) {
                        $this->success("删除成功！");exit();
                    } else {
                        $this->error("删除失败！");exit();
                    }
                }

            }
        }
        if (isset($_POST['ids'])) {
            $ids = join(",", $_POST['ids']);
            $rew = $live->where("id in ($ids)")->select();
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
                if ($live->where("id in ($ids)")->delete() !== false) {
                    $this->success("删除成功！");exit();
                } else {
                    $this->error("删除失败！");exit();
                }
            } else {
                $this->error("操作有误");exit();
            }
        }
    }


    /**
     * 生成回放
     */
    public function playback()
    {
        $live = M('live');
        if (IS_GET) {
            $id = I('get.id');
            if (is_numeric($id)) {
                $class_id = $live->where("id = $id")->getField('class_id');
                $junwei = M('junwei')->find();
                $response = new ResponseController();
                $loginName = $junwei['loginname'];
                $password = sp_authcode($junwei['password']);
                $resource = $response::get_past($loginName, $password, $class_id);
                if ($resource['code'] == 0) {
                    $res = $resource['coursewares'][0];
                    $data['number'] = $res['number'];
                    $data['status'] = 3;
                    $data['reply_url'] = $res['url'];
                    if ($live->where("id = $id")->save($data)) {
                        $this->success(L('ADD_SUCCESS'), U("Course/show"));
                        exit();
                    } else {
                        $this->error(L('ADD_FAILED'));
                        exit();
                    }
                }
            }
        }
    }


}