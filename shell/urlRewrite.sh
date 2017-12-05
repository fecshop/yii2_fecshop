#!/bin/sh
Cur_Dir=$(cd `dirname $0`; pwd)
# init  full search collection indexes.
$Cur_Dir/../../../../yii  product/search/initindex
# get now update timestamp.
nowtime=`$Cur_Dir/../../../../yii  helper/urlrewrite/nowtime`

# 1.Product 遍历所有产品，把product的url_key 写入到url rewrite
# get product all count.
count=`$Cur_Dir/../../../../yii helper/urlrewrite/productcount`
pagenum=`$Cur_Dir/../../../../yii helper/urlrewrite/productpagenum`


echo "There are $count products to process"
echo "There are $pagenum products pages to process"
echo "##############ALL BEGINING###############";
for (( i=1; i<=$pagenum; i++ ))
do
   $Cur_Dir/../../../../yii helper/urlrewrite/product $i
   echo "Page $i done"
done


# 2.Category 遍历所有的分类，把category的url_key 写入到 url rewrite

count=`$Cur_Dir/../../../../yii helper/urlrewrite/categorycount`
pagenum=`$Cur_Dir/../../../../yii helper/urlrewrite/categorypagenum`


echo "There are $count categorys to process"
echo "There are $pagenum categorys pages to process"
echo "##############ALL BEGINING###############";
for (( i=1; i<=$pagenum; i++ ))
do
   $Cur_Dir/../../../../yii helper/urlrewrite/category $i
   echo "Page $i done"
done


# delete all search data that sync_updated_at $gt $nowtime.
# $Cur_Dir/../../../../yii  helper/urlrewrite/clearnoactive $nowtime

###### 1.Sync Section End




echo "##############ALL COMPLETE###############";





