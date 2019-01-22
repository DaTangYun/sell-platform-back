<?php

namespace app\api\model;

use think\Model;

/**
 * 轮播
 */
class Carousel Extends Model
{

    public function getAll()
    {
        return self::order('weigh desc,id desc')->select();
    }

}
