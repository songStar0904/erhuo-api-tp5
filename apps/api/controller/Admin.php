<?php
namespace app\api\controller;
use think\db;

class Admin extends Common {
	public function get_fmsg() {
		$data = $this->params;
		// $this->check_admin();
		$join = [['erhuo_user u', 'u.user_id = f.fmsg_uid']];
		$field = 'user_id, user_name, user_icon, fmsg_id, fmsg_content, fmsg_status, fmsg_time';
		if (isset($data['fmsg_status'])) {
			$res = db('fmsg')->alias('f')->join($join)->field($field)->where('fmsg_status', $data['fmsg_status'])->select();
		} else {
			$res = db('fmsg')->alias('f')->join($join)->field($field)->select();
		}
		if ($res !== false) {
			$res = $this->arrange_data($res, 'user');
			$res['length'] = count($res);
			$this->return_msg(200, '获取反馈信息成功', $res);
		} else {
			$this->return_msg(400, '获取反馈信息失败');
		}
	}
	public function edit_fmsg() {
		$data = $this->params;
		$res = db('fmsg')->where('fmsg_id', $data['fmsg_id'])
			->update($data);
		if ($res) {
			$this->return_msg(200, '修改反馈信息成功', $res);
		} else {
			$this->return_msg(400, '修改反馈信息失败');
		}
	}
}