<?php
namespace app\api\controller;

class Report extends Common {
	public function add() {
		$data = $this->params;
		$data['report_uid'] = $this->login_uid();
		$data['report_time'] = time();
		$is_reported = db('report')->where('report_uid=' . $data['report_uid'] . ' AND report_type=' . $data['report_type'] . ' AND report_gid=' . $data['report_gid'])->find();
		if ($is_reported) {
			$this->return_msg(200, '您已经举报过了');
		} else {
			$res = db('report')->insert($data);
			if (!$res) {
				$this->return_msg(400, '举报失败');
			} else {
				$this->return_msg(200, '举报成功', $res);
			}
		}

	}
}