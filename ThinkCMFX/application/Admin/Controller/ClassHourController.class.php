<?php
/**
 * Created by PhpStorm.
 * User: ������
 * Date: 2017/4/26
 * Time: 11:47
 */

namespace Admin\Controller;

use Common\Controller\AdminbaseController;
use Api\Controller\ResponseController;

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
        $live = M('live');
        $junwei = M('junwei')->find();
        $loginName = $junwei['loginName'];
        $password = $junwei['password'];
        $count = $live->count();
        $page = $this->page($count,20);
        if (IS_POST) {
            $keyword = I('post.keyword');
            if (!empty($keyword)) {
                $data = $live->where("subject like '%$keyword%'")
                    ->order('startDate')->limit($page->firstRow . ',' . $page->listRows)->select();
            } else {
                $this->redirect('show');
            }
        } else {
            $data = $live->table('cmf_live as a')
                ->field('a.id,a.subject,b.course_name,a.startDate,a.invalidDate,a.class_id')
                ->join('left join cmf_course as b ON a.course_id = b.id')
                ->where("a.is_free = 2")
                ->order('a.id')->limit($page->firstRow . ',' . $page->listRows)->order('a.id desc')->select();
            if (empty($data)) {
                $this->redirect('create');
            }
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
        if (IS_GET) {
            $id = I('get.id');
            $live = M('live');
            if (!empty($id)) {
                $data = $live->table('cmf_live as a')
                    ->field('a.subject,a.id,a.startDate,a.invalidDate,a.number,a.stu_token,a.class_id,b.course_name,c.name,a.status,a.reply_url')
                    ->join('cmf_course as b ON a.course_id = b.id')
                    ->join('cmf_lector as c ON a.lector_id = c.l_id')
                    ->where("a.id = '$id'")->find();
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
        $course = M('course')->field('id,course_name')->where("is_free = 2")->select();
        $junwei = M('junwei')->find();
        $lector = M('lector')->select();
        $response = new ResponseController();
        if (IS_POST) {
            $data = I('');
            $loginName = $junwei['loginname'];
            $password = sp_authcode($junwei['password']);
            $startDate = I('post.startDate');
            $invalidDate = I('post.invalidDate');
            $data['startDate'] = strtotime(I('post.startDate'));
            $data['invalidDate'] = strtotime(I('post.invalidDate'));
            $subject = I('post.subject');
            $response = new ResponseController();
            //调用课时创建接口
            $resource = $response::create_course($subject,$loginName,$password,$startDate,$invalidDate);
           
            $data['number'] = $resource['number'];
            $data['stu_token'] = $resource['studentClientToken'];
            $data['class_id'] = $resource['id'];
            
            if ($resource['code'] == 0) {
                if ($live->add($data)) {
                    $this->success(L('ADD_SUCCESS'), U("ClassHour/show"));
                    exit();
                }else {
                    $this->error(L('ADD_FAILED'));
                    exit();
                }
            }else {
                $this->error(L('ADD_FAILED'));
                exit();
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
        $response = new ResponseController();
        $live = M('live');
        $lector = M('lector');
        $course = M('course');
        $junwei = M('junwei')->find();
        if (IS_GET) {
            $id = I('get.id');
            if (!empty($id)) {
                $data = $live
                    ->field('cmf_course.id,cmf_course.course_name,cmf_live.subject,cmf_live.reply_url,cmf_live.is_free,cmf_lector.l_id,cmf_lector.name,cmf_live.startdate,cmf_live.invaliddate,cmf_live.class_id')
                    ->join('cmf_course ON cmf_live.course_id = cmf_course.id')
                    ->join('cmf_lector ON cmf_live.lector_id = cmf_lector.l_id')
                    ->where("cmf_live.id = $id")
                    ->find();

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
            $loginName = $junwei['loginname'];
            $password = sp_authcode($junwei['password']);
            $realtime = I('post.realtime');
            $startDate = strtotime(I('post.startDate'));
            $invalidDate = strtotime(I('post.invalidDate'));
            $data['startDate'] = $startDate;
            $data['invalidDate'] = $invalidDate;
            $subject = I('post.subject');
            $class_id = I('post.class_id');
            //调用修改课时接口
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
     * 课时删除
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
                //调用课时删除接口
                $resourec = $response::delete($loginName,$password,$class_id);
                $id = intval(I("get.id"));
                if ($resourec['code'] == 0) {
                    if ($live->where("id = $id")->delete() !== false) {
                        $this->success(L('ADD_SUCCESS'));exit();
                    } else {
                        $this->error(L("ADD_FAILED"));exit();
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
            //调用删除课时接口
            $resourec = $response::delete($loginName, $password, $class_id);
            $ids = join(",", $_POST['ids']);
            if ($resourec['code'] == 0) {
                if ($live->where("id in ($ids)")->delete() !== false) {
                    $this->success(L('ADD_SUCCESS'));exit();
                } else {
                    $this->error(L("ADD_FAILED"));exit();
                }
            } else {
                $this->error(L("ADD_FAILED"));exit();
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