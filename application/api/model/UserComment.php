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

class UserComment extends Model
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
     * 关联评论人模型
     * @return \think\model\relation\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('User','comment_id')->bind(['nickname', 'avatar']);
    }

    /**
    * 评论列表
    */
    public function getAll($page,$limit,$userId)
    {
        return self::order('id desc')->with('user')->where('user_id',$userId)->page($page)->limit($limit)->select();
    }

    /**
     * 评论总数
     */
    public function getTotal($userId)
    {
        return self::where('user_id',$userId)->count();
    }

    /**
     * 进行评论
     */
    public function comment($data)
    {
        return self::save($data);
    }

}