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
// 用户退出登录
Route::get('user/login_out', 'user/login_out');
// 上传图片
Route::post('user/upload', 'user/upload');
// 修改密码
Route::post('user/change_psd', 'user/change_psd');
// 找回密码
Route::post('user/find_psd', 'user/find_psd');
// 绑定手机或邮箱
Route::post('user/bind_username', 'user/bind_username');
// 获取用户信息
Route::get('user/get', 'user/get');
// 获取单个用户信息
Route::get('user/get_one', 'user/get_one');
// 关注与取关
Route::post('user/follow', 'user/follow');
// 获得粉丝关注
Route::get('user/get_follower', 'user/get_follower');
// 添加商品
Route::post('goods/add', 'goods/add');
// 获得商品
Route::get('goods/get', 'goods/get');
// 获得单个商品
Route::get('goods/get_one', 'goods/get_one');
// 修改商品
Route::post('goods/edit', 'goods/edit');
// 删除商品
Route::delete('goods/delete', 'goods/delete');
// 关注与取关
Route::post('goods/follow', 'goods/follow');
// 统计
Route::get('main/get', 'main/get');