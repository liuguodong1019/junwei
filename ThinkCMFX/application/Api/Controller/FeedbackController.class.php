<?php
namespace Api\Controller;

use Think\Controller;
/*
 * 意见反馈的接口
 * */
class FeedbackController extends Controller{
    public function feed()
    {
        if (IS_POST) {
            $uid = I("post.uid");
            $data['uid'] = $uid;
            $data['f_desc'] = I('post.content');
            $data['f_information'] = I("post.information");
            $data['f_time'] = date("Y-m-d H:i:s");

            $model = M("Feedback");
            $res = $model->where("uid = $uid")->field("f_time")->select();

            for($i=0;$i<count($res);$i++){
                $datetime = $res[$i]['f_time'];
                $b = substr($datetime,0,10);
                // 获取今天的日期，格式为 YYYY-MM-DD
                $c = date('Y-m-d');
                if($b==$c){
                    $dat['status'] = 0;
                    $dat['msg'] = '您今天已经提交意见反馈！';
                    echo json_encode($dat);
                    die;
                }
            }
            if ($model->add($data)) {
                $dat['status'] = 1;
                $dat['msg'] = '提交成功';
                echo json_encode($dat);
                die;
            } else {
                $dat['status'] = 0;
                $dat['msg'] = '提交失败';
                echo json_encode($dat);
                die;
            }

        } else {
            $dat['status'] = 102;
            $dat['msg'] = '请求方式错误';
            echo json_encode($dat);die;

        }
    }
}