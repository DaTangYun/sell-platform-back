<?php
// +----------------------------------------------------------------------
// | DTCms
// +----------------------------------------------------------------------
// | 版权所有 2017-2019 西安大唐云信息科技有限公司 [ http://www.rungyun.com ]
// +----------------------------------------------------------------------
// | 技术博客: http://www.rungyun.com
// +----------------------------------------------------------------------


namespace app\api\controller;


use app\common\controller\Api;
use app\api\model\Active as ActiveModel;
use app\api\model\UserActive as UserActiveModel;

/**
 * 个人领取的优惠券控制器
 * Class Active
 * @package app\api\controller
 */
class UserActive extends Api
{
    protected $noNeedLogin = [''];
    protected $noNeedRight = [''];
    /**
     * 当前模型对象
     * @var \app\api\model\
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new UserActiveModel;

    }

    /**
     *个人中心参与优惠券列表(领取的优惠券)
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function profile()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isGet()) {
            //接收分页页数和每页显示的数据
            $page = $this->request->get('page/d', 1);
            $limit = $this->request->get('limit/d', 6);
            $user = $this->auth->getUser();
            $user_id = $user->id;
            $userActive = $this->model->getAllUserActive($page, $limit,$user_id);
            $total = $this->model->getTotal($user_id);
            $this->success('获取数据成功', compact('userActive', 'total'));
        }
    }

    /**
     * 领取活动优惠券
     * @return \think\response\Json
     */
    public function add()
    {
        if ($this->request->isPost()) {
            //传递活动(优惠券)ID
            $active_id = input('post.active_id/d',0);
            //判断优惠券是都存在
            $active = Active::get($active_id);
            if (!$active) $this->error('优惠券不存在');
            //当前用户id
            $user = $this->auth->getUser();
            $param_data = [
                'active_id'  => $active_id,
                'user_id'    => $user->id,
                'title'      => $active->title,
                'desc'       => $active->desc,
                'coupon_name'    => $active->coupon_name,
                'min_amount'     => $active->min_amount,
                'prefer_acount'  => $active->prefer_acount,
                'start_time'     => $active->getData('start_time'),
                'end_time'       => $active->getData('end_time')
            ];
            //参加活动(领取优惠券模型)
            try {
                $result = $this->model->allowField(true)->save($param_data);
                if ($result !== false) {
                    return json(['code' => 1, 'msg' => '领取成功','data'=>[]]);
                } else {
                    return json(['code' => 0, 'msg' => '领取失败','data'=>[]]);
                }
            } catch (\Exception $e) {
                return json(['code' => 0, 'msg' => $e->getMessage(),'data'=>[]]);
            }

        }
        //显示分类
        $cate = (new AbilityCateModel)->getAbilityCate();
        $this->success('获取分类成功',compact('cate'));
    }

    /**
     *删除
     * @param null $id
     * @return \think\response\Json
     */
    public function del($id = null)
    {
        //判断优惠券的id是否存在
        $row = $this->model->get($id);
        if (!$row) $this->error('没有查找到数据');
        if ($this->request->isPost()){
            //当前用户id
            $user = $this->auth->getUser();
            $user_id = $user->id;
            //只允许添加该案例的修改
            if ($row->user_id != $user_id) $this->error('很抱歉，你没有操作权限');
            try {
                $result = $row->delete();
                if ($result !== false) {
                    return json(['code' => 1, 'msg' => '删除成功','data'=>[]]);
                } else {
                    return json(['code' => 0, 'msg' => '删除失败','data'=>[]]);
                }
            } catch (\Exception $e) {
                return json(['code' => 0, 'msg' => $e->getMessage(),'data'=>[]]);
            }

        }
    }
}