<?php
namespace Api\Controller;

use Think\Controller;
/*
 * 我的收藏的模块的接口
 * */
class UsercollectController extends Controller{
    /*
    * 将收藏课程下面的课时写入数据库
    * */

    public function collectitem(){
        if (IS_POST) {
            $status = I("post.status");    //1收藏    0取消收藏
            $uid = I("post.uid");     //用户
            $id = I("post.live_id");            //课时的id


            $courseid = M("live")->where("id = $id")->find();
            $course_id = $courseid['course_id'];    //vip课程的ID

            if ($status == 1) {
                //先判断收藏的vip课时的课程是否存在
                $cidexixt = M("course_collection")->where("uid = $uid AND course_id = $course_id")->find();
                if(empty($cidexixt)){
                    $dat['uid'] = $uid;
                    $dat['course_id'] = $course_id;
                    $dat['create_time'] = date("Y-m-d H:i:s");
                    $dat['type'] = 1;

                    $course = M("course")->where("id = $course_id")->field("collection_num")->find();
                    $cour['collection_num'] = $course['collection_num'] + 1;
                    $courok = M("course")->where("id = $course_id")->save($cour);

                    $res1 = M("course_collection")->add($dat);
                }else{
                    if($cidexixt['type'] == 0){
                        $qq['type'] = 1;
                        $qq['vtime'] = date("Y-m-d H:i:s");

                        $course = M("course")->where("id = $course_id")->field("collection_num")->find();
                        $cour['collection_num'] = $course['collection_num'] + 1;
                        $courok = M("course")->where("id = $course_id")->save($cour);

                        $res1 = M("course_collection")->where("uid = $uid AND cid = $course_id")->save($qq);
                    }

                }

                //收藏
                //查看该课时是否收藏 如果存在判断type是否为1  如果是0则改为1
                $courexitb = M("live_collection")->where("uid = $uid AND course_id = $course_id AND live_id = $id ")->find();
                if (empty($courexitb)) {
                    $data['course_id'] = $course_id;
                    $data['live_id'] = $id;
                    $data['uid'] = $uid;
                    $data['create_time'] = date("Y-m-d H:i:s");
                    $data['type'] = 1;


                    $course = M("live")->where("id = $id")->field("like_num")->find();
                    $cour['like_num'] = $course['like_num'] + 1;
                    $courok = M("live")->where("id = $id")->save($cour);



                    $res = M("live_collection")->add($data);
                } else {
                    if ($courexitb['type'] == 0) {

                        $data['type'] = 1;
                        $data['create_time'] = date("Y-m-d H:i:s");

                        $course = M("live")->where("id = $id")->field("like_num")->find();
                        $cour['like_num'] = $course['like_num'] + 1;
                        $courok = M("live")->where("id = $id")->save($cour);

                        $res = M("live_collection")->where("uid = $uid AND course_id = $course_id AND live_id = $id ")->save($data);
                    }

                }

                if (empty($res)) {
                    $arr['status'] = 0;
                    $arr['msg'] = "收藏失败aaaaa";
                    echo json_encode($arr);
                    die;
                } else {
                    $arr['status'] = 1;
                    $arr['msg'] = "收藏成功";
                    echo json_encode($arr);
                    die;
                }

            } else {
                //取消收藏
                $data['type'] = 0;
                $res = M("live_collection")->where("uid = $uid AND course_id = $course_id AND live_id = $id ")->save($data);
                if (empty($res)) {
                    $arr['status'] = 0;
                    $arr['msg'] = "取消收藏失败bbbb";
                    echo json_encode($arr);
                    die;
                } else {

                    $arr['status'] = 1;
                    $arr['msg'] = "取消收藏";
                    echo json_encode($arr);
                    die;
                }
            }
        }else{
            $arr['status'] = 102;
            $arr['msg'] = "请求方式错误";
            echo json_encode($arr);die;
        }
    }


