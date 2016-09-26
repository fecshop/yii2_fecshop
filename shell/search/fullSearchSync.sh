#!/bin/sh
Cur_Dir=$(cd `dirname $0`; pwd)
# init  full search collection indexes.
$Cur_Dir/../../../../../yii  product/search/initindex
# get now update timestamp.
nowtime=`$Cur_Dir/../../../../../yii  product/search/nowtime`

###### 1.Sync Section : Sync Product  Serach Collection
# get product all count.
count=`$Cur_Dir/../../../../../yii product/search/synccount`
pagenum=`$Cur_Dir/../../../../../yii product/search/syncpagenum`


echo "There are $count products to process"
echo "There are $pagenum pages to process"
echo "##############ALL BEGINING###############";
for (( i=1; i<=$pagenum; i++ ))
do
   $Cur_Dir/../../../../../yii product/search/syncdata $i
   echo "Page $i done"
done
# ()delete all search data that sync_updated_at $gt $nowtime.
$Cur_Dir/../../../../../yii  product/search/deletenotactiveproduct $nowtime

echo "##############ALL COMPLETE###############";





