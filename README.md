对开发友好的极简IM

## 0 功能

* 单聊
* 群聊
* 发文字
* 发表情
* 发语音
* 发图片
* 拍一拍（拍头像，拍文字）
* 发文件
* 发视频
* 群内兴趣小组（该小组可以不断分裂，进化）
* 下拉刷新聊天记录
* 多设备登录及信息同步
* 支持pc,H5，小程序 和 apk ，桌面版，ios 理论上应该也可以，但太麻烦，有心者可研究

[demo](https://jc91715.top/im)
手机扫码体验
![1](https://jc91715.top/storage/app/blog/3vCEkfFjmS.png)

## 快速开始
[下载压缩包](https://jc91715.top/storage/app/media/im/colorui-im-admin-h5.zip)

压缩包里有后端源码和压缩后的前端代码。
github 
* 前端源码[https://github.com/colorui-im/colorui-im-h5](https://github.com/colorui-im/colorui-im-h5)
* 后端源码[https://github.com/colorui-im/colorui-im-admin](https://github.com/colorui-im/colorui-im-admin)
gitee
* 前端源码[https://gitee.com/colorui-im/colorui-im-h5](https://gitee.com/colorui-im/colorui-im-h5)
* 后端源码[https://gitee.com/colorui-im/colorui-im-admin](https://gitee.com/colorui-im/colorui-im-admin)


考虑到可以直接运行，使用sqlite 数据库，线上可以使用mysql

```
php artisan migrate 
php artisan db:seed
php artisan serve --port=8880 //启动api和前端服务
```
```
php workerman/start.php start  //启动websocket服务
```

访问[http://127.0.0.1:8880/h5](http://127.0.0.1:8880/h5)
-------
我是分割线
[二次开发看这里](https://jc91715.top/colorui-im-doc/10/chapters/14272)



