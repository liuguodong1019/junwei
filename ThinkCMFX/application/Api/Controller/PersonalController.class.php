<?php
namespace Api\Controller;

use Think\Controller;

class PersonalController extends Controller{
    /*
     * 展现个人信息
     **/
    public function showperson(){
        $uid = I('uid');
        $model = M("users");
        $data = $model->where("id = $uid")->field("user_nicename,mobile,user_email,avatar")->find();
        if (!empty($data)) {
            $arr['status'] = 1 ;
            $arr['msg'] = "请求成功";
            $arr['data'] = $data;
            echo json_encode($arr);
        }else {
            $arr['status'] = 1 ;
            $arr['msg'] = "请求成功";
            $arr['data'] = '';
            echo json_encode($arr);
        }
    }


    /*
     * 安卓的base64上传图片
     * */
    public function upload(){
        if(IS_POST){
            $data = $_POST['imgFile'];
            $uid = $_POST['uid'];
            $img=base64_decode($data);
            $path = "./upload/avatar/".date('Y-m-d',time());
            if(!is_dir($path)){
                mkdir($path,0777,true);
            }
            //$path1 = substr($path,1);
            $daata['avatar'] = $path .'/'.time().'.jpg';

            if(file_put_contents($daata['avatar'],$img)){
                //去掉前面的“。”
                $aa = substr($daata['avatar'],1);
                $datab['avatar'] = $aa;
                if(M("Users")->where("id = $uid")->save($datab)){
                    $dat['status'] = 1;
                    $dat['msg'] = "提交成功";
                    echo json_encode($dat);die;
                }else{
                    $dat['status'] = 0;
                    $dat['msg'] = "提交失败";
                    echo json_encode($dat);die;
                }
            }else{
                $dat['status'] = 0;
                $dat['msg'] = "文件写入错误";
                echo json_encode($dat);die;
            }
        }else{
            $dat['status'] = 102;
            $dat['msg'] = "请求方式错误";
            echo json_encode($dat);die;
        }
    }


    /*
     * ios的普通上传
     * */
    public function uplaoded(){
        if(IS_POST){
            $uid = I("post.uid");          //获取用户的ID
            $upload = new \Think\Upload();// 实例化上传类
            $upload->maxSize   =     2097152 ;// 设置附件上传大小
            $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
            $upload->rootPath   =     "./";                                       //根目录可改
            $upload->savePath   =     "upload/avatar/";                         //保存路径可改
            //上传头像
            $info   =   $upload->uploadOne($_FILES['imgFile']);

            if(!$info){
                $arr['status'] = 0;
                $arr['msg'] = $upload->getError();
                echo json_encode($arr);
                die;
            }else {
                $model = M("Users");
                $avatar = $upload->rootPath . $info['savepath'] . $info['savename'];
                $avatar1 = substr($avatar,1);   //去掉最前面的.
                $data['avatar'] = $avatar1;
                if($model->where("id = $uid")->save($data)){
                    $arr['status'] = 1 ;
                    $arr['avatar'] = $avatar1;
                    $arr['msg'] = "头像修改成功";
                    echo json_encode($arr);die;
                }else{
                    $arr['status'] = 0 ;
                    $arr['msg'] = "提交失败";
                    echo json_encode($arr);die;
                }

            }
        }else{
            $arr['status'] = 102 ;
            $arr['msg'] = "请求方式错误";
            echo json_encode($arr);die;
        }

    }

/*
    public function aa(){
        $a=array("a"=>"red","b"=>"green","c"=>"yellow","d"=>"123");
        $b=array("a"=>"red","b"=>"green","c"=>"blue","d"=>"tian");

        $arr = array();
        $result=array_merge(array_diff($a,$b),array_diff($b,$a));

        foreach($result as $k=>$v){
            $arr[$k] = $v;
            /*
            if($k == "c"){
                echo 1;
            }else{
                echo 0;
            }

        }
        print_r($arr);
    }
*/



