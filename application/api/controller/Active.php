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

/**
 * 活动模型
 * Class Active
 * @package app\api\controller
 */
class Active extends Api
{
    protected $noNeedLogin = ['lists','detail'];
    protected $noNeedRight = ['lists','detail'];
    /**
     * 当前模型对象
     * @var \app\api\model\
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new AbilityModel;

    }
    /**
     *活动列表
     */
    public function lists()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isGet()) {
            //接收分页页数和每页显示的数据
            $page = $this->request->get('page/d', 1);
            $limit = $this->request->get('limit/d', 6);
            $title = $this->request->get('title', false);
            $user_id = $this->request->get('user_id/d',0);
            $cate_id = $this->request->get('cate_id', 0);
            $ability = $this->model->getAllAbility($page, $limit, $title,$cate_id,true,$user_id);
            $total = $this->model->getTotal($title,$cate_id,true,$user_id);
            $this->success('获取数据成功', compact('ability', 'total'));
        }
    }
}