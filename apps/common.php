<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
namespace app\api\controller;
use think\Controller;
use think\Request;
use think\Validate;

class Common extends Controller {
	protected $request;
	protected $validate;
	protected $params; // 过滤符合要求的参数
	protected $rules = array(
		'User' => array(
			'login' => array(
				'user_name' => ['require', 'chsDash', 'max' => 20],
				'user_psd' => ['require', 'length' => 32],
			),
		));
	protected function _initialize() {
		parent::_initialize();
		$this->request = Request::instance();
		// $this->check_time($this->request->only(['time']));
		// $this->check_token($this->request->param());
		$this->params = $this->check_params($this->request->except(['time', 'token']));
	}
	// 返回信息
	public function return_msg($code, $msg = '', $data = []) {
		$return_data['code'] = $code;
		$return_data['msg'] = $msg;
		$return_data['data'] = $data;
		echo json_encode($return_data);die;
	}
	// 验证时间
	public function check_time($arr) {
		if (!isset($arr['time']) || intval($arr['time']) <= 1) {
			$this->return_msg(400, '时间戳不正确');
		}
		if (time() - intval(($arr['time']) > 60)) {
			$this->return_msg(400, '请求超时');
		}
	}
	// 验证token(防止黑客篡改数据)
	public function check_token($arr) {
		if (!isset($arr['token']) || empty($arr['token'])) {
			$this->return_msg(400, 'token不能为空');
		}
		$app_token = $arr['token']; // api传过来的token
		unset($arr['token']); // 去除token
		$service_token = '';
		foreach ($arr as $key => $value) {
			$service_token .= md5($value);
		}
		$service_token = md5('apo_' . $service_token . '_api');
		if ($app_token !== $service_token) {
			$this->return_msg(400, 'token值不正确');
		}
	}
	public function check_params($arr) {
		$rule = $this->rules[$this->request->controller()][$this->request->action()];
		$this->validate = new Validate($rule);
		if (!$this->validate->check($arr)) {
			$this->return_msg(400, $this->validate->getError());
		}
		// 通过验证
		return $arr;
	}
}