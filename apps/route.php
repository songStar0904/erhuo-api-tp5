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

Route::get('user', 'user/login');
// 获取验证码
<<<<<<< HEAD
// Route::get('code/get_code/:time/:token/:username/:is_exist', 'code/get_code');
=======
Route::get('code/get_code/:time/:token/:username/:is_exist', 'code/get_code');
>>>>>>> 推广商品api 下架
// 用户注册
Route::post('user/register', 'user/register');
// 用户登录
Route::post('user/login', 'user/login');
// 用户退出登录
Route::get('user/login_out', 'user/login_out');
// 是否登录
Route::get('user/is_login', 'user/is_login');
// 上传图片
Route::post('user/upload', 'user/upload');
// 修改密码
Route::post('user/change_psd', 'user/change_psd');
// 找回密码
Route::post('user/find_psd', 'user/find_psd');
// 绑定手机或邮箱
Route::post('user/bind_username', 'user/bind_username');
// 修改个人信息
Route::post('user/edit', 'user/edit');
// 获取用户信息
Route::get('user/get', 'user/get');
// 获取单个用户信息
Route::get('user/get_one', 'user/get_one');
// 关注与取关
Route::post('user/follow', 'user/follow');
// 获得粉丝关注
Route::get('user/get_follower', 'user/get_follower');
// 意见反馈
Route::post('user/send_fmsg', 'user/send_fmsg');
// 留言
Route::post('user/send_lmsg', 'user/send_lmsg');
<<<<<<< HEAD
=======
// 下架二货
Route::post('user/sold_goods', 'user/sold_goods');

>>>>>>> 推广商品api 下架
// 添加商品
Route::post('goods/add', 'goods/add');
// 获得商品
Route::get('goods/get', 'goods/get');

Route::get('goods/get_follower', 'goods/get_follower');
// 获得单个商品
Route::get('goods/get_one', 'goods/get_one');
Route::get('goods/get_edit', 'goods/get_edit');
// 上传图片
Route::post('goods/upload', 'goods/upload');
// 删除上传图片
Route::post('goods/del_img', 'goods/del_img');
// 修改商品
Route::post('goods/edit', 'goods/edit');
// 删除商品
Route::delete('goods/delete', 'goods/delete');
// 关注与取关
Route::post('goods/follow', 'goods/follow');
// 获得热搜
Route::get('goods/get_hot', 'goods/get_hot');

// 获取分类
Route::get('classify/get', 'classify/get');
// 添加分类
Route::post('classify/add', 'classify/add');
// 修改分类
Route::post('classify/edit', 'classify/edit');
// 删除分类
Route::delete('classify/delete', 'classify/delete');

// 关注与取关
Route::post('goods/follow', 'goods/follow');
// 统计
Route::get('main/get', 'main/get');
// 获得反馈
Route::get('admin/get_fmsg', 'admin/get_fmsg');
// 修改反馈状态
Route::post('admin/edit_fmsg', 'admin/edit_fmsg');
// 审核商品
Route::post('admin/pass_goods', 'admin/pass_goods');
<<<<<<< HEAD
=======
// 推广商品
Route::post('admin/spread_goods', 'admin/spread_goods');
>>>>>>> 推广商品api 下架
// 发布公告
Route::post('admin/send_notice', 'admin/send_notice');

// 发表评论/留言/回复
Route::post('messge/send', 'messge/send');
// 点赞
Route::post('messge/praise', 'messge/praise');
// 获得消息/数量
Route::get('messge/get', 'messge/get');
Route::get('messge/get_by_id', 'messge/get_by_id');
// 获得公告
Route::get('messge/get_notice', 'messge/get_notice');
Route::post('messge/change_status', 'messge/change_status');
// 删除
Route::delete('messge/delete', 'messge/delete');

// 动态
Route::post('dynamic/add', 'dynamic/add');
<<<<<<< HEAD
Route::get('dynamic/get', 'dynamic/get');
=======
Route::get('dynamic/get', 'dynamic/get');

// 举报
Route::post('report/add', 'report/add');
>>>>>>> 推广商品api 下架
