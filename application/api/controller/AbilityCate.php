<?php
// +----------------------------------------------------------------------
// | DTCms
// +----------------------------------------------------------------------
// | 版权所有 2017-2019 西安大唐云信息科技有限公司 [ http://www.rungyun.com ]
// +----------------------------------------------------------------------
// | 技术博客: http://www.rungyun.com
// +----------------------------------------------------------------------


namespace app\api\controller;


use app\common\controller\Api;
use app\api\model\AbilityCate as AbilityCateModel;

/**
 * 帮帮我控制器
 * Class Ability
 * @package app\api\controller
 */
class AbilityCate extends Api
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
        $this->model = new AbilityCateModel;

    }
    /**
     *帮帮我列表
     */
    public function lists()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isGet()) {
            //接收分页页数和每页显示的数据
            $abilityCate = $this->model->getAbilityCate();
            $this->success('获取数据成功', compact('abilityCate'));
        }
    }
}