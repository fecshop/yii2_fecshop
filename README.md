<p>
  <a href="http://fecshop.appfront.fancyecommerce.com/">
    <img src="http://img.appfront.fancyecommerce.com/custom/logo.png">
  </a>
</p>
<br/>



[![Latest Stable Version](https://poser.pugx.org/fancyecommerce/fecshop/v/stable)](https://packagist.org/packages/fancyecommerce/fecshop) [![Total Downloads](https://poser.pugx.org/fancyecommerce/fecshop/downloads)](https://packagist.org/packages/fancyecommerce/fecshop) [![Latest Unstable Version](https://poser.pugx.org/fancyecommerce/fecshop/v/unstable)](https://packagist.org/packages/fancyecommerce/fecshop)

**Fecshop文档(撰写中)**：[Fecshop Doc Guide](http://www.fecshop.com/doc/fecshop-guide/develop/cn-1.0/guide-README.html#)



项目状态：

> 项目已经开始,本项目由Terry筹划，预计到2017-05-01出来第一个正式版本。


1、Fecsop介绍
------------

Fecshop 全称为Fancy ECommerce Shop，是基于php Yii2框架之上开发的一款优秀的开源电商系统，遵循[OSL3.0协议](http://www.oschina.net/question/28_8527)，
Fecshop支持多语言，多货币，架构上支持pc，手机web，手机app，和erp对接等入口，您可以免费快速的定制和部署属于您的电商系统。

详细参看地址：[Fecsop介绍](http://www.fecshop.com/doc/fecshop-guide/develop/cn-1.0/guide-fecshop-about-description.html)

FecShop 英文演示地址：http://fecshop.appfront.fancyecommerce.com/

FecShop 中文演示地址：http://fecshop.appfront.fancyecommerce.com/cn

FecShop 作者QQ：2358269014

FecShop Github地址: https://github.com/fancyecommerce/yii2_fecshop

2、Fecsop文档
------------

二开以及安装文档：[Fecshop 安装开发文档](http://www.fecshop.com/doc/fecshop-guide/develop/cn-1.0/guide-README.html)

使用说明文档：制作中...


3、安装Fecsop
------------


3.1、vagrant安装：

通过vagrant加载box的方式直接安装，环境和fecshop的配置都已经弄好，详细可以参看文档地址：[Fecshop vagrant安装](http://www.fecshop.com/doc/fecshop-guide/develop/cn-1.0/guide-fecshop-about-vagrantinstall.html)


3.2、全手动安装：

从基础linux一步一步的配置的方式，详细参看文档： [Fecshop 全手动安装](http://www.fecshop.com/doc/fecshop-guide/develop/cn-1.0/guide-fecshop-about-hand-install.html)

推荐使用第一种方式快速部署。

4、fecshop 配置：
------------

配置详细参看：[fecshop 配置](http://www.fecshop.com/doc/fecshop-guide/develop/cn-1.0/guide-fecshop-about-config.html)

如果您使用vagrant box的安装方式，上面的这些步骤，在box都配置好了。

5、架构特色
-----------

架构特色：参看详细介绍：[Fecshop 架构特色](http://www.fecshop.com/doc/fecshop-guide/develop/cn-1.0/guide-fecshop-about-framework.html)

下面是简叙：

Fecshop 全称为Fancy ECommerce Shop，是一款优秀的开源电商系统，遵循[OSL3.0协议](http://www.oschina.net/question/28_8527)，
目的是为了方便yii2用户快速的
开发商城，Fecshop作为一款可以持续性发展的商城系统，
在框架层面有以下特性：

1. 由于商城系统的复杂性，原始的框架MVC结构，显的有点力不从心，Fecshop框架
加入了[Block层](fecshop-feature-block.md)，
Controller层只负责调度， Model只负责数据库映射，中间的处理逻辑由block来完成，View层
负责显示，这样各司其职， 以免造成controller文件过于庞大。

2. 加入[独立功能块](fecshop-feature-independent-block.md)，有点类似Yii2的Widget，目的是为了让一些侧栏公用块
可以通过配置的方式
添加，同时，还可以具有设置缓存的功能，譬如侧栏的产品浏览记录，
newsletter等独立显示块可能在很多
页面用到，通过独立功能块可以配置方便的载入。

3. 在Model层的上层加入[服务层Services](fecshop-services-abc.md)，这样，Controller，Block，View 层，在原则上
不能直接调用model，必须通过Services层以及子Services层，然后Services访问各个
model，组织数据，事务处理等操作，将数据