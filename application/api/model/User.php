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

    public function getBioAttr($value)
    {

        if (mb_strlen($value,'utf-8') > 14) {
            return mb_substr($value,0,12,"utf-8").'...';
        }
        return $value;
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
        return $this->field(['id','nickname','avatar','bio'])
            ->where(['is_identy'=>'2','status'=>'normal'])
            ->page($page,$limit)
            ->order('weigh desc')
            ->select();
    }

    /**
     * 获取总页数
     * @return int|string
     * @throws \think\Exception
     */
    public function getTotal()
    {
        return $this->where(['is_identy'=>'2','status'=>'normal'])->count();
    }
}