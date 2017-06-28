<?php
namespace Api\Controller;

use Think\Controller;
/*
 * 我的课程的模块的接口
 * */
class UsercourseController extends Controller{
    /*
     * 我的程列表的接口
     * */
    public function downshow(){
        $uid = I("uid");                       //判断接收过来的token是get传值还是post，token是判断用户的唯一标识符
        $page = I("page");
        $model = M("order");

        if(!empty($page)){
            $downinfo = $model
                ->join("INNER JOIN cmf_course ON cmf_course.id = cmf_order.course_id")
               // ->join("LEFT JOIN cmf_live ON cmf_live.course_id = cmf_course.id")
                ->field("cmf_course.course_name,cmf_course.cover,cmf_course.num_class,cmf_course.id,
                cmf_course.now_price,cmf_course.old_price,cmf_course.introduction,cmf_course.lector")
                ->where("cmf_order.uid = $uid AND cmf_order.pay_status = '1'")
                ->order("cmf_order.id desc")
                ->page($page.',10')
                ->select();
            //echo $model->getlastsql()."<br>";die;
            //判断该课程是否收藏
            for($i=0;$i<count($downinfo);$i++){
                $cid=$downinfo[$i]['id'];
                $exitcollect = M("course_collection")->where("course_id = $cid AND uid = $uid")->find();
                if(empty($exitcollect)){
                    $downinfo[$i]['type'] = 0;
                }else{
                    if($exitcollect['type'] == 0){
                        $downinfo[$i]['type'] = 0;
                    }else{
                        $downinfo[$i]['type'] = 1;
                    }
                }
            }
            //print_r($downinfo);die;
            if (!empty($downinfo)) {
               $data['status'] = 1 ;
                $data['msg'] = "操作成功";
                $data['data'] = $downinfo;
                echo json_encode($data);die;

            }else {
                $data['status'] = 1 ;
                $data['msg'] = "您还未购买课程";
                $data['data'] = '';
                echo json_encode($data);die;
            }

        }else{
            $data['status'] = 0 ;
            $data['msg'] = "操作失败";
            echo json_encode($data);die;
        }
    }
}


    /*
     * 获取课程下面的课时的列表
     *
    public function showusercourse(){
        $courseid = I("cid");              //获取课程的ID
        $uid = I("uid");              //   获取当前登录用户的唯一标识符
        $page = I("page");                 //获取页码

        $model = M("live");
        if(!empty($page)){
            $downinfo = $model
               ->join("")
                ->order("cmf_user_course.uc_id desc")
                ->page($page.',10')
                ->select();
            //echo $model->getlastsql();
            //print_r($downinfo);
            if (!empty($downinfo)) {
                $data['status'] = 1 ;
                $data['msg'] = "操作成功";
                $data['data'] = $downinfo;
                echo json_encode($data);die;

            }else {
                $data['status'] = 1 ;
                $data['msg'] = "操作成功";
                $data['data'] = '';
                echo json_encode($data);die;
            }
        }else{
            $data['status'] = 0 ;
            $data['msg'] = "操作失败";
            echo json_encode($data);die;
        }
    }
*/

    /*
     * 将课程下面的课时加入数据库
     *
    public function addlive(){
        if(IS_POST){
            $data['uid'] = I("post.uid");
            $data['live_id'] = I("post.live_id");     //课时的ID
            $data['cid'] = I("post.cid");               //  课程的ID
            $data['uc_time'] = date("Y-m-d H:i:s");
            $data['uc_type'] = 1;
            $data['pid'] = I("post.cid");
            $model = M("user_course");
            if($model->add($data)){
                $dat['status'] = 1 ;
                $dat['msg'] = "操作成功";
                echo json_encode($dat);die;
            }else{
                $dat['status'] = 0 ;
                $dat['msg'] = "操作失败";
                echo json_encode($dat);die;
            }

        }else{
            $data['status'] = 102 ;
            $data['msg'] = "请求方式错误";
            echo json_encode($data);die;
        }
    }
*/


    /*
    * 获取答题记录的列表
    *
    public function answersheet(){
        $uid = I("uid");
        $page = I("page");
        $model = M("record");
        $res = $model
           // ->join("LEFT JOIN cmf_itembank ON cmf_itembank.eid = cmf_record.eid")
            ->join("LEFT JOIN cmf_users ON cmf_users.id = cmf_record.uid")
            ->join("LEFT JOIN cmf_subjects ON cmf_subjects.sid = cmf_record.sid")
            ->join("LEFT JOIN cmf_chapter ON cmf_chapter.cid = cmf_record.cid")
           ->field("cmf_record.eid,cmf_record.etid,cmf_subjects.stitle,cmf_chapter.ctitle,cmf_record.sid,cmf_record.cid,cmf_record.record_time")
            ->where("cmf_record.uid = '$uid'")
            ->page($page . ',10')
            ->select();
        for($i=0;$i<count($res);$i++){
            if($res[$i]['stitle'] == ''){
                $eid = $res[$i]['eid'];
                $edit = $res[$i]['etid'];
                $eeid =  $res[$i]['etid']+1;  //在收到的卷几的基础上加1
                //$res[$i]['name'] = $res[$i]['eid']."年司法考试卷卷".$res[$i]['etid'];
                $res[$i]['name'] = $eid."年司法考试卷卷".$eeid;
                $res[$i]['count'] = M("itembank")->where("eid = $eid AND etid = $edit")->count();       //共多少题
            }else{
                $res[$i]['name'] = $res[$i]['ctitle'].'----'.$res[$i]['stitle'];
                $sid = $res[$i]['sid'];
                $cid = $res[$i]['cid'];
                $res[$i]['count'] = M("itembank")->where("sid = $sid AND cid = $cid")->count();
            }
        }

        //print_r($res);
        //die;
        if(!empty($res)){
            $data['status'] = 1;
            $data['msg'] = '请求成功';
            $data['data'] = $res;
            echo json_encode($data);die;
        }else{
            $data['status'] = 0;
            $data['msg'] = '请求成功';
            $data['data'] = '';
            echo json_encode($data);die;
        }

    }
 */
