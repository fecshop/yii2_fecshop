<p>
  <a href="http://fecshop.appfront.fancyecommerce.com/">
    <img src="http://www.fecmall.com/images/logo.png">
  </a>
</p>
<br/>




[![Latest Stable Version](https://poser.pugx.org/fancyecommerce/fecshop/v/stable)](https://packagist.org/packages/fancyecommerce/fecshop) [![Total Downloads](https://poser.pugx.org/fancyecommerce/fecshop/downloads)](https://packagist.org/packages/fancyecommerce/fecshop) [![Latest Unstable Version](https://poser.pugx.org/fancyecommerce/fecshop/v/unstable)](https://packagist.org/packages/fancyecommerce/fecshop)

Fecshop更名为FecMall
--------------

详细参看：[关于Fecshop更名为FecMall的原因](http://www.fecmall.com/topic/2020)


--------------


Fecmall-2.x版本
--------------

>Fecmall-2版本在用户体验方面进行了很多的优化，请安装Fecmall-2版本


[Fecmall-1.x版本](README-1.X.MD)


项目状态：


> Fecmall开源项目已经**全部开发完毕**，一共六大入口：pc端（appfront）,wap端（apphtml5），后台（appadmin）
> ，vue端（appserver），第三方系统对接端（appapi），后台脚本端（console），都全部开发完毕，
> 您可以使用Fecmall用于您的线上电商项目，
> Fecmall是一个功能齐全的开源电商系统，偏框架，适合程序员深入学习使用，
> 二开比较容易，欢迎大家使用Fecmall开发自己的电商项目。



1、Fecmall介绍
------------


[Fecmall](http://www.fecmall.com) 全称为Fancy ECommerce Mall，是基于php Yii2框架之上开发的一款优秀的开源电商系统，
Fecmall支持多语言，多货币，架构上支持pc，手机web，手机app，和erp对接等入口，您可以免费快速的定制和部署属于您的电商系统。

详细参看地址：[Fecmall介绍](http://www.fecmall.com/doc/fecshop-guide/develop/cn-1.0/guide-fecshop-about-description.html)

[Fecmall](http://www.fecmall.com) 官网：http://www.fecmall.com ，您可以在这里提交bug，问题咨询等等。

[Fecmall](http://www.fecmall.com) 【已完成】PC Web Demo ：[http://fecshop.appfront.fancyecommerce.com](http://fecshop.appfront.fancyecommerce.com/men?fec_campaign=xxx&fec_content=94&fec_design=96&fec_medium=Share&fec_source=Facebook&fid=7a77a6ba-2d90-4ad5-bf75-fdf920de41f7)

[Fecmall](http://www.fecmall.com) 【已完成】Mobile Web Demo（WAP）：[http://fecshop.apphtml5.fancyecommerce.com](http://fecshop.apphtml5.fancyecommerce.com?fec_campaign=xxxx&fec_content=94&fec_design=96&fec_medium=Adwords&fec_source=Facebook&fid=100000005)

[Fecmall](http://www.fecmall.com) 【已完成】Mobile VUE Demo(Appserver,前后端彻底分离模式)：[http://demo.fancyecommerce.com/#/](http://demo.fancyecommerce.com/#/?fec_campaign=xxxx&fec_content=94&fec_design=96&fec_medium=Adwords&fec_source=Facebook&fid=100000005)

[Fecmall](http://www.fecmall.com) 【已完成】微信小程序(Appserver,前后端彻底分离模式)：微信小程序搜索：Fecmall查看demo，或者扫下面的小程序码：

![wx_xiaochengxu_fecmall](wx_xiaochengxu_fecmall2.png)


[Fecmall](http://www.fecmall.com) 后台演示地址：加QQ群，在群公告里面有后台演示地址，账号密码等信息


作者, Terry Email：2358269014@qq.com

Fecmall QQ群号（新）：782387676，入群验证：fecmall
 
[Fecmall](http://www.fecmall.com) Github地址: https://github.com/fancyecommerce/yii2_fecshop

[Fecmall](http://www.fecmall.com) 码云地址: https://gitee.com/fecshopsoft/yii2_fecshop


Fecmall开源协议：[Fecmall 授权协议](http://www.fecmall.com/license)

Fecmall线上项目案例：http://www.fecmall.com/topic/55

Fecmall问题咨询，Bug提交等参看：[Fecmall论坛](http://www.fecmall.com/topic)

Fecmall开源项目历程：[Fecmall时间线](http://www.fecmall.com/site/timeline)


Fecmall Trace 网站流量广告分析系统
----------------------

> golang + mongodb + elasticSearch 做的一套用户行为分析系统， 管理系统界面使用了vue admin， 已经和fecmall无缝对接完成，Appfront, Apphtml5, Appserver三个入口都打通数据对接，尤其是appserver这类vue类型的数据对接，该系统也是开源项目，通过js打点和php发送数据的2种方式收集数据，存入mongodb，然后通过golang脚本进行一系列的统计，结果数据传递到elasticSearch进行查询，该系统对于初始的数据统计，以及广告分析已经完善，详细可以参看下面的demo，对于您自己想要的数据分析，可以自己二次开发。

对于google analysis，百度统计等统计系统，收集的数据太少，譬如购物车数据，搜索数据，用户email，另外还有订单支付状态不准确的问题等等，另外，对于广告数据也不能满足要求，因此terry开发了一套网站流量广告系统来完善周围，更加详细的介绍，以及github源码地址参看文档

网站流量分析系统文档： http://www.fecmall.com/doc/fec-go-guide/develop/cn-1.0/guide-trace-about.html

> 2018年开始的项目，历经8个月开发完毕， 文档逐步完善， 有数据分析需求的童鞋可以使用

Fecmall Trace Demo: http://trace.fecshop.com

测试账户： test test123 （清不要修改密码，该账户有一定的权限限制）


2、Fecmall文档，视频，扩展库
------------

**二开以及安装文档：** [Fecmall 开发文档](http://www.fecmall.com/doc/fecshop-guide/develop/cn-2.0/guide-README.html)【撰写完成】

**使用帮助说明文档：** [Fecmall 帮助文档](http://www.fecmall.com/doc/fecshop-guide/instructions/cn-1.0/guide-README.html)【撰写完成】



3、安装Fecmall
------------

> 请务必按照说明安装，Fecmall是基于composer在线安装，**直接git clone下载下来**是不行的，请按照下面的说明操作

[Fecmall 安装教程文档](http://www.fecmall.com/doc/fecshop-guide/develop/cn-2.0/guide-fecshop-2-graphical-install.html)


4、联系Terry：
----------------

扫一扫添加Terry的微信：

![weixin](http://www.fecmall.com/weixin_terry.jpg)



5、架构特色
-----------

架构特色：参看详细介绍：[Fecmall 架构特色](http://www.fecmall.com/doc/fecshop-guide/develop/cn-1.0/guide-fecshop-about-framework.html)

下面是简叙：

Fecmall 全称为Fancy ECommerce Mall，是一款优秀的开源电商系统，遵循BSD-3-Clause协议(和Yii2框架一样的开源协议)，
目的是为了方便yii2用户快速的
开发商城，Fecmall作为一款可以持续性发展的商城系统，
在框架层面有以下特性：

1. 由于商城系统的复杂性，原始的框架MVC结构，显的有点力不从心，Fecmall框架
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

4. [Fecmall](http://www.fecmall.com)多模板系统，[Fecmall](http://www.fecmall.com)设置了多个模板路径，各个模板路径下的文件被加载
的优先级不同，其中，Fecmall的模板路径下的文件最全面，但是优先级最低，
，第三方模板路径优先级其次，用户本地模板路径优先级最高，
用户可以通过
复制相应路径下的view或者js，css文件到本地模板路径，存在于高优先级
模板路径的文件会被优先加载，这样用户可以通过多模板系统的原理进行模板的
制作，同时，不影响[Fecmall](http://www.fecmall.com)模板的升级，如果[Fecmall](http://www.fecmall.com) view文件升级后被修改，
那么用户可以比对本地模板文件与升级模板文件的代码的不同，
复制更改的代码到本地模板路径
即可。第三方的模板路径的优先级介于本地模板路径和Fecmall
模板路径之间。

5. 重写机制，[Fecmall](http://www.fecmall.com)的功能基本都可以被用户重写，包括servies层，Modules，
Controller，Block，Views，View Layout，
以及Js Css Img等，都可以被用户重写，其中 Js，Css，Img，Views，View Layout
 是通过多模板
路径优先级来实现的，其他的是通过配置文件的覆盖更改来实现重写，这样，用户
就可以很方便重构[Fecmall](http://www.fecmall.com)或者第三方的功能和模板。

6. 升级最小化干扰，[Fecmall](http://www.fecmall.com)的核心文件是放到vendor/fancyecommerce/fecshop
路径下面，和第三方扩展，用户二次开发路径完全隔离开，
Fecmall可以通过composer进行核心功能的升级，用户只需要通过composer升级
即可。

7. 快速高效，Fecmall Servises遵循Yii2的懒加载方式，只初始化使用到的组件服务，
缓存方面有整页缓存，block部分缓存，动态数据ajax加载等方式，让您的网站快速响应。

8. [Fecmall](http://www.fecmall.com) 多入口模式，分为 appadmin（后台）， appfront（PC前端），apphtml5（手机web），
appserver（手机app服务），appapi（erp，或者其他接口对接），
不同的业务，不同的设备，进入不同的入口，各个入口共用服务层services，
但是modules部分独立，这样相互干扰最小，可以相互独立开发。

9. 后台封装化，fec_admin扩展可以快速的实现增删改查类型的表单列表，
方便用户快速的做增删改查。

鉴于以上特点，您可以下载安装[Fecmall](http://www.fecmall.com)，然后更改[Fecmall](http://www.fecmall.com)的模板和功能，扩展自己想要
的功能，或者安装第三方开发好了的扩展或者模板，来快速的组建起来您的网站。

6、捐赠
-----------

如果您认为这是一个不错的项目，对您有帮助，你可以通过下面的方式进行捐赠，
这里感谢您对开源项目的支持。

支付宝：

![](http://www.fecmall.com/alipay.png)

微信：

![](http://www.fecmall.com/weixin.png)

[捐赠历史](http://www.fecmall.com/donate)：（捐助，建议写一下留言）

| 捐赠人        | 金额      |  时间            |  方式           | 账户                       | 捐助者留言      |
| -------------| ---------| -----------    | -----------   | ----------------------| ----------------|
| （*亮亮    | ￥100.00   | 2019-04-06  |   支付宝          | -                   |   支持榜样，标杆              |
| （*亮）      | ￥50.00   | 2019-04-06  |   微信          | -                     |    坚持，加油！             |
| （*树桓）    | ￥50.00   | 2019-03-30  |   支付宝          | -                   |                 |
| pptrue      | ￥50.00   | 2019-03-19  |   微信          | -                     |    支持开源，感谢Terry             |
| （*军）      | ￥188.00   | 2019-03-10  |   微信          | -                     |    为3年的坚持点赞            |
| （*浩）      | ￥66.00   | 2019-02-13  |   微信          | -                     |    支持fecshop长久发展             |
| （*嘉文）    | ￥0.10   | 2019-01-28  |   支付宝          | -                   |                 |
| （*凡）    | ￥10.00   | 2019-01-07  |   支付宝          |  -                  |  加油               |
| （y*g）      | ￥100.00   | 2018-12-26  |   微信          | -                     |    腾讯andehuang             |
| （董*）      | ￥10.00   | 2018-12-24  |   微信          | -                     |                 |
| （*间）      | ￥1.00   | 2018-12-14  |   微信          | -                     |                 |
| pptrue      | ￥100.00   | 2018-12-13  |   微信          | -                     |  很喜欢这个项目，感谢terry和大家的无私奉献。作为编程小白一名，我还在努力学习中。感觉最近生活都充实了                |
| （*树桓）    | ￥20.00   | 2018-11-30  |   支付宝          | -                     |                 |
| （*艺业）    | ￥1.00   | 2018-11-30  |   支付宝          | -                     |                  |
| （*泰）      | ￥100.00   | 2018-11-27  |   微信          | -                     |                  |
| （*潇）      | ￥10.00   | 2018-11-26  |   微信          | -                     |   well done               |
| （*少平）    | ￥1.00   | 2018-11-15  |   支付宝          | -                     | 学生，赚钱了再来捐，牛逼，看源码学习                 |
| （*）         | ￥120.00   | 2018-10-28  |   微信          | -                     | 尽我一点绵薄之力给你帮助。                  |
| （*）         | ￥20.00   | 2018-10-27  |   微信           | -                     | 支持一下                 |
| （*）         | ￥8.88   | 2018-10-26  |   微信          | -                     | 国产良心之作！！                 |
| （*华峰）    | ￥5.00   | 2018-10-19  |   支付宝          | -                     | 官网看着真不错，项目好用再来捐赠，开学了                 |
| （*A）        | ￥6.66   | 2018-09-21  |   微信          | -                     |  感谢fecshop                |
| （*大成）    | ￥1.00   | 2018-09-18  |   支付宝          | -                     |                  |
| （*江林）   | ￥1.00   | 2018-09-11  |   支付宝          | -                     |                  |
| （*）         | ￥500.00   | 2018-07-31  |   微信          | -                     |                  |
| （*）         | ￥15.00   | 2018-07-23  |   微信          | -                     |                  |
| （*）         | ￥15.00   | 2018-07-19  |   微信          | -                     |                  |
| （*）         | ￥99.99  | 2018-07-12       |   微信        | -          | 作为国内的真正开源系统，真是良心之作，支持，祝愿长久发展！   |
| *方招         | ￥30.00  | 2018-07-10       |   支付宝        | fan***gmail.com          | 感谢作者。非常不错的电商平台   |
| 厦门码农网络科技有限公司         | ￥16.66  | 2018-07-04       |   支付宝        | cod***@126.com           | 厦门码农支持开源，为Fecshop！   |
| *雷雷         | ￥800.00  | 2018-06-27       |   支付宝        | 134******22           | 支持作者，支持fecshop   |
| （*）         | ￥1.00    | 2018-06-22       |   微信          | -                     |  |
| *建欣         | ￥50.00   | 2018-06-15       |   支付宝        | 593***@qq.com         | 小小心意，支持fecshop的发展   |
| *宁           | ￥5.00    | 2018-06-08       |   支付宝        | pay***@itoumao.com    | 感谢作者的开源精神，同为程序猿的我做不到   |
| （*）         | ￥10.00   | 2018-06-06       |   微信          | -                     | 感谢作者，感谢开源  |
| *庆飞         | ￥188.00  | 2018-06-05       |   支付宝        | lin***@aliyun.com     | 逛v2看到的，支持开源，为你点赞！   |
| *桦           | ￥10.00   | 2018-05-24       |   支付宝        | -                     | 真心做得不错    |
| （*）         | ￥1.00    | 2018-05-17       |   微信          | -                     | 搞的不错  |
| （*）         | ￥13.14   | 2018-05-11       |   微信          | -                     | 北京智翔财务为你加油  |
| （*）         | ￥10.00   | 2018-05-04       |   微信          | -                     | 希望fecshop越做越好  |
| （*）         | ￥16.66   | 2018-04-20       |   微信          | -                     | 为你们打call  |
| （*）         | ￥6.60    | 2018-04-16       |   微信          | -                     | 不会用一yii，为开源  |
| （*）         | ￥166.00  | 2018-04-03       |   微信          | -                     | 为开源打call！  |
| *仲春         | ￥66.66   | 2018-03-19       |   支付宝        | -                     | 加油加油，准备学习学习    |
| （*）         | ￥10.00   | 2018-03-19       |   微信          | -                     | -  |
| （*）         | ￥8.88    | 2018-03-18       |   微信          | -                     | 为作者坚持和耐心点赞  |
| Simon         | ￥20.00   | 2018-03-16       |   支付宝        | 186***40              | 希望继续坚持开源事业    |
| （*）         | ￥8.88    | 2018-03-16       |   微信          | -                     | -  |
| （*）         | ￥100.00  | 2018-03-15       |   微信          | -                     | 干得漂亮  |
| （*）         | ￥3.33    | 2018-03-15       |   微信          | -                     | 感谢为开源做出的贡献  |
| xhq           | ￥2.00    | 2018-03-14       |   支付宝        | 143***@qq.com         | 好项目就是要让更多人知道    |
| （*）         | ￥2.00    | 2018-03-13       |   微信          | -                     | -  |
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










