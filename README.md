# 二货api接口文档
## 1.获得验证码
>get api.erhuo.com/code

|参数|类型|必需/可选|默认|描述|
|-|-|-|-|-|
|time|int|必需|无|时间戳（用于判断请求是否超时）|
|token|string|必需|无|确定来着身份|
|username|string|必需|无|手机号或邮箱|
|is_exist|int|必需|无|用户名是否应该存在（1：是 0：否）|

```json
{
    "code": 200,
    "msg": "手机验证码已经发送成功, 请在五分钟内验证!",
    "data": []
}
```
## 2.用户注册
>post api.erhuo.com/user/register

|参数|类型|必需/可选|默认|描述|
|-|-|-|-|-|
|time|int|必需|无|时间戳（用于判断请求是否超时）|
|token|string|必需|无|确定来着身份|
|user_name|string|必需|无|手机号或邮箱|
|user_psd|string|必需|无|md5加密用户密码|
|code|int|必需|无|用户接收到的验证码|

```json
{
    "code": 200,
    "msg": "注册成功!",
    "data": []
}
```
## 3.用户登录
>post api.erhuo.com/user/login

|参数|类型|必需/可选|默认|描述|
|-|-|-|-|-|
|time|int|必需|无|时间戳（用于判断请求是否超时）|
|token|string|必需|无|确定来着身份|
|user_name|string|必需|无|手机号或邮箱|
|user_psd|string|必需|无|md5加密用户密码|

```javascript
{
    "code": 200,
    "msg": "登录成功",
    "data": {
        "user_id": 1,// 用户id
        "user_name": "songstar", // 用户昵称
        "user_phone": "15574406229",// 用户手机号
        "user_email": "10433210@qq.com",// 用户邮箱
        "user_sid": 0, // 用户学校
        "user_sex": "", // 用户性别
        "user_rtime": 2147483647,// 用户注册时间
        "user_ltime": 1517632584,// 用户上次登录时间
        "user_icon": "/uploads/20180131/0e1d10906703c56b69d4e800e47d2da0.png", // 用户头像地址
        "user_ip": "127.0.0.1"// 用户登录ip
    }
}
```
## 3.用户退出登录
>get api.erhuo.com/user/login_out

|参数|类型|必需/可选|默认|描述|
|-|-|-|-|-|
|time|int|必需|无|时间戳（用于判断请求是否超时）|
|token|string|必需|无|确定来着身份|
```javascript
{
    "code": 200,
    "msg": "退出登录成功",
    "data": []
}
```
## 4.用户修改密码
>post api.erhuo.com/user/change_psd

|参数|类型|必需/可选|默认|描述|
|-|-|-|-|-|
|time|int|必需|无|时间戳（用于判断请求是否超时）|
|token|string|必需|无|确定来着身份|
|user_name|string|必需|无|手机号或邮箱|
|user_old_psd|string|必需|无|md5加密用户原来密码|
|user_psd|string|必需|无|md5加密用户新密码|

```javascript
{
    "code": 200,
    "msg": "密码修改成功",
    "data": []
}
```
## 4.用户找回密码
>post api.erhuo.com/user/find_psd

|参数|类型|必需/可选|默认|描述|
|-|-|-|-|-|
|time|int|必需|无|时间戳（用于判断请求是否超时）|
|token|string|必需|无|确定来着身份|
|user_name|string|必需|无|手机号或邮箱|
|code|number|必需|无|6位数验证码|
|user_psd|string|必需|无|md5加密用户新密码|

```javascript
{
    "code": 200,
    "msg": "密码修改成功",
    "data": []
}
```
## 5.获取用户信息
>get api.erhuo.com/user/get?search=song&page=1&num=5

|参数|类型|必需/可选|默认|描述|
|-|-|-|-|-|
|time|int|必需|无|时间戳（用于判断请求是否超时）|
|token|string|必需|无|确定来着身份|
|search|string|可选|无|搜索的手机号或邮箱或用户名|
|page|number|可选|1|页数|
|num|number|可选|5|获取数量|
```javascript
{
    "code": 200,
    "msg": "查询用户信息成功",
    "data": [
        {
            "user_id": 1,
            "user_name": "songstar",
            "user_phone": "15574406229",
            "user_email": "10433210@qq.com",
            "user_sid": 0,
            "user_sex": "",
            "user_rtime": 2147483647,
            "user_ltime": 1517632792,
            "user_icon": "/uploads/20180131/0e1d10906703c56b69d4e800e47d2da0.png",
            "user_ip": "127.0.0.1"
        }
    ]
}
```
## 6.获取单个用户信息
>get api.erhuo.com/user/get_one?user_id=1

|参数|类型|必需/可选|默认|描述|
|-|-|-|-|-|
|time|int|必需|无|时间戳（用于判断请求是否超时）|
|token|string|必需|无|确定来着身份|
|user_id|number|必需|无|用户id|

```javascript
{
    "code": 200,
    "msg": "查询用户信息成功",
    "data": {
        "user_id": 1,
        "user_name": "songstar",
        "user_phone": "15574406229",
        "user_email": "10433210@qq.com",
        "user_sid": 0,
        "user_sex": "",
        "user_rtime": 2147483647,
        "user_ltime": 1517632792,
        "user_icon": "/uploads/20180131/0e1d10906703c56b69d4e800e47d2da0.png",
        "user_ip": "127.0.0.1",
        "user_rship": {
            "fans_num": 1, // 粉丝数
            "followers_num": 1 // 关注数
        }
    }
}
```
## 7.取关和关注
>post api.erhuo.com/user/follow

|参数|类型|必需/可选|默认|描述|
|-|-|-|-|-|
|time|int|必需|无|时间戳（用于判断请求是否超时）|
|token|string|必需|无|确定来着身份|
|user_id|number|必需|无|用户id|
|followers_id|number|必需|无|关注用户id|

```javascript
{
    "code": 200,
    "msg": "关注成功",
    "data": []
}
{
    "code": 200,
    "msg": "取关成功",
    "data": []
}
```
## 8.获得粉丝和关注
>get api.erhuo.com/user/get_follower?user_id=2&type=fans

|参数|类型|必需/可选|默认|描述|
|-|-|-|-|-|
|time|int|必需|无|时间戳（用于判断请求是否超时）|
|token|string|必需|无|确定来着身份|
|user_id|number|必需|无|用户id|
|type|string|必需|无|查询方法 只能为fans和followers|

```javascript
{
    "code": 200,
    "msg": "查找成功",
    "data": [
        {
            "user_id": 1,
            "user_name": "songstar",
            "user_sid": 0,
            "user_sex": "",
            "user_icon": "/uploads/20180131/0e1d10906703c56b69d4e800e47d2da0.png"
        }
    ]
}
```
## 8.统计
>get api.erhuo.com/main/get

|参数|类型|必需/可选|默认|描述|
|-|-|-|-|-|
|time|int|必需|无|时间戳（用于判断请求是否超时）|
|token|string|必需|无|确定来着身份|
```javascript
{
    "code": 200,
    "msg": "统计成功",
    "data": {
        "main_gnum": 3, // 商品数量
        "main_unum": 2, // 用户数量
        "main_egnum": 3, // 待审核商品数量
        "new": { // 新增数量
            "main_gnum": 0, 
            "main_unum": 0,
            "main_egnum": 0
        }
    }
}
```
