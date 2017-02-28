脚本说明
========

### 1. search/fullSearchSync.sh

此脚本是初始化产品数据到相应的搜索引擎中的表中
目前只有mongodb的fullTextSearch 和Xun Search
其中mongodb fullTextSearch支持大多数国外字母系列语言
Xun Search 只支持中文，

本脚本做的事情：

1.1 初始化搜索引擎索引

1.2 循环遍历产品数据，将产品数据拷贝到相应的搜索表中
同时更新sync_updated_at字段时间戳。

1.3 时间戳小于sync_updated_at的代表没有被批量处理
，说明这个产品存在问题（产品被删除，noactive  无库存等）
会被最后的脚本，从搜索表中清空。

### 2.initDb.sh

本脚本为第一次安装的时候，初始化数据库。

### 3.computeProductFinalPrice.sh

本脚本为计算每一个产品的final_price的值，
3.1 同时会同步信息到搜索表，但是不会执行删除操作（譬如某个产品
删除了，这个脚本是不会检测，同步删除的，如果要删除需要执行 fullSearchSync.sh脚本。）
3.2 url rewrite的产品数据也会同步，同样不会执行删除url rewrite里面的数据（
如果要清除残留的url rewrite数据，请执行urlRewrite.sh脚本）。


### 4.urlRewrite.sh脚本

4.1 将产品的url自定义重新跑一次。包括分类，产品等
4.2 将urlRewrite表中残留的重新数据，但是在产品和分类中不存在的清空。

### 5.returnPendingProductQtyStock.sh
cron执行，将60分钟内没有支付的订单的库存释放，并将订单状态设置为cancel。

### 6.order/returnPendingProductQtyStock.sh
将一段时间内还是pending的订单的库存返还给产品。












