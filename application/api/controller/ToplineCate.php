<?php

namespace app\api\controller;

use app\common\controller\Api;
use app\api\model\ToplineCate as ToplineCateModel;

/**
 * 头条分类接口
 */
class ToplineCate extends Api
{

    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];

    /**
     * 所有列表
     */
    public function lists()
    {
        if($this->request->isGet()){
            $toplineCate = (New ToplineCateModel)->getAll();
            $this->success('获取成功',compact('toplineCate'));
        }
    }

}
