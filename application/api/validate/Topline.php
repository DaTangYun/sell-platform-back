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

class Topline extends Validate
{
    /**
     * 验证规则
     */
    protected $rule = [
        'title'         => 'require',
        'topline_cate_id' => 'require',
        'cover'         => 'require',
        'desc'          => 'require',
        'content'       => 'require',
        'province'      => 'require',
        'city'          => 'require',
        'area'          => 'require',
    ];

    /**
     * 提示消息
     */
    protected $message = [
        'title.require' => '标题必须',
        'topline_cate_id.require' => '作者必须',
        'cover.require' => '图片必须',
        'desc.require'  => '描述必须',
        'content.require' => '内容必须',
        'province.require' => '省份必须',
        'city.require' => '城市必须',
        'area.require' => '区域必须必须',
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