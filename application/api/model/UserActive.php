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
 * 参与的活动(领取的优惠券)
 * Class Active
 * @package app\api\model
 */
class UserActive extends Model
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
     * 查询参与的活动(领取的优惠券)
     * @param      $page
     * @param      $limit
     * @param      $title
     * @param bool $flag
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getAllUserActive($page,$limit,$user_id)
    {
        return self::where('user_id',$user_id)->order(['id'=>'desc'])->page($page,$limit)->select();
    }

    /**
     * 获取总数
     * @param      $title
     * @param bool $flag
     * @param int  $user_id
     * @return int|string
     * @throws \think\Exception
     */
    public function getTotal($user_id = 0)
    {
        //条件筛选
        $map = [];
        $user_id > 0 && $map['user_id'] = $user_id;
        return self::where($map)->order(['id'=>'desc'])->count();
    }
}