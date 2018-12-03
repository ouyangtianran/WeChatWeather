<?php
namespace app\index\controller;

use think\Controller;

class Register extends Controller
{
  public function index()
  {
    return $this->fetch();
  }
  
   // 处理注册逻辑
public function doRegister()
{
	$param = input('post.');
	if(empty($param['user_name'])){
		$this->error('用户名不能为空');
	}
	if(empty($param['user_pwd'])){
		$this->error('密码不能为空');
	}
  
	// 验证用户名
	$has = db('users')->where('user_name', $param['user_name'])->find();
	if(!empty($has)){
		$this->error('用户名已存在');
	}
	
	// 记录用户注册信息
  $data = [		//接受传递的参数
				'user_name' => $param['user_name'],
				'user_pwd' => md5($param['user_pwd']),
			];

  
	
   Db('users') -> insert($data);
  
	$this->redirect(url('login/index'));
}
}