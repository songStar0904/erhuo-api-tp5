<?php
namespace app\api\controller;
use think\db;

class User extends Common {
	public function login() {
		$data = $this->params;
		dump($data);
	}
	public function register() {
		$data = $this->params;
		$this->check_code($data['user_name'], $data['code']);
		$user_name_type = $this->check_username($data['user_name']);
		switch ($user_name_type) {
		case 'phone':
			$data['user_phone'] = $data['user_name'];
			break;
		case 'email':
			$data['user_email'] = $data['user_name'];
			break;
		}
		$data['user_rtime'] = time(); // 注册时间
		dump($data);
		$res = db('user')->insert($data);
		if (!$res) {
			$this->return_msg(400, '用户注册失败');
		} else {
			$this->return_msg(200, '用户注册成功', $res);
		}
	}
}
