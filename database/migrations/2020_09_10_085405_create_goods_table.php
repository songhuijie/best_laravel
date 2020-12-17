<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use \Illuminate\Support\Facades\DB;
use Jialeo\LaravelSchemaExtend\Schema;


class CreateGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('商品名称');
            $table->integer('class_id')->comment('分类id');
            $table->decimal('price',8,2)->comment('价格');
            $table->tinyInteger('is_recommend')->default(0)->comment('是否推荐  0 默认不推荐 1推荐');
            $table->string('cover')->comment('封面图');
            $table->integer('sales')->default(0)->comment('销量');
            $table->integer('stock')->default(0)->comment('库存');
            $table->integer('integral')->default(0)->comment('此商品购买获得的积分');
            $table->text('introduce')->comment('描述');
            $table->tinyInteger('status')->default(1)->comment('状态 1.txt 默认上架 0 下架');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->comment = '商品';
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('goods');
    }
}
