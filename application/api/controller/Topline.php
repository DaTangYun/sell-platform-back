<?php

namespace app\api\controller;

use app\common\controller\Api;
use app\api\model\Topline as ToplineModel;

/**
 * 头条接口
 */
class Topline extends Api
{

    protected $noNeedLogin = ['lists','detail'];
    protected $noNeedRight = ['*'];


    /**
     * 无权限列表
     */
    public function lists()
    {
        if($this->request->isGet()){
            $cateId = input('get.cate_id/d',0);
            $page = input('get.page/d',1);
            $limit = input('get.limit/d',5);
            $title = input('get.title/s','');
            $userId = input('get.userId/d',0);
            //显示通过
            $status = ture;
            $topline = (New ToplineModel)->getAll($page,$limit,$cateId,$title,$userId,$status);
            $total = (New ToplineModel)->getTotal($cateId,$title,$userId,$status);
            $this->success('获取成功',compact('topline','total'));
        }
    }
    /**
     * 无权限详情页
     */
    public function detail()
    {
        if($this->request->isGet()){
            $id = input('get.id/d',0);
            $detail = (New ToplineModel())->edit($id,$userId = 0);
            $this->success('获取成功',compact('detail'));
        }
    }

    /**
     * 个人中心列表
     */
    public function profile()
    {
        if($this->request->isPost()){
            $cateId = input('get.cate_id/d',0);
            $page = input('get.page/d',1);
            $limit = input('get.limit/d',5);
            $title = input('get.title/s','');
            $user = $this->auth->getUser();
            $userId = $user->id;
            //显示所有的
            $status = false;
            $topline = (New ToplineModel)->getAll($page, $limit, $cateId, $title, $userId, $status);
            $total = (New ToplineModel)->getTotal($cateId,$title,$userId,$status);
            $this->success('获取成功',compact('topline','total'));
        }
    }

    /**
     * 添加
     */
    public function add()
    {
        if($this->request->isPost()){
            $post = $this->request->post();
            $user = $this->auth->getUser();
            $userId = $user->id;
            if ((New ToplineModel())->add($post,$userId))
                $this->success('发表成功');
            $this->error('发表失败');
        }
    }

    /**
     * 编辑
     */
    public function edit()
    {
        if($this->request->isPost()){
            $user = $this->auth->getUser();
            $userId = $user->id;
            $id = input('post.id/d',0);
            $detail = (New ToplineModel())->edit($id,$userId);
            $this->success('获取成功',compact('detail'));
        }
    }

    /**
     * 删除
     */
    public function del()
    {
        if($this->request->isPost()){
            $user = $this->auth->getUser();
            $id = input('post.id/d',0);
            $userId = $user->id;
            if ((New ToplineModel())->del($id,$userId))
                $this->success('删除成功');
            $this->error('删除失败');
        }
    }
}
