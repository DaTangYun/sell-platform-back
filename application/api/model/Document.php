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
    protected $autoWriteTimestamp = true;

    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $hidden = [
        'weigh',
        'updatetime'
    ];
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
    public function getUrlAttr($value)
    {
        return Request::instance()->domain().$value;
    }

    /**
     * 关联用户模型
     * @return \think\model\relation\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('User','user_id')->bind('username');
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
    public function getAllDocument($page,$limit,$title,$cate_id,$user_id,$flag = false)
    {
        //构建查询
        $query = self::with(['cate','user'])->field(['id','title','cate_id','user_id','url','createtime']);
        //判断是否有搜索条件
        $query->where('title','like', '%'.$title.'%');
        //判断文档分类
        if ($cate_id)
            $query->where(['cate_id'=>$cate_id]);
        //判断用户id
        if($user_id > 0) $query->where(['user_id'=>$user_id]);
        //判断案例状态
        if ($flag) $query->where(['status'=>'2']);
        return $query->page($page,$limit)
            ->order(['weigh'=>'desc','id'=>'desc'])
            ->select();

    }

    /**
     * 查询总页数
     * @param      $search
     * @param      $user_id
     * @param bool $flag
     * @return int|string
     * @throws \think\Exception
     */
    public function getTotal($search,$cate_id,$user_id,$flag=true)
    {
        //构建查询
        $query = self::field(['id']);
        //判断是否有搜索条件
        if ($search) $query->whereLike('title',$search);
        //判断文档分类
        if ($cate_id)
            $query->where(['cate_id'=>$cate_id]);
        //判断用户id
        if($user_id > 0) $query->where(['user_id'=>$user_id]);
        //判断案例状态
        if ($flag) $query->where(['status'=>'2']);
        return $query->count('id');
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