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
use app\api\model\Ability as AbilityModel;
use app\api\validate\Ability as AbilityValidate;
use app\api\model\AbilityCate as AbilityCateModel;

/**
 * 帮帮我控制器
 * Class Ability
 * @package app\api\controller
 */
class Ability extends Api
{
    protected $noNeedLogin = ['lists','detail'];
    protected $noNeedRight = ['lists','detail'];
    /**
     * 当前模型对象
     * @var \app\api\model\
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new AbilityModel;

    }
    /**
     *帮帮我列表
     */
    public function lists()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isGet()) {
            //接收分页页数和每页显示的数据
            $page = $this->request->get('page/d', 1);
            $limit = $this->request->get('limit/d', 6);
            $title = $this->request->get('title', false);
            $user_id = $this->request->get('user_id/d',0);
            $cate_id = $this->request->get('cate_id', 0);
            $ability = $this->model->getAllAbility($page, $limit, $title,$cate_id,true,$user_id);
            $total = $this->model->getTotal($title,$cate_id,true,$user_id);
            $this->success('获取数据成功', compact('ability', 'total'));
        }
    }

    /**
     * 帮帮我详情
     * @param $id
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public function detail($id)
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isGet()) {
            $detail = $this->model->getDetail($id);
            $this->success('获取数据成功', compact('detail'));
        }
    }

    /**
     *个人中心帮帮列表
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
            $title = $this->request->get('title', false);
            $cate_id = $this->request->get('cate_id', 0);
            $user = $this->auth->getUser();
            $user_id = $user->id;
            $cases = $this->model->getAllAbility($page, $limit, $title, $cate_id,true,$user_id);
            $total = $this->model->getTotal($title,$cate_id,true, $user_id);
            $this->success('获取数据成功', compact('cases', 'total'));
        }
    }

    /**
     * 添加帮助
     * @return \think\response\Json
     */
    public function add()
    {
        //判断用户是否登录，登录后才可以添加

        if ($this->request->isPost()) {
            //数据库字段 网页字段转换
            $params = [
                'title'         => 'title',
                'ability_id'    => 'ability_id',
                'image'         => 'image',
                'desc'          => 'desc',
                'price'         => 'price',
                'mobile'        => 'mobile',
                'content'       => 'content',
                'province'      => 'province',
                'city'          => 'city',
                'area'          => 'area',
                'province_code' => 'province_code',
                'city_code'     => 'city_code',
                'area_code'     => 'area_code'
            ];
            $param_data = $this->buildParam($params);
            //数据验证
            $validate = new AbilityValidate;
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
            } catch (\Exception $e) {
                return json(['code' => 0, 'msg' => $e->getMessage(),'data'=>[]]);
            }

        }
        //显示分类
        $cate = (new AbilityCateModel)->getAbilityCate();
        $this->success('获取分类成功',compact('cate'));
    }

    /**
     * 修改帮帮我
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
                'title'         => 'title',
                'ability_id'    => 'ability_id',
                'image'         => 'image',
                'desc'          => 'desc',
                'price'         => 'price',
                'mobile'        => 'mobile',
                'content'       => 'content',
                'province'      => 'province',
                'city'          => 'city',
                'area'          => 'area',
                'province_code' => 'province_code',
                'city_code'     => 'city_code',
                'area_code'     => 'area_code',
            ];
            $param_data = $this->buildParam($params);
            //数据验证
            $validate = new AbilityValidate;
            if (!$validate->check($param_data)) {
                $this->error($validate->getError());
            }
            $param_data['seo_title'] = $param_data['title'];
            $param_data['seo_keyword'] = $param_data['title'];
            $param_data['seo_desc'] = $param_data['desc'];
            //当前用户id
            $user = $this->auth->getUser();
            $user_id = $user->id;
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
        //显示分类
        $cate = (new AbilityCateModel)->getAbilityCate();
        $this->success('获取数据成',compact('row','cate'));
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