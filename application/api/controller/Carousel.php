<?php

namespace app\api\controller;

use app\common\controller\Api;
use app\api\model\Carousel as CarouselModel;

/**
 * 轮播接口
 */
class Carousel extends Api
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
            $carousel = (new CarouselModel)->getAll();
    		$this->success('请求成功',compact('carousel'));
    	}
    }
}
