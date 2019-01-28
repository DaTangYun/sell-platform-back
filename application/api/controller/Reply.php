<?php

namespace app\api\controller;

use app\common\controller\Api;
use app\api\model\Reply as ReplyModel;

/**
 * 能帮会干回复留言接口
 */
class Reply extends Api
{

    protected $noNeedLogin = [''];
    protected $noNeedRight = [''];

    /**
     * 当前模型对象
     * @var \app\api\model\
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new ReplyModel;

    }
    /**
     * 回复留言
     */
    public function add()
    {
        if($this->request->isPost()){
            $commentId = input('post.comment_id/d',0);
            $replyMsg = input('post.reply_msg/s','');
            $toUserId = input('post.to_user_id/d',0);
            $user = $this->auth->getUser();
            $fromUserId = $user->id;
            if (!$commentId || !$replyMsg || !$toUserId) {
                $this->error('参数错误');
            }
            $data = [
                'comment_id'     =>  $commentId,
                'from_user_id'   =>  $fromUserId,
                'to_user_id'     =>  $toUserId,
                'reply_msg'      =>  $replyMsg
            ];
            if ($this->model->where(['from_user_id'=>$fromUserId])->whereTime('createtime', '>=', time()-60)->find()){
                $this->error('回复过于频繁');
            }
            $res = $this->model->add($data);
            if ($res){
                $this->success('回复成功');
            }
            $this->error('回复失败,请稍后再试');
        }
    }
}
