<?php

namespace app\api\controller;

use app\common\controller\Api;
use app\api\model\AbilityMessage as AbilityMessageModel;

/**
 * 能帮会干留言接口
 */
class AbilityMessage extends Api
{

    protected $noNeedLogin = ['lists'];
    protected $noNeedRight = ['*'];

    /**
     * 当前模型对象
     * @var \app\api\model\
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new AbilityMessageModel;

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
            $abilityId = input('get.ability_id/d',0);
            $message = $this->model->getAbilityMessage($page,$limit,$abilityId);
            $total = $this->model->getTotal($abilityId);
            return $this->success('获取成功',compact('message','total'));
    	}
    }

    /**
     * 进行留言
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
                $this->error('留言过于频繁');
            }
            $res = $this->model->add($data);
            if ($res){
                $this->success('留言成功');
            }
            $this->error('留言失败,请稍后再试');
        }
    }
}
