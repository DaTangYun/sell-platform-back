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

class Topline extends Model
{
    protected $autoWriteTimestamp = true;

    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';

    public function getCreatetimeAttr($value)
    {
        return date('Y-m-d',$value);
    }
    /**
     * 获取列表
     */
    public function getAll($page = 1, $limit = 5, $cateId = 0, $title = '', $userId = 0, $status)
    {
        $query = self::order('weigh desc,id desc');
        if($cateId)
            $query->where('topline_cate_id',$cateId);
        if(!empty($title))
            $query->where('title','like', $title . '%');
        if($status)
            $query->where('status','2');
        return $query->page($page)->limit($limit)->select();
    }
    /**
     * 获取总数
     */
    public function getTotal($cateId = 0, $title = '',$userId = 0,$status)
    {
        $query = self::order('weigh desc');
        if($cateId)
            $query->where('topline_cate_id',$cateId);
        if(!empty($title))
            $query->where('title','like', $title . '%');
        if($userId)
            $query->where('user_id',$userId);
        if($status)
            $query->where('status','2');
        return $query->count();
    }

    /**
     * 添加修改
     */
    public function add($data,$userId)
    {
        if (!empty($data['id'])) {
            $id = $data['id'];
            unset($data['id']);
            return self::where(['id'=>$id,'user_id'=>$userId])->save($data);
        } else {
            $data['user_id'] = $userId;
            return self::save($data);
        }
    }

    /**
     * 获取详情
     */
    public function edit($id,$userId)
    {   
        $where = ['id'=> $id];
        if ($userId) {
            $where['user_id'] = $userId;
        }
        $data = self::where($where)->find();
        $data->setInc('reading_count');
        return $data;
    }

    /**
     * 删除
     */
    public function del($id,$userId)
    {
        return self::where(['id'=>$id,'user_id'=>$userId])->delete();
    }
}