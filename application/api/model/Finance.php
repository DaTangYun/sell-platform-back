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
    public function getFinance($page,$limit,$title,$cate_id)
    {
        //构建查询
        $query = self::with(['cate'])->field(['id','cate_id','createtime','title'])->where(['status'=>'1']);
        //检测是否有搜索内容
        if ($title) $query->where('title','like', '%'.$title.'%');
        //检测是否有分类id
        if ($cate_id > 0) $query->where(['cate_id'=>$cate_id]);
        return $query->page($page,$limit)
            ->order(['weigh'=>'desc','id'=>'desc'])
            ->select();
    }

    /**
     * 财经法规的总记录数
     * @param $id
     * @return int|string
     * @throws \think\Exception
     */
    public function getFinanceTotal($title,$cate_id)
    {
        //构建查询
        $query = self::field(['id'])->where(['status'=>'1']);
        //检测是否有搜索内容
        if ($title) $query->where('title','like', '%'.$title.'%');
        //检测是否有分类id
        if ($cate_id > 0) $query->where(['cate_id'=>$cate_id]);
        return $query->count('id');
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