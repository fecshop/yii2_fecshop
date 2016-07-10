# Yii2 FecShop

========


github: https://github.com/fancyecommerce/yii2_fecshop

[![Latest Stable Version](https://poser.pugx.org/fancyecommerce/fecshop/v/stable)](https://packagist.org/packages/fancyecommerce/fecshop) [![Total Downloads](https://poser.pugx.org/fancyecommerce/fecshop/downloads)](https://packagist.org/packages/fancyecommerce/fecshop) [![Latest Unstable Version](https://poser.pugx.org/fancyecommerce/fecshop/v/unstable)](https://packagist.org/packages/fancyecommerce/fecshop)

关于Terry

> 2010年进入外贸电商领域，使用magento搞仿牌网站，到最后转入正品网站，维护日均IP 10w+的网站
到发现magento的各种不足（在知乎上面发表了自己对magento的观点：https://www.zhihu.com/question/19813425#zh-question-answer-wrap），
于是，在2014年用yii2写了一套支持多语言，多货币，支持pc web，手机web等功能的外贸电商系统，大致用了10个月的时间做成，来解决并发问题。
，现在已经上线运行1年多，但是从架构上面看，扩展性比较差，灵活度不是很高，2015年开始筹划将magento的灵活和yii2的可配置结合起来
做一套开源电商系统，可以长久的做下去，经过将近一年的时间，尝试多种方式，构思底层架构，目前架构层面已经搭建好，现在开始代码填写，也就是本项目：FecShop。

项目状态：

> 项目已经开始,本项目由Terry筹划，预计到2017年元旦出来第一个正式版本。在架构宏观上解决两个大问题：

架构特色：

> 1.解决三者之间的矛盾：
  a) fecshop系统核心代码，模板，数据库升级
, b)第三方代码，模板，数据升级
, c)用户二次开发，代码，模板，数据修改

> 1.1逻辑代码的矛盾解决：服务层，模块层，controller，都是通过依赖注入的方式生成，通过配置,可以将服务层，模块层，controller层功能的重写。

> 1.2模板view，js css等模板文件的解决：通过多模板优先级加载，解决模板文件，js,css,的重写问题。

> 2.解决功能重构：在模块VC与数据层中间加入功能服务层，在架构层面可以很好的解决重构问题，
譬如，对于用户中心模块，我可以用mysql，也可以用mongodb，甚至用redis等数据库来存储，
只要实现了服务层的功能函数，就可以实现该服务的重构，通过配置文件，设置服务指向重构的组件即可。
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
