<?php
// +----------------------------------------------------------------------
// | DTCms
// +----------------------------------------------------------------------
// | 版权所有 2017-2019 西安大唐云信息科技有限公司 [ http://www.rungyun.com ]
// +----------------------------------------------------------------------
// | 技术博客: http://www.rungyun.com
// +----------------------------------------------------------------------


namespace app\api\controller;


use app\api\model\TeamApply;
use app\common\controller\Api;
use app\api\model\Team as TeamModel;
use app\api\validate\Team as TeamValidate;
use app\api\model\TeamApply as TeamApplyModel;
use think\Db;

/**
 * 团队控制器
 * Class Team
 * @package app\api\controller
 */
class Team extends Api
{
    protected $model = null;
    protected $noNeedLogin = ['lists','detail'];
    protected $noNeedRight = ['lists','detail'];
    /**
     * 控制器初始化
     */
    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stubd
        $this->model = new TeamModel;
    }

    /**
     * 团队列表
     */
    public function lists()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isGet()){
            $page = $this->request->get('page',1);
            $limit = $this->request->get('limit',10);
            $userId = $this->request->get('user_id',0);
            $team = $this->model->getLists($page,$limit,$userId);
            $total = $this->model->getTotal($userId);
            $this->success('获取团队列表成功',compact('team','total'));
        }
    }
    /**
     * 个人中心团队列表
     */
    public function profile()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isGet()){
            $page = $this->request->get('page',1);
            $limit = $this->request->get('limit',10);
            //当前用户id
            $user = $this->auth->getUser();
            $userId = $user->id;
            $team = $this->model->getLists($page,$limit,$userId);
            $total = $this->model->getTotal($userId);
            $this->success('获取团队列表成功',compact('team','total'));
        }
    }
    /**
     * 添加团队
     * @return \think\response\Json
     */
    public function add()
    {
        if ($this->request->isPost()){
            //数据库字段 网页字段转换
            $params = [
                'team_name' => 'team_name/s',
                'image' => 'image/s',
                'content' => 'content',

            ];
            $param_data = $this->buildParam($params);
            //数据验证
            $validate = new TeamValidate;
            if (!$validate->check($param_data)) {
                $this->error($validate->getError());
            }
            //当前用户id
            $user = $this->auth->getUser();
            $param_data['user_id'] = $user->id;
            //实例化案例模型
            try {
                $result = $this->model->save($param_data);
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
     * 修改团队
     * @param null $id
     * @return \think\response\Json
     */
    public function edit($id = null)
    {
        //判断修改案例的id是否存在
        $row = $this->model->get($id);
        if (!$row) $this->error('没有查找到数据');
        if ($this->request->isPost()) {
            $params = [
                'team_name' => 'team_name',
                'image' => 'image',
                'content' => 'content',

            ];
            $param_data = $this->buildParam($params);
            //数据验证
            $validate = new TeamValidate;
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
                $result = $row->save($param_data);
                if ($result !== false) {
                    return json(['code' => 1, 'msg' => '修改成功','data'=>[]]);
                } else {
                    return json(['code' => 0, 'msg' => '修改失败','data'=>[]]);
                }
            } catch (\Exception $e) {
                return json(['code' => 0, 'msg' => $e->getMessage(),'data'=>[]]);
            }
        }
        $this->success('获取数据成',compact('row'));
    }
    /**
     * 删除团队
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
                // 启动事务
                Db::startTrans();
                try {
                    $result = $row->delete();
                    (new TeamApplyModel)->del($row->id);
                    // 提交事务
                    Db::commit();
                    return json(['code' => 1, 'msg' => '删除成功','data'=>[]]);
                } catch (\Exception $e) {
                    // 回滚事务
                    Db::rollback();
                    return json(['code' => 0, 'msg' => '删除失败','data'=>[]]);
                }
            } catch (\Exception $e) {
                return json(['code' => 0, 'msg' => $e->getMessage(),'data'=>[]]);
            }

        }
    }

    /**
     * 团队详情
     * @param $id
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function detail($id)
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isGet()){
            $detail = $this->model->getDetail($id);
            $member = (new TeamApplyModel)->getTeamMember($id);
           if ($member) collection($member)->hidden(['mobile','user_id']);
            $this->success('获取成功',compact('detail','member'));
        }
    }

    /**
     * 查询团队成员
     * @param $id
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function member($id)
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isGet()){
            $page = $this->request->get('page',1);
            $limit = $this->request->get('limit',10);
            $member = (new TeamApplyModel)->getMember($page,$limit,$id);
            $this->success('获取成功',compact('member'));
        }
    }


}