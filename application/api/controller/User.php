<?php

namespace app\api\controller;

use app\common\controller\Api;
use app\common\library\Ems;
use app\common\library\Sms;
use fast\Random;
use think\Validate;
use app\api\model\User as UserModel;

/**
 * 会员接口
 */
class User extends Api
{

    protected $noNeedLogin = ['login', 'mobilelogin', 'register', 'resetpwd', 'changeemail', 'third','showme'];
    protected $noNeedRight = '*';

    public function _initialize()
    {
        parent::_initialize();
    }

    /**
     * 会员中心
     */
    public function info()
    {
        if ($this->request->isGet()){
            $info = $this->auth->getUser();
            $this->success('获取成功',compact('info'));
        }
    }

    /**
     * 会员登录
     * 
     * @param string $account 账号
     * @param string $password 密码
     */
    public function login()
    {
        if ($this->request->isPost()) {
            $mobile = $this->request->request('mobile');
            $password = $this->request->request('password');
            if (!$mobile || !$password)
            {
                $this->error(__('Invalid parameters'));
            }
            $ret = $this->auth->login($mobile, $password);
            if ($ret)
            {
                $data = ['userinfo' => $this->auth->getUserinfo()];
                $this->success(__('Logged in successful'), $data);
            }else
            {
                $this->error($this->auth->getError());
            }
        }
    }

    /**
     * 手机验证码登录
     * 
     * @param string $mobile 手机号
     * @param string $captcha 验证码
     */
    public function mobilelogin()
    {
        $mobile = $this->request->request('mobile');
        $captcha = $this->request->request('captcha');
        if (!$mobile || !$captcha)
        {
            $this->error(__('Invalid parameters'));
        }
        if (!Validate::regex($mobile, "^1\d{10}$"))
        {
            $this->error(__('Mobile is incorrect'));
        }
        if (!Sms::check($mobile, $captcha, 'mobilelogin'))
        {
            $this->error(__('Captcha is incorrect'));
        }
        $user = \app\common\model\User::getByMobile($mobile);
        if ($user){
            //如果已经有账号则直接登录
            $ret = $this->auth->direct($user->id);
        }else{
            $ret = $this->auth->register($mobile, Random::alnum(), '', $mobile, []);
        }
        if ($ret){
            Sms::flush($mobile, 'mobilelogin');
            $data = ['userinfo' => $this->auth->getUserinfo()];
            $this->success(__('Logged in successful'), $data);
        }else{
            $this->error($this->auth->getError());
        }
    }

    /**
     * 注册会员
     * 
     * @param string $username 用户名
     * @param string $password 密码
     * @param string $email 邮箱
     * @param string $mobile 手机号
     */
    public function register()
    {
        if ($this->request->isPost()) {
            $password = $this->request->request('password');
            $repassword = $this->request->request('repassword');
            $email = '';
            $username = $mobile = $this->request->request('mobile');
            //验证码
            $captcha = $this->request->request('captcha');
            if (!$username || !$password || !$repassword){
                $this->error(__('Invalid parameters'));
            }
            if ($mobile && !Validate::regex($mobile, "^1\d{10}$")){
                $this->error(__('Mobile is incorrect'));
            }
            if($password !== $repassword){
                $this->error('两次密码不一致!');
            }
            if ($captcha && !Validate::regex($captcha, "^\d{4}$")) {
                $this->error('验证码格式错误');
            }
            //验证验证码是否错误
            if (!Sms::check($mobile, $captcha, 'register')){
                $this->error(__('Captcha is incorrect'));
            }
            $ret = $this->auth->register($username, $password, $email, $mobile, []);
            if ($ret){
                $data = ['userinfo' => $this->auth->getUserinfo()];
                $this->success(__('Sign up successful'), $data);
            }else{
                $this->error($this->auth->getError());
            }
        }
    }

    /**
     * 注销登录
     */
    public function logout()
    {
        if($this->request->isPost()){
            $this->auth->logout();
            $this->success(__('Logout successful'));
        }
    }

    

    /**
     * 修改邮箱
     * 
     * @param string $email 邮箱
     * @param string $captcha 验证码
     */
    public function changeemail()
    {
        $user = $this->auth->getUser();
        $email = $this->request->post('email');
        $captcha = $this->request->request('captcha');
        if (!$email || !$captcha)
        {
            $this->error(__('Invalid parameters'));
        }
        if (!Validate::is($email, "email"))
        {
            $this->error(__('Email is incorrect'));
        }
        if (\app\common\model\User::where('email', $email)->where('id', '<>', $user->id)->find())
        {
            $this->error(__('Email already exists'));
        }
        $result = Ems::check($email, $captcha, 'changeemail');
        if (!$result)
        {
            $this->error(__('Captcha is incorrect'));
        }
        $verification = $user->verification;
        $verification->email = 1;
        $user->verification = $verification;
        $user->email = $email;
        $user->save();

        Ems::flush($email, 'changeemail');
        $this->success();
    }

