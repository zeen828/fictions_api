<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Books extends Migration
{
    /**
     * Run the migrations.
     * 新版書籍
     * 建立: php artisan make:migration books --path="database/migrations/20200901_books"
     * 執行: php artisan migrate --path="database/migrations/20200901_books/"
     * 回復: php artisan migrate:rollback --path="database/migrations/20200901_books"
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_bookinfo', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('ID');
            $table->tinyInteger('mode')->default(0)->comment('模式(0:OSS,1:文字,2:圖片,3:音頻)');
            $table->string('name', 50)->nullable()->comment('名稱');
            $table->text('description')->nullable()->comment('描述');
            $table->string('author', 50)->nullable()->comment('作者');
            $table->string('tags')->nullable()->comment('標籤');
            $table->integer('tid')->default(0)->comment('分類');
            $table->string('cover')->nullable()->comment('封面');
            $table->string('cover_h')->nullable()->comment('封面-橫');
            $table->integer('size')->default(0)->comment('字數');
            $table->tinyInteger('nature')->default(0)->comment('性質(1:男頻,2:女頻,3:中性)');
            $table->timestamp('new_at')->nullable()->comment('新書日期');
            $table->tinyInteger('end')->default(0)->comment('完結(0:連載,1:完結)');
            $table->tinyInteger('open')->default(0)->comment('完全上架(0:還有章節未開放,1:完全開放)');
            $table->tinyInteger('free')->default(0)->comment('免費(0:停用,1:啟用)');
            $table->tinyInteger('recom')->default(0)->comment('推薦(0:停用,1:啟用)');
            $table->integer('recom_chapter_id')->default(0)->comment('推薦章節');
            $table->tinyInteger('vip')->default(0)->comment('VIP專屬(0:普通,1:VIP專屬)');
            $table->tinyInteger('search')->default(0)->comment('搜尋(0:全戰搜,1:前台不可,2:後台不可,3:全站不可)');
            $table->integer('click_w')->default(0)->comment('周點擊');
            $table->integer('click_m')->default(0)->comment('月點擊');
            $table->integer('click_s')->default(0)->comment('總點擊');
            $table->integer('click_o')->default(0)->comment('外頭點擊擊');// 追加在click_s後面
            $table->integer('gid')->default(0)->comment('所属规则id?');
            $table->integer('index')->default(0)->comment('派單指數?');
            $table->tinyInteger('status')->default(2)->comment('狀態(0:停用,1:啟用,2:待審)');
            $table->timestamps();
            // 索引
            $table->index(['mode', 'tid', 'nature', 'end', 'open', 'free', 'search', 'status'], 'query');
        });
        //TRUNCATE TABLE `cps_fiction`.`t_bookinfo`;
        
        //INSERT INTO `cps_fiction`.`t_bookinfo` (`id`, `mode`, `name`, `description`, `author`,
        //`tags`, `tid`, `cover`, `size`, `nature`,
        //`end`, `open`, `free`, `recom`,
        //`recom_chapter_id`, `vip`, `search`, `click_w`, `click_m`,
        //`click_s`, `gid`, `index`, `status`, `created_at`,
        //`updated_at`)
        //SELECT `id`, `store_mode`, `book_title`, `description`, `book_author`,
        //`tag`, `tid`, `book_img`, `booksize`, CASE WHEN `book_property` = 1 THEN 0 WHEN `book_property` = 2 THEN 1 ELSE 2 END,
        //`book_status`, if(`open_finish` = 1, 1, 0), `free`, if(`is_recom` = 2, 1, 0),
        //`recom_chapter`, if(`is_vip` = 1, 1, 0), CASE WHEN `is_search` = 1 THEN 0 WHEN `is_search` = 2 THEN 1 WHEN `is_search` = 3 THEN 2 ELSE 3 END, `bookclickw`, `bookclickm`,
        //`bookclick`, `gid`, `book_index`, CASE WHEN `is_offline` = 1 THEN 1 WHEN `is_offline` = 2 THEN 0 ELSE 2 END, `create_t`,
        //`create_t`
        //FROM `cps_waitou`.`go_bookinfo`
        //LIMIT 20;
        
        //FROM_UNIXTIME(`update_time`, '%d-%m-%Y %h:%i:%s')
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_bookinfo');
    }
}
