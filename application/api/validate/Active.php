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

class Active extends Validate
{
    /**
     * 验证规则
     */
    protected $rule = [
        'title'         => 'require',
        'desc'          => 'require|min:30',
        'coupon_name'   => 'require',
        'min_amount'    => 'require',
        'prefer_acount' => 'require',
        'start_time'    => 'require',
        'end_time'      => 'require',
    ];

    /**
     * 提示消息
     */
    protected $message = [
        'title.require'       => '活动标题不能为空',
        'desc.require'        => '活动描述不能为空',
        'desc.min'            => '活动描述不能少于30字',
        'coupon_name.require' => '优惠券不能为空',
        'min_amount.require'  => '最低金额限度不能为空',
        'prefer_acount.require'=> '优惠金额不能为空',
        'start_time.require' => '开始时间不能为空',
        'end_time.require'   => '结束时间不能为空'
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