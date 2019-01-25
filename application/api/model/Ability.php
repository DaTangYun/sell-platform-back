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

/**
 * 帮帮我模型
 * Class Ability
 * @package app\api\model
 */
class Ability extends Model
{
    protected $autoWriteTimestamp = true;

    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';

    /**
     * 图片获取器
     * @param $value
     * @return string
     */
    public function getImageAttr($value)
    {
        return Request::instance()->domain().$value;
    }

    /**
     * 时间获取器
     * @param $value
     * @return false|string
     */
    public function getCreatetimeAttr($value)
    {
        return date('Y-m-d H:i:s',$value);
    }
    /**
     * 查询所有的帮帮我
     * @param      $page
     * @param      $limit
     * @param      $title
     * @param bool $flag
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getAllAbility($page,$limit,$title,$flag = false,$user_id = 0)
    {
        //条件筛选
        $map = [];
        $title && $map['title'] = $title;
        $flag  && $map['status'] = '2';
        $user_id > 0 && $map['user_id'] = $user_id;
        return self::where($map)->order(['id'=>'desc'])->select();
    }
    public function getTotal($title,$flag = false,$user_id = 0)
    {
        //条件筛选
        $map = [];
        $title && $map['title'] = $title;
        $flag  && $map['status'] = '2';
        $user_id > 0 && $map['user_id'] = $user_id;
        return self::where($map)->order(['id'=>'desc'])->count();
    }
}