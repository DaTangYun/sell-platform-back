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
use app\api\model\TeamApply as TeamApplyModel;
use app\api\validate\TeamApply as TeamApplyValidate;

/**
 * 团队申请控制器
 * Class TeamApply
 * @package app\api\controller
 */
class TeamApply extends Api
{
    protected $noNeedLogin = [''];
    protected $noNeedRight = [''];
    /**
     * 当前模型对象
     * @var \app\api\model\
     */
    protected $model = null;

    /**
     * 控制器初始化
     */
    public function _initialize()
    {
        parent::_initialize();
        $this->model = new TeamApplyModel;

    }

    /**
     * 查询个人中的团队成员
     * @param $id
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function lists($id)
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isGet()) {
            $page = $this->request->get('page/d', 1);
            $limit = $this->request->get('limit/d', 10);
            $member = $this->model->getMember($page, $limit, $id);
            $this->success('获取成功', compact('member'));
        }
    }

    /**
     * 查询团队审核通过的成员
     * @param $id
     */
    public function apply($id)
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isGet()) {
            $member = $this->model->getTeamMember($id);
            $this->success('获取成功', compact('member'));
        }
    }
    /**
     * 添加案例
     * @return \think\response\Json
     */
    public function add()
    {
        //判断用户是否登录，登录后才可以添加

        if ($this->request->isPost()) {
            //数据库字段 网页字段转换
            $params = [
                'team_id' => 'team_id',
                'name' => 'name',
                'mobile' => 'mobile',
                'excellence' => 'excellence',
                'desc' => 'desc',

            ];
            $param_data = $this->buildParam($params);
            //数据验证
            $validate = new TeamApplyValidate;
            if (!$validate->check($param_data)) {
                $this->error($validate->getError());
            }
            //当前用户id
            $user = $this->auth->getUser();
            $param_data['user_id'] = $user->id;
            //实例化案例模型
            try {
                $result = $this->model->allowField(true)->save($param_data);
                if ($result !== false) {
                    return json(['code' => 1, 'msg' => '添加成功','data'=>[]]);
                } else {
                    return json(['code' => 0, 'msg' => '添加失败','data'=>[]]);
                }
            } catch (\think\exception\PDOException $e) {
                return json(['code' => 0, 'msg' => $e->getMessage(),'data'=>[]]);
            }  catch (\Exception $e) {
                return json(['code' => 0, 'msg' => $e->getMessage(),'data'=>[]]);
            }

        }
    }

    /**
     * 修改案例
     * @param null $id
     * @return \think\response\Json
     */
    public function edit($id = null)
    {
        //判断修改案例的id是否存在
        $row = $this->model->get($id);
        if (!$row) $this->error('没有查找到数据');
        if ($this->request->isPost()) {
            //数据库字段 网页字段转换
            $params = [
                'team_id' => 'team_id',
                'name' => 'name',
                'mobile' => 'mobile',
                'excellence' => 'excellence',
                'desc' => 'desc',

            ];
            $param_data = $this->buildParam($params);
            //数据验证
            $validate = new TeamApplyValidate;
            if (!$validate->check($param_data)) {
                $this->error($validate->getError());
            }
            //当前用户id
            $user = $this->auth->getUser();
            $user_id = $user->id;
            //只允许添加该案例的修改
            if ($row->user_id != $user_id) $this->error('很抱歉，你没有操作权限');
            //实例化案例模型
            try {
                $result = $row->allowField(true)->save($param_data);
                if ($result !== false) {
                    return json(['code' => 1, 'msg' => '修改成功','data'=>[]]);
                } else {
                    return json(['code' => 0, 'msg' => '修改失败','data'=>[]]);
                }
            } catch (\think\exception\PDOException $e) {
                return json(['code' => 0, 'msg' => $e->getMessage(),'data'=>[]]);
            } catch (\Exception $e) {
                return json(['code' => 0, 'msg' => $e->getMessage(),'data'=>[]]);
            }
        }
        $this->success('获取数据成',compact('row'));
    }

    /**
     * 删除案例
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
            }  catch (\think\exception\PDOException $e) {
                return json(['code' => 0, 'msg' => $e->getMessage(),'data'=>[]]);
            } catch (\Exception $e) {
                return json(['code' => 0, 'msg' => $e->getMessage(),'data'=>[]]);
            }

        }
    }

    /**
     * 用户详情
     * @param $id
     */
    public function detail($id)
    {
        if ($this->request->isGet()){
            $detail = $this->model->get($id);
            $this->success('获取成功',compact('detail'));
        }
    }
    /**
     * 团队成员审核
     * @return \think\response\Json
     */
    public function examine()
    {
        if ($this->request->isPost()) {
            //数据库字段 网页字段转换
            $id = $this->request->post('id/d', 0);
            $status = $this->request->post('status/d', 1);
            try {
                if ((new TeamApplyModel())->examineMember($id, $status)) {
                    return json(['code' => 1, 'msg' => '审核成功', 'data' => []]);
                } else {
                    return json(['code' => 0, 'msg' => '审核失败', 'data' => []]);
                }
            } catch (\Exception $e) {
                return json(['code' => 0, 'msg' => $e->getMessage(), 'data' => []]);
            }
        }
    }
}