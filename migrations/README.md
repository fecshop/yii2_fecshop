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
./yii mongodb-migrate/create  --interactive=0 --migrationPath=@fecshop/migrations/mongodb
```









