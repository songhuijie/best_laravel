<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use \Illuminate\Support\Facades\DB;
use Jialeo\LaravelSchemaExtend\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->comment('订单编号');
            $table->integer('user_id')->comment('下单用户');
            $table->bigInteger('phone')->comment('下单用户电话');
            $table->text('goods_detail')->comment('商品详情（多商品）');
            $table->text('address_detail')->nullable()->comment('地址详情');
            $table->integer('total_num')->comment('数量');
            $table->decimal('total_price',10,2)->comment('总价');
            $table->tinyInteger('status')->default(0)->comment('订单状态 0 已预订/待支付  1.txt 支付中  2 完成支付 3 退款');
            $table->tinyInteger('order_type')->default(1)->comment('订单类型 1.txt 正常订单  2 联名卡订单');
            $table->tinyInteger('order_delivery')->default(1)->comment('交付方式 1.txt 到店消费 2 快递配送');
            $table->string('remarks')->default('')->comment('备注');
            $table->string('express')->default('')->comment('快递单号');
            $table->integer('express_type')->default(0)->comment('快递类型');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->comment = '订单';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
