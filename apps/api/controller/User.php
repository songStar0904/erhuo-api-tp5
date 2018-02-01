<?php
namespace app\api\controller;
use think\db;

class User extends Common {
	public function login() {
		$data = $this->params;
		$user_name_type = $this->check_username($data['user_name']);
		$this->check_exist($data['user_name'], $user_name_type, 1);
		$db_res = db('user')
			->where('user_' . $user_name_type, '=', $data['user_name'])
			->where('user_psd', '=', $data['user_psd'])
			->find();
		if (!$db_res) {
			$this->return_msg(400, '密码不正确');
		} else {
			unset($db_res['user_psd']);
			$this->return_msg(200, '登录成功', $db_res);
		}
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
	public function upload() {
		$data = $this->params;
		$img_path = $this->upload_file($data['user_icon'], 'head_img');
		$res = db('user')->where('user_id', $data['user_id'])
			->setField('user_icon', $img_path);
		if ($res) {
			$this->return_msg(200, '头像上传成功', $img_path);
		} else {
			$this->return_msg(400, '头像上传失败');
		}
	}
	public function change_psd() {
		$data = $this->params;
		$user_name_type = $this->check_username($data['user_name']);
		$this->check_exist($data['user_name'], $user_name_type, 1);
		$db_old_psd = db('user')->where('user_' . $user_name_type, $data['user_name'])->value('user_psd');
		if ($db_old_psd !== $data['user_old_psd']) {
			$this->return_msg(400, '原密码错误');
		}
		$res = db('user')->where('user_' . $user_name_type, $data['user_name'])->setField('user_psd', $data['user_psd']);
		if ($res !== false) {
			$this->return_msg(200, '密码修改成功');
		} else {
			$this->return_msg(400, '密码修改失败');
		}
	}
	public function find_psd() {
		$data = $this->params;
		$user_name_type = $this->check_username($data['user_name']);
		$this->check_exist($data['user_name'], $user_name_type, 1);
		$this->check_code($data['user_name'], $data['code']);
		$res = db('user')->where('user_' . $user_name_type, $data['user_name'])->setField('user_psd', $data['user_psd']);
		if ($res !== false) {
			$this->return_msg(200, '密码修改成功');
		} else {
			$this->return_msg(400, '密码修改失败');
		}
	}
	public function bind_username() {
		$data = $this->params;
		$user_name_type = $this->check_username($data['user_name']);
		if ($user_name_type == 'phone') {
			$name_type = '手机';
		} else {
			$name_type = '邮箱';
		}
		$this->check_code($data['user_name'], $data['code']);
		$res = db('user')->where('user_id', $data['user_id'])->setField('user_' . $user_name_type, $data['user_name']);
		if ($res !== false) {
			$this->return_msg(200, $name_type . '绑定成功');
		} else {
			$this->return_msg(400, $name_type . '绑定失败');
		}
	}
}
