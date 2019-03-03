#!/bin/sh
Cur_Dir=$(cd `dirname $0`; pwd)
# get product all count.
count=`$Cur_Dir/../../../../../yii order/item/count`
pagenum=`$Cur_Dir/../../../../../yii order/item/pagenum`

echo "There are $count order products to process"
echo "There are $pagenum pages to process"
echo "##############ALL BEGINING###############";
for (( i=1; i<=$pagenum; i++ ))
do
   $Cur_Dir/../../../../../yii order/item/computesellercount $i
   echo "Page $i done"
done

echo "##############ALL COMPLETE###############";