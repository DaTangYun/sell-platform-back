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

class Topline extends Model
{
    protected $autoWriteTimestamp = true;

    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $hidden = [
        'weigh',
        'seo_titile',
        'seo_keyword',
        'seo_desc',
        'updatetime'
    ];
    /**
     * 图片获取器
     * @param $value
     * @return string
     */
    public function getCoverAttr($value)
    {
        return Request::instance()->domain().$value;
    }
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
     * 描述获取器
     * @param $value
     * @return false|string
     */
    public function getDescAttr($value)
    {

        if (mb_strlen($value,'utf-8') > 120) {
            return mb_substr($value,0,120,"utf-8").'...';
        }
        return $value;
    }

    /**
     * 关联分类模型
     * @return \think\model\relation\BelongsTo
     */
    public function cate()
    {
        return $this->belongsTo('ToplineCate','topline_cate_id')->bind('cate_name');
    }
    /**
     * 获取列表
     */
    public function getAll($page = 1, $limit = 5, $cateId = 0, $title = '', $userId = 0, $flag = false)
    {
        //过滤筛选
        $map = [];
        $cateId > 0 && $map['topline_cate_id'] = $cateId;
        !empty($title) && $map['title'] = ['like', '%' . trim($title) . '%'];
        $userId > 0 && $map['user_id'] = $userId;
        $flag && $map['status'] = '2';
        //定义显示字段
        $field = ['id','title','topline_cate_id','cover','desc','reading_count','createtime'];
        return self::with(['cate'])->field($field)->where($map)->order('weigh desc,id desc')->page($page)->limit($limit)->select();
    }
    /**
     * 获取总数
     */
    public function getTotal($cateId = 0, $title = '',$userId = 0,$flag = false)
    {
        //过滤筛选
        $map = [];
        $cateId > 0 && $map['topline_cate_id'] = $cateId;
        !empty($title) && $map['title'] = ['like', '%' . trim($title) . '%'];
        $userId > 0 && $map['user_id'] = $userId;
        $flag && $map['status'] = '2';
        return self::where($map)->order('weigh desc,id desc')->count();
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