# Laravel Admin Settings 

### Admin Ext Setting 是一个后台配置列表。
### 项目背景
- 因为经常在各个项目里面需要使用后台配置，为了不重复编码，把配置做成一个laravel-admin的插件，方便在各个项目使用。找了一堆的settings的模块。一个都没有后台界面，让管理员怎么用～～
- 受不了自己来一个～～

### 安装
- composer require zuweie/settings

- 执行以下命令
```
php artisan vendor:publish --provider="Zuweie\Setting\SettingServiceProvider"

php artisan migrate
```
- 若是在publish的过程中没有发生文件的拷贝，请将Zuweie\Setting\SettingServiceProvider注册到 /config/app.php中。
再进行publish和migrate。

### 使用
#### 写数据
- 打开地址：http://yourhost/admin/setting?tags=xxx, 传入参数tags，当前tags的设置。例如tags=language_zh,打开中文的配置。若tags参数为空则返回全部的设置。

- 在配置列表添加你先要的配置。

#### 读数据
- use Zuweie\Setting\Settings;
- 使用可以使用的接口

### 完了
