# 小說後端端 & 後台 (Laravel)

## 基礎資訊
API提供前端串接
使用GraphiQL的API方式
後台管理程式
 
##### 版本
laravel/framework           -5.8.*
noh4ck/graphiql             -@dev
rebing/graphql-laravel      -^5.1

##### 專案安裝
```php
git clone https://rainy820605@bitbucket.org/rainy820605/graphiql_api.git wt_api
sudo chown www:www -R wt_api

composer install
sudo chown www:www -R vendor/
sudo chmod -R 777 storage

ln -s /usr/share/nginx/website/wt_api/storage/app/public /usr/share/nginx/website/wt_api/public/storage

cp .env.example .env
sudo chown www:www .env
```

##### 後台渠道包樣板處理
複製渠道樣板到作業目錄
```
cp -R public/channel/ storage/app/public/channel/
sudo chown www:www -R storage/app/public/channel/
sudo chmod -R 777 storage/app/public/channel/

```

###### 安裝問題排除
如果出現Please provide a valid cache path.
他需要正確的緩存路徑
去添加緩存預設資料夾
storage/framework/cache
storage/framework/sessions
storage/framework/views

如果無法正式啟用請清除他的混存
參考下面指令"清緩存"


##### 上傳檔案路由設定
```
ln -s /usr/share/nginx/website/wt_api/storage/app/public /usr/share/nginx/website/wt_api/public/storage

或

Route::get('/foo', function () {
    Artisan::call('storage:link');
});
```
laravel的原始上船路徑在設定是storage/app/public所以要做個連結到public下有兩種方法擇一即可

##### 本機運行
```php
php artisan serve
```

##### 清緩存
```php
composer clear-cache
composer dump-autoload
php artisan cache:clear
php artisan view:clear
php artisan config:cache
php artisan route:clear
php artisan route:list
```

## 目錄
<details>
<summary>展开查看</summary>
<pre><code>
├── app
│   ├── Admin
│   │   ├── Actions
│   │   ├── Controllers (後台控制)
│   │   ├── Extensions
│   │   ├── bootstrap.php
│   │   └── routes.php (後台路由)
│   ├── Console (排程)
│   │   ├── Commands
│   │   │   ├── Analysis
│   │   │   │   └── Hour.php (每小時統計::未完成)
│   │   │   └── Promotes
│   │   │       └── PackageJob.php (渠道包產生器)
│   │   └── Kernel.php
│   ├── Exceptions
│   ├── GraphQL
│   ├── Http
│   ├── Model
│   └── Providers
├── bootstrap
├── config
├── database
├── public
├── resources
├── routes
├── storage
├── tests
├── vendor
├── .env.example
├── composer.json
├── composer.lock
├── package.json
├── README.md
└── server.php
</code></pre>
</details>

## Laravel 指令
##### 資料庫
```php
php artisan migrate --path="database/migrations/"
php artisan migrate --path="database/migrations/20200818_user/"
php artisan migrate --path="database/migrations/20200902_domains/"
php artisan migrate --path="database/migrations/20200824_order/"
php artisan migrate --path="database/migrations/20200901_books/"
php artisan migrate --path="database/migrations/20200918_tags/"
php artisan migrate --path="database/migrations/20200922_books/"
php artisan migrate --path="database/migrations/20200922_ga/"
php artisan migrate --path="database/migrations/20200924_channel/"
php artisan migrate --path="database/migrations/20200925_apk/"
php artisan migrate --path="database/migrations/20201007_log/"
php artisan migrate --path="database/migrations/20201015_ring_add_rr/"

php artisan db:seed --class=AdminUserSeeder
php artisan db:seed --class=AdminMenuSeeder
php artisan db:seed --class=OrderPaymentSeeder
php artisan db:seed --class=SystemDomainSeeder
```

