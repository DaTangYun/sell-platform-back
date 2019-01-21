<?php

namespace app\api\controller;

use app\common\controller\Api;

/**
 * 案例与解读
 */
class Cases extends Api
{

    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];

    /**
     * 首页接口
     * 
     */
    public function index()
    {
        if ($this->request->isGet()) {
            $this->success('请求成功');
        }
    }

    /**
     * 全部案例接口
     */

}
