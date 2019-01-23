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
use app\api\model\Finance as FinanceModel;
use app\api\model\FinanceCate as FinanceCateModel;

class Finance extends Api
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
        $this->model = new FinanceModel;

    }
    /**
     * 获取财经法规列表数据
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function lists()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isGet()){
            //接收分页页数和每页显示的数据
            $page = $this->request->get('page/d',1);
            $limit = $this->request->get('limit/d',10);
            $title = $this->request->get('title/s',false);
            $cate_id = $this->request->get('cate_id/s',0);
            $finance = $this->model->getFinance($page,$limit,$title,$cate_id);
            $total = $this->model->getFinanceTotal($title,$cate_id);
            $cate = (new FinanceCateModel)->getAllCate();
            $this->success('获取数据成功',compact('finance','total','cate'));
        }
    }

    /**
     * 财经法规详情
     * @param $id
     * @throws \think\exception\DbException
     */
    public function detail($id)
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isGet()){
            //接收分页页数和每页显示的数据
            $detail = $this->model->getDetail($id);
            $this->success('获取数据成功',compact('detail'));
        }
    }
}