##### Laravel Model
```php
php artisan make:model Model/Users/User
php artisan make:model Model/Domains/Domain
php artisan make:model Model/Books/Bookinfo
php artisan make:model Model/Books/Bookchapter
php artisan make:model Model/Books/Booktype
php artisan make:model Model/Rankings/Ranking
php artisan make:model Model/Rankings/RankingBooks
php artisan make:model Model/Orders/Payment
php artisan make:model Model/Orders/Amount
php artisan make:model Model/Orders/Order
php artisan make:model Model/Books/BookUserRead
php artisan make:model Model/Analysis/AnalysisUser
php artisan make:model Model/Analysis/AnalysisChannel
php artisan make:model Model/Promotes/Channel
php artisan make:model Model/Promotes/Apk
php artisan make:model Model/Promotes/ChannelApk
php artisan make:model Model/Analysis/LogsUsersAccess
php artisan make:model Model/Analysis/LogsBooksRanking
php artisan make:model Model/Admin/AdminUsers
php artisan make:model Model/Admin/AdminRoles
```

##### Laravel Controller
```PHP

```

## Laravel Admin 指令
##### Admin Controller
```php
php artisan admin:make Fictions\UserController --model=App\Model\Users\User
php artisan admin:make Fictions\DomainsController --model=App\Model\Domains\Domain
php artisan admin:make Fictions\Books\BookinfoController --model=App\Model\Books\Bookinfo
php artisan admin:make Fictions\Books\BookchapterController --model=App\Model\Books\Bookchapter
php artisan admin:make Fictions\Books\BooktypeController --model=App\Model\Books\Booktype
php artisan admin:make Fictions\Books\RankingController --model=App\Model\Rankings\Ranking
php artisan admin:make Fictions\Books\RankingBooksController --model=App\Model\Rankings\RankingBooks
php artisan admin:make Fictions\Payments\PaymentController --model=App\Model\Orders\Payment
php artisan admin:make Fictions\Payments\AmountController --model=App\Model\Orders\Amount
php artisan admin:make Fictions\OrderController --model=App\Model\Orders\Order
php artisan admin:make Fictions\Promotes\ChannelController --model=App\Model\Promotes\Channel
php artisan admin:make Fictions\Promotes\ApkController --model=App\Model\Promotes\Apk
php artisan admin:make Fictions\Promotes\ChannelApkController --model=App\Model\Promotes\ChannelApk
php artisan admin:make Fictions\Analysis\AnalysisUserController --model=App\Model\Analysis\AnalysisUser
php artisan admin:make Fictions\Analysis\AnalysisChannelController --model=App\Model\Analysis\AnalysisChannel
php artisan admin:make Management\AdminUsersController --model=App\Model\Admin\AdminUsers
```

## 套件安裝
##### 使用Redis安裝套件
```
composer require predis/predis
```

##### JWT
https://jwt.io/
https://github.com/firebase/php-jwt

##### GraphiQL測試工具
http://127.0.0.1:8000/graphql-ui
```php
Type::string();  // String type
Type::int();     // Int type
Type::float();   // Float type
Type::boolean(); // Boolean type
Type::id();      // ID type

Type::nonNull(Type::listOf(Type::string())),
```

##### 自訂API路由
修改這個文件(配合改成JAVA路由解少支付改CODE)
app/Providers/RouteServiceProvider.php

## 備註
##### 回源網址
###### 開發
```
IP：8.210.90.180
前端：dev.numyue.cn:930
API：dev.numyue.cn:931
後台：dev.numyue.cn:932
APK：dev.numyue.cn:933
```

###### 正式
```
前端：www.numyue.cn
API：graphiql.numyue.cn
後台：admin.numyue.cn
APK：apk.numyue.cn
```

###### 香港LBS
```
新小說-VUE：8.210.15.58(公网IPv4)
新小說-API：8.210.169.57(公网IPv4)
新小說-後台：8.210.147.108(公网IPv4)
新小說-APK：47.242.30.241(公网IPv4)
```

##### 支付共用JAVA
http://graphiql.numyue.cn
查訂單
/2/cartoon/pay/payNotifyOrder
```json
{
    "orderid":"WT020200826cjO2xJYl0Jk"
}
```

支付完成
/2/cartoon/pay/payNotify
```json
{
	"outTradeNo":"WT020200826cjO2xJYl0Jk",
	"money":"50.00",
	"transactionTime":1598246389000,
	"transactionId":"4200000693202008247589622085"
}
```

##### OSS搬移(JASON安裝)
https://help.aliyun.com/document_detail/56990.html