    /**
     * 修改手机号
     * 
     * @param string $email 手机号
     * @param string $captcha 验证码
     */
    public function changemobile()
    {
        if ($this->request->isPost()){
            $user = $this->auth->getUser();
            $mobile = $this->request->request('mobile');
            $captcha = $this->request->request('captcha');
            if ($mobile == $user->mobile) {
                $this->error('修改手机号与原手机相同');
            }   
            if (!$mobile || !$captcha){
                $this->error(__('Invalid parameters'));
            }
            if (!Validate::regex($mobile, "^1\d{10}$")){
                $this->error(__('Mobile is incorrect'));
            }
            if (\app\common\model\User::where('mobile', $mobile)->where('id', '<>', $user->id)->find()){
                $this->error(__('Mobile already exists'));
            }
            $result = Sms::check($mobile, $captcha, 'changemobile');
            if (!$result){
                // $this->error(__('Captcha is incorrect'));
            }
            $verification = $user->verification;
            $verification->mobile = 1;
            $user->verification = $verification;
            $user->mobile = $mobile;
            $user->username = $mobile;
            $user->save();
            Sms::flush($mobile, 'changemobile');
            $this->success('修改成功,下次登录有效');
        }
    }

    /**
     * 第三方登录
     * 
     * @param string $platform 平台名称
     * @param string $code Code码
     */
    public function third()
    {
        $url = url('user/index');
        $platform = $this->request->request("platform");
        $code = $this->request->request("code");
        $config = get_addon_config('third');
        if (!$config || !isset($config[$platform]))
        {
            $this->error(__('Invalid parameters'));
        }
        $app = new \addons\third\library\Application($config);
        //通过code换access_token和绑定会员
        $result = $app->{$platform}->getUserInfo(['code' => $code]);
        if ($result)
        {
            $loginret = \addons\third\library\Service::connect($platform, $result);
            if ($loginret)
            {
                $data = [
                    'userinfo'  => $this->auth->getUserinfo(),
                    'thirdinfo' => $result
                ];
                $this->success(__('Logged in successful'), $data);
            }
        }
        $this->error(__('Operation failed'), $url);
    }

    /**
     * 重置密码
     * 
     * @param string $mobile 手机号
     * @param string $newpassword 新密码
     * @param string $captcha 验证码
     */
    public function resetpwd()
    {
        $mobile = $this->request->request("mobile");
        $password = $this->request->request("password");
        $captcha = $this->request->request("captcha");
        if (!$password || !$captcha){
            $this->error(__('Invalid parameters'));
        }
        if (!Validate::regex($mobile, "^1\d{10}$")){
            $this->error(__('Mobile is incorrect'));
        }
        $user = \app\common\model\User::getByMobile($mobile);
        if (!$user){
            $this->error(__('User not found'));
        }
        $ret = Sms::check($mobile, $captcha, 'resetpwd');
        if (!$ret){
            $this->error(__('Captcha is incorrect'));
        }
        Sms::flush($mobile, 'resetpwd');
        //模拟一次登录
        $this->auth->direct($user->id);
        $ret = $this->auth->changepwd($password, '', true);
        if ($ret){
            $this->success(__('Reset password successful'));
        } else {
            $this->error($this->auth->getError());
        }
    }

    /**
     * 用户的秀秀我列表
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function showme()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isGet()){
            //接收分页页数和每页的数据
            $page = $this->request->get('page/d',1);
            $limit = $this->request->get('limit/d',10);
            //显示秀秀我的数据
            $showme = (new UserModel)->getAllUser($page,$limit);
            $this->success('获取秀秀我数据成功',compact('showme'));
        }
    }

    /**
     * 用户认证
     * 
     * @param string $avatar 头像地址
     * @param string $username 用户名
     * @param string $nickname 昵称
     * @param string $bio 个人简介
     */
    public function identy()
    {
        if($this->request->isPost()){
            $user     = $this->auth->getUser();
            $avatar   = input('post.avatar/s','');
            $nickname = input('post.nickname/s','');
            $type = input('post.type/d',0);
            $identyImages = input('post.identy_images/s','');
            $expiretime  = strtotime(input('post.expiretime/s',''));
            $province = input('post.province/s','');
            $city     = input('post.city/s','');
            $area     = input('post.area/s','');
            $bio      = input('post.bio/s','');
            if (!$avatar || !$nickname || !$identyImages|| !$expiretime|| !$province|| !$city|| !$area|| !$bio) {
                $this->error('参数错误');
            }
            //保存数据
            $user->avatar = $avatar;
            $user->nickname = $nickname;
            $user->type = $type;
            $user->identy_images = $identyImages;
            $user->expiretime = $expiretime;
            $user->province = $province;
            $user->city = $city;
            $user->area = $area;
            $user->bio = $bio;

            //改变成认证中
            $user->is_identy = '1';
            $user->save();
            $this->success('修改成功');
        }
    }
    /**
     * 修改密码
     */
    public function changePass()
    {
        if($this->request->isPost()){
            $oldpassword = input("post.oldpassword/s",'');
            $password = input("post.password/s",'');
            $repassword = input("post.repassword/s",'');
            if (!$oldpassword || !$password || !$repassword){
                $this->error(__('Invalid parameters'));
            }
            if (strlen($password) < 6) {
                $this->error(__('新密码长度不能少于6位'));
            }
            if ($password !== $repassword) {
                $this->error(__('两次新密码不相同'));
            }
            $user = $this->auth->getUser();
            if ($user->password !== md5(md5($oldpassword).$user->salt)) {
                $this->error(__('原密码不正确'));
            }
            $ret = $this->auth->changepwd($repassword, '', true);
            if ($ret){
                $this->success(__('Reset password successful'));
            }else{
                $this->error($this->auth->getError());
            }
        }
    }
}
