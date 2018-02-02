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
}