<?php

namespace app\api\controller;

use app\common\controller\Api;
use app\api\model\Cases as CasesModel;

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
    public function lists()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isGet()){
            //接收分页页数和每页显示的数据
            $page = $this->request->get('page/d',1);
            $limit = $this->request->get('limit/d',6);
            $cases = (new CasesModel)->getAllCases($page,$limit);
            $this->success('获取数据成功',compact('cases'));
        }
    }
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
