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
	public function pass_goods() {
		$data = $this->params;
		$res = db('goods')->where('goods_id', $data['goods_id'])->setField('goods_status', $data['goods_status']);
		if ($res !== false) {
			$this->return_msg(200, '审核商品成功', intval($data['goods_status']));
		} else {
			$this->return_msg(400, '审核商品失败');
		}
	}
	public function send_notice() {
		$data = $this->params;
		//$uid = $this->login_uid();
		$data['notice_uid'] = 1;
		if (!isset($data['notice_time'])) {
			$data['notice_time'] = time();
		}
		$res = db('notice')->insertGetId($data);
		if ($res) {
			$this->return_msg(200, '发送公告成功', $res);
		} else {
			$this->return_msg(400, '发送公告失败');
		}
	}
}