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
 * 能帮会干验证器
 * Class HelpMe
 * @package app\api\validate
 */
class HelpMe extends Validate
{
    /**
     * 验证规则
     */
    protected $rule = [
        'title'         => 'require',
        'cate_id'       => 'require',
        'image'         => 'require',
        'desc'          => 'require',
        'mobile'        => 'require|length:11',
        'province'      => 'require',
        'city'          => 'require',
        'area'          => 'require',
        'commission'    => 'require',
        'start_time'    => 'require',
        'end_time'      => 'require',
        'contact'       => 'require',
        'content'       => 'require'
        
    ];

    /**
     * 提示消息
     */
    protected $message = [
        'title.require'     => '标题必须',
        'cate_id.require'   => '分类必须',
        'image.require'     => '图片必须',
        'desc.require'      => '描述必须',
        'content.require'   => '内容必须',
        'commission.require'=> '佣金必须',
        'mobile.require'    => '手机号必须',
        'mobile.length'     => '手机号错误',
        'province.require'  => '省份必须',
        'city.require'      => '城市必须',
        'area.require'      => '区域必须必须',
        'start_time.require'=> '开始时间必须',
        'end_time.require'  => '结束时间必须',
        'contact.require'   => '联系人必须'
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