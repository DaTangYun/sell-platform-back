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

class Team extends Model
{
    protected $autoWriteTimestamp = true;

    protected $createTime = 'createtime';
    protected $updateTime = false;

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
        return date('Y-m-d',$value);
    }
    /**
     * 关联申请人模型
     * @return \think\model\relation\HasOne
     */
    public function apply()
    {
        return $this->hasMany('TeamApply','team_id')->where('status','2');
    }

    /**
     * 查询团队列表
     * @param $page
     * @param $limit
     * @param $user_id
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getLists($page,$limit,$user_id)
    {
        return $this->withCount('apply')->where(['user_id'=>$user_id])->page($page,$limit)->select();
    }

    /**
     * 团队详情
     * @param $id
     * @return Team|null
     * @throws \think\exception\DbException
     */
    public function getDetail($id)
    {
        return self::get($id);
    }

}