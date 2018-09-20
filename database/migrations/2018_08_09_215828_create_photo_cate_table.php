<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhotoCateTable extends Migration
{
    protected $table = 'photo_cate';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable($this->table)) {
            Schema::create('photo_cate', function (Blueprint $table) {
                //表的设计
                $table->engine = 'InnoDB';
                $table->charset = 'utf8mb4';
                $table->collation = 'utf8mb4_general_ci';
                //表的数据结构
                $table->increments('id')->comment('主键ID');
                $table->integer('userid')->comment('用户id');
                $table->string('pname')->comment('相册名称');
                $table->integer('pid')->nullable()->default(0)->comment('相册分类0-顶级分类');
                $table->smallInteger('share')->nullable()->default(2)->comment('是否分享1-分享2-不分享');
                $table->smallInteger('status')->nullable()->default(1)->comment('是否使用1-使用2-不适用');
                $table->smallInteger('is_del')->nullable()->default(1)->comment('是否删除1-不删除2-删除');
                $table->smallInteger('is_pv_use')->nullable()->default(1)->comment('观看权限1-所有人2-指定人3-自己');
                $table->dateTime('created_at')->nullable()->comment('创建于');
                $table->dateTime('updated_at')->nullable()->comment('更新于');
            });
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable($this->table) || \DB::table($this->table)->count() <1) {
            Schema::dropIfExists('photo_cate');
        }

    }
}
