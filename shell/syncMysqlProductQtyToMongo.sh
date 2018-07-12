#!/bin/sh
Cur_Dir=$(cd `dirname $0`; pwd)
# get product all count.
count=`$Cur_Dir/../../../../yii product/qty/synccount`
pagenum=`$Cur_Dir/../../../../yii product/qty/syncpagenum`

echo "There are $count products to process"
echo "There are $pagenum pages to process"
echo "##############ALL BEGINING###############";
for (( i=1; i<=$pagenum; i++ ))
do
   $Cur_Dir/../../../../yii product/qty/sync $i
   echo "Page $i done"
done

###### 1.Sync Section End

echo "##############ALL COMPLETE###############";

