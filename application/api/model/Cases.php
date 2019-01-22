<?php
// +----------------------------------------------------------------------
// | DTCms
// +----------------------------------------------------------------------
// | 版权所有 2017-2019 西安大唐云信息科技有限公司 [ http://www.rungyun.com ]
// +----------------------------------------------------------------------
// | 技术博客: http://www.rungyun.com
// +----------------------------------------------------------------------


namespace app\api\model;


use think\Model;

class Cases extends Model
{
    /**
     * 案例与解读
     * @param $page
     * @param $limit
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getAllCases($page,$limit)
    {
        return $this->field(['id','cover','cate_id'])
            ->where(['status'=>'1'])
            ->page($page,$limit)
            ->order(['weigh'=>'desc','id'=>'desc'])
            ->select();
    }

    /**
     *
     * @param $id
     * @return Cases|null
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public function getDetail($id)
    {
        $data = self::get($id);
        $data->setInc('reading_count');
        return $data;
    }
}