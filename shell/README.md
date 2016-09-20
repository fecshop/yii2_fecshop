脚本说明
========

### 1. fullSearchSync.sh

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
同时会同步信息到搜索表，但是不会处理删除掉的表
因此，执行这个价格完成后，还需要执行 fullSearchSync.sh脚本。









