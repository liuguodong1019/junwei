<?php
namespace Common\Model;

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
                ->field('a.id,a.course_name,a.now_price,a.old_price,a.people,a.book,a.startdate,a.invaliddate,a.class_id')
                ->join('left join cmf_lector as b ON a.lector_id = b.l_id')
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
            if ($this->add($data)) {
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
        if ($is_free == 1) {
            $id = I('post.id');
            $res = $this->field('id,course_name,num_class,class_id,is_free,now_price,old_price,startDate,invalidDate,introduction,lector_id,people,book,cover')->where("id = '$id'")->find();
            $data = I('');
            $data['people'] = join(",",I('post.people'));
            $data['book'] = join(",",I('post.book'));
            $info = $upload->upload();
            foreach($info as $file){
                $cover = $file['savepath'].$file['savename'];
            }
            $data['cover'] = $cover;
            $photo = array_diff_assoc($data,$res);
            $image = $photo['cover'];
            if (!empty($image)) {
                if ($this->where("id = $id")->save($photo)) {
                    return true;
                }
                    return false;
            }else {
                $junwei = M('junwei')->find();
                $loginName = $junwei['loginname'];
                $password = sp_authcode($junwei['password']);
                $realtime = I('post.realtime');
                $startDate = I('post.startDate');
                $invalidDate = I('post.invaliddate');
                $subject = I('post.course_name');
                $class_id = I('post.class_id');
                $response = new ResponseController();
                //调用修改课时接口
                $resource = $response::update_course($loginName,$password,$realtime,$startDate,$invalidDate,$subject,$class_id);
                if ($resource['code'] == 0) {
                    if ($this->where("id = $id")->save($data)) {
                        return true;
                    } else {
                        return false;
                    }
                }else {
                    return false;
                }
            }
        }else {
            $id = I('post.id');
            $data = I('');
            $data['people'] = join(",",I('post.people'));
            $data['book'] = join(",",I('post.book'));
            $info = $upload->upload();
            foreach($info as $file){
                $cover[] = $file['savepath'].$file['savename'];
                $data['cover'] = $cover[0];
                $data['detail_cover'] = $cover[1];
            }
            if ($this->where("id = $id")->save($data)) {
                return true;
            } else {
                return false;
            }
        }
    }
}