<?php

namespace app\api\controller;

use app\common\controller\Api;
use app\api\model\MessageCate as MessageCateModel;

/**
 * 信息分类接口
 */
class MessageCate extends Api
{

    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];

    /**
     * 所有列表
     */
    public function lists()
    {
        if($this->request->isGet()){
            $messageCate = (New MessageCateModel)->getAll();
            $this->success('获取成功',compact('messageCate'));
        }
    }

}
