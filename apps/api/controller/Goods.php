<?php
namespace app\api\controller;
class Goods extends Common {
	public function add() {
		$data = $this->params;
		$data['goods_time'] = time();
		$res = db('goods')->insert($data);
		if (!$res) {
			$this . return_msg(400, '添加商品失败');
		} else {
			$this->return_msg(200, '添加商品成功', $data);
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
		if (isset($data['user_id'])) {

		}
	}
	public function get_one() {
		$data = $this->params;
		// 浏览数+1
		$view = db('goods')->where('goods_id', $data['goods_id'])->setInc('goods_view');
		$join = [['erhuo_user u', 'u.user_id = g.goods_uid'], ['erhuo_gclassify c', 'c.gclassify_id = g.goods_cid']];
		$res = db('goods')->alias('g')->join($join)->where('goods_id', $data['goods_id'])->select();
		if ($res) {
			$res = $res[0];
			$res['goods_detail'] = htmlspecialchars_decode($res['goods_detail']);
			// 这里还要整理数据
			$res['goods_lmsg'] = $this->get_lmsg($data['goods_id'], 'goods');
			// 记录浏览记录
			$uid = session('user_id');
			if ($uid) {
				$this->record($uid, $data['goods_id']);
			}
			$this->return_msg(200, '查询商品成功', $res);
		} else {
			$this->return_msg(400, '查询商品失败', $res);
		}
	}
	public function edit() {
		$data = $this->params;
		$res = db('goods')->where('goods_id', $data['goods_id'])
			->update($data);
		if ($res !== false) {
			$this->return_msg(200, '修改商品成功', $res);
		} else {
			$this->return_msg(400, '修改商品失败', $res);
		}
	}
	public function delete() {
		$data = $this->params;
		$res = db('goods')->where('goods_id', $data['goods_id'])
			->delete();
		if ($res) {
			$this->return_msg(200, '删除商品成功', $res);
		} else {
			$this->return_msg(400, '删除商品失败', $res);
		}
	}
	public function follow() {
		$data = $this->params;
		$this->common_follow($data['user_id'], $data['goods_id'], 'goods');
	}
}