<?php

namespace app\api\controller;

use app\common\controller\Api;
use app\api\model\AbilityComment as AbilityCommentModel;

/**
 * 能帮会干评论接口
 */
class AbilityComment extends Api
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
        $this->model = new AbilityCommentModel;

    }
    /**
     * 评论列表
     * 
     */
    public function lists()
    {
    	if($this->request->isGet()){
            $page = input('get.page/d',1);
            $limit = input('get.limit/d',6);
            $abilityId = input('get.ability_id/d',0);
            $comment = $this->model->getAll($page,$limit,$abilityId);
            $total = $this->model->getTotal($abilityId);
            return $this->success('获取成功',compact('comment','total'));
    	}
    }

    /**
     * 进行评论
     */
    public function add()
    {
        if($this->request->isPost()){
            $abilityId = input('post.ability_id/d',0);
            $content = input('post.content/s','');
            $user = $this->auth->getUser();
            $userId = $user->id;
            if (!$abilityId || !$content) {
                $this->error('参数错误');
            }
            $data = [
                'user_id'      =>  $userId,
                'ability_id'   =>  $abilityId,
                'content'      =>  $content
            ];
            if ($this->model->where(['user_id'=>$userId])->whereTime('createtime', '>=', time()-60)->find()){
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
