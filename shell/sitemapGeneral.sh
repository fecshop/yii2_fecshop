#!/bin/sh
Cur_Dir=$(cd `dirname $0`; pwd)

$Cur_Dir/../../../../yii sitemap/xml/begin

$Cur_Dir/../../../../yii sitemap/xml/home

categoryPageCount=`$Cur_Dir/../../../../yii sitemap/xml/categorypagecount`

echo "There are $categoryPageCount page product to process"
for (( i=1; i<=$categoryPageCount; i++ ))
do
   $Cur_Dir/../../../../yii sitemap/xml/category $i
   echo "Page $i done"
done


$Cur_Dir/../../../../yii sitemap/xml/end
echo 'end success'