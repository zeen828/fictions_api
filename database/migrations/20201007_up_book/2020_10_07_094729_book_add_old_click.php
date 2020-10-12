<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BookAddOldClick extends Migration
{
    /**
     * Run the migrations.
     * 
     * 建立: php artisan make:migration book_add_old_click --path="database/migrations/20201007_up_book"
     * 執行: php artisan migrate --path="database/migrations/20201007_up_book/"
     * 回復: php artisan migrate:rollback --path="database/migrations/20201007_up_book"
     * 
     * @return void
     */
    public function up()
    {
        // Schema::table('t_analysis_user', function (Blueprint $table)
        // {
        //     $table->integer('app_user_acc_hour')->default(0)->comment('每小時訪問數')->after('app_user_acc');
        //     $table->integer('wap_user_acc_hour')->default(0)->comment('每小時訪問數')->after('app_user_acc');
        // });
        // Schema::table('t_analysis_channel', function (Blueprint $table)
        // {
        //     $table->integer('app_user_acc_hour')->default(0)->comment('每小時訪問數')->after('app_user_acc');
        //     $table->integer('wap_user_acc_hour')->default(0)->comment('每小時訪問數')->after('app_user_acc');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('t_analysis_user', function (Blueprint $table)
        // {
        //     $table->dropColumn('wap_user_acc_hour');// 刪除欄位
        //     $table->dropColumn('app_user_acc_hour');// 刪除欄位
        // });
        // Schema::table('t_analysis_channel', function (Blueprint $table)
        // {
        //     $table->dropColumn('wap_user_acc_hour');// 刪除欄位
        //     $table->dropColumn('app_user_acc_hour');// 刪除欄位
        // });
    }
}
