<?php
namespace app\api\controller;

class Message extends Common {
	public function send() {
		$data = $this->params;
		$params['lmsg_sid'] = $this->login_uid();
		switch ($data['type']) {
		case 'goods':
			$params['lmsg_type'] = 0;
			break;
		case 'dynamic':
			$params['lmsg_type'] = 1;
			break;
		}
		$params['lmsg_gid'] = $data['id'];
		$params['lmsg_content'] = $data['content'];
		$params['lmsg_time'] = time();
		$params['lmsg_lid'] = $data['lid'];
		$params['lmsg_rid'] = $data['rid'];
		$res = db('lmsg')->insertGetId($params);
		if (!$res) {
			$this->return_msg(400, '留言失败');
		} else {
			if ($params['lmsg_lid'] !== 0) {
				db('lmsg')->where('lmsg_id', $params['lmsg_lid'])->setField('lmsg_status', 2);
			}
			$this->return_msg(200, '留言成功', $params);
		}
	}
	public function get() {
		$data = $this->params;
		$params['lmsg_rid'] = $this->login_uid();
		switch ($data['type']) {
		case 'goods':
			$params['lmsg_type'] = 0;
			break;
		case 'dynamic':
			$params['lmsg_type'] = 1;
		}
		$params['lmsg_status'] = $data['status'];
		$res = $this->get_msg($data['type'], $params);
		$total = db('lmsg')->where($params)->count();
		if ($res === false) {
			$this->return_msg(400, '查询消息失败');
		} else {
			$this->return_msg(200, '查询消息成功', $res, $total);
		}
	}
	public function get_by_id() {
		$data = $this->params;
		$res = $this->get_lmsg($data['gid'], $data['type']);
		if ($res === false) {
			$this->return_msg(400, '查询消息失败');
		} else {
			$this->return_msg(200, '查询消息成功', $res, count($res));
		}
	}
	public function get_msg($type, $params) {
		if ($type === 'goods') {
			$join = [['erhuo_user s', 's.user_id = l.lmsg_sid'], ['erhuo_user r', 'r.user_id = l.lmsg_rid'], ['erhuo_goods g', 'g.goods_id = l.lmsg_gid']];
			$field = 'lmsg_id, lmsg_lid, lmsg_content, lmsg_status, lmsg_time, r.user_id as ruser_id,r.user_icon as ruser_icon,r.user_name as ruser_name,s.user_id as suser_id, s.user_name as suser_name, s.user_icon as suser_icon, g.goods_id as goods_id, g.goods_name as goods_name';
			$res = db('lmsg')->alias('l')->join($join)->field($field)->where($params)->order('lmsg_time desc')->select();
			$res = $this->arrange_data($res, 'goods');
		} else if ($type === 'dynamic') {
			$join = [['erhuo_user s', 's.user_id = l.lmsg_sid'], ['erhuo_user r', 'r.user_id = l.lmsg_rid'], ['erhuo_dynamic dy', 'dy.dynamic_id = l.lmsg_gid']];
			$field = 'lmsg_id, lmsg_lid, lmsg_content, lmsg_status, lmsg_time, r.user_id as ruser_id,r.user_icon as ruser_icon,r.user_name as ruser_name,s.user_id as suser_id, s.user_name as suser_name, s.user_icon as suser_icon';
			$res = db('lmsg')->alias('l')->join($join)->field($field)->where($params)->order('lmsg_time desc')->select();
		}
		$res = $this->arrange_data($res, 'ruser');
		$res = $this->arrange_data($res, 'suser');
		return $res;
	}
	public function change_status() {
		$data = $this->params;
		$params['lmsg_rid'] = $this->login_uid();
		$params['lmsg_id'] = $data['lmsg_id'];
		switch ($data['type']) {
		case 'goods':
			$res = db('lmsg')->where($params)->setField('lmsg_status', $data['status']);
			break;
		}
		if (!$res) {
			$this->return_msg(400, '更改消息状态失败');
		} else {
			$this->return_msg(200, '更改消息状态成功', $res);
		}
	}
	public function delete() {
		$data = $this->params;
		$params['lmsg_rid'] = $this->login_uid();
		$params['lmsg_id'] = $data['lmsg_id'];
		switch ($data['type']) {
		case 'goods':
			$res = db('lmsg')->where($params)->delete();
			break;
		}
		if (!$res) {
			$this->return_msg(400, '删除消息失败');
		} else {
			$this->return_msg(200, '删除消息成功', $res);
		}
	}
	public function get_notice() {
		$data = $this->params;
		$uid = $this->login_uid();
		$join = [['erhuo_user u', 'u.user_id = n.notice_uid']];
		$field = 'notice_id, notice_title, notice_content, notice_time, user_id, user_name, user_icon';
		$db = db('notice')->alias('n')->join($join)->field($field)->order('notice_time desc');
		if (isset($data['last_time'])) {
			$last_time = db('user')->where('user_id', $uid)->field('user_lntime')->find()['user_lntime'];
<<<<<<< HEAD
			$res = db('notice')->alias('n')->join($join)->field($field)->order('notice_time desc')->where('notice_lid=0 AND notice_time>' . $last_time)->whereOr('notice_lid =' . $uid . 'AND notice_time>' . $last_time)->select();
			dump(db()->getLastSql());
=======
			$res = db('notice')->alias('n')->join($join)->field($field)->order('notice_time desc')->where('notice_lid = 0 OR notice_lid =' . $uid)->where('notice_time > ' . $last_time)->select();
>>>>>>> 推广商品api 下架
		} else {
			$res = $db->where('notice_lid', 0)->whereOr('notice_lid', $uid)->select();
		}
		if ($res === false) {
			$this->return_msg(400, '获得公告失败');
		} else {
			foreach ($res as $key => $value) {
				$res[$key]['notice_content'] = htmlspecialchars_decode($value['notice_content']);
			}
			$res = $this->arrange_data($res, 'user');
			db('user')->where('user_id', $uid)->setField('user_lntime', time());
			$this->return_msg(200, '获得公告成功', $res);
		}
	}
	public function praise() {
		$data = $this->params;
		$uid = $this->login_uid();
		$params['praise_type'] = $data['type'];
		$params['praise_mid'] = $data['mid'];
		$params['praise_uid'] = $uid;
		$is_praised = db('praise')->where($params)->find();
		if ($is_praised) {
			$res = db('praise')->where('praise_id', $is_praised['praise_id'])->delete();
			$msg = '取消点赞';
		} else {
			$params['praise_time'] = time();
			$res = db('praise')->insert($params);
			$msg = '点赞成功';
		}
		if (!$res) {
			$this->return_msg(400, '点赞失败');
		} else {
			$this->return_msg(200, $msg, $res);
		}
	}
}