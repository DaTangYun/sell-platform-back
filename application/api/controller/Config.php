<?php

namespace app\api\controller;

use app\common\controller\Api;
use think\Db;

/**
 * 网站配置接口
 */
class Config extends Api
{

    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];

    /**
     * 网站页面配置
     * 
     */
    public function site()
    {
    	if($this->request->isGet()){
            $site = [
                'header_logo'   => $this->request->domain().config('site.header_logo'),
                'footer_logo'   => $this->request->domain().config('site.footer_logo'),
                'logo'          => $this->request->domain().config('site.logo'),
                'beian'         => config('site.beian'),
                'beianhao'      => config('site.beianhao'),
                'company_code'  => $this->request->domain().config('site.company_code'),
                'person_code'      => $this->request->domain().config('site.person_code'),
                'mobile'         => config('site.mobile'),
                'phone'      => config('site.phone'),
                'banquan'       => config('site.banquan')
            ];
    		$this->success('请求成功',compact('site'));
    	}
    }

    /**
     * 网站公共seo
     */
    public function seo()
    {
        if($this->request->isPost()){
            //数据库字段 网页字段转换
            $params = [
                'scene'=> 'scene',
                'id'   => 'id'
            ];
            $param_data = $this->buildParam($params);
            $seo = $this->getSeo($param_data['scene'],$param_data['id']);
    		$this->success('请求成功',compact('seo'));
        }
    }

    private function getSeo($scene,$id = 0)
    {
        $field = 'seo_title,seo_keyword,seo_desc';
        $seo = [
            'seo_title'    => config('site.seo_title'),
            'seo_keyword'  => config('site.seo_keyword'),
            'seo_desc'     => config('site.seo_desc')
        ];
        switch ($scene) {
            //首页
            case 'home':
                break;
            //登录
            case 'login':
                $seo['seo_title']  = '登录·'.$seo['seo_title'];
                break;

            //注册
            case 'register':
                $seo['seo_title']  = '注册·'.$seo['seo_title'];
                break;

            //认证
            case 'ident':
                $seo['seo_title']  = '用户认证·'.$seo['seo_title'];
                break;
            //信息
            case 'message':
                $seo['seo_title']  = '信息·'.$seo['seo_title'];
                break;
            //信息详情
            case 'messageDetail':
                if (!empty($id)) {
                    $detail = Db::name('Message')->where('id',$id)->field($field)->find();
                }
                break;
            //头条
            case 'topline':
                $seo['seo_title']  = '头条·'.$seo['seo_title'];
                break;
            //头条详情
            case 'toplineDetail':
                if (!empty($id)) {
                    $detail = Db::name('Topline')->where('id',$id)->field($field)->find();
                }
                break;
            

            //云智慧
            case 'cloud':
                $seo['seo_title']  = '云智慧·'.$seo['seo_title'];
                break;

            //智慧库
            case 'libra':
                $seo['seo_title']  = '智慧库·'.$seo['seo_title'];
                break;
            //财经法规详情
            case 'financeDetail':
                if (!empty($id)) {
                    $detail = Db::name('Finance')->where('id',$id)->field($field)->find();
                }
                break;
            //案例详情
            case 'casesDetail':
                if (!empty($id)) {
                    $detail = Db::name('Cases')->where('id',$id)->field($field)->find();
                }
                break;

            //秀秀我
            case 'show':
                $seo['seo_title']  = '秀秀我·'.$seo['seo_title'];
                break;
            //秀秀我详情
            case 'showDetail':
                if (!empty($id)) {
                    $nickname = Db::name('User')->where('id',$id)->value('nickname');
                    if (!empty($nickname))
                        $seo['seo_title']  = $nickname.'·秀秀我·'.$seo['seo_title'];
                }
                break;

            //能帮会干
            case 'ability':
                $seo['seo_title']  = '能帮会干·'.$seo['seo_title'];
                break;
            //能帮会干详情
            case 'abilityDetail':
                if (!empty($id)) {
                    $detail = Db::name('Ability')->where('id',$id)->field($field)->find();
                }
                break;

            //帮帮我
            case 'helpme':
                $seo['seo_title']  = '帮帮我·'.$seo['seo_title'];
                break;
            //帮帮我详情
            case 'helpmeDetail':
                if (!empty($id)) {
                    $detail = Db::name('HelpMe')->where('id',$id)->field($field)->find();
                }
                break;

            //优惠活动
            case 'active':
                $seo['seo_title']  = '优惠活动·'.$seo['seo_title'];
                break;
            
            //个人信息
            case 'profile':
                if (!empty($id)) {
                    $nickname = Db::name('User')->where('id',$id)->value('nickname');
                    if (!empty($nickname))
                        $seo['seo_title']  = $nickname.'·个人中心·'.$seo['seo_title'];
                }
                break;

            
            case 'page':
                //联系我们 id = 1
                //服务条款 id = 2
                if (!empty($id)) {
                    $detail = Db::name('Page')->where('id',$id)->field($field)->find();
                }
                break;
            default:
                # code...
                break;
        }
        if (!empty($detail)) {
            //分别进行seo判断   没有默认为公共
            if (!empty($detail['seo_title'])) {
                $seo['seo_title'] = $detail['seo_title'];
            }

            if (!empty($detail['seo_keyword'])) {
                $seo['seo_keyword'] = $detail['seo_keyword'];
            }

            if (!empty($detail['seo_desc'])) {
                $seo['seo_desc'] = $detail['seo_desc'];
            }
        }
        return $seo;
    }
}