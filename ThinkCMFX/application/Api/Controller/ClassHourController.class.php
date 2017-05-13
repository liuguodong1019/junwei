<?php
namespace Api\Controller;

use Think\Controller;

class ClassHourController extends Controller
{
    /**
     * 课时列表接口
     */
    public function class_hour ()
    {
        $status = C('status');
        $msg = C('msg');
        $live = M('live');
        $response = new SubmitController();
        if (IS_GET) {
            $id = I('get.id');
            $page = empty(I('get.page')) ? 1:I('get.page');
            $rew = M('course')->field('status')->where("id = $id")->find();
            if (is_numeric($id)) {
                if ($rew['status'] == 2) {
                    $data = $live
                        ->field('id,subject,reply_url,status,startDate,invalidDate,number,stu_token,class_id')
                        ->where("course_id = $id and status = 2")->order('cmf_live.id')->page($page.',10')->select();
                }else {
                    $data = $live
                        ->field('id,subject,reply_url,status,startDate,invalidDate,number,stu_token,class_id')
                        ->where("course_id = $id")->order('cmf_live.id')->page($page.',10')->select();
                }
                if (!empty($data)) {
                    echo json_encode([
                        'status' => $status[0],
                        'msg' => $msg[0],
                        'data' => $data
                    ]);exit();
                }else {
                    echo $response::state($status[0],$msg[0],$data = null);exit();
                }
            }else {
                echo $response::state($status[2],$msg[2]);exit();
            }
        }else {
            echo $response::state($status[3],$msg[3]);exit();
        }
    }
}