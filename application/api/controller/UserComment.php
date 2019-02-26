<?php

namespace app\api\controller;

use app\common\controller\Api;
use app\api\model\UserComment as UserCommentModel;

/**
 * 用户评论接口
 */
class UserComment extends Api
{

    protected $noNeedLogin = ['lists'];
    protected $noNeedRight = ['lists'];

    /**
     * 当前模型对象
     * @var \app\api\model\
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new UserCommentModel;

    }
    /**
     * 评论列表
     * 
     */
    public function lists()
    {
    	if($this->request->isGet()){
            $page = input('get.page/d',1);
            $limit = input('get.limit/d',5);
            $userId = input('get.user_id/d',0);
            $comment = $this->model->getAll($page,$limit,$userId);
            $total = $this->model->getTotal($userId);
            return $this->success('获取成功',compact('comment','total'));
    	}
    }
    /*
     *个人中心评论
     * */
    public function profile()
    {
        if($this->request->isGet()){
            $page = input('get.page/d',1);
            $limit = input('get.limit/d',5);
            $user = $this->auth->getUser();
            $userId = $user->id;
            $comment = $this->model->getAll($page,$limit,$userId);
            $total = $this->model->getTotal($userId);
            return $this->success('获取成功',compact('comment','total'));
        }
    }
    /**
     * 进行评论
     */
    public function comment()
    {
        if($this->request->isPost()){
            $userId = input('post.user_id/d',0);
            $content = input('post.content/s','');
            $user = $this->auth->getUser();
            $commentId = $user->id;
            $data = [
                'user_id'      =>  $userId,
                'comment_id'   =>  $commentId,
                'content'      =>  $content
            ];
            if ($this->model->where(['comment_id'=>$commentId])->whereTime('createtime', '>=', time()-60)->find()){
                $this->error('评论过于频繁');
            }
            $res = $this->model->comment($data);
            if ($res){
                $this->success('评论成功');
            }
            $this->error('评论失败,请稍后再试');
        }
    }
}
