<?php

namespace app\admin\model;

use think\Model;

class Team extends Model
{
    // 表名
    protected $name = 'team';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = false;
    
    // 追加属性
    protected $append = [

    ];
    
    //查找用户
    public function user()
    {
        return $this->belongsTo('User','user_id')->bind('username');
    }

    //统计团队人数总数
    public function teamnum()
    {
        return $this->hasMany('TeamApply');
    }





}
