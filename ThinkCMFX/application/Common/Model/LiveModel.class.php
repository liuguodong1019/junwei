<?php
/**
 * Created by PhpStorm.
 * User: 刘国栋
 * Date: 2017/6/24
 * Time: 10:57
 */
namespace Common\Model;
use Api\Controller\CacheController;
use Think\Model;
use Api\Controller\ResponseController;
use Common\Model\CourseModel;

class LiveModel extends Model
{
    /**
     * @param $page
     * @return bool
     * 课时列表
     */
    public function show ($page)
    {
        if (IS_POST) {
            $keyword = I('post.keyword');
            if (!empty($keyword)) {
                $data = $this->where("subject like '%$keyword%'")
                    ->order('startDate')->limit($page->firstRow . ',' . $page->listRows)->select();
            } else {
                return false;
            }
        } else {
            $data = $this->table('cmf_live as a')
                ->field('a.id,a.subject,b.course_name,a.startDate,a.invalidDate,a.class_id,a.courseware_id')
                ->join('left join cmf_course as b ON a.course_id = b.id')
                ->where("a.is_free = 2")
                ->order('a.id')->limit($page->firstRow . ',' . $page->listRows)->order('a.id desc')->select();
        }
        return $data;
    }

    /**
     * 创建课时
     */
    public function create ($data)
    {
        $str = new CourseModel();
        $redis = $str::redis();
        $key = $redis->get("live_id");
        if (empty($key)) {
            $redis->set("live_id",0);
        }
        $response = new ResponseController();
        $junwei = M('junwei')->find();
        $loginName = $junwei['loginname'];
        $password = sp_authcode($junwei['password']);
        $startDate = I('post.startDate');
        $invalidDate = I('post.invalidDate');
        $data['startDate'] = strtotime(I('post.startDate'));
        $data['invalidDate'] = strtotime(I('post.invalidDate'));
        $subject = I('post.subject');
        //调用课时创建接口
        $resource = $response::create_course($subject,$loginName,$password,$startDate,$invalidDate);
        $data['number'] = $resource['number'];
        $data['stu_token'] = $resource['studentClientToken'];
        $data['class_id'] = $resource['id'];
        if ($resource['code'] == 0) {
            if ($this->add($data)) {
                $redis->incr("live_id");
                $id = $redis->get("live_id");
                $data['id'] = $id;
                $data['status'] = 0;
                $data['reply_url'] = 0;
                $data['startDate'] = $startDate;
                $data['invalidDate'] = $invalidDate;
                $res = json_encode($data);
                $redis->hSet('live',$id,$res);
                return true;
            }else {
                return false;
            }
        }else {
            return false;
        }
    }

    /**
     * 课时修改
     */
    public function update ($data,$id)
    {
        $test = new CacheController();
        $rew = $test->liveUpdate($data,$id);

        if (!empty($rew)) {
            if (!is_array($rew)) {
                if ($rew !== false) {
                    return true;
                }
            }else {
                $data = $rew;
            }
        }
        $response = new ResponseController();
        $junwei = M('junwei')->find();
        $loginName = $junwei['loginname'];
        $password = sp_authcode($junwei['password']);
        $realtime = I('post.realtime');
        $startDate = I('post.startDate');
        $invalidDate = I('post.invalidDate');
        $data['startDate'] = $startDate;
        $data['invalidDate'] = $invalidDate;
        $subject = I('post.subject');
        $class_id = I('post.class_id');
        $courseware_id = I('post.courseware_id');
        //调用修改课时接口
        $resource = $response::update_course($loginName,$password,$realtime,$startDate,$invalidDate,$subject,$class_id);
        $ter = $response::update_live($courseware_id,$subject,$loginName,$password);
        if ($resource['code'] == 0 && $ter['code'] == 0) {
            $data['startDate'] = strtotime(I('post.startDate'));
            $data['invalidDate'] = strtotime(I('post.invalidDate'));
            if ($this->where("id = $id")->save($data)) {
                return true;
            } else {
                return false;
            }
        }
    }
}