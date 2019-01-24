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
use think\Request;

class User extends Model
{
    public function getAvatarAttr($value)
    {
        return Request::instance()->domain().$value;
    }

    /**
     * 秀秀我数据
     * @param $page
     * @param $limit
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getAllUser($page,$limit)
    {
        return $this->field(['id','username','avatar','bio'])->page($page,$limit)->order('weigh desc')->select();
    }
}