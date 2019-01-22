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
use app\api\model\CloudIntelligence as CloudIntelligenceModel;


class CloudIntelligence extends Api
{
    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];

    public function lists()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
       if ($this->request->isGet()){}
        //分页的页数和每页显示的数据数
        $page = $this->request->get('page/d',1);
        $limit = $this->request->get('limit/d',16);
        //云智慧数据
        $cloud = (new CloudIntelligenceModel)->getCloudAll($page,$limit);
        $this->success('获取云智慧数据成功',compact('cloud'));
    }
}