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
 * 帮帮我验证器
 * Class Ability
 * @package app\api\validate
 */
class Ability extends Validate
{
    /**
     * 验证规则
     */
    protected $rule = [
        'title'     => 'require',
        'ability_id'    => 'require',
        'image'     => 'require',
        'desc'    => 'require',
        'price'   => 'require',
        'mobile'  => 'require|length:11',
    ];

    /**
     * 提示消息
     */
    protected $message = [
        'title.require' => '标题必须',
        'ability_id.require' => '分类必须',
        'image.require' => '图片必须',
        'desc.require' => '描述必须',
        'content.require' => '内容必须',
        'price.require' => '佣金必须',
        'mobile.require' => '手机号必须',
        'mobile.length' => '手机号错误',
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