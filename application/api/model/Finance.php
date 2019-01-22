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

class Finance extends Model
{
    /**
     * 创建时间获取器
     * @param $value
     * @return false|string
     */
    public function getCreatetimeAttr($value)
    {
        return date('Y-m-d',$value);
    }

    /**
     * 关系财经法规模型
     * @return \think\model\relation\BelongsTo
     */
    public function cate()
    {
        return $this->belongsTo('FinanceCate','cate_id')->bind('name');
    }

    /**
     * 获取财经法规
     * @param $page
     * @param $limit
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getFinance($page,$limit)
    {
        return $this->field(['id','cate_id','createtime'])
            ->with(['cate'])
            ->where(['status'=>'1'])
            ->page($page,$limit)
            ->order(['weigh'=>'desc','id'=>'desc'])
            ->select();
    }

    /**
     * 获取财经法规详情
     * @param $id
     * @return Finance|null
     * @throws \think\exception\DbException
     */
    public function getDetail($id)
    {
        $data = self::get($id);
        $data->setInc('reading_count');
        return $data;
    }
}