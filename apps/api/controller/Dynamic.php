<?php
namespace app\api\controller;
use think\db;

class Dynamic extends Common {
	public function get() {
		$data = $this->params;
		if (!isset($data['page'])) {
			$data['page'] = 1;
		}
		if (!isset($data['num'])) {
			$data['num'] = 5;
		}
		$uid = session('user_id');
		$join = [['erhuo_user u', 'u.user_id = d.dynamic_uid']];
		$field = 'dynamic_id, dynamic_content, dynamic_time, dynamic_time, dynamic_type, dynamic_gid, dynamic_share, user_id, user_name, user_icon';
		$_db = db('dynamic')->alias('d')->join($join)->field($field)->order('dynamic_time desc')->page($data['page'], $data['num']);
		$res = $_db->select();
		// ->chunk(100, function ($item) {
		// 	foreach ($item as $key => $value) {
		// 		$item[$key]['praise_num'] = db('praise')->where('praise_type=1 AND praise_mid=' . $value['dynamic_id'])->count();
		// 		$item[$key]['is_praise'] = db('praise')->where('praise_type=1 AND praise_mid=' . $value['dynamic_id'] . 'AND praise_uid=' . $uid)->find();
		// 	}
		// })
		$total = $_db->count();
		if ($res !== false) {
			$res = $this->arrange_data($res, 'user');
			foreach ($res as $key => $value) {
				// 获得图片
				// $icon = db('gicon')->where('gIcon_gid', $value['goods_id'])->field('gIcon_url')->select();
				// foreach ($icon as $k => $val) {
				// 	$res[$key]['goods_icon'][$k]['url'] = $val['gIcon_url'];
				// }
				$res[$key]['praise_num'] = db('praise')->where('praise_type=1 AND praise_mid=' . $value['dynamic_id'])->count();
				$res[$key]['is_praise'] = db('praise')->where('praise_type=1 AND praise_mid=' . $value['dynamic_id'])->where('praise_uid', $uid)->find() ? true : false;
				$res[$key]['comment_num'] = db('lmsg')->where('lmsg_type=1 AND lmsg_gid=' . $value['dynamic_id'])->count();
				// 商品动态
				if ($res[$key]['dynamic_type'] == 2 && $res[$key]['dynamic_gid'] !== 0) {
					$res[$key]['goods'] = $this->get_one_by_dynamic($res[$key]['dynamic_gid']);
				}
			}
			$this->return_msg(200, '查询动态成功', $res, $total);
		} else {
			$this->return_msg(400, '查询动态失败', $res);
		}
	}
	public function add() {
		$data = $this->params;
		$uid = $this->login_uid();
		$params['dynamic_time'] = time();
		$params['dynamic_content'] = $data['content'];
		$params['dynamic_uid'] = $uid;
		$params['dynamic_type'] = $data['type'];
		$res = db('dynamic')->insert($params);
		if (!$res) {
			$this->return_msg(400, '发布动态失败');
		} else {
			$this->return_msg(200, '发布动态成功', $res);
		}
	}
}