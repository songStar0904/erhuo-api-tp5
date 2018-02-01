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
## 1.用户注册
>get api.erhuo.com/user/register

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
## 1.用户登录
>get api.erhuo.com/user/login

|参数|类型|必需/可选|默认|描述|
|-|-|-|-|-|
|time|int|必需|无|时间戳（用于判断请求是否超时）|
|token|string|必需|无|确定来着身份|
|user_name|string|必需|无|手机号或邮箱|
|user_psd|string|必需|无|md5加密用户密码|

```javascript
{
    "code": 200,
    "msg": "登录成功!",
    "data": {
        "user_id":1, // 用户id
        "user_phone":"15639279530", // 用户手机号
        "user_name":"", // 用户昵称
        "user_email":"", // 用户邮箱
        "user_rtime":1501414343 // 用户注册时间
    }
}
```
