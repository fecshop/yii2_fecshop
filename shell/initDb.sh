#!/bin/sh
#processDate=$1
#Cur_Dir=$(pwd)
Cur_Dir=$(cd `dirname $0`; pwd)
#fec_admin
$Cur_Dir/../../../../yii migrate --migrationPath=@fecadmin/migrations

#db
$Cur_Dir/../../../../yii migrate --migrationPath=@fecshop/migrations/db/product/log

#mongodb
$Cur_Dir/../../../../yii mongodb-migrate --migrationPath=@fecshop/migrations/mongodb/urlwrite
$Cur_Dir/../../../../yii mongodb-migrate --migrationPath=@fecshop/migrations/mongodb/product/log



