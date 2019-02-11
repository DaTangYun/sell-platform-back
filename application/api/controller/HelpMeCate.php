<?php

namespace app\api\controller;

use app\common\controller\Api;
use app\api\model\HelpMeCate as HelpMeCateModel;

/**
 * 帮帮我分类接口
 */
class HelpMeCate extends Api
{

    protected $noNeedLogin = [''];
    protected $noNeedRight = [''];

    /**
     * 所有列表
     */
    public function lists()
    {
        if($this->request->isGet()){
            $helpMeCate = (New HelpMeCateModel)->getHelpMeCate();
            $this->success('获取成功',compact('helpMeCate'));
        }
    }

}
