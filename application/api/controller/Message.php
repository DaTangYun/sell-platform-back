<?php

namespace app\api\controller;

use app\common\controller\Api;
use app\api\model\Message as MessageModel;
use app\api\model\MessageCate as MessageCateModel;
use app\api\validate\Message as MessageValidate;

/**
 * 头条接口
 */
class Message extends Api
{

    protected $noNeedLogin = ['lists','detail'];
    protected $noNeedRight = ['*'];
    /**
     * 当前模型对象
     * @var \app\api\model\
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new MessageModel;

    }

    /**
     * 无权限列表
     */
    public function lists()
    {
        if($this->request->isGet()){
            $cateId = input('get.cate_id/d',0);
            $page = input('get.page/d',1);
            $limit = input('get.limit/d',5);
            $title = input('get.title/s','');
            $userId = input('get.user_id/d',0);
            $message = $this->model->getAll($page,$limit,$cateId,$title,$userId,true);
            $total = $this->model->getTotal($cateId,$title,$userId,true);
            $this->success('获取成功',compact('message','total'));
        }
    }

    /**
     * 无权限详情页
     */
    public function detail()
    {
        if($this->request->isGet()){
            $id = input('get.id/d',0);
            $detail = $this->model->getDetail($id);
            $this->success('获取成功',compact('detail'));
        }
    }

    /**
     * 个人中心列表
     */
    public function profile()
    {
        if($this->request->isGet()){
            $cateId = 0;
            $page = input('get.page/d',1);
            $limit = input('get.limit/d',5);
            $title = '';
            $user = $this->auth->getUser();
            $userId = $user->id;
            //显示所有的
            $status = false;
            $message = $this->model->getAll($page, $limit, $cateId, $title, $userId, $status);
            $total = $this->model->getTotal($cateId,$title,$userId,$status);
            $this->success('获取成功',compact('message','total'));
        }
    }

    /**
     * 添加信息
     * @return \think\response\Json
     */
    public function add()
    {
        //判断用户是否登录，登录后才可以添加
        if ($this->request->isPost()) {
            //数据库字段 网页字段转换
            $params = [
                'title'             => 'title',
                'message_cate_id'   => 'message_cate_id',
                'cover'             => 'cover',
                'content'           => 'content',
                'mobile'            => 'mobile',
                'province'          => 'province',
                'city'              => 'city',
                'area'              => 'area',
            ];
            $param_data = $this->buildParam($params);
            //数据验证
            $validate = new MessageValidate;
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
        //显示分类
        $cate = (new MessageCateModel)->getAll();
        $this->success('获取文档分类成功',compact('cate'));
    }
    /**
     * 修改头条
     * @param null $id
     * @return \think\response\Json
     */
    public function edit($id = null)
    {
        //判断修改案例的id是否存在
        $row = $this->model->get($id);
        if (!$row) $this->success('暂无数据');
        if ($this->request->isPost()) {
            //数据库字段 网页字段转换
            $params = [
                'title'             => 'title',
                'message_cate_id'   => 'message_cate_id',
                'cover'             => 'cover',
                'content'           => 'content',
                'mobile'            => 'mobile',
                'province'          => 'province',
                'city'              => 'city',
                'area'              => 'area',

            ];
            $param_data = $this->buildParam($params);
            //数据验证
            $validate = new MessageValidate;
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
            } catch (\Exception $e) {
                return json(['code' => 0, 'msg' => $e->getMessage(),'data'=>[]]);
            }
        }
        //显示分类
        $cate = (new MessageCateModel)->getAll();
        $this->success('获取数据成功',compact('row','cate'));
    }

    /**
     * 删除头条
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
