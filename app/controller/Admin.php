<?php
namespace app\controller;

use app\BaseController;
use think\facade\Db;
use think\facade\Session;
class Admin extends BaseController
{
	function index()
	{
		$admin=Session::get('admin');
		echo 'hello, '.$admin['admin_name'];
	}
	//后台登录
	function login()
	{
		//判断是否为post提交
		if($this->request->isPost())
		{
			//验证  验证码是否正确
			$admin_captcha=$this->request->param('admin_captcha');
			if(!captcha_check($admin_captcha)){
 				// 验证失败
 				return '验证码错误 请重新登录';die;
			}
			//验证用户是否存在
			$admin_name=$this->request->param('admin_name');
			$res=Db::table('admin')->where('admin_name',$admin_name)->find();
			if(empty($res))
			{
				return '管理员不存在，请核对在登录';die;
			}
			//验证 密码是否正确
			$admin_pwd = $this->request->param('admin_pwd');
			if($res['admin_pwd']!==md5($admin_pwd))
			{
				return '密码错误 ，请核对后在登录';die;
			}
			//修改用户登录时间
			Db::name('admin')->where('id', $res['id'])->update(['lasttime' => date('Y-m-d
			H:i:s')]);
			Session::set('admin',$res);
			return redirect('/admin/index');
		}
		return view();
	}
}