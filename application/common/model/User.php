<?php

namespace app\common\model;

use think\Model;
use think\Request;

/**
 * 会员模型
 */
class User Extends Model
{

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    // 追加属性
    protected $append = [
        'url',
    ];
    protected $hidden = [
        'password',
        'salt'
    ];

    /**
     * 获取个人URL
     * @param   string  $value
     * @param   array   $data
     * @return string
     */
    public function getUrlAttr($value, $data)
    {
        return "/u/" . $data['id'];
    }

    /**
     * 获取头像
     * @param   string    $value
     * @param   array     $data
     * @return string
     */
    public function getAvatarAttr($value, $data)
    {
        return $value ? Request::instance()->domain().$value : Request::instance()->domain().'/assets/img/avatar.png';
    }

    /**
     * 获取过期时间
     */
    public function getExpiretimeAttr($value, $data)
    {
        return date('Y-m-d',$value);
    }

    /**
     * 获取会员的组别
     */
    public function getGroupAttr($value, $data)
    {
        return UserGroup::get($data['group_id']);
    }

    /**
     * 获取验证字段数组值
     * @param   string    $value
     * @param   array     $data
     * @return  object
     */
    public function getVerificationAttr($value, $data)
    {
        $value = array_filter((array) json_decode($value, TRUE));
        $value = array_merge(['email' => 0, 'mobile' => 0], $value);
        return (object) $value;
    }

    /**
     * 设置验证字段
     * @param mixed $value
     * @return string
     */
    public function setVerificationAttr($value)
    {
        $value = is_object($value) || is_array($value) ? json_encode($value) : $value;
        return $value;
    }

    /**
     * 变更会员余额
     * @param int $money    余额
     * @param int $user_id  会员ID
     * @param string $memo  备注
     */
    public static function money($money, $user_id, $memo)
    {
        $user = self::get($user_id);
        if ($user)
        {
            $before = $user->money;
            $after = $user->money + $money;
            //更新会员信息
            $user->save(['money' => $after]);
            //写入日志
            MoneyLog::create(['user_id' => $user_id, 'money' => $money, 'before' => $before, 'after' => $after, 'memo' => $memo]);
        }
    }

    /**
     * 变更会员积分
     * @param int $score    积分
     * @param int $user_id  会员ID
     * @param string $memo  备注
     */
    public static function score($score, $user_id, $memo)
    {
        $user = self::get($user_id);
        if ($user)
        {
            $before = $user->score;
            $after = $user->score + $score;
            $level = self::nextlevel($after);
            //更新会员信息
            $user->save(['score' => $after, 'level' => $level]);
            //写入日志
            ScoreLog::create(['user_id' => $user_id, 'score' => $score, 'before' => $before, 'after' => $after, 'memo' => $memo]);
        }
    }

    /**
     * 根据积分获取等级
     * @param int $score 积分
     * @return int
     */
    public static function nextlevel($score = 0)
    {
        $lv = array(1 => 0, 2 => 30, 3 => 100, 4 => 500, 5 => 1000, 6 => 2000, 7 => 3000, 8 => 5000, 9 => 8000, 10 => 10000);
        $level = 1;
        foreach ($lv as $key => $value)
        {
            if ($score >= $value)
            {
                $level = $key;
            }
        }
        return $level;
    }

}
