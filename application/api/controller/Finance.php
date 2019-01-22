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

class Finance extends Api
{
    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];

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
            $finance = (new FinanceModel)->getFinance($page,$limit);
            $this->success('获取数据成功',compact('finance'));
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
            $detail = (new FinanceModel)->getDetail($id);
            $this->success('获取数据成功',compact('detail'));
        }
    }
}