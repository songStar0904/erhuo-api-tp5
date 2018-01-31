<?php
namespace app\api\controller;
use dysms\Sms;

class Code extends Common {
	public function get_code() {
		$username = $this->params['username'];
		$exist = $this->params['is_exist'];
		$username_type = $this->check_username($username);
		$this->get_code_by_username($username, $username_type, $exist);

	}
	// 获取验证码
	public function get_code_by_username($username, $type, $exist) {
		if ($type == 'phone') {
			$type_name = '手机';
		} else {
			$type_name = '邮箱';
		}
		// 检测username是否存在
		$this->check_exist($username, $type, $exist);
		// 检测请求频率 60s
		if (session('?' . $username . '_last_send_time')) {
			if (time() - session($username . '_last_send_time') < 60) {
				$this->return_msg(400, $type_name . '验证码，每60秒只能发送一次');
			}
		}
		// 生成验证码 6位
		$code = $this->make_code(6);
		// 试用session存储验证码，md5加密
		$md5_code = md5($username . '_' . md5($code));
		session($username . '_code' . $md5_code);
		// 使用session存储验证码发送时间
		session($username . '_last_send_time' . time());
		// 发送验证码
		if ($type == 'phone') {
			$this->send_code_to_phone($username, $code);
		} else {
			$this->send_code_to_email($username, $code);
		}
	}
	// 生成验证码
	public function make_code($num) {
		$max = pow(10, $num) - 1;
		$min = pow(10, $num - 1);
		return rand($min, $max);
	}
	public function send_code_to_phone($phone, $code) {
		set_time_limit(60);
		header('Content-Type: text/plain; charset=utf-8');
		$response = Sms::sendSms($phone, $code);
		if ($response->Code !== 'OK') {
			$this->return_msg(400, $response->Message);
		}
	}
	// 发送验证码 给邮箱
	public function send_code_to_email($email, $code) {
		$value['body'] = '这是测试邮件，您的验证码是' . $code . '，邮箱时间1分钟';
		$value['subject'] = '您有新的验证码!';
		$value['return_msg'] = '验证码已发送成功，请注意查收！';
		$this->send_email($email, $value);
	}
}