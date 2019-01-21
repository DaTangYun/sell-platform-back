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
     * 头部配置
     * 
     */
    public function header()
    {
    	if($this->request->isGet()){
    		$this->success('请求成功');
    	}
    }
    
    /**
     * 底部配置
     * 
     */
    public function footer()
    {
        if($this->request->isGet()){
            $this->success('请求成功');
        }
    }

    /**
     * 登录页配置
     * 
     */
    public function login()
    {
        if($this->request->isGet()){
            $this->success('请求成功');
        }
    }
}
