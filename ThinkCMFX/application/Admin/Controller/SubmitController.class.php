<?php
namespace Admin\Controller;

use Common\Controller\AdminbaseController;

class SubmitController extends AdminbaseController
{
   public function post ($url,$data){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // post数据
        curl_setopt($ch, CURLOPT_POST, 1);
        // post的变量
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);//组装post变量
        $output = curl_exec($ch);
        curl_close($ch);
        //打印获得的数据
        // print_r($output);
        return $output;
    }

    public static function state($code,$mess){
        return json_encode([
            'status'=>$code,
            'msg'=>$mess,
            'data'=>NULL
        ]);die;
    }
}