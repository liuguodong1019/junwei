<?php
namespace Api\Controller;

use Think\Controller;

class ClassHourController extends Controller
{
    /**
     * 课时列表接口
     */
    public function class_hour()
    {
        $status = C('status');
        $msg = C('msg');
        $live = M('live');
        $response = new SubmitController();
        if (IS_GET) {
            $id = I('get.id');
            $page = empty(I('get.page')) ? 1 : I('get.page');
            if (is_numeric($id)) {
            $data = $live
                ->field('id,subject,reply_url,status,startDate,invalidDate,number,stu_token,class_id')
                ->where("course_id = $id")->order('cmf_live.id')->page($page . ',10')->select();
                if (!empty($data)) {
                    echo json_encode([
                        'status' => $status[0],
                        'msg' => $msg[0],
                        'data' => $data
                    ]);
                    exit();
                } else {
                    echo $response::state($status[0], $msg[0], $data = null);
                    exit();
                }
            } else {
                echo $response::state($status[2], $msg[2]);
                exit();
            }
        } else {
            echo $response::state($status[3], $msg[3]);
            exit();
        }
    }

    /**
     * vip生成回放
     */
    public function vipReply ()
    {
        $live = M('live');
        $course = M('course');
        $data = $live->field('id,class_id,course_id')->where("status = '2'")->select();
        if (!empty($data)) {
            foreach ($data as $value) {
                $class_id[] = $value['class_id'];
                $id[] = $value['id'];
                $course_id[] = $value['course_id'];
            }
            $course_id = join(",", $course_id);
            $junwei = M('junwei')->find();
            $loginName = $junwei['loginname'];
            $password = sp_authcode($junwei['password']);
            $response = new ResponseController();
            $resource = $response::get_past($loginName,$password,$class_id);
            $len = count($resource);
            for ($k = 0; $k < $len; $k++) {
                $rew[] = json_decode($resource[$k],true);
                $res[] = $rew[$k]['coursewares'][0];
                if ($rew[$k]['code'] == 0) {
                    $data['number'] = $res[$k]['number'];
                    $data['reply_url'] = $res[$k]['url'];
                    $data['status'] = 3;
                }
                if ($live->where("id = '$id[$k]'")->save($data)) {
                    $dat['status'] = 3;
                    $course->where("id in ($course_id)")->save($dat);
                }
            }
        }
    }
}