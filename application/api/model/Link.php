<?php

namespace app\api\model;

use think\Model;

/**
 * 友情链接
 */
class Link Extends Model
{

    public function getAll()
    {
        return self::order('weigh desc,id desc')->select();
    }

}
