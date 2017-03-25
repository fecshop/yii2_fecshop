#!/bin/sh
Cur_Dir=$(cd `dirname $0`; pwd)
echo "begin xml code"
$Cur_Dir/../../../../yii sitemap/xml/begin
echo "add home url to sitemap xml"
$Cur_Dir/../../../../yii sitemap/xml/home
echo "add category url to sitemap xml"
categoryPageCount=`$Cur_Dir/../../../../yii sitemap/xml/categorypagecount`

echo "There are $categoryPageCount page category to process"
for (( i=1; i<=$categoryPageCount; i++ ))
do
   $Cur_Dir/../../../../yii sitemap/xml/category $i
   echo "Page $i done"
done

echo "add product url to sitemap xml"
productPageCount=`$Cur_Dir/../../../../yii sitemap/xml/productpagecount`

echo "There are $productPageCount page product to process"
for (( i=1; i<=$productPageCount; i++ ))
do
   $Cur_Dir/../../../../yii sitemap/xml/product $i
   echo "Page $i done"
done

echo "add cms page url to sitemap xml"
cmsPagePageCount=`$Cur_Dir/../../../../yii sitemap/xml/cmspagepagecount`

echo "There are $cmsPagePageCount page product to process"
for (( i=1; i<=$cmsPagePageCount; i++ ))
do
   $Cur_Dir/../../../../yii sitemap/xml/cmspage $i
   echo "Page $i done"
done

echo "end xml code"
$Cur_Dir/../../../../yii sitemap/xml/end
echo 'end success'