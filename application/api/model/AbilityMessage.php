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

class AbilityMessage extends Model
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
     * 关联回复人模型
     * @return \think\model\relation\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('User','user_id')->bind(['nickname', 'avatar']);
    }

    /**
     * 关联回复人from模型
     * @return \think\model\relation\BelongsTo
     */
    public function fromuser()
    {
        return $this->belongsTo('User','from_user_id')->bind(['nickname', 'avatar']);
    }

    /**
     * 关联回复模型
     * @return \think\model\relation\BelongsTo
     */
    public function reply()
    {
        return $this->hasMany('Reply','comment_id')->order('id asc');
    }
    
    /**
     * 获取留言列表以及回复
     */
    public function getAbilityMessage($page,$limit,$abilityId)
    {
        return self::order('id desc')->with(['user','reply'])->page($page)->limit($limit)->where('ability_id',$abilityId)->select();
    }

    /**
     * 获取总数
     */
    public function getTotal($abilityId)
    {
        return self::where('ability_id',$abilityId)->count('id');
    }


    /**
     * 添加留言
     */
    public function add($data)
    {
        return self::save($data);
    }
} 