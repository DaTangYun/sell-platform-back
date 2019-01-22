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

class CloudIntelligence extends Model
{
    /**
     * 云智慧
     * @param $page
     * @param $limit
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getCloudAll($page,$limit)
    {
        return $this->field(['id','image','url'])->page($page,$limit)->select();
    }

    /**
     * 云智慧的总数
     * @return int|string
     * @throws \think\Exception
     */
    public function getCloudCount()
    {
        return $this->count('id');
    }

}