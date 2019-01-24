<?php

namespace app\api\controller;

use app\common\controller\Api;
use app\api\model\Page as PageModel;

/**
 * 单页面配置
 */
class Page extends Api
{

    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];
    /**
     * 当前模型对象
     * @var \app\api\model\
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new PageModel;

    }

    /**
     * 无权限列表
     */
    public function detail()
    {
        if($this->request->isPost()){
            $id = input('post.id/d',1);
            $detail = $this->model->getPageDetail($id);
            $this->success('获取成功',compact('detail'));
        }
    }
}
