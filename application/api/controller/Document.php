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
use app\api\model\Document as DocumentModel;

/**
 * 文档控制器
 * Class Document
 * @package app\api\controller
 */
class Document extends Api
{
    /**
     * 文档列表
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function lists()
    {
        if ($this->request->isGet()){
            //接收分页页数和每页显示的数据
            $page = $this->request->get('page/d',1);
            $limit = $this->request->get('limit/d',10);
            $document = (new DocumentModel())->getAllDocument($page,$limit);
            $this->success('获取数据成功',compact('document'));
        }
    }

    /**
     * 文档详情
     * @param $id
     * @throws \think\exception\DbException
     */
    public function detail($id)
    {
        if ($this->request->isGet()){
            $detail = (new DocumentModel())->getDetail($id);
            $this->success('获取数据成功',compact('detail'));
        }
    }
}