CentOS下載
```
wget http://gosspublic.alicdn.com/ossimport/standalone/ossimport-2.3.4.zip?spm=a2c63.p38356.879954.7.25121015za0Al6&file=ossimport-2.3.4.zip
mv ossimport-2.3.4.zip\?spm\=a2c63.p38356.879954.7.25121015za0Al6 ossimport-2.3.4.zip
unzip ossimport-2.3.4.zip
rm __MACOSX/ -Rf
```

CentOS安裝JAVA
```
yum install openjdk
yum install java-1.8.0-openjdk
```

設定
```
cd ossimport-2.3.4
cd conf
vim local_job.cfg
```

執行
```
vim console.sh
vim import.sh
sh console.sh
sh import.sh
vim sys.properties
```

### APP OSS
https://cps-books-img.oss-cn-hongkong.aliyuncs.com/app/fictions.json

##### 渠道打包APP
/mnt/apk
client
version-client

##### 清除資料庫
```SQL
TRUNCATE TABLE `admin_operation_log`;
TRUNCATE TABLE `t_users`;
TRUNCATE TABLE `t_point_log`;
TRUNCATE TABLE `t_book_user_read`;
TRUNCATE TABLE `t_order`;
TRUNCATE TABLE `t_channels`;
TRUNCATE TABLE `t_analysis_user`;
TRUNCATE TABLE `t_analysis_channel`;
```

##### 刪除資料庫
```SQL
DROP TABLE `admin_menu`;
DROP TABLE `admin_operation_log`;
DROP TABLE `admin_permissions`;
DROP TABLE `admin_role_menu`;
DROP TABLE `admin_role_permissions`;
DROP TABLE `admin_role_users`;
DROP TABLE `admin_roles`;
DROP TABLE `admin_user_permissions`;
DROP TABLE `admin_users`;
DROP TABLE `migrations`;
DROP TABLE `t_amounts`;
DROP TABLE `t_analysis_channel`;
DROP TABLE `t_analysis_user`;
DROP TABLE `t_apk`;
DROP TABLE `t_book_booktype`;
DROP TABLE `t_book_user_read`;
DROP TABLE `t_bookchapter`;
DROP TABLE `t_bookinfo`;
DROP TABLE `t_booktype`;
DROP TABLE `t_channels`;
DROP TABLE `t_channels_apk`;
DROP TABLE `t_domains`;
DROP TABLE `t_logs_books_ranking`;
DROP TABLE `t_logs_users_access`;
DROP TABLE `t_order`;
DROP TABLE `t_payment_amount`;
DROP TABLE `t_payments`;
DROP TABLE `t_point_log`;
DROP TABLE `t_ranking`;
DROP TABLE `t_users`;
```

## 參考
https://github.com/z-song/laravel-admin

rebing/graphql-laravel
Book新增刪除修改範例
https://www.twilio.com/blog/build-graphql-powered-api-laravel-php

https://cps-hc.oss-cn-shenzhen.aliyuncs.com/book/74037/1612178.txt?Expires=1597387844&OSSAccessKeyId=TMP.3KetnFZFgXHhhm6YcigjB9sFWCccNz1tgnDL1oi2DmmvgZRidLVGMJFZoSg9LLvYCxT9nQDmcVmZnfAd7GFKGS3Hc7p9Mt&Signature=hobAkVbYtKpg7lTezlXmzSRgNQI%3D

https://github.com/laravel-admin-extensions/chartjs

https://laravel.io/forum/09-15-2015-redis-and-pagination

ICON
https://adminlte.io/themes/AdminLTE/pages/UI/icons.html

## JASON Linux 備註
測試用

http://8.210.90.180:930/getmyip

最新版NGINX安裝
http://nginx.org/en/linux_packages.html#RHEL-CentOS
可參考/root/geoip/install.sh

高併發變數
vim /etc/sysctl.conf
複製檔案
sysctl -p

系統限制
/etc/systemd/system/nginx.service.d
建目錄
複製檔案
systemctl daemon-reload 
重啟NGINX

監控
/usr/local/sbin
ps_mem.py

排程
/etc/supervisord.d/
tailf /logs/Php_Apk_Update.log
systemctl restart supervisord
systemctl enable supervisord
查狀態
systemctl status supervisord

查詢運行狀態
ps aux  // 執行序
bwm-ng  // 查主機流量
sscon 	// 看記憶體
free	//

