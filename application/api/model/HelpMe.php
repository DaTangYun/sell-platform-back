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
use think\Request;

/**
 * 帮帮我模型
 * Class HelpMe
 * @package app\api\model
 */
class HelpMe extends Model
{
    protected $autoWriteTimestamp = true;

    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';

    /**
     * 图片修改器
     * @param $value
     * @return string
     */
    public function setImageAttr($value)
    {
        $ret = preg_match('/\/uploads\/[\w*\d\/.]*/',$value,$arr);
        if ($ret) {
            $value = $arr[0];
        }
        return $value;
    }

    /**
     * 开始时间修改器
     * @param $value
     * @return false|string
     */
    public function setStartTimeAttr($value)
    {
        return strtotime($value);
    }
    /**
     * 结束时间修改器
     * @param $value
     * @return false|string
     */
    public function setEndTimeAttr($value)
    {
        return strtotime($value);
    }

    /**
     * 时间获取器
     * @param $value
     * @return false|string
     */
    public function getCreateTimeAttr($value)
    {
        return date('Y-m-d H:i:s',$value);
    }

    /**
     * 开始时间获取器
     * @param $value
     * @return false|string
     */
    public function getStartTimeAttr($value)
    {
        return date('Y-m-d',$value);
    }
    /**
     * 结束时间获取器
     * @param $value
     * @return false|string
     */
    public function getEndTimeAttr($value)
    {
        return date('Y-m-d',$value);
    }
    /**
     * 关联分类模型
     * @return \think\model\relation\BelongsTo
     */
    public function cate()
    {
        return $this->belongsTo('HelpMeCate','cate_id')->bind(['cate_name'=>'title']);
    }
    /**
     * 图片获取器
     * @param $value
     * @return string
     */
    public function getImageAttr($value)
    {
        return Request::instance()->domain().$value;
    }
    /**
     * 查询所有的能帮会干
     * @param      $page
     * @param      $limit
     * @param      $title
     * @param bool $flag
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getAllHelp($page,$limit,$title,$flag = false,$user_id = 0)
    {
        //条件筛选
        $map = [];
        $title && $map['title'] = ['like', '%' . trim($title) . '%'];
        $flag  && $map['status'] = '2';
        $user_id > 0 && $map['user_id'] = $user_id;
        return self::field(['content'],true)->where($map)->order('weigh desc,id desc')->page($page,$limit)->select();
    }

    /**
     * 获取总数
     * @param      $title
     * @param bool $flag
     * @param int  $user_id
     * @return int|string
     * @throws \think\Exception
     */
    public function getTotal($title,$flag = false,$user_id = 0)
    {
        //条件筛选
        $map = [];
        $title && $map['title'] = ['like', '%' . trim($title) . '%'];
        $flag  && $map['status'] = '2';
        $user_id > 0 && $map['user_id'] = $user_id;
        return self::where($map)->order(['id'=>'desc'])->count();
    }

    /**
     * 帮帮我详情
     * @param $id
     * @return Ability|null
     * @throws \think\exception\DbException
     */
    public function getDetail($id)
    {
        return self::get($id,['cate']);
    }
}