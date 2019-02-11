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
use app\api\validate\Active as ActiveValidate;

/**
 * 优惠券控制器
 * Class Active
 * @package app\api\controller
 */
class Active extends Api
{
    protected $noNeedLogin = ['lists'];
    protected $noNeedRight = ['lists'];
    /**
     * 当前模型对象
     * @var \app\api\model\
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new ActiveModel;

    }
    /**
     *活动列表
     */
    public function lists()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isGet()) {
            //接收分页页数和每页显示的数据
            $page = $this->request->get('page/d', 1);
            $limit = $this->request->get('limit/d', 10);
            $title = $this->request->get('title/s', false);
            $user_id = $this->request->get('user_id/d',0);
            $active = $this->model->getAllActive($page, $limit, $title,$user_id,true);
            $total = $this->model->getTotal($title,$user_id,true);
            $this->success('获取数据成功', compact('active', 'total'));
        }
    }

    /**
     *个人中心活动列表
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
            $active = $this->model->getProfileActive($page, $limit,$user_id);
            $total = $this->model->getTotal('', $user_id,false);
            $this->success('获取数据成功', compact('active', 'total'));
        }
    }

    /**
     * 添加活动
     * @return \think\response\Json
     */
    public function add()
    {
        if ($this->request->isPost()) {
            //数据库字段 网页字段转换
            $params = [
                'title'         => 'title',
                'desc'          => 'desc',
                'coupon_name'   => 'coupon_name',
                'min_amount'    => 'min_amount',
                'prefer_acount' => 'prefer_acount',
                'start_time'    => 'start_time',
                'end_time'      => 'end_time',
            ];
            $param_data = $this->buildParam($params);
            //数据验证
            $validate = new ActiveValidate;
            if (!$validate->check($param_data)) {
                $this->error($validate->getError());
            }
            //当前用户id
            $user = $this->auth->getUser();
            $param_data['user_id'] = $user->id;
            $param_data['start_time'] = strtotime($param_data['start_time']);
            $param_data['end_time']   = strtotime($param_data['end_time']);
            //实例化案例模型
            try {
                $result = $this->model->allowField(true)->save($param_data);
                if ($result !== false) {
                    return json(['code' => 1, 'msg' => '添加成功','data'=>[]]);
                } else {
                    return json(['code' => 0, 'msg' => '添加失败','data'=>[]]);
                }
            } catch (\Exception $e) {
                return json(['code' => 0, 'msg' => $e->getMessage(),'data'=>[]]);
            }

        }
    }

    /**
     * 修改活动
     * @param null $id
     * @return \think\response\Json
     */
    public function edit($id = null)
    {
        //判断修改案例的id是否存在
        $active = $this->model->get($id);
        if (!$active) $this->error('没有查找到数据');
        if ($this->request->isPost()) {
            //数据库字段 网页字段转换
            $params = [
                'title'         => 'title',
                'desc'          => 'desc',
                'coupon_name'   => 'coupon_name',
                'min_amount'    => 'min_amount',
                'prefer_acount' => 'prefer_acount',
                'start_time'    => 'start_time',
                'end_time'      => 'end_time',
            ];
            $param_data = $this->buildParam($params);
            $param_data['start_time'] = strtotime($param_data['start_time']);
            $param_data['end_time']   = strtotime($param_data['end_time']);
            //数据验证
            $validate = new ActiveValidate;
            if (!$validate->check($param_data)) {
                $this->error($validate->getError());
            }
            //当前用户id
            $user = $this->auth->getUser();
            $user_id = $user->id;
            if ($active->user_id != $user_id) $this->error('很抱歉，你没有操作权限');
            //实例化案例模型
            try {
                $result = $active->save($param_data);
                if ($result !== false) {
                    return json(['code' => 1, 'msg' => '修改成功','data'=>[]]);
                } else {
                    return json(['code' => 0, 'msg' => '修改失败','data'=>[]]);
                }
            } catch (\Exception $e) {
                return json(['code' => 0, 'msg' => $e->getMessage(),'data'=>[]]);
            }
        }
        //显示分类
        $this->success('获取数据成',compact('active'));
    }

    /**
     *删除
     * @param null $id
     * @return \think\response\Json
     */
    public function del($id = null)
    {
        //判断修改案例的id是否存在
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