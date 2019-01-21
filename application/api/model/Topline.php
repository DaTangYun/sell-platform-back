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

class Topline extends Model
{

    public function getCreatetimeAttr($value)
    {
        return date('Y-m-d',$value);
    }

    public function getAll($page = 1,$cateId = 0,$title = '')
    {
        $query = self::order('weigh desc,id desc');
        if($cateId)
            $query->where('topline_cate_id',$cateId);
        if($title)
            $query->where('title','like', $title . '%');
        return $query->page($page)->limit(5)->select();
    }

    public function getTotal($cateId = 0,$title = '')
    {
        $query = self::order('weigh desc');
        if($cateId)
            $query->where('topline_cate_id',$cateId);
        if($title)
            $query->where('title','like', $title . '%');
        return $query->count();
    }
}