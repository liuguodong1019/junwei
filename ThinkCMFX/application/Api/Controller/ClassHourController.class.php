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
            if (is_numeric($id)) {
                $data = $live
                    ->field('id,subject,reply_url,status,startDate,invalidDate,number,stu_token,class_id')
                    ->where("course_id = $id")->order('cmf_live.id')->page($page.',10')->select();
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

    /**
     * 课时直播结束
     */
    public function live_end ()
    {
        $succ = C('status');
        $mess = C('msg');
        $model = new SubmitController();
        if (IS_GET) {
            $id = I('get.id');
            if (is_numeric($id)) {
                $live = M('live');
                $rew = $live->where("id = $id")->getField('class_id');
                if ($rew) {
                    $live->status = 2;
                    if ($live->where("id = $id")->save()) {
                        echo json_encode([
                            'status' => $succ[0],
                            'msg' => $mess[5],
                        ]);die;
                    }
                }else {
                    echo $model::state($succ[2], $mess[2]);die;
                }
            }else {
                echo $model::state($succ[2], $mess[2]);die;
            }
        }else {
            echo $model::state($succ[3], $mess[3]);die;
        }
    }

    /**
     * 直播结束生成回放
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
                        $this->success(L('ADD_SUCCESS'), U("ClassHour/show"));
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