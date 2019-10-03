## 说明
* 这个是我在向老师那里拿的文件，他的好久都没看到更新了，所以自己更新了一下。

## 更新说明
* 减少权限请求数据库
* 增加了缓存配置，减少数据库访问。
* 更新权限认证缓存
* 由于版本更新问题，composer.json 移除了laravel-modules 和 laravel-permission，请自行安装
* 优化了导航和侧边栏

## 更新介绍
| **Laravel**  |  **laravel-modules** |
|---|---|
| 5.8  | ^1.0.9  |
| 6.0  | ^2.0.3  |
## 组件介绍

通过使用模块来管理大型Laravel项目，模块就像一个laravel包非常方便的进行添加或移除。
laravel-modules 和 laravel-permission 组件的功能都可以正常使用

## 安装组件

    composer require sunjim/laravel-module
    php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider" --tag="config"
配置 composer.json 设置自动加载目录

    {
      "autoload": {
        "psr-4": {
          "App\\": "app/",
          "Modules\\": "Modules/"
        }
      }
    }

## 创建模块

下面的命令是安装 `Admin` 模块

```
php artisan hd:module Admin
```
创建模块会同时执行以下操作：

* 生成 `menus.php` 配置文件
* 生成 `permission.php` 权限文件

## 模块配置

新建模块时系统会自动创建配置，一般情况下不需要执行以下命令生成配置文件（除组件添加新配置功能外）

```
php artisan hd:config Admin
```


* $data——配置数据
* $name——配置文件

## 后台菜单

系统会根据模块配置文件 `menus.php` 生成后台菜单项

当 menus.php 文件不存在时，执行 `php artisan hd:config Admin` 系统会为模块 Admin 创建菜单。

**获取菜单**

获取系统可使用的所有菜单，以集合形式返回数据。可用于后台显示菜单列表。

```
\HDModule::getMenus()
```

## 权限管理

首先需要安装 [laravel-permission](https://github.com/spatie/laravel-permission#installation) 组件，安装方式在上面已经介绍。

### 创建权限配置

系统根据 `Admin` 模块配置文件 `permission.php` 重新生成权限，执行以下命令会创建权限配置文件。

```
php artisan hd:permission Admin
```

不指定模块时生成所有模块的权限表

```
php artisan hd:permission
```

> 页面导航在配置文件中 
```
'allow_navigate' =>['后台管理'=>'admin'],
```


## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
