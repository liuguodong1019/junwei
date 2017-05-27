<?php

namespace Api\Controller;

use Think\Controller;

use Api\Controller\SubmitController;

class ResponseController extends Controller
{
    /**
     * 创建实时课堂
     */
    public static function create_course($subject, $loginName, $password, $startDate, $invalidDate)
    {
        $url = "http://junwei.gensee.com/integration/site/training/room/created";
        $data = array(
            'loginName' => $loginName,
            'password' => $password,
            'subject' => $subject,
            'startDate' => $startDate,
            'invalidDate' => $invalidDate
        );
        $model = new SubmitController();
        $result = $model->post($url, $data);
        $result = json_decode($result, true);
        return $result;
    }

    /**
     * 修改实时课堂
     */
    public static function update_course($loginName, $password, $realtime, $startDate, $invalidDate, $subject, $class_id)
    {
        $url = "http://junwei.gensee.com/integration/site/training/room/modify";
        $data = array(
            'loginName' => $loginName,
            'password' => $password,
            'subject' => $subject,
            'startDate' => $startDate,
            'realtime' => $realtime,
            'invalidDate' => $invalidDate,
            'id' => $class_id
        );
        $model = new SubmitController();
        $result = $model->post($url, $data);
        $result = json_decode($result, true);
        return $result;
    }

    /**
     * 删除实时课堂
     */
    public static function delete($loginName, $password, $class_id)
    {
        $url = "http://junwei.gensee.com/integration/site/training/room/deleted";
        $len = count($class_id);
        if ($len > 1) {
            for ($k = 0; $k < $len; $k++) {
                $data = array(
                    'loginName' => $loginName,
                    'password' => $password,
                    'roomId' => $class_id[$k]
                );
            }
        } else {
            $data = array(
                'loginName' => $loginName,
                'password' => $password,
                'roomId' => $class_id
            );
        }

        $model = new SubmitController();
        $result = $model->post($url, $data);
        $result = json_decode($result, true);
        return $result;
    }

    /**
     * 获取录制的课件
     */
    public static function get_past($loginName, $password, $class_id)
    {
        $url = "http://junwei.gensee.com/integration/site/training/courseware/list";
        $model = new SubmitController();
        $len = count($class_id);
        for ($a = 0; $a < $len; $a++) {
            $data[] = array(
                'loginName' => $loginName,
                'password' => $password,
                'roomId' => $class_id[$a]
            );
        }
        $length = count($data);
        for ($j = 0; $j < $length; $j++) {
            $result[] = $model->post($url, $data[$j]);
        }
        return $result;
    }

    /**
     * 创建讲师接口
     */
    public static function create_lector($loginName,$password,$name,$teacherLoginName,$teacherPassword)
    {
        $url = 'http://junwei.gensee.com/integration/site/training/teacher/created';
        $model = new SubmitController();
        $data = array(
            'loginName' => $loginName,
            'password' => $password,
            'name' => $name,
            'teacherLoginName' => $teacherLoginName,
            'teacherPassword' => $teacherPassword,
        );
        $result = $model->post($url, $data);
        $result = json_decode($result, true);
        return $result;
    }
}