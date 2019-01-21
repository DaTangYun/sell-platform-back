<?php

namespace app\api\controller;

use app\common\controller\Api;
use app\api\model\Topline as ToplineModel;

/**
 * 头条接口
 */
class Topline extends Api
{

    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];

    /**
     * 主页接口
     * 
     */
    public function main()
    {
    	if($this->request->isGet()){
            $carousel = (new CarouselModel)->gerAll();
    		$this->success('请求成功',compact('carousel'));
    	}
    }

    /**
     * 所有列表
     */
    public function lists()
    {
        if($this->request->isGet()){
            $cateId = input('get.cate_id/d',0);
            $page = input('get.page/d',1);
            $title = input('get.title/s','');
            $topline = (New ToplineModel)->getAll($page,$cateId,$title);
        }
    }
}
