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

class Cases extends Model
{
    protected $autoWriteTimestamp = true;

    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
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
     * 案例与解读
     * @param $page
     * @param $limit
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getAllCases($page,$limit,$search,$user_id,$flag=true)
    {
        //构建查询
        $query = self::field(['id','cover','title'])->page($page,$limit)->order(['weigh'=>'desc','id'=>'desc']);
        //判断是否有搜索条件
        $query->where('title','like', '%'.$search.'%');
        //判断用户id
        if($user_id > 0) $query->where(['user_id'=>$user_id]);
        //判断案例状态
        if ($flag) $query->where(['status'=>'2']);
        return $query->select();
    }

    /**
     * 案例与解读总数
     * @return int|string
     * @throws \think\Exception
     */
    public function getCasesTotal($search,$user_id,$flag=true)
    {
        //构建查询
        $query = self::order(['weigh'=>'desc','id'=>'desc']);
        //判断是否有搜索条件
        if ($search) $query->whereLike('title',$search);
        //判断用户id
        if($user_id > 0) $query->where(['user_id'=>$user_id]);
        //判断案例状态
        if ($flag) $query->where(['status'=>'2']);
        return $query->count('id');
    }
    /**
     *
     * @param $id
     * @return Cases|null
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public function getDetail($id)
    {
        $field = ['title','author','cover','source','reading_count','content','seo_title','seo_keyword','seo_desc','createtime'];
        $data = self::field($field)->find($id);
        $data->setInc('reading_count');
        return $data;
    }
}