    /*
     * 将收藏课程/试题的写入数据库  不同的表中
     * */
    public function coursecoll(){
        if(IS_POST){
            $uid = I("post.uid");
            $cid  = I("post.course_id");            //课程得 ID
            //$data['msg'] = $cid;
            //echo json_encode($data);die;
            $model = M("course_collection");
            $status = I("post.status");
            if($status == 1){
                if(empty($cid)){
                    $data['uid'] = $uid;
                    $data['ctime'] = date("Y-m-d H:i:s");
                    $eid = I("post.eid");
                    $model = M("item_collection");

                    if(empty($eid)){    //没有年份和卷几
                        //$data['msg'] = "eid是空的";
                        //echo json_encode($data);
                        $sid = I("post.sid");
                        $data['sid'] = $sid;
                        $cid = I("post.cid");
                        $data['cid'] = $cid;
                        $data['eid'] = NULL;
                        $data['type'] = 1;
                        $exict = $model->where("uid = $uid AND sid = $sid AND cid = $cid")->find();

                        if(empty($exict)){
                            $data['status'] = 1;
                            if ($model->add($data)) {
                                $dat['status'] = 1;
                                $dat['msg'] = "收藏成功";
                                echo json_encode($dat);die;

                            }else {
                                $dat['status'] = 0;
                                $dat['msg'] = "收藏失败";
                                echo json_encode($dat);die;
                            }
                        }else{
                            $aa['ctime'] = date("Y-m-d H:i:s");
                            $aa['status'] = 1;
                            if ($model->where("uid = $uid AND sid = $sid AND cid = $cid")->save($aa)) {
                                $dat['status'] = 1;
                                $dat['msg'] = "收藏成功";
                                echo json_encode($dat);die;

                            }else {
                                $dat['status'] = 0;
                                $dat['msg'] = "收藏失败";
                                echo json_encode($dat);die;
                            }
                        }
                    }else{
                        //$data['mag'] = "nihao";
                        //echo json_encode($data);
                        $etid = I("post.etid");//卷几
                        $data['etid'] = $etid;

                        $data['eid'] =  $eid;           //年份
                        $data['type'] = 0;
                        $exict = $model->where("uid = $uid AND eid = $eid AND etid = $etid")->find();
                        if(empty($exict)){
                            $data['status'] = 1;
                            if ($model->add($data)) {
                                $dat['status'] = 1;
                                $dat['msg'] = "收藏成功";
                                echo json_encode($dat);die;

                            }else {
                                $dat['status'] = 0;
                                $dat['msg'] = "收藏失败";
                                echo json_encode($dat);die;
                            }
                        }else{
                            $aa['ctime'] = date("Y-m-d H:i:s");
                            $aa['status'] = 1;
                            if ($model->where("uid = $uid AND eid = $eid AND etid = $etid")->save($aa)) {
                                $dat['status'] = 1;
                                $dat['msg'] = "收藏成功";
                                echo json_encode($dat);die;

                            }else {
                                $dat['status'] = 0;
                                $dat['msg'] = "收藏失败";
                                echo json_encode($dat);die;
                            }
                        }

                    }

                }else{
                    $data['course_id'] = $cid;
                    $data['create_time'] = date("Y-m-d H:i:s");
                    $data['uid'] = $uid;
                    $data['type'] = 1;
                    $res = $model->where("uid = $uid AND course_id = $cid")->find();
                    if(empty($res)){
                        $course = M("course")->where("id = $cid")->field("collection_num")->find();
                        $cour['collection_num'] = $course['collection_num'] +1;
                        $courok = M("course")->where("id = $cid")->save($cour);
                        $rs = $model->add($data);
                        if($courok && $rs){
                            $arr['status'] = 1;
                            $arr['msg'] = "收藏成功";
                            echo json_encode($arr);die;
                        }else {
                            $arr['status'] = 0;
                            $arr['msg'] = "收藏失败";
                            echo json_encode($arr);die;
                        }
                    }else{
                        $daa['type'] = 1;
                        $daa['create_time'] = date("Y-m-d H:i:s");
                        $course = M("course")->where("id = $cid")->field("collection_num")->find();
                        $cour['collection_num'] = $course['collection_num'] +1;
                        $courok = M("course")->where("id = $cid")->save($cour);
                        if($model->where("uid = $uid AND course_id = $cid")->save($daa)){
                            $arr['status'] = 1;
                            $arr['msg'] = "收藏成功";
                            echo json_encode($arr);die;
                        }else{
                            $arr['status'] = 0;
                            $arr['msg'] = "收藏失败";
                            echo json_encode($arr);die;
                        }

                    }
                }


            }else{
                //取消收藏
                if(empty($cid)){
                    $eid = I("eid");            //年份
                    $data['status'] = 0;
                    if(empty($eid)){
                        $sid = I("post.sid");
                        $cid = I("post.cid");
                        $res = M("item_collection")->where("uid = $uid AND sid = $sid AND cid = $cid ")->save($data);

                    }else{
                        $etid = I("post.etid");//卷几
                        $res = M("item_collection")->where("uid = $uid AND eid = $eid AND etid = $etid")->save($data);
                    }
                    if($res){
                        $dat['status'] = 1;
                        $dat['msg'] = "取消收藏";
                        echo json_encode($dat);die;
                    }else{
                        $dat['status'] = 0;
                        $dat['msg'] = "取消收藏失败";
                        echo json_encode($dat);die;
                    }
                }else{
                    $data['type'] = 0;
                    if($model->where("uid = $uid AND course_id = $cid")->save($data)){
                        $arr['status'] = 1;
                        $arr['msg'] = "取消收藏";
                        echo json_encode($arr);die;
                    }else{
                        $arr['status'] = 0;
                        $arr['msg'] = "取消收藏失败";
                        echo json_encode($arr);die;
                    }
                }

            }

        }else{
            $arr['status'] = 102;
            $arr['msg'] = "请求方式错误";
            echo json_encode($arr);die;
        }

    }

