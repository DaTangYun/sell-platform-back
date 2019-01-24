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

/**
 * 团队验证器
 * Class Team
 * @package app\api\validate
 */
class Team extends Validate
{
    /**
     * 验证规则
     */
    protected $rule = [
        'team_name'     => 'require',
        'image'    => 'require',
        'content'     => 'require',
    ];

    /**
     * 提示消息
     */
    protected $message = [
        'team_name.require' => '团队名称必须',
        'image.require' => '图片必须',
        'content.require' => '内容必须',
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