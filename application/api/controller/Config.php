<?php

namespace app\api\controller;

use app\common\controller\Api;

/**
 * 网站配置接口
 */
class Config extends Api
{

    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];

    /**
     * 网站页面配置
     * 
     */
    public function site()
    {
    	if($this->request->isGet()){
            $site = [
                'header_logo'   => config('site.header_logo'),
                'footer_logo'   => config('site.footer_logo'),
                'beian'         => config('site.beian'),
                'beianhao'      => config('site.beianhao'),
                'banquan'       => config('site.banquan')
            ];
    		$this->success('请求成功',compact('site'));
    	}
    }
}
