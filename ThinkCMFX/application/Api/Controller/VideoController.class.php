<?php
namespace Api\Controller;

use Think\Controller;
/*
 * 学习记录---视频记录
 * */
class VideoController extends Controller{
    //插入视频记录表
    public function videoinsert(){

        if(IS_POST){
            //默认为get提交
            $uid = I("post.uid");              //获得用户的唯一标识符
            $cid= I("post.cid");                  //看的那个课程的id

            $lid = @I("post.lid");                 //看的课时的ID  （可有可没有）
            $model = M("video_record");
            if(!empty($lid)){
                //添加的是课时
                $data['uid'] = $uid;
                $dat['uid'] = $uid;
                $dat['lid'] = $lid;
                $data['pid'] = 0;
                $data['cid'] = $cid;
                $dat['pid'] = $cid;
                $data['vtime'] = date("Y-m=d H:i:s");
                $dat['vtime'] = date("Y-m=d H:i:s");

                $bb = $model->where("lid = $lid and pid = $cid and uid = $uid")->find();
                if(empty($bb)){
                    $res1 = $model->add($dat);
                }else{
                    $qq['vtime'] = date("Y-m-d H:i:s");
                    $res1 = $model->where("lid = $lid and pid = $cid and uid = $uid")->save($qq);
                }
                if(empty($res1)){
                    $arr['status'] = 0;
                    $arr['msg'] = "操作失败";
                    echo json_encode($arr);die;
                }else{
                    $arr['status'] = 1;
                    $arr['msg'] = "操作成功";
                    echo json_encode($arr);die;
                }

            }else{
                //添加的是课程
                $data['uid'] = $uid;
                $data['cid'] = $cid;
                $data['vtime'] = date("Y-m-d H:i:s");
                $data['pid'] = 0;
                $aa = $model->where("cid = $cid AND uid = $uid")->find();
                if(empty($aa)){
                    $res = $model->add($data);
                }else{
                    $dat['vtime'] = date("Y-m-d H:i:s");
                    $res = $model->where("uid = $uid AND cid = $cid")->save($dat);
                }
                if(empty($res)){
                    $arr['status'] = 0;
                    $arr['msg'] = "操作失败";
                    echo json_encode($arr);die;
                }else{
                    $arr['status'] = 1;
                    $arr['msg'] = "操作成功";
                    echo json_encode($arr);die;
                }
            }


        }else{
            $arr['status'] = 102;
            $arr['msg'] = "请求方式错误";
            echo json_encode($arr);die;
        }

    }
    //查看我的视频记录的列表
    public function videoshow(){
        $uid = I("uid");    //获取token的值    get方式
        $page = I("page");     //获取页码
        $model = M("video_record");

        $info = $model
            ->join("LEFT JOIN cmf_course ON cmf_course.id = cmf_video_record.cid")
            ->join("LEFT JOIN cmf_users ON cmf_users.id = cmf_video_record.uid")
            //->join("LEFT JOIN cmf_lector ON cmf_lector.l_id = cmf_course.lector_id")
            ->join("LEFT JOIN cmf_live ON cmf_live.id = cmf_video_record.lid")
            ->where("cmf_video_record.uid = $uid")
            ->field("cmf_course.course_name,cmf_course.num_class,cmf_course.cover,cmf_video_record.vtime,cmf_course.name,
            cmf_video_record.vid,cmf_course.number,cmf_course.stu_token,cmf_course.type,cmf_video_record.lid,cmf_video_record.pid,cmf_course.introduction,cmf_course.id,cmf_live.subject")
            ->page($page . ',10')
            ->order("cmf_video_record.vid desc")
            ->select();


        for($i=0;$i<count($info);$i++){
            if(!empty($info[$i]['lid'])){
                $cid = $info[$i]['pid'];
                $lid = $info[$i]['lid'];
                $res = M("live")->where("id = $lid AND course_id = $cid")->find();
                $info[$i]['stu_token'] = $res['stu_token'];
                $info[$i]['number'] = $res['number'];

                $res1 = M("course")->where("id = $cid")->find();
                $info[$i]['course_name'] = $res1['course_name'];
                $info[$i]['num_class'] = $res1['num_class'];
                $info[$i]['introduction'] = $res1['introduction'];
                $info[$i]['cover'] = $res1['cover'];
                $info[$i]['type'] = $res1['type'];
                $lector_id = $res1['lector_id'];
                $res2 = M("lector")->where("l_id = $lector_id")->find();
                $info[$i]['name'] = $res2['name'];           //老师名字
                $info[$i]['id'] = $info[$i]['pid'];      //course_id

            }
        }

       // echo $model->getlastsql()."<br>";
       // print_r($info);die;
        if (empty($info)) {
            $arr['status'] = 1;
            $arr['msg'] = "您还未学习呢";
            $arr['data'] = '';
            echo json_encode($arr);die;
        } else {
            $arr['status'] = 1;
            $arr['msg'] = "操作成功";
            $arr['data'] = $info;
            echo json_encode($arr);die;
        }
    }


    /*
     * 学习记录---答题记录
     * */
    public function sheet(){
        $uid = I("uid");   //获取用户的id
        $page = I("page");
        $model = M("sheet");
        $res = $model
            ->join("LEFT JOIN cmf_users ON cmf_users.id = cmf_sheet.uid")
            ->join("LEFT JOIN cmf_subjects ON cmf_subjects.sid = cmf_sheet.sid")
            ->join("LEFT JOIN cmf_chapter ON cmf_chapter.cid = cmf_sheet.cid")
            ->field("cmf_sheet.sheet_id,cmf_sheet.eid,cmf_sheet.etid,cmf_subjects.stitle,cmf_chapter.ctitle,cmf_sheet.sid,cmf_sheet.cid,cmf_sheet.sheet_time")
            ->where("cmf_sheet.uid = $uid")
            ->page($page . ',10')
            ->select();
        for ($i = 0; $i < count($res); $i++) {
            if ($res[$i]['stitle'] == '') {
                $eid = $res[$i]['eid'];
                $edit = $res[$i]['etid'];
                $eeid = $res[$i]['etid'] + 1;
                //$res[$i]['name'] = $res[$i]['eid']."年司法考试卷卷".$res[$i]['etid'];
                $res[$i]['name'] = $eid . "年司法考试卷卷" . $eeid;
                $res[$i]['status'] = 0 ;
                $res[$i]['count'] = M("itembank")->where("eid = $eid AND etid = $edit")->count();       //共多少题

            } else {
                $res[$i]['name'] = $res[$i]['ctitle'] . '----' . $res[$i]['stitle'];
                $sid = $res[$i]['sid'];
                $cid = $res[$i]['cid'];
                $res[$i]['status'] = 1 ;
                $res[$i]['count'] = M("itembank")->where("sid = $sid AND cid = $cid")->count();
            }

        }
//print_r($res);die;
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



    /*
     * 获取课程下面的课时的ID
     *
    public function videolive(){
        $cid = I("cid");              //获取课程的ID
        $uid = I("uid");                //判断接收过来的token是get传值还是post，token是判断用户的唯一标识符   获取当前登录用户的唯一标识符
        $page = I("page");                 //获取页码
        $model = M("video_record");

        $data = $model
            ->join("LEFT JOIN cmf_live ON cmf_live.id = cmf_video_record.lid")
            ->join("LEFT JOIN cmf_users ON cmf_users.id = cmf_video_record.uid")
            ->join("LEFT JOIN cmf_course ON cmf_course.id = cmf_video_record.cid")
            ->where("cmf_video_record.pid = $cid AND cmf_video_record.uid = $uid")
            ->order("cmf_video_record.vid desc")
            ->page($page.',10')
            ->select();
        if (!empty($data)) {
            $arr['status'] = 1;
            $arr['msg'] = "操作成功";
            $arr['data'] = $data;
            echo json_encode($arr);die;
        } else {
            $arr['status'] = 1;
            $arr['msg'] = "操作成功";
            $arr['data'] = '';
            echo json_encode($arr);die;
        }
    }

 */





























    /*
     * 获取我的下载的课程列表的接口
     *
    public function downshow(){
        $token = I("token");                       //判断接收过来的token是get传值还是post，token是判断用户的唯一标识符
        $page = I("page");
        $model = M("course_download");
        $users = M("users");
        $userinfo = $users->where("token = $token")->field("id")->find();           //获取用户的ID
        $uid = $userinfo["id"];
        $succ = C('succ');
        $mess = C('mess');
        $models = new \Admin\Controller\SubmitController();

        if(!empty($page)){
            $downinfo = $model
                ->join("cmf_course ON cmf_course.id = cmf_course_download.course_id")
                ->join("cmf_users ON cmf_users.id = cmf_course_download.uid")
                ->where("cmf_course_download.uid = $uid")
                //->field("cmf_course.subject,cmf_course.num_class,cmf_course.cover,cmf_course_download.create_time")
                ->order("cmf_course_download.cu_id desc")
                ->page($page.',10')
                ->select();

            if(!empty($downinfo)){
                echo json_encode([
                   'succ' => $succ[0],
                    'mess' =>$mess[0],
                    'data' => $downinfo
                ]);die;

                //echo ok;
            }else{
                echo $models::state($succ[0],$mess[0],$downinfo=null);die;
                //echo 1;

            }

        }else{
            echo $models::state($succ[2], $mess[2]);die;
            //echo 2;
        }
    }
*/

    /*
     * 获取课程下面的课时的列表
     *
    public function showlive(){
        $courseid = I("cid");              //获取课程的ID
        $token = I("token");              //判断接收过来的token是get传值还是post，token是判断用户的唯一标识符   获取当前登录用户的唯一标识符
        $page = I("page");                 //获取页码

        $succ = C('succ');
        $mess = C('mess');
        $models = new \Admin\Controller\SubmitController();

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
            if(!empty($downinfo)){
                echo json_encode([
                    'succ' => $succ[0],
                    'mess' =>$mess[0],
                    'data' => $downinfo
                ]);die;

                //echo ok;
            }else{
                echo $models::state($succ[0],$mess[0],$downinfo=null);die;
                //echo 1;

            }
        }else{
            echo $models::state($succ[2], $mess[2]);die;
        }


    }

*/
    /*
     * 课程下面的课时的批量删除
     *
    public function livedowndel(){
        $succ = C('succ');
        $mess = C('mess');
        $models = new \Admin\Controller\SubmitController();
        $model = M("live_download");
        $id = I("ids");                          //接收传递过来的id的值或者数组
        $token = I("token");                    //获取用户的唯一标识

        $users = M("users");
        $userinfo = $users->where("token = $token")->field("id")->find();           //获取用户的ID
        $uid = $userinfo["id"];


        //判断id是数组还是一个数值
        if(is_array($id)){
            $where = 'ul_id in ('.implode(',',$id).') ';
        }else{
            $where = 'ul_id ='.$id ;
        } //dump($where);
        $where .= " AND uid = $uid";
        $list=$model->where($where)->delete();
        if(!empty($list)){
            echo json_encode([
                'succ' => $succ[0],
                'mess' =>$mess[0],
                'data' => '成功'
            ]);die;
            //echo ok;
        }else{
            echo $models::state($succ[2],$mess[2]);die;
            //echo 2;
        }

    }
    */




    /*
     * 实现我的下载列表的批量删除
     *
    public function downdel(){
        $succ = C('succ');
        $mess = C('mess');
        $models = new \Admin\Controller\SubmitController();
        $model = M("course_download");
        $token = I("token");                    //获取用户的唯一标识
        $id = I("ids");                          //接收传递过来的id的值或者数组
        $users = M("users");
        $userinfo = $users->where("token = $token")->field("id")->find();           //获取用户的ID
        $uid = $userinfo["id"];

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


        if(!empty($res && $list)){
            echo json_encode([
                'succ' => $succ[0],
                'mess' =>$mess[0],
                'data' => '成功'
            ]);
           // echo ok;
        }else{
            echo $models::state($succ[1],$mess[1]);
            //echo 2;
        }


    }
*/


}