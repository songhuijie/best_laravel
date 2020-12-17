<?php

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\Index\IndexController;

Route::get('/user', [UserController::class, 'index']);

Route::get('/', function () {



    return phpinfo();
//    $content = '测试';
//    $to = "2677060927@qq.com";
//    $subject = '测试';
//    Mail::send('emails.test',['content'=>$content],function($message) use($to,$subject){
//        $message ->to($to)->subject($subject);
//    });
    return view('welcome');
});

Route::group(['namespace'=>'App\Http\Controllers'],function() {
    Route::get('/index','Index\IndexController@index');

});

Route::get('/tCode',[IndexController::class,'tnCode']);
Route::get('/words',[IndexController::class,'word']);
Route::get('/tests',[IndexController::class,'test']);

Route::get('/nCode',function(){
    return view('tncode');
});
Route::get('/test',function(){
    return view('test');
});
Route::post('/mycheck',function(\Illuminate\Http\Request $request){

    dd($request->all());
    return view('test');
});
