<?php
/**
 * Created by PhpStorm.
 * User: 刘国栋
 * Date: 2017/4/28
 * Time: 10:20
 */
namespace Admin\Controller;

use Common\Controller\AdminbaseController;
use Common\Model\CourseModel;
class BookController extends AdminbaseController
{
    /**
     * 配发图书
     */
    public function show ()
    {
        $book = M('book');
        $count      = $book->count();
        $page       = $this->page($count,20);
        if (IS_POST) {
            $keyword = I('post.keyword');
            if (!empty($keyword)) {
                $data = $book->where("book like '%$keyword%'")
                    ->order('create_time')->limit($page->firstRow.','.$page->listRows)->select();
            }
        }else {
            $data = $book->order('create_time')->limit($page->firstRow.','.$page->listRows)->select();
        }
        $this->assign('data',$data);
        $this->assign('page',$page->show('Admin'));
        $this->display();
    }

    /**
     * 添加适用人群
     */
    public function create ()
    {
        $book = M('book');
        if (IS_POST) {
            $data['book'] = I('post.book');
            $data['create_time'] = time();
            if ($book->add($data)) {
                $this->success(L('ADD_SUCCESS'), U("Book/show"));exit();
            }else {
                $this->error(L('ADD_FAILED'));exit();
            }
        }
        $this->display();
    }

    /**
     * 修改配发图书
     */
    public function update ()
    {
        $book = M('book');
        if (IS_GET) {
            $id = I('get.id');
            if (!empty($id)) {
                $people = $book->where("b_id = $id")->getField('book');;
            }
            $this->assign('id',$id);
            $this->assign('book',$book);
        }
        if (IS_POST) {
            $id = I('post.id');
            $data['book'] = I('post.book');
            $data['update_time'] = time();
            if ($book->where("b_id = $id")->save($data)) {
                $this->success(L('ADD_SUCCESS'),U("Book/show"));exit();
            }else {
                $this->error(L("ADD_FAILED"));exit();
            }
        }
        $this->display();
    }

    /**
     * 删除配发图书
     */
    public function delete ()
    {
        $book = M("book");
        if (IS_GET) {
            if(isset($_GET['id'])){
                $id = intval(I("get.id"));
                if ($book->where("b_id = $id")->delete()!==false) {
                    $this->success("删除成功！");
                } else {
                    $this->error("删除失败！");
                }
            }
        }
        if(isset($_POST['b_ids'])){
            $ids=join(",",$_POST['b_ids']);
            if ($book->where("b_id in ($ids)")->delete()!==false) {
                $this->success("删除成功！");
            } else {
                $this->error("删除失败！");
            }
        }
    }

    public function notify_url ()
    {
        $str = new CourseModel();
        $redis = $str::redis();
        
        // $notifyBody = file_get_contents('php://input');
        // $json = json_encode($notifyBody);
        // $redis->set('name',$json);
        $res = $redis->del('name');
        print_r($res);die;
        // error_log($notifyBody);
    }
}