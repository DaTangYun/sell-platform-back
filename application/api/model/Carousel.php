<?php

namespace app\api\model;

use think\Model;
use think\Request;

/**
 * 轮播
 */
class Carousel Extends Model
{
    protected $hidden = [
        'weigh',
        'createtime',
    ];
    /**
     * 时间获取器
     * @param $value
     * @return string
     */
    public function getImageAttr($value)
    {
        return Request::instance()->domain().$value;
    }

    /**
     * 获取全部轮播图
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getAll()
    {
        return self::order('weigh desc,id desc')->select();
    }

}
