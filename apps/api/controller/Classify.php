<?php
namespace app\api\controller;

class Classify extends Common {
	public function get() {
		$data = $this->params;
		$res = db($data['type'])->select();
		if ($res !== false) {
			$this->return_msg(200, '查询分类成功', $res);
		} else {
			$this->return_msg(400, '查询分类失败');

		}
	}
	public function add() {
		$data = $this->params;
		$type = $data['type'];
		unset($data['type']);
		$data[$type . '_name'] = $data['name'];
		$res = db($type)->insert($data);
		if (!$res) {
			$this->return_msg(400, '添加分类失败');
		} else {
			$this->return_msg(200, '添加分类成功', $data);
		}
	}
	public function edit() {
		$data = $this->params;
		$type = $data['type'];
		$params[$type . '_id'] = $data['id'];
		$params[$type . '_name'] = $data['name'];
		$res = db($type)->where($type . '_id', $params[$type . '_id'])->setField($params);
		if ($res !== false) {
			$this->return_msg(200, '修改分类成功', $params);
		} else {
			$this->return_msg(400, '修改分类失败');
		}
	}
	public function delete() {
		$data = $this->params;
		$res = db($data['type'])->where($data['type'] . '_id', $data['id'])->delete();
		if (!$res) {
			$this->return_msg(400, '删除分类失败');
		} else {
			$this->return_msg(200, '删除分类成功', $data);
		}
	}
}