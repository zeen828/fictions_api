<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Chapter extends Migration
{
    /**
     * Run the migrations.
     * 新版章節
     * 建立: php artisan make:migration chapter --path="database/migrations/20200901_books"
     * 執行: php artisan migrate --path="database/migrations/20200901_books/"
     * 回復: php artisan migrate:rollback --path="database/migrations/20200901_books"
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_bookchapter', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('ID');
            $table->integer('book_id')->comment('書籍ID');
            $table->string('name', 100)->nullable()->comment('名稱');
            $table->text('content')->nullable()->comment('內容');
            $table->string('description')->nullable()->comment('描述');
            $table->text('next_description')->nullable()->comment('下章節描述');
            $table->string('oss_route')->nullable()->comment('OSS路徑');
            $table->tinyInteger('free')->default(0)->comment('免費(0:停用,1:啟用)');
            $table->integer('money')->default(0)->comment('點數');
            $table->integer('sort')->default(0)->comment('排序');
            $table->tinyInteger('status')->default(2)->comment('狀態(0:停用,1:啟用,2:待審)');
            $table->timestamps();
            // 索引
            $table->index(['book_id', 'free', 'sort', 'status'], 'query');
        });
        //TRUNCATE TABLE `cps_fiction`.`t_bookchapter`;

        //INSERT INTO `cps_fiction`.`t_bookchapter` (`id`, `book_id`, `name`,
        //`next_description`, `oss_route`, `free`, `money`, `sort`,
        //`status`, `created_at`, `updated_at`)
        //SELECT `id`, `tid`, `chapter_name`,
        //`nextcontent`, `route`, if(`is_money` = 2, 1, 0), `money`, `sort`,
        //CASE WHEN `is_offline` = 1 THEN 1 WHEN `is_offline` = 2 THEN 0 ELSE 2 END, NOW(), NOW()
        //FROM `cps_waitou`.`go_bookchapter`
        //LIMIT 20;
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_bookchapter');
    }
}
