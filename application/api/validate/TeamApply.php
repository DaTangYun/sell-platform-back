<?php
// +----------------------------------------------------------------------
// | DTCms
// +----------------------------------------------------------------------
// | 版权所有 2017-2019 西安大唐云信息科技有限公司 [ http://www.rungyun.com ]
// +----------------------------------------------------------------------
// | 技术博客: http://www.rungyun.com
// +----------------------------------------------------------------------


namespace app\api\validate;


use think\Validate;

class TeamApply extends Validate
{
    /**
     * 验证规则
     */
    protected $rule = [
        'team_id'       => 'require|integer',
        'name'          => 'require|max:20',
        'mobile'        => 'require|length:11',
        'excellence'    =>'require'
    ];

    /**
     * 提示消息
     */
    protected $message = [
        'team_id.require' => '没有团队',
        'team_id.integer' => '团队id必须为整数',
        'name.require' => '用户名必须',
        'mobile.require' => '手机号必须',
        'mobile.length' => '手机号长度必须为十一位',
        'mobile.excellence' => '擅长内容必须',
    ];

    /**
     * 字段描述
     */
    protected $field = [
    ];

    /**
     * 验证场景
     */
    protected $scene = [

    ];
}