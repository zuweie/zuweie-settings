# Zuweie Settings 

### Zuweie Setting 是一个laravel-admin后台配置功能。
### 项目背景
- 因为经常在各个项目里面需要使用后台配置，为了不重复编码，把配置做成一个laravel-admin的插件，方便在各个项目使用。找了一堆的settings的模块。一个都没有后台界面，让管理员怎么用～～让管理员怎么用～～让管理员怎么用～～
- 本项目使用数据库作固化，cache做加速。

### 安装
- composer require zuweie/settings

- 执行以下命令
```
php artisan vendor:publish --provider="Zuweie\Setting\SettingServiceProvider"
```
```
php artisan migrate
```
- 若是在publish的过程中没有发生文件的拷贝，请将Zuweie\Setting\SettingServiceProvider注册到 /config/app.php中。
再进行publish和migrate。

- 安装成功后打开连接http://yourhost/admin/setting?tag=xxx, 将会出现以下页面：
![settings](http://cdn.qiniu.midea.bankoo.co/Snip20190818_1.png)

### 使用
#### 写数据
- 打开地址：http://yourhost/admin/setting?tags=xxx, 传入参数tags，则显示当前tags的配置。例如tags=language_zh,打开language_zh的配置。若tags参数为空则返回全部的配置。

- 在配置列表添加你想要的配置。键值只能出现一次，不能重复，重复的键值无法写入数据库。

#### 读数据
- use Zuweie\Setting\Settings
- 两个主要代码上使用的接口
- 1
```
    /**
     * 根据键名获取相应值
     * @param string $key 键名。
     * @param string $tags 对应tags，默认为空。
     * @param boolean $keepKey 是否以键值对的形式返回。     
     * @param function $callback 对获取的值进行加工，例如将值进行分割。
     * @return mixed
     */
    public static function get_value_by_key ($key,  $tags='', $keepKey=false, $callback=null)
```
- 2
```
    /**
     * 根据tags获取相应的配置值。
     * @param string $tags 标签
     * @param boolean $keepKey 是否以键值对的形式返回。     
     * @param function $callback 回调函数，对返回的值进行加工，例如将值进行分割。
     * @return array 值的数组。
     */
```

### 完了
