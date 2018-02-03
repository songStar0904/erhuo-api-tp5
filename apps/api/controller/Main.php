<?php
namespace app\api\controller;

class Main extends Common {
	public function get() {
		$gnum = db('goods')->count();
		$unum = db('user')->count();
		$egnum = db('goods')->where('goods_status', 0)->count();
		$main = db('main')->where('main_id', 0)->find();
		$data['main_gnum'] = $gnum;
		$data['main_unum'] = $unum;
		$data['main_egnum'] = $egnum;
		$data['new']['main_gnum'] = $gnum - $main['main_gnum'];
		$data['new']['main_unum'] = $unum - $main['main_unum'];
		$data['new']['main_egnum'] = $egnum - $main['main_egnum'];
		$res = db('main')->where('main_id', 0)->update($data);
		if ($res >= 0) {
			$this->return_msg(200, '统计成功', $data);
		} else {
			$this->return_msg(400, '更新统计失败');
		}
	}
}