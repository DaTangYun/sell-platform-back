<?php

namespace app\api\controller;

use app\common\controller\Api;
use app\api\model\Link as LinkModel;

/**
 * 友情链接接口
 */
class Link extends Api
{

    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];

    /**
     * 轮播列表
     * 
     */
    public function lists()
    {
    	if($this->request->isGet()){
            $link = (new LinkModel)->getAll();
    		$this->success('请求成功',compact('link'));
    	}
    }

}
