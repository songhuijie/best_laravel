<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use \Illuminate\Support\Facades\DB;
use App\Services\MySqlGrammar\Schema;
//use Illuminate\Support\Facades\Schema;
//use Jialeo\LaravelSchemaExtend\Schema;

class CreateWeUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('we_users', function (Blueprint $table) {
            $table->id();
            $table->string('nick_name')->comment('用户昵称');
            $table->string('portrait')->comment('用户头像');
            $table->integer('sex')->default(1)->comment('1为男2为女');
            $table->string('country')->default('')->comment('用户国家');
            $table->string('city')->default('')->comment('用户省市');
            $table->string('phone')->default('')->comment('手机号');
            $table->string('access_token')->comment('access_token');
            $table->integer('expires_in')->comment('token 过期时间');
            $table->string('user_openid')->comment('用户openid');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->comment = '微信用户2';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('we_users');
    }
}
