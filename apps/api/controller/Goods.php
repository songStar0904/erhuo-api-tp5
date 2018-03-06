<?php
namespace app\api\controller;
use think\db;

class Goods extends Common {
	public function add() {
		$data = $this->params;
		$data['goods_time'] = time();
		$res = db('goods')->insertGetId($data);
		if (!$res) {
			$this . return_msg(400, '添加商品失败');
		} else {
			$icon_data['gIcon_gid'] = $res;
			foreach ($data['goods_icon'] as $key => $value) {
				$icon_data['gIcon_url'] = $value['url'];
				db('gicon')->insert($icon_data);
			}
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
		$join = [['erhuo_user u', 'u.user_id = g.goods_uid'], ['erhuo_gclassify c', 'c.gclassify_id = g.goods_cid']];
		$field = 'goods_id, goods_name, goods_status, goods_nprice, goods_oprice, goods_summary, goods_time, goods_view, gclassify_id, gclassify_name, user_id, user_name, user_icon';
		$has_search = !isset($data['search']) ? 0 : 1;
		$_db = db('goods')->alias('g')->join($join)->field($field);
		$params = [];
		if (isset($data['uid'])) {
			$params['goods_uid'] = $data['uid'];
		}
		// 等于0代表全部
		if (isset($data['cid']) && $data['cid'] !== '0') {
			$params['goods_cid'] = $data['cid'];
		}
		switch ($has_search) {
		case 0:
			$res = $_db->page($data['page'], $data['num'])->where($params)->select();
			$total = $_db->where($params)->count();
			break;
		case 1:
			$res = $_db->where('goods_name', 'like', '%' . $data['search'] . '%')->where($params)->page($data['page'], $data['num'])->select();
			$total = $_db->where('goods_name', 'like', '%' . $data['search'] . '%')->where($params)->count();
			break;
		}
		if ($res !== false) {
			$res = $this->arrange_data($res, 'gclassify');
			$res = $this->arrange_data($res, 'user');
			foreach ($res as $key => $value) {
				// 获得图片
				$icon = db('gicon')->where('gIcon_gid', $value['goods_id'])->field('gIcon_url')->select();
				foreach ($icon as $k => $val) {
					$res[$key]['goods_icon'][$k]['url'] = $val['gIcon_url'];
				}
				// 收藏 留言
			}
			$this->return_msg(200, '查询商品成功', $res, $total);
		} else {
			$this->return_msg(400, '查询商品失败', $res);
		}
	}
	public function get_one() {
		$data = $this->params;
		// 浏览数+1
		$view = db('goods')->where('goods_id', $data['goods_id'])->setInc('goods_view');
		$join = [['erhuo_user u', 'u.user_id = g.goods_uid'], ['erhuo_gclassify c', 'c.gclassify_id = g.goods_cid']];
		$field = 'goods_id, goods_name, goods_status, goods_nprice, goods_oprice, goods_summary, goods_time, goods_view, gclassify_id, gclassify_name, user_id, user_name, user_icon';
		$res = db('goods')->alias('g')->join($join)->field($field)->where('goods_id', $data['goods_id'])->select();
		if ($res) {
			$res = $res[0];
			$res['goods_detail'] = htmlspecialchars_decode($res['goods_detail']);
			// 这里还要整理数据
			$res = $this->arrange_data($res, 'user');
			$res = $this->arrange_data($res, 'gclassify');
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
	public function upload() {
		$data = $this->params;
		$img_path = $this->upload_file($data['goods_icon'], 'goods_img');
		if ($img_path) {
			$this->return_msg(200, '头像上传成功', $img_path);
		} else {
			$this->return_msg(400, '头像上传失败');
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
	public function get_hot() {
		$data = $this->params;
		$res = db('search')->order('search_num desc')->limit($data['num'])->select();
		if ($res) {
			$this->return_msg(200, '查询热搜成功', $res);
		} else {
			$this->return_msg(400, '查询热搜失败', $res);
		}
	}
}