    public function persondata(){
        if(IS_POST){
            $uid = I("post.uid");
            $model = M("Users");
            $user_nicename = htmlspecialchars(trim($_POST['user_nicename']));     //昵称
            //$mobile = I("post.mobile");   //电话
            $user_email = I("post.user_email");  //邮箱

            $data['user_nicename'] = $user_nicename;
           // $data['mobile'] = $mobile;
            $data['user_email'] = $user_email;
            $model = M("Users");
            $res = $model->where("id = $uid")->field("user_nicename,user_email")->find();
            $result=array_merge(array_diff($res,$data),array_diff($data,$res));
            $arr = array();
            foreach($result as $k=>$v){
                $arr[$k] = $v;
                //判断昵称不能重复

                if($k == "user_nicename"){
                    //正则判断---昵称
                    if(!preg_match("/^[a-zA-Z0-9_\x{4e00}-\x{9fa5}]{2,16}$/ui",$v)){
                        ///^[a-zA-Z0-9_\x{4e00}-\x{9fa5}]{2,16}$/ui
                        //^[\u4e00-\u9fa5a-zA-Z0-9]{6,}$ 用户名里不允许有符号 最少6位 最多16位 用户名允许有字母、中文、数字
                        $arr1['status'] = 0 ;
                        $arr1['msg'] = "用户名只能包括中文，英文字母，数字，下划线 ( _ ) 不能含空格、表情";
                        echo json_encode($arr1);die;
                    }

                    $rr = $model->where("user_nicename = '$v'")->find();
                    if($rr){
                        $arr1['status'] = 0 ;
                        $arr1['msg'] = "该昵称已经存在";
                        echo json_encode($arr1);die;
                    }
                }

                if($k == "user_email"){
                    //正则判断---邮箱
                    if(!preg_match("/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i",$v)){
                        $arr['status'] = 0 ;
                        $arr['msg'] = "邮箱格式不正确";
                        echo json_encode($arr);die;
                    }

                    $rr1 = $model->where("user_email = '$v'")->find();
                    if($rr1){
                        $arr1['status'] = 0 ;
                        $arr1['msg'] = "该邮箱已经存在";
                        echo json_encode($arr1);die;
                    }
                }


                /*
                if($k == "mobile"){
                    //正则判断---电话
                    if(!preg_match("/^1[34578]\d{9}$/",$v)){
                        $arr['status'] = 0 ;
                        $arr['msg'] = "号码格式不正确";
                        echo json_encode($arr);die;
                    }
                }
                */
            }
            if($model->where("id = $uid")->save($arr)){
                $arr1['status'] = 1 ;
                $arr1['msg'] = "提交成功";
                echo json_encode($arr1);die;
            }else{
                $arr1['status'] = 0 ;
                $arr1['msg'] = "您未做出修改";
                echo json_encode($arr1);die;
            }

        }else{
            $arr1['status'] = 102 ;
            $arr1['msg'] = "请求方式错误";
            echo json_encode($arr1);die;
        }
    }

    //个人资料的修改
    /*
    public function persondata1(){
        if(IS_POST){
            $uid = I("post.uid");
            $model = M("Users");
            $user_nicename = I("post.user_nicename");     //昵称

            $mobile = I("post.mobile");   //电话
            $user_email = I("post.user_email");  //邮箱

            if(!empty($user_nicename)){
                if(!preg_match("/^[a-zA-Z0-9_\x{4e00}-\x{9fa5}]{2,16}$/ui",$user_nicename)){
                    ///^[a-zA-Z0-9_\x{4e00}-\x{9fa5}]{2,16}$/ui
                    //^[\u4e00-\u9fa5a-zA-Z0-9]{6,}$ 用户名里不允许有符号 最少6位 最多16位 用户名允许有字母、中文、数字
                    $arr['status'] = 0 ;
                    $arr['msg'] = "用户名只能包括中文，英文字母，数字，下划线 ( _ ) 不能含空格";
                    echo json_encode($arr);die;
                }
                //非空的情况下判断用户是否存在
                $uname = $model->where("user_nicename = '$user_nicename'")->find();

                if(!empty($uname)){
                    $arr['status'] = 0 ;
                    $arr['msg'] = "该昵称已经存在";
                    echo json_encode($arr);die;
                }else{
                    $data['user_nicename'] = $user_nicename;
                }

            }
            if(!empty($mobile)){
                if(!preg_match("/^1[34578]\d{9}$/",$mobile)){
                    $arr['status'] = 0 ;
                    $arr['msg'] = "号码格式不正确";
                    echo json_encode($arr);die;
                }
                $data['mobile'] = $mobile;
            }
            if(!empty($user_email)){
                if(!preg_match("/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i",$user_email)){
                    $arr['status'] = 0 ;
                    $arr['msg'] = "邮箱格式不正确";
                    echo json_encode($arr);die;
                }
                $umail = $model->where("user_email = '$user_email'")->find();
                if(!empty($umail)){
                    $arr['status'] = 0 ;
                    $arr['msg'] = "该邮箱已经存在";
                    echo json_encode($arr);die;
                }else{
                    $data['user_email'] = $user_email;
                }

            }

            if($model->where(" id = $uid ")->save($data)){
                $arr['status'] = 1 ;
                $arr['msg'] = "提交成功";
                echo json_encode($arr);die;
            }else{
                $arr['status'] = 0 ;
                $arr['msg'] = "提交失败";
                echo json_encode($arr);die;
            }
        }else{
            $arr['status'] = 102 ;
            $arr['msg'] = "请求方式错误";
            echo json_encode($arr);die;
        }
    }
    */

}