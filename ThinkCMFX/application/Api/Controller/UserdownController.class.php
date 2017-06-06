<?php
namespace Api\Controller;

use Think\Controller;
/*
 * 我的下载的模块的接口
 * */
class UserdownController extends Controller{
    /*
     * 获取我的下载的课程列表的接口
     * */
    public function downshow(){
        $uid = I("uid");                       //判断接收过来的token是get传值还是post，token是判断用户的唯一标识符
        $page = I("page");
        $model = M("course_download");



        if (!empty($page)) {
            $downinfo = $model
                ->join("cmf_course ON cmf_course.id = cmf_course_download.course_id")
                ->join("cmf_users ON cmf_users.id = cmf_course_download.uid")
                ->join("cmf_lector ON cmf_lector.l_id = cmf_course.lector_id")
                ->where("cmf_course_download.uid = $uid")
                //->field("cmf_course.subject,cmf_course.num_class,cmf_course.cover,cmf_course_download.create_time")
                ->order("cmf_course_download.cu_id desc")
                ->page($page . ',10')
                ->select();
                if (!empty($downinfo)) {
                    $data['status'] = 1;
                    $data['msg'] = "操作成功";
                    $data['data'] = $downinfo;
                    echo json_encode($data);die;

                }else {
                    $data['status'] = 1;
                    $data['msg'] = "操作成功";
                    $data['data'] = '';
                    echo json_encode($data);die;
                }
            }else{
            $data['status'] = 0;
            $data['msg'] = "操作失败";
            $data['data'] = '';
            echo json_encode($data);die;
                    //echo 1;
            }
        }

    /*
     * 获取课程下面的课时的列表
     * */
    public function showlive(){
        $courseid = I("cid");              //获取课程的ID
        $token = I("token");              //判断接收过来的token是get传值还是post，token是判断用户的唯一标识符   获取当前登录用户的唯一标识符
        $page = I("page");                 //获取页码

        $status = C('status');
        $msg = C('msg');
        $response = new SubmitController();

        $users = M("users");
        $userinfo = $users->where("token = $token")->field("id")->find();           //获取用户的ID
        $uid = $userinfo["id"];
        $model = M("live_download");
        if(!empty($page)){
            $downinfo = $model
                ->join("cmf_live ON cmf_live.id = cmf_live_download.live_id")
                ->join("cmf_users ON cmf_users.id = cmf_live_download.uid")
                ->join("cmf_course ON cmf_course.id = cmf_live.course_id")
                ->where("cmf_live.course_id = $courseid AND cmf_live_download.uid = $uid AND cmf_live.status=3")
                ->order("cmf_live_download.ul_id desc")
                ->page($page.',10')
                ->select();
            if (!empty($downinfo)) {
                $data['status'] = 1;
                $data['msg'] = "操作成功";
                $data['data'] = $downinfo;
                echo json_encode($data);die;

            }else {
                $data['status'] = 1;
                $data['msg'] = "操作成功";
                $data['data'] = '';
                echo json_encode($data);die;
            }
        }else{
            $data['status'] = 0;
            $data['msg'] = "操作失败";
            echo json_encode($data);die;
        }


    }


    /*
     * 课程下面的课时的批量删除
     * */
    public function livedowndel(){
        $model = M("live_download");

        $id = I("ids");                          //接收传递过来的id的值或者数组
        $uid = I("uid");                    //获取用户的唯一标识


        //判断id是数组还是一个数值
        if(is_array($id)){
            $where = 'ul_id in ('.implode(',',$id).') ';
        }else{
            $where = 'ul_id ='.$id ;
        } //dump($where);
        $where .= " AND uid = $uid";
        $list=$model->where($where)->delete();
        if (!empty($list)) {
            $data['status'] = 1;
            $data['msg'] = "操作成功";
            echo json_encode($data);die;
        }else {
            $data['status'] = 0;
            $data['msg'] = "操作失败";
            echo json_encode($data);die;
        }

    }




    /*
     * 实现我的下载列表的批量删除
     * */
    public function downdel(){
        $model = M("course_download");
        $uid = I("uid");                    //获取用户的唯一标识
        $id = I("ids");                          //接收传递过来的id的值或者数组

        //判断id是数组还是一个数值
        if(is_array($id)){
            $where = 'cu_id in ('.implode(',',$id).')';
        }else{
            $where = 'cu_id ='.$id;
        } //dump($where);
        $where .= " AND uid = $uid";
       $list=$model->where($where)->delete();


        //当课程删除的时候下面的课时也要删除
        $mod = M("live_download");
        if(is_array($id)){
            $wheres = 'cu_id in ('.implode(',',$id).')';
        }else{
            $wheres = 'cu_id ='.$id;
        }
        $wheres .= " AND uid = $uid";
        //echo $mod->getlastsql();
        $res=$mod->where($wheres)->delete();

        if (!empty($res && $list)) {
            $data['status'] = 1;
            $data['msg'] = "操作成功";
            echo json_encode($data);die;

        }else {
            $data['status'] = 0;
            $data['msg'] = "操作失败";
            echo json_encode($data);die;
        }

    }
    

    /*
     * 将下载的课程下面的课时写入到数据库
     * */
    public function insertitem(){

        $uid = I("post.uid");     //用户
        $live_id = I("post.live_id");            //课程的id
        $data['live_id'] = $live_id ;
        $data['create_time'] = time();                     //时间
        $cid  = I("post.course_id");            //课程得 ID
        $data['cu_id'] = $cid;
        $data['uid'] = $uid;



        //判断下载的课程表中是否有该课程的id  如果没有则添加
        $downcourse = M("course_download")->where("course_id = $cid AND uid = $uid")->find();
        if(empty($downcourse)){
            $dat['course_id'] = $cid;
            $dat['create_time'] = time();
            $dat['uid'] = $uid;
            $rs = M("course_download")->add($dat);
        }



        $model = M("live_download");
        //先判断download_live表中数据是否存在
        $downlive = $model->where("uid = $uid AND live_id = $live_id AND course_id = $cid")->find();
        if(empty($downlive)){
            $res = $model->add($data);
            //如果下载了要在在course表内的下载次数加一

            $course = M("course")->where("id = $cid")->field("download_num")->find();
            $cour['download_num'] = $course['download_num'] +1;
            $courok = M("course")->where("id = $cid")->save($cour);

            if (!empty($res && $courok)) {
                $arr['status'] = 1;
                $arr['msg'] = "操作成功";
                echo json_encode($arr);die;

            }else {
                $arr['status'] = 0;
                $arr['msg'] = "操作失败";
                echo json_encode($arr);die;
            }
        }
    }

    //下载我的课程到数据库
    public function insertcourse(){

        $uid = I("post.uid");
        $data['uid'] = $uid;

        $cid  = I("post.course_id");            //课程得 ID

        $data['course_id'] = $cid;
        $data['create_time'] = time();

        $model = M("course_download");
        $res = $model->where("uid = $uid AND course_id = $cid")->find();
        if(empty($res)){
            $course = M("course")->where("id = $cid")->field("download_num")->find();
            $cour['download_num'] = $course['download_num'] +1;
            $courok = M("course")->where("id = $cid")->save($cour);
            $rs = $model->add($data);
            if($courok && $rs){
                $arr['status'] = 1;
                $arr['msg'] = "操作成功";
                echo json_encode($arr);die;

            }else {
                $arr['status'] = 0;
                $arr['msg'] = "操作失败";
                echo json_encode($arr);die;
            }

        }

    }
}