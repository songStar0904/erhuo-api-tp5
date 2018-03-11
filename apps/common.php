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
			'login_out' => array(),
			'is_login' => array(),
			'register' => array(
				'user_name' => ['require', 'max' => 20],
				'user_psd' => ['require', 'length' => 32],
				'user_sid' => 'require|number',
				'user_sex' => 'require',
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
			'edit' => array(
				'user_id' => 'require|number',
				'user_name' => 'max:20',
				'user_sign' => 'max:255'),
			'send_fmsg' => array(
				'fmsg_uid' => 'require|number',
				'fmsg_content' => 'require|max:255'),
			'send_lmsg' => array(
				'lmsg_id' => 'number',
				'lmsg_rid' => 'require|number',
				'lmsg_sid' => 'require|number',
				'lmsg_gid' => 'require|number',
				'lmsg_content' => 'require|max:255'),
			'get' => array(
				'search' => 'chsDash',
				'page' => 'number',
				'num' => 'number',
			),
			'get_one' => array(
				'uid' => 'number',
				'user_id' => 'require|number',
			),
			'follow' => array(
				'followers_id' => 'require|number',
			),
			'get_follower' => array(
				'uid' => 'number',
				'user_id' => 'require|number',
				'type' => 'require|check_name:fans,followers'),
		),
		'Code' => array(
			'get_code' => array(
				'username' => 'require',
				'is_exist' => 'require|number|length:1',
			),
		),
		'Goods' => array(
			'add' => array(
				'goods_uid' => 'require|number',
				'goods_icon' => 'require|array',
				'goods_name' => ['require', 'max' => 20],
				'goods_cid' => 'require|number',
				'goods_oprice' => 'require|number',
				'goods_nprice' => 'require|number',
				'goods_summary' => 'require|max:255'),
			'get' => array(
				'search' => 'chsDash',
				'sort' => 'chsDash',
				'uid' => 'number',
				'page' => 'number',
				'num' => 'number'),
			'get_one' => array(
				'goods_id' => 'require|number',
			),
			'get_edit' => array(
				'goods_id' => 'require|number',
			),
			'edit' => array(
				'goods_uid' => 'require|number',
				'goods_icon' => 'require|array',
				'goods_id' => 'require|number',
				'goods_name' => ['require', 'max' => 20],
				'goods_cid' => 'require|number',
				'goods_oprice' => 'require|number',
				'goods_nprice' => 'require|number',
				'goods_summary' => 'require|max:255',
			),
			'follow' => array(
				'followers_id' => 'require|number',
			),
			'upload' => array(
				'goods_icon' => 'require|image|fileSize:2000000|fileExt:jpg,png,bmp,jpeg',
			),
			'del_img' => array(
				'goods_id' => 'require|number',
				'url' => 'require',
			),
			'delete' => array(
				'goods_id' => 'require|number',
			),
			'get_hot' => array(
				'num' => 'require|number',
			),
		),
		'Main' => array(
			'get' => array()),
		'Admin' => array(
			'get_fmsg' => array(
				'fmsg_status' => 'number',
				'fmsg_uid' => 'number'),
			'edit_fmsg' => array(
				'fmsg_status' => 'require|number',
				'fmsg_id' => 'require|number')),
		'Classify' => array(
			'get' => array(
				'type' => 'require'),
			'add' => array(
				'type' => 'require',
				'name' => 'require|chs|max:6'),
			'edit' => array(
				'type' => 'require',
				'id' => 'require|number',
				'name' => 'require|chs|max:6'),
			'delete' => array(
				'type' => 'require',
				'id' => 'require|number')));
	protected function _initialize() {
		parent::_initialize();
		header("Access-Control-Allow-Origin: http://localhost:3000");
		header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT, OPTIONS");
		header("Access-Control-Allow-Credentials: true");
		header("Access-Control-Allow-Headers: Content-Type, X-Requested-With, Cache-Control,accept");
		//dump($this->request->param(true));
		//$this->check_time($this->request->only(['time']));
		//$this->check_token($this->request->param());
		//$this->params = $this->check_params($this->request->except(['time', 'token']));
		// files
		$this->request = Request::instance();
		$this->params = $this->check_params($this->request->param(true));
	}
	// 返回信息
	public function return_msg($code, $msg = '', $data = [], $total = false) {
		$return_data['code'] = $code;
		$return_data['msg'] = $msg;
		$return_data['data'] = $data;
		if ($total) {
			$return_data['total'] = $total;
		}
		echo json_encode($return_data);die;
	}
	// 验证时间
	public function check_time($arr) {
		if (!isset($arr['time']) || intval($arr['time']) <= 1) {
			$this->return_msg(400, '时间戳不正确', $arr);
		}
		if ((time() - intval($arr['time'])) > 60) {
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
		unset($arr['time']);
		$service_token = '';
		foreach ($arr as $key => $value) {
			$service_token .= md5($value);
		}
		$service_token = md5('api_' . $service_token . '_api');
		if ($app_token !== $service_token) {
			$this->return_msg(400, 'token值不正确', $service_token);
		}
	}
	// 过滤参数
	public function check_params($arr) {
		foreach ($arr as $key => $value) {
			if (empty($value) && $this->request->file($key)) {
				$arr[$key] = $this->request->file($key);
			}
		}
		$rule = $this->rules[$this->request->controller()][$this->request->action()];
		$this->validate = new Validate($rule);
		if (!$this->validate->check($arr)) {
			$this->return_msg(400, $this->validate->getError());
		}
		// 通过验证
		return $arr;
	}
	// 检验 是否管理员
	public function check_admin() {
		if (session('user_access') > 0) {
			return session('user_access');
		} else {
			$this->return_msg(400, '抱歉，您的权限不够', session('user_id'));
		}
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
		// dump($_SESSION);
		if (time() - $last_time > 60 * 5) {
			$this->return_msg(400, '验证超时， 请在五分钟内验证');
		}

		$md5_code = md5($username . '_' . md5($code));
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
			$path = 'http://api.erhuo.com/public/uploads/' . $info->getSaveName();
			// 裁剪图片
			// if (!empty($type)) {
			// 	$this->image_edit($path, $type);
			// }
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
	// 更新登录时间和ip
	public function update_login($user_id) {
		$now = time();
		$ip = $this->request->ip();
		$res = db('user')->where('user_id', $user_id)->update(['user_ltime' => $now, 'user_ip' => $ip]);
		if (!$res) {
			$this->return_msg(400, '更新登录时间/ip失败');
		} else {
			return array(
				'user_ltime' => $now,
				'user_ip' => $ip,
			);
		}
	}
	// 用户关注用户和商品
	public function common_follow($followers_id, $type) {
		if (session('user_id')) {
			$fans_id = session('user_id');
			$has = db($type . 'rship')->where('fans_id', $fans_id)
				->where('followers_id', $followers_id)
				->find();
			if ($has) {
				$msg = '取关';
				$result = false;
				$res = db($type . 'rship')->where('fans_id', $fans_id)
					->where('followers_id', $followers_id)
					->delete();
			} else {
				$msg = '关注';
				$result = true;
				$data['fans_id'] = $fans_id;
				$data['followers_id'] = $followers_id;
				$data['follower_time'] = time();
				$res = db($type . 'rship')->insert($data);
			}
			if ($res) {
				$this->return_msg(200, $msg . '成功', $result);
			} else {
				$this->return_msg(400, $msg . '失败');
			}
		} else {
			$this->return_msg(400, '请先登录');
		}

	}
	// 单个用户间是否关注 uid 访问者id
	public function is_fans($type, $user_id, $uid) {
		$fans = db($type . 'rship')->where('fans_id', $uid)
			->where('followers_id', $user_id)->find();
		$res = $fans ? true : false;
		return $res;
	}
	// 记录浏览用户
	public function record($id, $gid) {
		$data = array(
			'grecord_uid' => $id,
			'grecord_gid' => $gid);
		// 先检测数据库是否存在 存在则仅更新时间
		$res = db('grecord')->where($data)->find();
		if ($res) {
			$result = db('grecord')->where($data)->setField('grecord_time', time());
		} else {
			$data['grecord_time'] = time();
			$result = db('grecord')->insert($data);
		}
	}
	// 获得留言
	public function get_lmsg($id, $type) {
		$join = [['erhuo_user s', 's.user_id = l.lmsg_sid'], ['erhuo_user r', 'r.user_id = l.lmsg_rid']];
		$field = 'lmsg_id, lmsg_content,r.user_id as ruser_id,r.user_icon as ruser_icon,r.user_name as ruser_name,s.user_id as suser_id, s.user_name as suser_name, s.user_icon as suser_icon, lmsg_content, lmsg_status';
		$res = db('lmsg')->alias('l')->join($join)->field($field)->where('lmsg_gid', $id)->order('lmsg_time desc')->select();
		$res = $this->arrange_data($res, 'ruser');
		$res = $this->arrange_data($res, 'suser');
		return $res;
	}
	// 添加搜索
	public function add_search($search) {
		$res = db('search')->where('search_name', $search)->setInc('search_num');
		if (!$res) {
			$data['search_name'] = $search;
			$res2 = db('search')->insert($data);
		}
	}
	// 整理数据
	public function arrange_data($data, $name) {
		$len = strlen($name);
		foreach ($data as $k => $val) {
			if (is_array($val)) {
				foreach ($val as $key => $value) {
					if (substr($key, 0, $len) == $name) {
						$data[$k][$name][substr($key, $len + 1)] = $value;
						unset($data[$k][$key]);
					}
				}
			} else {
				if (substr($k, 0, $len) == $name) {
					$data[$name][substr($k, $len + 1)] = $val;
					unset($data[$k]);
				}
			}
		}
		return $data;
	}

}