<?php

namespace app\api\model;

use think\Model;

/**
 * 轮播
 */
class Carousel Extends Model
{

    public function gerAll()
    {
        return self::order('weigh desc,id desc')->select();
    }

}
