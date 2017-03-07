#!/bin/sh
#processDate=$1
#Cur_Dir=$(pwd)
Cur_Dir=$(cd `dirname $0`; pwd)

#db   
$Cur_Dir/../../../../yii migrate --interactive=0 --migrationPath=@fecshop/migrations/mysqldb
#mongodb
$Cur_Dir/../../../../yii mongodb-migrate  --interactive=0 --migrationPath=@fecshop/migrations/mongodb


