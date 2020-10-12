<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Booktype extends Migration
{
    /**
     * Run the migrations.
     * 新版書籍分類
     * 建立: php artisan make:migration booktype --path="database/migrations/20200901_books"
     * 執行: php artisan migrate --path="database/migrations/20200901_books/"
     * 回復: php artisan migrate:rollback --path="database/migrations/20200901_books"
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_booktype', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('ID');
            $table->string('name', 50)->comment('名稱');
            $table->string('description')->nullable()->comment('描述');
            $table->tinyInteger('sex')->default(0)->comment('性別(0:男,1:女)');
            $table->string('color')->nullable()->comment('顏色');
            $table->integer('sort')->default(0)->comment('排序');
            $table->tinyInteger('status')->default(1)->comment('狀態(0:停用,1:啟用)');
            $table->timestamps();
            // 唯一
            $table->unique('name', 'un_name');
            // 索引
            $table->index(['sex', 'status'], 'query');
        });
        //TRUNCATE TABLE `cps_fiction`.`t_booktype`;

        //INSERT INTO `cps_fiction`.`t_booktype` (`name`, `description`, `sex`, `sort`, `status`, `created_at`, `updated_at`)
        //SELECT `typename`, `remark`, if(`sextype` = 1, 0, 1), `sort`, if(`status` = 1, 1, 0), now(), now() FROM `cps_waitou`.`go_booktype`
        //LIMIT 20;
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_booktype');
    }
}
