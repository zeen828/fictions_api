<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Ranking extends Migration
{
    /**
     * Run the migrations.
     * 新版章節
     * 建立: php artisan make:migration ranking --path="database/migrations/20200901_books"
     * 執行: php artisan migrate --path="database/migrations/20200901_books/"
     * 回復: php artisan migrate:rollback --path="database/migrations/20200901_books"
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_ranking', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('ID');
            $table->string('name', 50)->nullable()->comment('名稱');
            $table->string('book_id')->nullable()->comment('書擊ID');
            $table->text('random_title')->nullable()->comment('隨機標題');
            $table->string('random_tag')->nullable()->comment('隨機標籤');
            $table->tinyInteger('status')->default(1)->comment('狀態(0:停用,1:啟用)');
            $table->timestamps();
            // 索引
            $table->index(['status'], 'query');
        });
        //TRUNCATE TABLE `cps_fiction`.`t_ranking`;

        //INSERT INTO `cps_fiction`.`t_ranking` (`id`, `name`, `book_id`, `random_title`, `random_tag`, `status`, `created_at`, `updated_at`)
        //SELECT `id`, `description`, `bookid`, `titles`, `tags`, 1, NOW(), NOW() FROM `cps_waitou`.`go_position`
        //LIMIT 20;
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_ranking');
    }
}
