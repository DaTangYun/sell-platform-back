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
 * 活动(优惠券)
 * Class Active
 * @package app\api\model
 */
class Active extends Model
{
    protected $autoWriteTimestamp = true;

    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';

    /**
     * 开始时间获取器
     * @param $value
     * @return false|string
     */
    public function getStartTimeAttr($value)
    {
        return date('Y-m-d',$value);
    }

    /**
     * 结束时间获取器
     * @param $value
     * @return false|string
     */
    public function getEndTimeAttr($value)
    {
        return date('Y-m-d',$value);
    }
    /**
     * 活动(优惠券)列表
     * @param      $page
     * @param      $limit
     * @param      $title
     * @param bool $flag
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getAllActive($page,$limit,$title,$user_id)
    {
        //过滤筛选
        $map = [];
        !empty($title) && $map['title'] = ['like', '%' . trim($title) . '%'];
        $userId > 0 && $map['user_id'] = $userId;
        $flag && $map['status'] = '2';
        //定义显示字段
        return self::where($map)->order('weigh desc,id desc')->page($page)->limit($limit)->select();
    }

    /**
     * 获取总数
     * @param      $title
     * @param bool $flag
     * @param int  $user_id
     * @return int|string
     * @throws \think\Exception
     */
    public function getTotal($title,$user_id)
    {
        //条件筛选
        $map = [];
        !empty($title) && $map['title'] = ['like', '%' . trim($title) . '%'];
        $user_id > 0 && $map['user_id'] = $user_id;
        return self::where($map)->count();
    }
}