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

class Reply extends Model
{
    protected $autoWriteTimestamp = 'int';

    protected $createTime = 'createtime';
    protected $updateTime = false;
    
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
     * from_user_id获取器
     */
    public function getFromUserIdAttr($value)
    {
        return (new User)->where('id',$value)->field('id,avatar,nickname')->find();
    }

    /**
     * to_user_id获取器
     */
    public function getToUserIdAttr($value)
    {
        return (new User)->where('id',$value)->field('id,avatar,nickname')->find();
    }

    /**
     * 添加回复
     */
    public function add($data)
    {
        return self::save($data);
    }
}