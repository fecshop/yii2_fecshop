# yii2_fecshop
fecshop for ecommerce  , online shop open source
========


github: https://github.com/fancyecommerce/yii2_fecshop

[![Latest Stable Version](https://poser.pugx.org/fancyecommerce/fecshop/v/stable)](https://packagist.org/packages/fancyecommerce/fecshop) [![Total Downloads](https://poser.pugx.org/fancyecommerce/fecshop/downloads)](https://packagist.org/packages/fancyecommerce/fecshop) [![Latest Unstable Version](https://poser.pugx.org/fancyecommerce/fecshop/v/unstable)](https://packagist.org/packages/fancyecommerce/fecshop) [![License](https://poser.pugx.org/fancyecommerce/fecshop/license)](https://packagist.org/packages/fancyecommerce/fecshop)

> 项目已经开始,本项目由Terry筹划，经过一年的构思，现在已经把底层结构想好， 预计到2017年元旦出来第一个正式版本。

> 在架构宏观上解决两个大问题：

> 1. 

> 1.1fecshop系统核心代码，模板，数据库升级

> 1.2 第三方代码，模板，数据升级

> 1.3 用户二次开发，代码，模板，数据修改

> 上面三者之间的矛盾的冲突，通过依赖注入的方式，通过配置解决服务层，模块层，controller层功能的重写。

> 通过多模板优先级加载，解决模板文件，js,css,的重写问题。

> 2.在模块VC与数据层中间加入功能服务层，在架构层面可以很好的解决重构问题，
譬如，对于用户中心模块，我可以用mysql，也可以用mongodb，甚至用redis等数据库来存储，
只要实现了服务层的功能函数，就可以实现该服务的重构。
。

1、安装Yii2
------------

安装这个扩展的首选方式是通过 [composer](http://getcomposer.org/download/).

我的安装路径是在 /www/web/develop/fecadmin 文件夹下面

执行

```
composer  require "fxp/composer-asset-plugin:~1.1.1"

```



2、安装FecShop
------------

执行

```
composer require --prefer-dist fancyecommerce/fecshop

```
或添加

```
"fancyecommerce/fecshop": "~1.0"
composer install
```

执行完上面，就安装完成了。
