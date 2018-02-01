<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\Route;

Route::domain('api', 'api');

Route::post('user', 'user/login');
// 获取验证码
Route::get('code/:time/:token/:username/:is_exist', 'code/get_code');
// 用户注册
Route::post('user/register', 'user/register');
// 用户登录
Route::post('user/login', 'user/login');
// 上传图片
Route::post('user/upload', 'user/upload');
// 修改密码
Route::post('user/change_psd', 'user/change_psd');
// 找回密码
Route::post('user/find_psd', 'user/find_psd');
// 绑定手机或邮箱
Route::post('user/bind_username', 'user/bind_username');