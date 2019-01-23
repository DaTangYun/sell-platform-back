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
use app\api\model\Document as DocumentModel;
use app\api\validate\Document as DocumentValidate;
use app\api\model\DocumentCate as DocumentCateModel;

/**
 * 文档控制器
 * Class Document
 * @package app\api\controller
 */
class Document extends Api
{
    protected $noNeedLogin = ['lists', 'detail'];
    protected $noNeedRight = ['*'];
    /**
     * 当前模型对象
     * @var null
     */
    protected $model = null;

    /**
     * 初始化函数
     */
    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        $this->model = new DocumentModel;
    }

    /**
     * 文档列表
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function lists()
    {
        if ($this->request->isGet()){
            //接收分页页数和每页显示的数据
            $page = $this->request->get('page/d',1);
            $limit = $this->request->get('limit/d',10);
            $title = $this->request->get('title', false);
            $user_id = $this->request->get('userId', 0);
            $document = $this->model->getAllDocument($page,$limit,$title,$user_id);
            $total = $this->model->getTotal($title, $user_id);
            $this->success('获取数据成功',compact('document'));
        }
    }

    /**
     * 文档详情
     * @param $id
     * @throws \think\exception\DbException
     */
    public function detail($id)
    {
        if ($this->request->isGet()){
            $detail = $this->model->getDetail($id);
            $this->success('获取数据成功',compact('detail'));
        }
    }
    /**
     *个人中心
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
            $user = $this->auth->getUser();
            $user_id = $user->id;
            $flag = false;
            $cases = $this->model->getAllDocument($page, $limit, $title, $user_id, $flag);
            $total = $this->model->getTotal($title, $user_id,$flag);
            $this->success('获取数据成功', compact('cases', 'total'));
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
                'title' => 'title',
                'cate_id' => 'cate_id',
                'url' => 'url',
                'province' => 'province',
                'city' => 'city',
                'area' => 'area',

            ];
            $param_data = $this->buildParam($params);
            //数据验证
            $validate = new DocumentValidate;
            if (!$validate->check($param_data)) {
                $this->error($validate->getError());
            }
            //当前用户id
            //$user = $this->auth->getUser();
            //$param_data['user_id'] = $user->id;
            $param_data['user_id'] = 1;
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
        //显示分类
        $cate = (new DocumentCateModel)->getDocumentCate();
        $this->success('获取文档分类成功','cate');
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
                'title' => 'title',
                'cate_id' => 'cate_id',
                'url' => 'url',
                'province' => 'province',
                'city' => 'city',
                'area' => 'area',

            ];
            $param_data = $this->buildParam($params);
            //数据验证
            $validate = new DocumentValidate;
            if (!$validate->check($param_data)) {
                $this->error($validate->getError());
            }
            //$user = $this->auth->getUser();
            //只允许添加该案例的修改
            if ($row->user_id != 1) $this->error('很抱歉，你没有操作权限');
            //实例化案例模型
            try {
                $result = $row->allowField(true)->save($param_data);
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
        $cate = (new DocumentCateModel)->getDocumentCate();
        $this->success('获取数据成',compact('row','cate'));
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
            //$user = $this->auth->getUser();
            //只允许添加该案例的修改
            if ($row->user_id != 1) $this->error('很抱歉，你没有操作权限');
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