<?php

namespace app\admin\model;

use think\Model;

class Ability extends Model
{
    // 表名
    protected $name = 'ability';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    
    // 追加属性
    protected $append = [
        'status_text'
    ];
    

    
    public function getStatusList()
    {
        return ['2' => __('Status 2'),'1' => __('Status 1'),'0' => __('Status 0')];
    }     


    public function getStatusTextAttr($value, $data)
    {        
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }

    
    public function user()
    {
        return $this->belongsTo('User','user_id')->bind('username');
    }

    public function cate()
    {
        return $this->belongsTo('AbilityCate','ability_id')->bind(['cate_title' => 'title']);
    }


}
