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

class Document extends Model
{
    /**
     * 时间获取器
     * @param $value
     * @return false|string
     */
    public function getCreatetimeAttr($value)
    {
        return date('Y-m-d',$value);
    }

    /**
     * 关联文档分类模型
     * @return \think\model\relation\BelongsTo
     */
    public function cate()
    {
        return $this->belongsTo('DocumentCate','cate_id')->bind('name');
    }

    /**
     * url获取器
     * @param Request $request
     * @param         $value
     * @return string
     */
    public function getUrlAttr(Request $request,$value)
    {
        return $this->request->domon . $value;
    }

    /**
     * 关联用户模型
     * @return \think\model\relation\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('User')->bind('username');
    }

    /**
     * 文档模型数据
     * @param $page
     * @param $limit
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getAllDocument($page,$limit)
    {
        return $this->field(['id','title','cate_id','createtime'])
            ->with(['cate','user'])
            ->where(['status'=>'1'])
            ->page($page,$limit)
            ->order(['weigh'=>'desc','id'=>'desc'])
            ->select();
    }
    /**
     * 获取文档模型详情
     * @param $id
     * @return Document|null
     * @throws \think\exception\DbException
     */
    public function getDetail($id)
    {
        return self::get($id);
    }

}