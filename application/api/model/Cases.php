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
    public function getAllCases($page,$limit,$search,$user_id,$flag=false)
    {
        //查询条件
        $map = 'status = 2';
        if ($search){
           $map = "stauts = 2 and title like %{$search}%";
        }

        return $this->field(['id','cover','title'])
            ->where($map)
            ->page($page,$limit)
            ->order(['weigh'=>'desc','id'=>'desc'])
            ->select();
    }

    /**
     * 案例与解读总数
     * @return int|string
     * @throws \think\Exception
     */
    public function getCasesTotal()
    {
        return $this->where(['status'=>'1'])->count('id');
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