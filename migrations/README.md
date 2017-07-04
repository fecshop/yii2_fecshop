
这里是执行的命令，在安装fecshop的时候，文档里面已经
写好了migrate的命令，您不需要再次执行下面的操作。

### 1. 生成迁移文件的命令：

1.1 生成mysql文件:

```
./yii migrate/create   --migrationPath=@fecshop/migrations/mysqldb    fecshop_tables
```

1.2 生成mongodb文件:

```
./yii mongodb-migrate/create   --migrationPath=@fecshop/migrations/mongodb    fecshop_tables
```


### 2. 迁移的命令（导入数据库表）

2.1 mysql(导入mysql的表，数据，索引):

```
./yii migrate --interactive=0 --migrationPath=@fecshop/migrations/mysqldb
```


2.2 mongodb(导入mongodb的表，数据，索引):

```
./yii mongodb-migrate  --interactive=0 --migrationPath=@fecshop/migrations/mongodb
```

2.2.2 mongodb的示例数据存放路径为：

/vendor/fancyecommerce/fecshop/migrations/mongodb-example-data/example_data.js

可以通过mongodb的后台，或者通过php的rockmongo安装这些mongodb中的示例数据。








