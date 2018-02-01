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
use phpmailer\PHPMailer;
use think\Controller;
use think\db;
use think\Image;
use think\Request;
use think\Validate;

class Common extends Controller {
	protected $request;
	protected $validate;
	protected $params; // 过滤符合要求的参数
	protected $rules = array(
		'User' => array(
			'login' => array(
				'user_name' => ['require', 'max' => 20],
				'user_psd' => ['require', 'length' => 32],
			),
			'register' => array(
				'user_name' => ['require', 'max' => 20],
				'user_psd' => ['require', 'length' => 32],
				'code' => 'require|number|length:6',
			),
			'upload' => array(
				'user_id' => 'require|number',
				'user_icon' => 'require|image|fileSize:2000000|fileExt:jpg,png,bmp,jpeg',
			),
			'change_psd' => array(
				'user_name' => ['require', 'max' => 20],
				'user_old_psd' => ['require', 'length' => 32],
				'user_psd' => ['require', 'length' => 32],
			),
			'find_psd' => array(
				'user_name' => ['require', 'max' => 20],
				'user_psd' => ['require', 'length' => 32],
				'code' => 'require|number|length:6',
			),
			'bind_username' => array(
				'user_id' => 'require|number',
				'user_name' => ['require', 'max' => 20],
				'code' => 'require|number|length:6',
			),
		),
		'Code' => array(
			'get_code' => array(
				'username' => 'require',
				'is_exist' => 'require|number|length:1',
			),
		));
	protected function _initialize() {
		parent::_initialize();
		$this->request = Request::instance();
		// $this->check_time($this->request->only(['time']));
		// $this->check_token($this->request->param());
		//$this->params = $this->check_params($this->request->except(['time', 'token']));
		// files
		$this->params = $this->check_params($this->request->param(true));
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
	// 过滤参数
	public function check_params($arr) {
		$rule = $this->rules[$this->request->controller()][$this->request->action()];
		$this->validate = new Validate($rule);
		if (!$this->validate->check($arr)) {
			$this->return_msg(400, $this->validate->getError());
		}
		// 通过验证
		return $arr;
	}
	// 判断手机还是邮箱
	public function check_username($username) {
		$is_email = Validate::is($username, 'email') ? 1 : 0;
		$is_phone = preg_match('/^(13[0-9]|14[5|7]|15[0|1|2|3|5|6|7|8|9]|18[0|1|2|3|5|6|7|8|9])\d{8}$/', $username) ? 4 : 2;
		$flag = $is_email + $is_phone;
		switch ($flag) {
		case 2:
			$this->return_msg(400, '邮箱或手机号不正确');
			break;
		case 3:
			return 'email';
			break;
		case 4:
			return 'phone';
			break;
		}
	}
	// 判断邮箱手机是否存在
	public function check_exist($value, $type, $exist) {
		$type_num = $type == 'phone' ? 2 : 4;
		$flag = $type_num + $exist;
		$res = db('user')->where('user_' . $type, $value)->find();
		switch ($flag) {
		case 2:
			if ($res) {
				$this->return_msg(400, '此手机号已被占用');
			}
			break;
		case 3:
			if (!$res) {
				$this->return_msg(400, '此手机号不存在');
			}
			break;
		case 4:
			if ($res) {
				$this->return_msg(400, '此邮箱已被占用');
			}
			break;
		case 5:
			if (!$res) {
				$this->return_msg(400, '此邮箱不存在');
			}
			break;

		}

	}
	// 发送邮箱
	public function send_email($email, $value) {
		$mail = new PHPMailer();
		// $mail->SMTPDebug = 2;
		$mail->isSMTP();
		$mail->CharSet = 'utf8';
		$mail->Host = 'smtp.163.com';
		$mail->SMTPAuth = true;
		$mail->Username = '15574406229@163.com';
		$mail->Password = 'song5201314';
		$mail->SMTPSecure = 'ssl';
		$mail->Port = 994;
		$mail->setFrom('15574406229@163.com', '接口测试');
		$mail->addAddress($email, 'test');
		$mail->addReplyTo('15574406229@163.com', 'Reply');
		$mail->Subject = $value['subject'];
		$mail->Body = $value['body'];
		if (!$mail->send()) {
			$this->return_msg(400, $mail->ErrorInfo);
		} else {
			$this->return_msg(200, $value['return_msg']);
		}
	}
	// 检测验证码是否正确
	public function check_code($username, $code) {
		$last_time = session($username . '_last_send_time');
		if (time() - $last_time > 60 * 5) {
			$this->return_msg(400, '验证超时， 请在五分钟内验证');
		}
		$md5_code = md5($username . '_' . md5($code));
		dump(session($username . '_code'));
		if (session($username . '_code') !== $md5_code) {
			$this->return_msg(400, '验证码不正确');
		}
		session($username . '_code', null);
	}
	// 上传图片
	public function upload_file($file, $type = '') {
		$base_path = substr(ROOT_PATH, 0, strlen(ROOT_PATH) - 4);
		$info = $file->move($base_path . 'public' . DS . 'uploads');
		if ($info) {
			$path = '/uploads/' . $info->getSaveName();
			dump($path);
			// 裁剪图片
			if (!empty($type)) {
				$this->image_edit($path, $type);
			}
			return str_replace('\\', '/', $path);
		} else {
			$this->return_msg(400, $file->getError());
		}
	}
	public function image_edit($path, $type) {
		$base_path = substr(ROOT_PATH, 0, strlen(ROOT_PATH) - 4);
		$image = Image::open($base_path . 'public' . $path);
		switch ($type) {
		case 'head_img':
			$image->thumb(200, 200, Image::THUMB_CENTER)->save($base_path . 'public' . $path);
			break;
		}
	}
}