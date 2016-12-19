<p>
  <a href="http://fecshop.appfront.fancyecommerce.com/">
    <img src="http://img.appfront.fancyecommerce.com/custom/logo.png">
  </a>
</p>
<br/>

演示地址：http://fecshop.appfront.fancyecommerce.com/

截止到2016-12-19号，除了支付部分，其他的基本都已经完成，关注fecshop的
在等2-3个月，也就是明年2,3月份，版本已经就可以出来，2017年4,5月份在把手机web
做一下，预计到明年5月份，后台，pc前台，手机web前台 ，命令控制台  这几个入口
基本可以完善，多谢大家关注和你们的Star，我会坚持把他写好。
目前由于项目还没有完成，因此，下载安装只能安装部分代码，
而且数据库部分没有提供migrate，也就是说，目前fecshop还不可用，
等代码开发完成，我在整体核对一遍代码，
然后把文档落实，该项目是开源项目，从事外贸电商6年来，
用了不少开源电商系统，譬如magento，发现开源框架都有一定
的诟病，在并发方面差，后期扩展，业务发展后期重构难，
尤其是现在的移动端的发展，多入口的电商模式占据主流，
性能方面的要求越来越高，Fecshop采用了nosql和mysql结合的方式，
关系型表放到mysql中，譬如优惠券，购物车，订单等，
非关系型数据表（非关系型代表不会出现多表强事务类型操作）
放到mysql中，缓存用redis，搜索用ElasticSearch（目前用的
mongodb的fullTextSearch，mongodb的搜索有分词功能，
相对来说可以应付一般的搜索）。


作者QQ：2358269014

github: https://github.com/fancyecommerce/yii2_fecshop

[![Latest Stable Version](https://poser.pugx.org/fancyecommerce/fecshop/v/stable)](https://packagist.org/packages/fancyecommerce/fecshop) [![Total Downloads](https://poser.pugx.org/fancyecommerce/fecshop/downloads)](https://packagist.org/packages/fancyecommerce/fecshop) [![Latest Unstable Version](https://poser.pugx.org/fancyecommerce/fecshop/v/unstable)](https://packagist.org/packages/fancyecommerce/fecshop)

**Fecshop文档(撰写中)**：[Fecshop Doc Guide](http://www.fecshop.com/doc/fecshop-guide/cn-1.0/guide-index.html#)



项目状态：

> 项目已经开始,本项目由Terry筹划，预计到2017-05-01出来第一个正式版本。

架构特色：参看详细介绍：[Fecshop 架构特色](http://www.fecshop.com/doc/fecshop-guide/cn-1.0/guide-fecshop-about-fecshop.html)

中文博客：[yii2 教程](http://www.fancyecommerce.com).




1、安装Fecsop
------------

本部分为fecshop的核心代码部分，
是以yii2扩展的方式制作，因此，您安装入口库包`fecshop app advanced`
，通过`composer update`, 本部分代码会以依赖包的方式被加载安装。

点击这里，进入[安装 fecshop app advanced](https://github.com/fancyecommerce/yii2_fecshop_app_advanced)
页面。

2、架构特色
-----------

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
model，组织数据，事务处理等操作，将数据结果返回给上层，这种设计可以方便以后业务
发展后，进而根据业务特点进行重构，或者以后如果出现新技术，新方式，
都重构成自己想要的样子，譬如，
将某个底层由mysql换成mongodb，或者为了应付高并发读写并且多事务性的功能部分，
进行分库分表的设计方式。

4. Fecshop[ 多模板系统](fecshop-feature-mutil-themes.md)，Fecshop设置了多个模板路径，各个模板路径下的文件被加载
的优先级不同，其中，Fecshop的模板路径下的文件最全面，但是优先级最低，
，第三方模板路径优先级其次，用户本地模板路径优先级最高，
用户可以通过
复制相应路径下的view或者js，css文件到本地模板路径，存在于高优先级
模板路径的文件会被优先加载，这样用户可以通过多模板系统的原理进行模板的
制作，同时，不影响Fecshop模板的升级，如果Fecshop view文件升级后被修改，
那么用户可以比对本地模板文件与升级模板文件的代码的不同，
复制更改的代码到本地模板路径
即可。第三方的模板路径的优先级介于本地模板路径和Fecshop
模板路径之间。

5. [重写机制](fecshop-feature-rewrite.md)，Fecshop的功能基本都可以被用户重写，包括servies层，Modules，
Controller，Block，Views，View Layout，
以及Js Css Img等，都可以被用户重写，其中 Js，Css，Img，Views，View Layout
 是通过多模板
路径优先级来实现的，其他的是通过配置文件的覆盖更改来实现重写，这样，用户
就可以很方便重构Fecshop或者第三方的功能和模板。

6. 升级最小化干扰，Fecshop的核心文件是放到vendor/fancyecommerce/fecshop
路径下面，和第三方扩展，用户二次开发路径完全隔离开，
Fecshop可以通过composer进行核心功能的升级，用户只需要通过composer升级
即可。

7. 快速高效，[Fecshop Servises](fecshop-services-abc.md)遵循Yii2的懒加载方式，只初始化使用到的组件服务，
缓存方面有整页缓存，block部分缓存，动态数据ajax加载等方式，让您的网站快速响应。

8. [Fecshop 多入口模式](fecshop-feature-mutil-entrances.md)，分为 appadmin（后台）， appfront（PC前端），apphtml5（手机web），
appserver（手机app服务），appapi（erp，或者其他接口对接），
不同的业务，不同的设备，进入不同的入口，各个入口共用服务层services，
但是modules部分独立，这样相互干扰最小，可以相互独立开发。

9. 后台封装化，fec_admin扩展可以快速的实现增删改查类型的表单列表，
方便用户快速的做增删改查。

鉴于以上特点，您可以下载安装fecshop，然后更改fecshop的模板和功能，扩展自己想要
的功能，或者安装第三方开发好了的扩展或者模板，来快速的组建起来您的网站。


