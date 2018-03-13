<p>
  <a href="http://fecshop.appfront.fancyecommerce.com/">
    <img src="http://img.appfront.fancyecommerce.com/custom/logo.png">
  </a>
</p>
<br/>




[![Latest Stable Version](https://poser.pugx.org/fancyecommerce/fecshop/v/stable)](https://packagist.org/packages/fancyecommerce/fecshop) [![Total Downloads](https://poser.pugx.org/fancyecommerce/fecshop/downloads)](https://packagist.org/packages/fancyecommerce/fecshop) [![Latest Unstable Version](https://poser.pugx.org/fancyecommerce/fecshop/v/unstable)](https://packagist.org/packages/fancyecommerce/fecshop)




项目状态：


> Fecshop开源项目已经**全部开发完毕**，一共六大入口：pc端（appfront）,wap端（apphtml5），后台（appadmin）
> ，vue端（appserver），第三方系统对接端（appapi），后台脚本端（console），都全部开发完毕，
> 您可以使用Fecshop用于您的线上电商项目，
> Fecshop是一个功能齐全的开源电商系统，偏框架，适合程序员深入学习使用，
> 二开比较容易，欢迎大家使用Fecshop开发自己的电商项目。



1、Fecshop介绍
------------

[Fecshop](http://www.fecshop.com) 全称为Fancy ECommerce Shop，是基于php Yii2框架之上开发的一款优秀的开源电商系统，
Fecshop支持多语言，多货币，架构上支持pc，手机web，手机app，和erp对接等入口，您可以免费快速的定制和部署属于您的电商系统。

详细参看地址：[Fecshop介绍](http://www.fecshop.com/doc/fecshop-guide/develop/cn-1.0/guide-fecshop-about-description.html)

[Fecshop](http://www.fecshop.com) 官网：http://www.fecshop.com ，您可以在这里提交bug，问题咨询等等。

[Fecshop](http://www.fecshop.com) 【已完成】PC Web Demo ：http://fecshop.appfront.fancyecommerce.com/

[Fecshop](http://www.fecshop.com) 【已完成】Mobile Web Demo（WAP）：http://fecshop.apphtml5.fancyecommerce.com

[Fecshop](http://www.fecshop.com) 【已完成】Mobile VUE Demo(Appserver,前后端彻底分离模式)：http://demo.fancyecommerce.com/#/

[Fecshop](http://www.fecshop.com) 后台演示地址：加QQ群，在群公告里面有后台演示地址，账号密码等信息

[Fecshop](http://www.fecshop.com) QQ群：186604851 ，入群验证：fecshop


作者, Terry QQ Email：2358269014@qq.com

[Fecshop](http://www.fecshop.com) Github地址: https://github.com/fancyecommerce/yii2_fecshop

开源协议：[Fecshop 授权协议](http://www.fecshop.com/license)

2、Fecshop文档，视频，扩展库
------------

**二开以及安装文档：** [Fecshop 安装开发文档](http://www.fecshop.com/doc/fecshop-guide/develop/cn-1.0/guide-README.html)【撰写完成】

**使用帮助说明文档：** [Fecshop 使用帮助文档](http://www.fecshop.com/doc/fecshop-guide/instructions/cn-1.0/guide-README.html)【撰写完成】

**安装开发帮助视频：** [Fecshop 安装开发视频](http://www.fecshop.com/doc/fecshop-guide/instructions/cn-1.0/guide-fecshop_video_install.html)【撰写完成】

**前后端分离模式Api:** [Appserver VUE Api文档](http://www.fecshop.com/doc/fecshop-guide/develop/cn-1.0/guide-fecshop-server.html)【撰写完成】

**Fecshop 扩展:** [Fecshop 官方扩展列表](http://www.fecshop.com/doc/fecshop-guide/develop/cn-1.0/guide-fecshop-pkg-list.html)


3、安装Fecshop
------------

> 请务必按照说明安装，Fecshop是基于composer在线安装，直接git clone下载下来是不行的，请按照下面的说明操作

3.1、【标准方式】标准安装方式：从基础linux一步一步的配置的方式，详细参看文档： [Fecshop 安装](http://www.fecshop.com/doc/fecshop-guide/develop/cn-1.0/guide-fecshop-about-hand-install.html)

3.2、【强烈推荐】docker compose快速部署 
：由于上面的安装操作需要安装的东西比较多，过程比较繁杂，
建议使用docker compose，参看：
[docker compose快速部署Fecshop](https://github.com/fecshop/yii2_fecshop_docker)

3.3、【不推荐】vagrant box的方式：2017.6月做的box，以后不再维护vagrant box，
推荐使用docker compose安装，如果您想使用vagrant box可以参看：
[Fecshop vagrant安装](http://www.fecshop.com/doc/fecshop-guide/develop/cn-1.0/guide-fecshop-about-vagrantinstall.html)

4、Fecshop 配置：
----------------

配置详细参看：[Fecshop 配置](http://www.fecshop.com/doc/fecshop-guide/develop/cn-1.0/guide-fecshop-about-config.html)

如果您使用docker compose或者 vagrant box的安装方式，不需要执行这个操作。

5、架构特色
-----------

架构特色：参看详细介绍：[Fecshop 架构特色](http://www.fecshop.com/doc/fecshop-guide/develop/cn-1.0/guide-fecshop-about-framework.html)

下面是简叙：

Fecshop 全称为Fancy ECommerce Shop，是一款优秀的开源电商系统，遵循BSD-3-Clause协议(和Yii2框架一样的开源协议)，
目的是为了方便yii2用户快速的
开发商城，Fecshop作为一款可以持续性发展的商城系统，
在框架层面有以下特性：

1. 由于商城系统的复杂性，原始的框架MVC结构，显的有点力不从心，Fecshop框架
加入了Block层，
Controller层只负责调度， Model只负责数据库映射，中间的处理逻辑由block来完成，View层
负责显示，这样各司其职， 以免造成controller文件过于庞大。

2. 加入独立功能块，有点类似Yii2的Widget，目的是为了让一些侧栏公用块
可以通过配置的方式
添加，同时，还可以具有设置缓存的功能，譬如分类侧栏的产品推荐，
newsletter等独立显示块可能在很多
页面用到，通过独立功能块可以配置方便的载入。

3. 在Model层的上层加入服务层Services，这样，Controller，Block，View 层，在原则上
不能直接调用model，必须通过Services层以及子Services层，然后Services访问各个
model，组织数据，事务处理等操作，
将数据结果返回给上层，这种设计可以方便以后业务
发展后，进而根据业务特点进行重构，或者以后如果出现新技术，新方式，
都重构成自己想要的样子，譬如，
将某个底层由mysql换成mongodb，或者为了应付高并发读写并且多事务性的功能部分，
进行分库分表的设计方式。

4. [Fecshop](http://www.fecshop.com)多模板系统，[Fecshop](http://www.fecshop.com)设置了多个模板路径，各个模板路径下的文件被加载
的优先级不同，其中，Fecshop的模板路径下的文件最全面，但是优先级最低，
，第三方模板路径优先级其次，用户本地模板路径优先级最高，
用户可以通过
复制相应路径下的view或者js，css文件到本地模板路径，存在于高优先级
模板路径的文件会被优先加载，这样用户可以通过多模板系统的原理进行模板的
制作，同时，不影响[Fecshop](http://www.fecshop.com)模板的升级，如果[Fecshop](http://www.fecshop.com) view文件升级后被修改，
那么用户可以比对本地模板文件与升级模板文件的代码的不同，
复制更改的代码到本地模板路径
即可。第三方的模板路径的优先级介于本地模板路径和Fecshop
模板路径之间。

5. 重写机制，[Fecshop](http://www.fecshop.com)的功能基本都可以被用户重写，包括servies层，Modules，
Controller，Block，Views，View Layout，
以及Js Css Img等，都可以被用户重写，其中 Js，Css，Img，Views，View Layout
 是通过多模板
路径优先级来实现的，其他的是通过配置文件的覆盖更改来实现重写，这样，用户
就可以很方便重构[Fecshop](http://www.fecshop.com)或者第三方的功能和模板。

6. 升级最小化干扰，[Fecshop](http://www.fecshop.com)的核心文件是放到vendor/fancyecommerce/fecshop
路径下面，和第三方扩展，用户二次开发路径完全隔离开，
Fecshop可以通过composer进行核心功能的升级，用户只需要通过composer升级
即可。

7. 快速高效，Fecshop Servises遵循Yii2的懒加载方式，只初始化使用到的组件服务，
缓存方面有整页缓存，block部分缓存，动态数据ajax加载等方式，让您的网站快速响应。

8. [Fecshop](http://www.fecshop.com) 多入口模式，分为 appadmin（后台）， appfront（PC前端），apphtml5（手机web），
appserver（手机app服务），appapi（erp，或者其他接口对接），
不同的业务，不同的设备，进入不同的入口，各个入口共用服务层services，
但是modules部分独立，这样相互干扰最小，可以相互独立开发。

9. 后台封装化，fec_admin扩展可以快速的实现增删改查类型的表单列表，
方便用户快速的做增删改查。

鉴于以上特点，您可以下载安装[Fecshop](http://www.fecshop.com)，然后更改[Fecshop](http://www.fecshop.com)的模板和功能，扩展自己想要
的功能，或者安装第三方开发好了的扩展或者模板，来快速的组建起来您的网站。

6、捐赠
-----------

如果您认为这是一个不错的项目，对您有帮助，你可以通过下面的方式进行捐赠，
这里感谢您对开源项目的支持。

支付宝：

![](http://www.fecshop.com/alipay.png)

微信：

![](http://www.fecshop.com/weixin.png)


[捐赠历史](http://www.fecshop.com/donate)：（捐助，建议写一下留言）

| 捐赠人        | 金额      |  时间            |  方式           | 账户                  | 捐助者留言      |
| --------------| ----------| -------------    | -----------     | ----------------------| ----------|
| 饭饭          | ￥120.00  | 2018-03-13       |   支付宝        | 420***@qq.com         | 为2年半的坚持加油    |
| kingsee       | ￥6.66    | 2018-03-13       |   支付宝        | kin***@gmail.com      | -    |
| （*）         | ￥10.00   | 2018-03-13       |   微信          | -                     | 感谢为开源做出的贡献  |
| yangfch3      | ￥66.66   | 2018-03-13       |   支付宝        | 875***@qq.com| 感谢为开源默默付出的工程师们    |
| （*）         | ￥66.66   | 2018-03-08       |   微信          | -                     | 已经在线上项目使用了，多谢Terry开发的这么好的开源系统。|
| （*）         | ￥8.88    | 2018-03-06       |   微信          | -                     | 好项目，在学习。支持|
| 华生          | ￥8.88    | 2018-02-22       |   支付宝        | 294***@qq.com         | 不错的开源项目，支持一下|
| （*）         | ￥6.66    | 2018-02-18       |   微信          | -                     | 诚意的开源祝项目66顺 |
| 剑清          | ￥99.99   | 2018-01-03       |   支付宝        | 262***@qq.com         | 很好的项目，祝越做越好，长久发展|
| （*）         | ￥2.00    | 2017-12-28       |   微信          | -                     | -     |
| （*）         | ￥6.60    | 2017-12-21       |   微信          | -                     | -     |
| 水哥          | ￥99.99   | 2017-12-20       |   支付宝        | dd_***@sohu.com       | 祝fecshop发展永久|
| （*）         | ￥9.90    | 2017-12-11       |   微信          | -                     | 希望长久！|
| （*）         | ￥100.00  | 2017-10-21       |   微信          | -                     | 希望你坚持下去成为更多人的榜样|
| （*）         | ￥100.00  | 2017-09-27       |   微信          | -                     | -     |
| （*）         | ￥2.00    | 2017-09-18       |   微信          | -                     | 赞一个|
| （*）         | ￥1.00    | 2017-09-15       |   微信          | -                     | -     |










