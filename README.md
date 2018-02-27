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
|uid|number|必需|无|访问者用户id|
|page|number|可选|1|页码|
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
            "user_icon": "/uploads/20180131/0e1d10906703c56b69d4e800e47d2da0.png",
            "is_fans": true // 访问者与用户是否粉丝关系
        }
    ],
    "total": 1
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
## 9.修改个人信息
>post api.erhuo.com/user/edit

|参数|类型|必需/可选|默认|描述|
|-|-|-|-|-|
|time|int|必需|无|时间戳（用于判断请求是否超时）|
|token|string|必需|无|确定来着身份|
|user_id|number|必需|无|用户id|
|user_name|string|可选|无|用户昵称|
|user_sign|string|可选|无|用户个性签名|

```javascript
{
    "code": 200,
    "msg": "修改个人信息成功",
    "data": {
        "user_id": "2",
        "user_name": "2",
        "user_sign": "我的联系方式15574406229"
    }
}
```
## 10.反馈信息
>post api.erhuo.com/user/send_fmsg

|参数|类型|必需/可选|默认|描述|
|-|-|-|-|-|
|time|int|必需|无|时间戳（用于判断请求是否超时）|
|token|string|必需|无|确定来着身份|
|fmsg_uid|number|必需|无|用户id|
|fmsg_content|string|必需|无|反馈内容|

```javascript
{
    "code": 200,
    "msg": "意见反馈成功",
    "data": {
        "lmsg_uid": "1",
        "lmsg_content": "我要留言",
        "lmsg_time": 1517639041
    }
}
```
## 11.留言
>post api.erhuo.com/user/send_lmsg

|参数|类型|必需/可选|默认|描述|
|-|-|-|-|-|
|time|int|必需|无|时间戳（用于判断请求是否超时）|
|token|string|必需|无|确定来着身份|
|lmsg_sid|number|必需|无|发送者id|
|lmsg_rid|number|必需|无|接受者id|
|lmsg_gid|number|必需|无|商品id|
|lmsg_id|number|可选|无|留言id|
|lmsg_content|string|必需|无|反馈内容|

```javascript
{
    "code": 200,
    "msg": "留言成功",
    "data": {
        "lmsg_sid": "1",
        "lmsg_rid": "2",
        "lmsg_content": "我要留言",
        "lmsg_gid": "1",
        "lmsg_time": 1517639041
    }
}
```
## 11.获得反馈信息
>post api.erhuo.com/admin/get_fmsg?fmsg_status=0

|参数|类型|必需/可选|默认|描述|
|-|-|-|-|-|
|time|int|必需|无|时间戳（用于判断请求是否超时）|
|token|string|必需|无|确定来着身份|
|fmsg_sid|number|必需|无|反馈信息id|
|fmsg_status|number|可选|无|修改状态|
|fmsg_uid|number|可选|无|发送者|

```javascript
{
    "code": 200,
    "msg": "获取反馈信息成功",
    "data": {
        "0": {
            "fmsg_id": 4,
            "fmsg_content": "我要反馈",
            "fmsg_status": 0,
            "fmsg_time": 1517638511,
            "user": {
                "user_id": 1,
                "user_name": "songstar",
                "user_icon": "/uploads/20180131/0e1d10906703c56b69d4e800e47d2da0.png"
            }
        },
        "length": 1
    }
}
```

## 12.修改反馈信息
>post api.erhuo.com/admin/edit_fmsg

|参数|类型|必需/可选|默认|描述|
|-|-|-|-|-|
|time|int|必需|无|时间戳（用于判断请求是否超时）|
|token|string|必需|无|确定来着身份|
|fmsg_sid|number|必需|无|反馈信息id|
|fmsg_status|number|必需|无|修改状态|

```javascript
{
    "code": 200,
    "msg": "修改反馈信息成功",
    "data": 1
}
```

## 13.查询分类
>get api.erhuo.com/classify/get?type=gclassify

|参数|类型|必需/可选|默认|描述|
|-|-|-|-|-|
|time|int|必需|无|时间戳（用于判断请求是否超时）|
|token|string|必需|无|确定来着身份|
|type|string|必需|无|分类数据库名称（商品分类，文章分类）|

```javascript
{
    "code": 200,
    "msg": "查询分类成功",
    "data": [
        {
            "gclassify_id": 1,
            "gclassify_name": "书籍"
        }
    ]
}
```

## 14.添加分类
>post api.erhuo.com/classify/add

|参数|类型|必需/可选|默认|描述|
|-|-|-|-|-|
|time|int|必需|无|时间戳（用于判断请求是否超时）|
|token|string|必需|无|确定来着身份|
|type|string|必需|无|分类数据库名称（商品分类，文章分类）|
|name|string|必需|无|分类名称|

```javascript
{
    "code": 200,
    "msg": "添加分类成功",
    "data": {
        "gclassify_name": "玩具"
    }
}
```

## 15.修改分类
>post api.erhuo.com/classify/edit

|参数|类型|必需/可选|默认|描述|
|-|-|-|-|-|
|time|int|必需|无|时间戳（用于判断请求是否超时）|
|token|string|必需|无|确定来着身份|
|type|string|必需|无|分类数据库名称（商品分类，文章分类）|
|name|string|必需|无|分类名称|
|id|number|必需|无|分类id|

```javascript
{
    "code": 200,
    "msg": "修改分类成功",
    "data": {
        "gclassify_id": "1",
        "gclassify_name": "电器"
    }
}
```

## 15.删除分类
>delete api.erhuo.com/classify/delete

|参数|类型|必需/可选|默认|描述|
|-|-|-|-|-|
|time|int|必需|无|时间戳（用于判断请求是否超时）|
|token|string|必需|无|确定来着身份|
|type|string|必需|无|分类数据库名称（商品分类，文章分类）|
|id|number|必需|无|分类id|

```javascript
{
    "code": 200,
    "msg": "删除分类成功",
    "data": {
        "type": "gclassify",
        "id": "3"
    }
}
```
## 15.获得热搜
>get api.erhuo.com/goods/get_hot?num=5

|参数|类型|必需/可选|默认|描述|
|-|-|-|-|-|
|time|int|必需|无|时间戳（用于判断请求是否超时）|
|token|string|必需|无|确定来着身份|
|num|string|必需|无|热搜前num|

```json
{
    "code": 200,
    "msg": "查询热搜成功",
    "data": [
        {
            "search_id": 5,
            "search_name": "Vue",
            "search_num": 20
        },
        {
            "search_id": 1,
            "search_name": "四级",
            "search_num": 2
        },
        {
            "search_id": 4,
            "search_name": "jacascript",
            "search_num": 1
        }
    ]
}
```
## 16.添加商品
>get api.erhuo.com/goods/add

|参数|类型|必需/可选|默认|描述|
|-|-|-|-|-|
|time|int|必需|无|时间戳（用于判断请求是否超时）|
|token|string|必需|无|确定来着身份|
|goods_uid|number|必需|无|添加者id|
|goods_name|number|必需|无|添加者id|
|goods_cid|number|必需|无|分类id|
|goods_oprice|number|必需|无|原价|
|goods_nprice|number|必需|无|现价|
|goods_summary|number|必需|无|商品介绍|
|goods_icon|array|必需|无|商品图片|
```json
{
    "code": 200,
    "msg": "查询热搜成功",
    "data": ...商品信息
}
```

