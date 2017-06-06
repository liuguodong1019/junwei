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
        if(IS_POST){
            $status = I("post.status");    //1收藏    0取消收藏
            $uid = I("post.uid");     //用户
            $live_id = I("post.live_id");            //课时的id
            $cid  = I("post.course_id");            //课程得 ID
            if($status == 1){
                $data['live_id'] = $live_id ;
                $data['create_time'] = time();                     //时间

                $data['course_id'] = $cid;
                $data['uid'] = $uid;

                $data['type'] = 1;
                //判断下载的课程表中是否有该课程的id  如果没有则添加
                $downcourse = M("course_collection")->where("course_id = $cid AND uid = $uid")->find();
                if(empty($downcourse)){
                    $dat['course_id'] = $cid;
                    $dat['create_time'] = time();
                    $dat['uid'] = $uid;
                    $dat['type'] = 1;
                    $rs = M("course_collection")->add($dat);
                }

                $model = M("live_collection");
                //先判断download_live表中数据是否存在
                $downlive = $model->where("uid = $uid AND live_id = $live_id AND course_id = $cid")->find();
                if(empty($downlive)){
                    $res = $model->add($data);
                    //如果下载了要在在course表内的下载次数加一
                    $course = M("course")->where("id = $cid")->field("collection_num")->find();
                    $cour['collection_num'] = $course['collection_num'] +1;
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
                }else{
                    $course = M("course")->where("id = $cid")->field("collection_num")->find();
                    $cour['collection_num'] = $course['collection_num'] +1;
                    $courok = M("course")->where("id = $cid")->save($cour);
                    $datt['type'] = 1;
                    $datt['create_time'] = time();
                    $rrr = $model->where("uid = $uid AND live_id = $live_id AND course_id = $cid")->save($datt);
                    if (!empty($rrr && $courok)) {
                        $arr['status'] = 1;
                        $arr['msg'] = "操作成功";
                        echo json_encode($arr);die;

                    }else {
                        $arr['status'] = 0;
                        $arr['msg'] = "操作失败";
                        echo json_encode($arr);die;
                    }

                }
            }else{
                $model = M("live_collection");
                $data['type'] = 0;   //取消收藏
                if($model->where("uid = $uid AND live_id = $live_id AND course_id = $cid")->save($data)){
                    $arr['status'] = 1;
                    $arr['msg'] = "操作成功";
                    echo json_encode($arr);die;
                }else{
                    $arr['status'] = 0;
                    $arr['msg'] = "操作失败";
                    echo json_encode($arr);die;
                }
            }

        }ELSE{
            $arr['status'] = 102;
            $arr['msg'] = "请求方式错误";
            echo json_encode($arr);die;
        }
    }



    /*
     * 将收藏课程的写入数据库
     * */
    public function coursecoll(){
        if(IS_POST){
            $uid = I("post.uid");
            $cid  = I("post.course_id");            //课程得 ID
            $model = M("course_collection");
            $status = I("post.status");
            if($status == 1){
                $data['course_id'] = $cid;
                $data['create_time'] = time();
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

            }else{
                $data['type'] = 0;
                if($model->where("uid = $uid AND course_id = $cid")->save($data)){
                    $arr['status'] = 1;
                    $arr['msg'] = "取消收藏成功";
                    echo json_encode($arr);die;
                }else{
                    $arr['status'] = 0;
                    $arr['msg'] = "取消收藏失败";
                    echo json_encode($arr);die;
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

        if(!empty($page)){
            $downinfo = $model
                ->join("LEFT JOIN cmf_course ON cmf_course.id = cmf_course_collection.course_id")
                ->join("cmf_users ON cmf_users.id = cmf_course_collection.uid")
                ->where("cmf_course_collection.uid = $uid AND cmf_course_collection.type = 1")
                ->order("cmf_course_collection.uc_id desc")
                ->page($page.',10')
                ->select();
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
     * 获取收藏的课程下面的课时的列表
     * */
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
                ->page($page . ',10')
                ->select();
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
     * */
    public function livecolldel(){

        $model = M("live_collection");
        $id = I("ids");                          //接收传递过来的id的值或者数组(接收的主键自增的id  )
        $uid = I("uid");                    //获取用户的唯一标识
        //判断id是数组还是一个数值
        if(is_array($id)){
            $where = 'ul_id in ('.implode(',',$id).') ';
        }else{
            $where = 'ul_id ='.$id ;
        } //dump($where);
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

        $id = I("ids");                          //接收传递过来的id的值或者数组（接收的课程的ids）

        $mod = M("course_collection");
        //判断id是数组还是一个数值
        if(is_array($id)){
            $where = 'course_id in ('.implode(',',$id).')';
            $idss = implode(',',$id);
            //print_r($idss);
            $aa = M("live_collection")->where("course_id in ($idss) AND uid = $uid")->select();
            if($aa){
                $rs = M("live_collection")->where("course_id in ($idss) AND uid = $uid")->delete();
            }
        }else{
            $where = 'course_id ='.$id;
            $aa = M("live_collection")->where("course_id = $id AND uid = $uid")->select();
            if($aa){
                $rs = M("live_collection")->where("course_id = $id AND uid = $uid")->delete();
            }
        }
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
            ->field("cmf_item_collection.eid,cmf_item_collection.etid,cmf_subjects.stitle,cmf_chapter.ctitle,cmf_item_collection.sid,cmf_item_collection.cid,cmf_item_collection.ctime")
            ->where("cmf_item_collection.uid = $uid AND cmf_item_collection.status = 1")
            ->page($page . ',10')
            ->select();
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
            $data['status'] = 0;
            $data['msg'] = '请求成功';
            $data['data'] = '';
            echo json_encode($data);die;
        }
    }

    /*
     * 编辑收藏的课题
     * */
    public function  delitem(){
        $uid = I("uid");                    //获取用户的唯一标识
        $id = I("ids");                          //接收传递过来的需要删除的我的收藏的课程的自增id    collect_id
        $model = M("item_collection");


            if(is_array($id)){
                $where = 'collect_id in ('.implode(',',$id).')';

            }else{
                $where = "collect_id = $id";
            }


        $where .= " AND uid = $uid";


        $data=$model->where($where)->delete();
        if (!empty($data)) {
           $dat['status'] = 1;
            $dat['msg'] = '操作成功';
            echo json_encode($dat);

        }else {
            $dat['status'] = 0;
            $dat['msg'] = '操作失败';
            echo json_encode($dat);
        }

    }

    /*
    * 将题库加入收藏
    * */
    public function insertitem(){
        if(IS_POST){
            $status = I("post.status");
            if($status == 1){
                $data['uid'] = I("post.uid");
                $data['ctime'] = date("Y-m-d H:i:s");
                $data['eid'] = I("eid");            //年份
                $data['status'] = 1;
                if(empty($data['eid'])){
                    $data['sid'] = I("post.sid");
                    $data['cid'] = I("post.cid");
                    $data['eid'] = NULL;

                }else{
                    $data['etid'] = I("post.etid");//卷几
                }

                $model = M("item_collection");
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
