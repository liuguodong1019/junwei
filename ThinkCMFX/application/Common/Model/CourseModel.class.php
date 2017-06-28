<?php
namespace Common\Model;

use Api\Controller\CacheController;
use Think\Model;
use Api\Controller\ResponseController;
class CourseModel extends Model
{
    /**
     * @param $Page
     * @return bool
     * 后台课堂列表
     */
    public function show ($Page)
    {
        if (IS_POST) {
            $keyword = I('post.keyword');
            if (!empty($keyword)) {
                $data = $this->where("course_name like '%$keyword%'")
                    ->order('startDate')->limit($Page->firstRow . ',' . $Page->listRows)->select();
            }else {
                return false;
            }
        } else {
            $data = $this->table('cmf_course as a')
                ->field('a.id,a.course_name,a.now_price,a.old_price,a.people,a.book,a.lector,a.startdate,a.invaliddate,a.class_id,a.courseware_id')
                ->order('a.id DESC')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        }
        return $data;
    }

    /**
     * @param mixed|string $data
     * @param string $photo
     * @return bool
     * 课程创建
     */
    public function create ($data,$photo)
    {
        $response = new ResponseController();
        $redis = self::redis();
        $id = $redis->get('id');
        if (empty($id)) {
            $redis->set('id',0);
        }
        $data['people'] = !empty(join(",",I('post.people'))) ? join(",",I('post.people')): 0;
        $data['book'] = !empty(join(",",I('post.book'))) ? join(",",I('post.book')) : 0;
        $is_free = I('post.is_free');
        $upload = new \Think\Upload($photo);// 实例化上传类
        if ($is_free == 1) {
            $info = $upload->upload();
            foreach($info as $file){
                $cover[] = $file['savepath'].$file['savename'];
                $data['cover'] = $cover[0];
            }
            $junwei = M('junwei')->find();
            $loginName = $junwei['loginname'];
            $password = sp_authcode($junwei['password']);
            $startDate = I('post.startDate');
            $invalidDate = I('post.invalidDate');
            $subject = I('post.course_name');
            //调用课时创建接口
            $resource = $response::create_course($subject,$loginName,$password,$startDate,$invalidDate);
            $data['number'] = $resource['number'];
            $data['stu_token'] = $resource['studentClientToken'];
            $data['class_id'] = $resource['id'];
            if ($resource['code'] == 0) {
                $redis->incr('id');
                $id = $redis->get('id');
                $data['id'] = $id;
                $data['status'] = 0;
                $data['reply_url'] = 0;
                $res = json_encode($data);
                $redis->hSet('course',$id,$res);
                if ($this->add($data)) {
                    return true;
                }else {
                    return false;
                }
            }else {
                return false;
            }
        }else {
            $info = $upload->upload();
            foreach($info as $file){
                $cover[] = $file['savepath'].$file['savename'];
                $data['cover'] = $cover[0];
                $data['detail_cover'] = $cover[1];
            }
            $data['startDate'] = !empty(join(",",I('post.startDate'))) ? join(",",I('post.startDate')): '0000-00-00 00:00:00';
            $data['invalidDate'] = !empty(join(",",I('post.invalidDate'))) ? join(",",I('post.invalidDate')) : '0000-00-00 00:00:00';

            if ($this->add($data)) {
                $redis->incr('id');
                $id = $redis->get('id');
                $data['id'] = $id;
                $res = json_encode($data);
                $redis->hSet('course',$id,$res);
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * @param $is_free
     * @return bool
     * 课程修改
     */
    public function update ($is_free)
    {
        $photo = C('upload');
        $upload = new \Think\Upload($photo);// 实例化上传类
        $redis = self::redis();
        $test = new CacheController();
        if ($is_free == 1) {
            $id = I('post.id');
            $data = I('');
            $data['people'] = !empty(join(",",I('post.people'))) ? join(",",I('post.people')): 0;
            $data['book'] = !empty(join(",",I('post.book'))) ? join(",",I('post.book')) : 0;
            $info = $upload->upload();
            foreach($info as $file){
                $cover = $file['savepath'].$file['savename'];
            }
            $data['cover'] = $cover;
            unset($data['realtime']);
            $str = $redis->hGet('course',$id);
            $array = json_decode($str,true);
            $hell = $test->updateCourse($array,$data,$id);
            
            if ($hell !== false) {
                return true;
            }
            return false;
        }else {
            $id = I('post.id');
            $data = I('');
            $data['people'] = !empty(join(",",I('post.people'))) ? join(",",I('post.people')): 0;
            $data['book'] = !empty(join(",",I('post.book'))) ? join(",",I('post.book')) : 0;
            $info = $upload->upload();
            foreach($info as $file){
                $cover[] = $file['savepath'].$file['savename'];
                $data['cover'] = $cover[0];
                $data['detail_cover'] = $cover[1];
            }
            unset($data['class_id']);
            unset($data['realtime']);
            unset($data['number']);
            unset($data['stu_token']);
            if ($this->where("id = $id")->save($data)) {
                $res = json_encode($data);
                $redis->hSet('course',$id,$res);
                return true;
            } else {
                return false;
            }
        }
    }

    public static function redis ()
    {
        $redis=new \Redis();
        $connect=$redis->connect("127.0.0.1",6379);
        if(!$connect){
            echo '连接异常';die;
        }
        return $redis;
    }
}