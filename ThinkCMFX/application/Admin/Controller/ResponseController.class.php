<?php

namespace Admin\Controller;

use Common\Controller\AdminbaseController;

use Admin\Controller\SubmitController;

class ResponseController extends AdminbaseController
{
    /**
     * 创建实时课堂
     */
    public static function create_course ($subject,$loginName,$password,$startDate)
    {
        $url = "http://junwei.gensee.com/integration/site/training/room/created";
        $data = array(
            'loginName' => $loginName,
            'password' => $password,
            'subject' => $subject,
            'startDate' => $startDate
        );
        $model = new SubmitController();
        $result = $model->post($url,$data);
        $result = json_decode($result,true);
        return $result;
    }

    /**
     * 修改实时课堂
     */
    public static function update_course ($loginName,$password,$realtime,$startDate,$subject,$class_id) {
        $url = "http://junwei.gensee.com/integration/site/training/room/modify";
        $data = array(
            'loginName' => $loginName,
            'password' => $password,
            'subject' => $subject,
            'startDate' => $startDate,
            'realtime' => $realtime,
            'id' => $class_id
        );
        $model = new SubmitController();
        $result = $model->post($url,$data);
        $result = json_decode($result,true);
        return $result;
    }

    /**
     * 删除实时课堂
     */
    public static function delete ($loginName,$password,$class_id)
    {
        $url = "http://junwei.gensee.com/integration/site/training/room/deleted";
        $len = count($class_id);
        for ($k = 0; $k < $len; $k++) {
            $data = array(
                'loginName' => $loginName,
                'password' => $password,
                'roomId' => $class_id[$k]
            );
        }
        $model = new SubmitController();
        $result = $model->post($url,$data);
        $result = json_decode($result,true);
        return $result;
    }
}