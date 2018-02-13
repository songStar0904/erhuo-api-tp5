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
			$update = $this->update_login($db_res['user_id']);
			$db_res['user_ltime'] = $update['user_ltime'];
			$db_res['user_ip'] = $update['user_ip'];
			unset($db_res['user_psd']);
			session('user_id', $db_res['user_id']);
			session('user_access', $db_res['user_access']);
			$this->return_msg(200, '登录成功', $db_res);
		}
	}
	public function login_out() {
		session('user_id', null);
		session('user_access', null);
		if (session('user_id')) {
			$this->return_msg(400, '退出登录失败');
		} else {
			$this->return_msg(200, '退出登录成功');
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
		$res = db('user')->insert($data);
		if (!$res) {
			$this->return_msg(400, '用户注册失败');
		} else {
			$this->return_msg(200, '用户注册成功', $res);
		}
	}
	public function upload() {
		$data = $this->params;
		dump($data['user_icon']);
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
	public function edit() {
		$data = $this->params;
		$res = db('user')->where('user_id', $data['user_id'])
			->setField($data);
		if ($res < 0) {
			$this->return_msg(400, '修改个人信息失败');
		} else {
			$this->return_msg(200, '修改个人信息成功', $data);
		}
	}
	public function send_fmsg() {
		$data = $this->params;
		$data['fmsg_time'] = time();
		$res = db('fmsg')->insert($data);
		if (!$res) {
			$this->return_msg(400, '意见反馈失败');
		} else {
			$this->return_msg(200, '意见反馈成功', $data);
		}
	}
	public function send_lmsg() {
		$data = $this->params;
		if (isset($data['lmsg_id'])) {
			$update_status = db('lmsg')->where('lmsg_id', $data['lmsg_id'])->setField('lmsg_status', 1);
			unset($data['lmsg_id']);
		}
		$data['lmsg_time'] = time();
		$res = db('lmsg')->insert($data);
		if (!$res) {
			$this->return_msg(400, '留言失败');
		} else {
			$this->return_msg(200, '留言成功', $data);
		}
	}
	public function get() {
		$data = $this->params;
		if (!isset($data['page'])) {
			$data['page'] = 1;
		}
		if (!isset($data['num'])) {
			$data['num'] = 5;
		}
		if (isset($data['search'])) {
			$res = db('user')->where('user_name|user_phone|user_email', 'like', '%' . $data['search'] . '%')
				->page($data['page'], $data['num'])
				->select();
		} else {
			$res = db('user')
				->page($data['page'], $data['num'])
				->select();
		}
		if ($res == false) {
			$this->return_msg(400, '未找到用户信息', $res);
		} else {
			foreach ($res as $key => $value) {
				unset($res[$key]['user_psd']);
			}
			$total = db('user')->count();
			$this->return_msg(200, '查询用户信息成功', $res, $total);
		}
	}
	public function get_one() {
		$data = $this->params;
		$res = db('user')->where('user_id', $data['user_id'])->select()[0];
		if ($res == false) {
			$this->return_msg(400, '未找到用户信息');
		} else {
			unset($res['user_psd']);
			$fans_num = db('userrship')->where('followers_id', $data['user_id'])->count();
			$followers_num = db('userrship')->field('followers_id')->where('fans_id', $data['user_id'])->count();
			$res['user_rship']['fans_num'] = $fans_num;
			$res['user_rship']['followers_num'] = $followers_num;
			$this->return_msg(200, '查询用户信息成功', $res);
		}
	}
	// 关注与取关
	public function follow() {
		$data = $this->params;
		$this->common_follow($data['user_id'], $data['followers_id'], 'user');
	}
	// 获得 粉丝关注
	public function get_follower() {
		$data = $this->params;
		$field = 'user_id,user_name,user_sid,user_sex,user_icon';
		if ($data['type'] == 'fans') {
			$join_type = 'followers';
		} else {
			$join_type = 'fans';
		}
		if (!isset($data['page'])) {
			$data['page'] = 1;
		}
		if (!isset($data['num'])) {
			$data['num'] = 5;
		}
		$join = [['erhuo_user u', 'u.user_id = s.' . $join_type . '_id']];
		$res = db('userrship')->alias('s')->field($field)
			->join($join)
			->where($data['type'] . '_id', $data['user_id'])
			->page($data['page'], $data['num'])
			->select();
		if (!is_array($res)) {
			$this->return_msg(400, '查找失败');
		} else {
			$this->return_msg(200, '查找成功', $res);
		}
	}
}
