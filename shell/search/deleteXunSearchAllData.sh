#!/bin/sh
Cur_Dir=$(cd `dirname $0`; pwd)

count=`$Cur_Dir/../../../../../yii product/search/synccount`
pagenum=`$Cur_Dir/../../../../../yii product/search/syncpagenum`

###### delete xunsearch 
echo "There are $pagenum pages to check if is delete in xunSearch"
echo "##############ALL BEGINING###############";
for i in `seq $pagenum`
do
   $Cur_Dir/../../../../../yii product/search/xundeleteallproduct $i
   echo "Page $i done"
done


