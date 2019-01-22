<?php

namespace app\api\controller;

use app\common\controller\Api;
use app\api\model\Cases as CasesModel;

/**
 * 案例与解读
 */
class Cases extends Api
{

    protected $noNeedLogin = ['lists','detail'];
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
    public function lists()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isGet()){
            //接收分页页数和每页显示的数据
            $page = $this->request->get('page/d',1);
            $limit = $this->request->get('limit/d',6);
            $search =  $this->request->get('search',false);
            $user_id = $this->request->get('userId',0);
            $cases = (new CasesModel)->getAllCases($page,$limit,$search,$user_id,$flag);
            $total = (new CasesModel())->getCasesTotal();
            $this->success('获取数据成功',compact('cases','total'));
        }
    }

    /**
     * 案例详情
     * @param $id
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public function detail($id)
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isGet()){
            $detail = (new CasesModel())->getDetail($id);
            $this->success('获取数据成功',compact('detail'));
        }
    }
}
