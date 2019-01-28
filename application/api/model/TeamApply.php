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

/**
 * 加入团队控制器
 * Class TeamApply
 * @package app\api\model
 */
class TeamApply extends Model
{
    protected $autoWriteTimestamp = true;

    protected $createTime = 'createtime';
    protected $updateTime = false;
    protected $hidden = [
        'status',
        'user_id',
    ];

    /**
     * 带分页的团队成员
     * @param $page
     * @param $limit
     * @param $id
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getMember($page, $limit, $id)
    {
        return self::where(['team_id' => $id])->page($page, $limit)->select();
    }

    /**
     * 查询团队成员
     * @param $id
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getTeamMember($id)
    {
        return self::field(['id','name','excellence','desc'])->where(['team_id' => $id, 'status' => '2'])->select();
    }

    /**
     * 删除团队成员
     * @param $id
     * @return int
     */
    public function del($id)
    {
        return self::where(['team_id' => $id])->delete();
    }

    /**
     * 对团审核
     * @param $id
     * @param $status
     * @return int
     */
    public function examineMember($id, $status)
    {
        return $this->where(['id'=>$id])->setField('status',$status);
    }
}