    /*
     * 获取我的收藏的课程列表的接口
     * */
    public function downshow(){
        $uid = I("uid");                       //判断接收过来的token是get传值还是post，token是判断用户的唯一标识符
        $page = I("page");
        $model = M("course_collection");
        //\Admin\Model\InfoModel();
        $news = new ResponseController();

        if(!empty($page)) {
            $downinfo = $model
                ->join("LEFT JOIN cmf_course ON cmf_course.id = cmf_course_collection.course_id")
                ->join("LEFT JOIN cmf_users ON cmf_users.id = cmf_course_collection.uid")
               // ->join("LEFT JOIN cmf_lector ON cmf_lector.l_id = cmf_course.lector_id")
                ->where("cmf_course_collection.uid = $uid AND cmf_course_collection.type = 1 ")
                ->field("cmf_course.id,cmf_course.cover,cmf_course.num_class,cmf_course_collection.create_time,cmf_course.course_name,
                cmf_course.course_name,cmf_course.introduction,cmf_course.invalidDate,cmf_course.is_free,cmf_course.now_price,
                cmf_course.old_price,cmf_course.number,cmf_course.reply_url,cmf_course.startDate,cmf_course.status,
                cmf_course.status,cmf_course.type,cmf_course.stu_token")
                ->order("cmf_course_collection.uc_id desc")
                ->page($page . ',10')
                ->select();

            $res = $news->gotten($uid, $page, $downinfo);
            //print_r($res);
            for($i=0;$i<count($res);$i++){
               if($res[$i]['pay_status'] == 1){
                    $res[$i]['pay_status'] = 1;

                }else{
                    $res[$i]['pay_status'] = 0;
               }
                $res[$i]['startDate'] = $res[$i]['startdate'];
            }
            //echo "122"."<br>";
            //print_r($res);die;
            if (!empty($res)) {
                $arr['status'] = 1;
                $arr['msg'] = "操作成功";
                $arr['data'] = $res;
                echo json_encode($arr);die;

            }else {
                $arr['status'] = 1;
                $arr['msg'] = "操作成功";
                $arr['data'] = '';
                echo json_encode($arr);die;
            }
        }

        else{
            $arr['status'] = 0;
            $arr['msg'] = "操作失败";
            echo json_encode($arr);die;
        }

    }


    /*
     * 获取收藏的课程下面的课时的列表
     **/
    public function showlive(){
        $courseid = I("cid");              //获取课程的ID
        $uid = I("uid");              //判断接收过来的token是get传值还是post，token是判断用户的唯一标识符   获取当前登录用户的唯一标识符
        $page = I("page");                 //获取页码

        $model = M("live_collection");
        if(!empty($page)) {
            $downinfo = $model
                ->join("cmf_live ON cmf_live.id = cmf_live_collection.live_id")
                ->join("cmf_users ON cmf_users.id = cmf_live_collection.uid")
                ->join("cmf_course ON cmf_course.id = cmf_live_collection.course_id")
                ->where("cmf_live_collection.course_id = $courseid AND cmf_live_collection.uid = $uid AND cmf_live_collection.type = 1")
                ->order("cmf_live_collection.ul_id desc")
                ->field("cmf_live_collection.ul_id,cmf_live.subject,cmf_live.startDate,cmf_live.course_id,
                cmf_live.status,cmf_live.cover,cmf_live.number,cmf_live.stu_token,cmf_live.reply_url,cmf_live_collection.type")
                ->page($page . ',10')
                ->select();
            for($i=0;$i<count($downinfo);$i++){
                $downinfo[$i]['startDate'] = date("Y-m-d H:i:s",$downinfo[$i]['startdate']);
            }
            //echo $model->getlastsql();
            //print_r($downinfo);die;
            if (!empty($downinfo)) {
                $arr['status'] = 1;
                $arr['msg'] = "操作成功";
                $arr['data'] = $downinfo;
                echo json_encode($arr);die;

            }else {
                $arr['status'] = 1;
                $arr['msg'] = "操作成功";
                $arr['data'] = '';
                echo json_encode($arr);die;
            }
        }else{
            $arr['status'] = 0;
            $arr['msg'] = "操作失败";
            echo json_encode($arr);die;
        }

    }

    /*
     * 收藏课程下面的课时的批量删除
     **/
    public function livecolldel(){

        $model = M("live_collection");
        $id = I("ids");                          //接收传递过来的id的值或者数组(接收的主键自增的id  )
        $uid = I("uid");                    //获取用户的唯一标识

        $where = "ul_id in $id";

        $where .= " AND uid = $uid";


        if ($model->where($where)->delete()) {
            $arr['status'] = 1;
            $arr['msg'] = "操作成功";
            echo json_encode($arr);die;

        }else {
            $arr['status'] = 0;
            $arr['msg'] = "操作失败";
            echo json_encode($arr);die;
        }
    }


    /*
     * 实现收藏列表的批量删除
     * */
    public function collectdel(){
        $model = M("course_collection");
        $uid = I("uid");                    //获取用户的唯一标识

        $id = I("ids");                          //接收传递过来的ids为字符串（接收的课程的ids）
        $where = "course_id in ($id)";
        $where .= " AND uid = $uid";

        if ($model->where($where)->delete()) {
            $arr['status'] = 1;
            $arr['msg'] = "操作成功";
            echo json_encode($arr);die;

        }else {
            $arr['status'] = 0;
            $arr['msg'] = "操作失败";
            echo json_encode($arr);die;
        }
    }

//获得收藏的试题
    public function itemcollect(){
        $uid = I("uid");   //获取用户的id
        $page = I("page");
        $model = M("item_collection");
        $res = $model
            ->join("LEFT JOIN cmf_users ON cmf_users.id = cmf_item_collection.uid")
            ->join("LEFT JOIN cmf_subjects ON cmf_subjects.sid = cmf_item_collection.sid")
            ->join("LEFT JOIN cmf_chapter ON cmf_chapter.cid = cmf_item_collection.cid")
            ->field("cmf_item_collection.collect_id,cmf_item_collection.eid,cmf_item_collection.etid,cmf_subjects.stitle,
            cmf_chapter.ctitle,cmf_item_collection.sid,cmf_item_collection.cid,cmf_item_collection.ctime,cmf_item_collection.type")
            ->where("cmf_item_collection.uid = $uid AND cmf_item_collection.status = 1")
            ->page($page . ',10')
            ->select();
       // echo $model->getlastsql()."<br>";
        //print_r($res);die;
        for ($i = 0; $i < count($res); $i++) {
            if ($res[$i]['stitle'] == '') {
                $eid = $res[$i]['eid'];
                $edit = $res[$i]['etid'];
                $eeid = $res[$i]['etid'] + 1;
                //$res[$i]['name'] = $res[$i]['eid']."年司法考试卷卷".$res[$i]['etid'];
                $res[$i]['name'] = $eid . "年司法考试卷卷" . $eeid;
                $res[$i]['count'] = M("itembank")->where("eid = $eid AND etid = $edit")->count();       //共多少题
            } else {
                $res[$i]['name'] = $res[$i]['ctitle'] . '----' . $res[$i]['stitle'];
                $sid = $res[$i]['sid'];
                $cid = $res[$i]['cid'];
                $res[$i]['count'] = M("itembank")->where("sid = $sid AND cid = $cid")->count();
            }

        }

        if(!empty($res)){
            $data['status'] = 1;
            $data['msg'] = '请求成功';
            $data['data'] = $res;
            echo json_encode($data);die;
        }else{
            $data['status'] = 1;
            $data['msg'] = '请求成功';
            $data['data'] = '';
            echo json_encode($data);die;
        }
    }

    /*
     * 编辑收藏的试题
     * */
    public function  delitem(){
       // $aa['msg'] = "nihao";
        //echo json_encode($aa);die;
        $uid = I("uid");                    //获取用户的唯一标识
        $id = I("ids");                          //接收传递过来的需要删除的我的收藏的课程的自增id    collect_id

        $model = M("item_collection");

        $where = "collect_id in ($id)";
        //$aa['msg'] = $where;
       // echo json_encode($aa);die;
        $where .= " AND uid = $uid";
        if ($model->where($where)->delete()) {
           $dat['status'] = 1;
            $dat['msg'] = '操作成功';
            echo json_encode($dat);die;

        }else {
            $dat['status'] = 0;
            $dat['msg'] = '操作失败';
            echo json_encode($dat);die;
        }

    }

    /*
    * 将题库加入收藏
    *
    public function insertitem(){
        if(IS_POST){
            $status = I("post.status");
            if($status == 1){
                $uid = I("post.uid");
                $data['uid'] = $uid;
                $data['ctime'] = date("Y-m-d H:i:s");
                $eid = I("eid");
                $model = M("item_collection");
                if(empty($eid)){    //没有年份和卷几
                    $sid = I("post.sid");
                    $data['sid'] = $sid;
                    $cid = I("post.cid");
                    $data['cid'] = $cid;
                    $data['eid'] = NULL;
                    $exict = $model->where("uid = $uid AND sid = $sid AND cid = $cid")->find();

                    if(empty($exict)){
                        $data['status'] = 1;
                        if ($model->add($data)) {
                            $dat['status'] = 1;
                            $dat['msg'] = "操作成功";
                            echo json_encode($dat);die;

                        }else {
                            $dat['status'] = 0;
                            $dat['msg'] = "操作失败";
                            echo json_encode($dat);die;
                        }
                    }else{
                        $aa['ctime'] = date("Y-m-d H:i:s");
                        $aa['status'] = 1;
                        if ($model->where("uid = $uid AND sid = $sid AND cid = $cid")->save($aa)) {
                            $dat['status'] = 1;
                            $dat['msg'] = "操作成功";
                            echo json_encode($dat);die;

                        }else {
                            $dat['status'] = 0;
                            $dat['msg'] = "操作失败";
                            echo json_encode($dat);die;
                        }
                    }
                }else{
                    $etid = I("post.etid");//卷几
                    $data['etid'] = $etid;

                    $data['eid'] =  $eid;           //年份

                    $exict = $model->where("uid = $uid AND eid = $eid AND etid = $etid")->find();
                    if(empty($exict)){
                        $data['status'] = 1;
                        if ($model->add($data)) {
                            $dat['status'] = 1;
                            $dat['msg'] = "操作成功";
                            echo json_encode($dat);die;

                        }else {
                            $dat['status'] = 0;
                            $dat['msg'] = "操作失败";
                            echo json_encode($dat);die;
                        }
                    }else{
                        $aa['ctime'] = date("Y-m-d H:i:s");
                        $aa['status'] = 1;
                        if ($model->where("uid = $uid AND eid = $eid AND etid = $etid")->save($aa)) {
                            $dat['status'] = 1;
                            $dat['msg'] = "操作成功";
                            echo json_encode($dat);die;

                        }else {
                            $dat['status'] = 0;
                            $dat['msg'] = "操作失败";
                            echo json_encode($dat);die;
                        }
                    }

                }

            }else{
                $uid = I("post.uid");
                $eid = I("eid");            //年份
                $data['status'] = 0;
                if(empty($eid)){
                    $sid = I("post.sid");
                    $cid = I("post.cid");
                    $res = M("item_collection")->where("uid = $uid AND sid = $sid AND cid = $cid ")->save($data);

                }else{
                    $etid = I("post.etid");//卷几
                    $res = M("item_collection")->where("uid = $uid AND eid = $eid AND etid = $etid")->save($data);
                }
                if($res){
                    $dat['status'] = 1;
                    $dat['msg'] = "操作成功";
                    echo json_encode($dat);die;
                }else{
                    $dat['status'] = 0;
                    $dat['msg'] = "操作失败";
                    echo json_encode($dat);die;
                }
            }

        }else{
            $dat['status'] = 102;
            $dat['msg'] = "请求方式错误";
            echo json_encode($dat);die;
        }


    }

}
*/
    /*
     * 获取我的收藏的题库的接口
     *
    public function itemcollect(){
        $token = I("token");   //获取用户的token的值
        $users = M("users");
        $userinfo = $users->where("token = $token")->field("id")->find();           //获取用户的ID
        $uid = $userinfo["id"];

        $status = C('status');
        $msg = C('msg');
        $response = new SubmitController();

        $model = M("item_collection");
        $res = $model
            ->join("LEFT JOIN cmf_users ON cmf_users.id = cmf_item_collection.uid")
            ->join("LEFT JOIN cmf_itembank ON cmf_itembank.item_id = cmf_item_collection.item_id")
            ->where("cmf_item_collection.uid = $uid")
            ->field("cmf_itembank.question,cmf_itembank.item_id,cmf_itembank.eid,cmf_itembank.etid,cmf_itembank.material_id,cmf_item_collection.ctime")

            ->select();

            $result= array();
            foreach ($res as $key => $info) {
                if($res[$key]['material_id'] !=""){
                    //卷四的情况下
                    $result[$info['eid']][]  = $info;
                }else{
                    //卷一卷二卷三的情况下
                    $result[$info['eid'].'-'.$info["etid"]][]  = $info;
                }

            }

        foreach($result as $k => $v){
            //print_r($k);
            //var_dump($k);die;
            if(strstr($k,'-')){
                //echo "1"."<br>";
                $arr=explode('-',$k);
                $dat['year'] = $arr[0];
                $dat['edit'] = $arr[1]+1;
                for($i=1;$i<=count($v);$i++){
                    $data['count'] = $i;                                                       //个数
                }
                //print_r($v);
                $data['title'] = $dat['year'].'年司法考试试卷'.$dat['edit'];                  //题目
            }else{
                $arr=explode('-',$k);
                $dat['year'] = $arr[0];
                for($i=1;$i<=count($v);$i++){
                    $data['count'] = $i;                                                       //个数
                }
                //print_r($v);
                $data['title'] = $dat['year'].'年司法考试试卷4';                  //题目
            }

            if (!empty($data)) {
                echo json_encode([
                    'status' => $status[0],
                    'msg' => $msg[0],
                    'data' => $data
                ]);

            }else {
                echo $response::state($status[0],$msg[0],$data = null);
            }
        }
        //print_r($data);
    }
*/



        /*
         * 获取收藏的课题下面的试题
         *
        public function itemport(){
            $year = I("year");         //年份
            $edit = I("edit")-1;      //获得试卷几  数据库的卷数   存储为3  当为卷四的时候不能有值
            $material_id = I("mid");   //如果是卷四才不为空  为卷123的时候为空   //可有可没有
            $token = I("token");
            $users = M("users");
            $userinfo = $users->where("token = $token")->field("id")->find();           //获取用户的ID
            $uid = $userinfo["id"];

            $model = M("item_collection");
            if($material_id == ""){
                $data = $model
                    ->join("LEFT JOIN cmf_itembank ON cmf_itembank.item_id = cmf_item_collection.item_id")
                    ->join("LEFT JOIN cmf_option ON cmf_option.qid = cmf_item_collection.item_id")
                    ->join("LEFT JOIN cmf_answer ON cmf_answer.qid = cmf_item_collection.item_id")
                    ->where("cmf_item_collection.eid = $year and cmf_item_collection.edit = $edit and cmf_item_collection.uid=$uid")
                    ->field("cmf_itembank.question,cmf_itembank.parsing,cmf_option.key,cmf_option.options,cmf_item_collection.item_id,cmf_answer.option_id")
                    ->select();
            }else {
                $data = $model
                    ->join("LEFT JOIN cmf_itembank ON cmf_itembank.item_id = cmf_item_collection.item_id")
                    ->join("LEFT JOIN cmf_material ON cmf_material.material_id = cmf_item_collection.material_id")
                    ->join("LEFT JOIN cmf_subjective ON cmf_subjective.meterial_id = cmf_item_collection.material_id")
                    ->where("cmf_item_collection.eid = $year and cmf_item_collection.material_id=$material_id and cmf_item_collection.uid=$uid")
                    ->field("cmf_itembank.question,cmf_itembank.parsing,cmf_material.content,cmf_item_collection.item_id")
                    ->select();
            }
            //echo $model->getlastsql();
            //print_r($data);
            $status = C('status');
            $msg = C('msg');
            $response = new SubmitController();

            if (!empty($data)) {
                echo json_encode([
                    'status' => $status[0],
                    'msg' => $msg[0],
                    'data' => $data
                ]);exit();

            }else {
                echo $response::state($status[0],$msg[0],$data = null);exit();
            }

        }
*/

    /*
     * 收藏的卷几下面的试题的批量操作
     *
    public function deltest(){
        $status = C('status');
        $msg = C('msg');
        $response = new SubmitController();
        $model = M("item_collection");

        $id = I("ids");                          //接收传递过来的id的值或者数组   item_id
        $token = I("token");                    //获取用户的唯一标识

        $users = M("users");
        $userinfo = $users->where("token = $token")->field("id")->find();           //获取用户的ID
        $uid = $userinfo["id"];

        //判断id是数组还是一个数值
        if(is_array($id)){
            $where = 'item_id in ('.implode(',',$id).') ';
        }else{
            $where = 'item_id ='.$id ;
        } //dump($where);
        $where .= " AND uid = $uid";
        $list=$model->where($where)->delete();


        if (!empty($list)) {
            echo json_encode([
                'status' => $status[0],
                'msg' => $msg[0],
                // 'data' => $downinfo
            ]);exit();

        }else {
            echo $response::state($status[0],$msg[0]);exit();
        }


    }

 */
    /*
     *收藏的卷几的批量操作 
     *
    public function  delitem(){
        $token = I("token");                    //获取用户的唯一标识
        $eids = I("eid");                      //年份
        $id = I("edit");                          //接收传递过来的id的值或者数组  卷几  当为卷四的时候此项为空

        $users = M("users");
        $userinfo = $users->where("token = $token")->field("id")->find();           //获取用户的ID
        $uid = $userinfo["id"];

        $model = M("item_collection");

        if(empty($id)){
                $where = "edit is null";
        }else{
            if(is_array($id)){
                for($i=0;$i<count($id);$i++){
                    $id[$i] = $id[$i]-1;
                }
                $where = 'edit in ('.implode(',',$id).')';

            }else{
               $id = $id-1;
                $where = "edit = $id";
            }
        }

        $where .= " AND uid = $uid  AND eid = $eids";


        $status = C('status');
        $msg = C('msg');
        $response = new SubmitController();


        $data=$model->where($where)->delete();
        if (!empty($data)) {
            echo json_encode([
                'status' => $status[0],
                'msg' => $msg[0],
                // 'data' => $downinfo
            ]);exit();

        }else {
            echo $response::state($status[0],$msg[0]);exit();
        }

    }
*/

    /*
     * 将题库加入收藏
     *
    public function insertitem(){

        $status = C('status');
        $msg = C('msg');
        $response = new SubmitController();

        $token = I("token");            //获取token值
        $users = M("users");
        $userinfo = $users->where("token = $token")->field("id")->find();           //获取用户的ID
        $uid = $userinfo["id"];
        $material_id = I("material_id");
        $data['item_id'] = I("item_id");
        $data['uid'] = $uid;
        $data['ctime'] = date("Y-m-d H:i:s");
        $data['eid'] = I("eid");            //年份
        if(empty($material_id)){
            $data['edit'] = I("edit");
        }else{
            $data['material_id'] = $material_id;
            $data['edit'] = NULL;
        }

        $model = M("item_collection");
        $res = $model->add($data);
        if (!empty($res)) {
            echo json_encode([
                'status' => $status[0],
                'msg' => $msg[0],
            ]);exit();

        }else {
            echo $response::state($status[0],$msg[0]);exit();
        }

    }

*/
}
