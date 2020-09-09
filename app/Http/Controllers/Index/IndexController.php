<?php
/**
 * Created by PhpStorm.
 * User: songhuijie
 * Date: 2020-09-09
 * Time: 10:49
 */

namespace App\Http\Controllers\Index;

use App\Events\Mails;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class IndexController extends Controller{


    public function index(){

        $email = '2677060927@qq.com';
        $subject = '测试标题';
        $message = '内容信息';
//        event(new Mails($email,$subject,$message));
//        Mails::dispatch($email,$subject,$message);
        $content = $message;
        $to = $email;
        try{
            Mail::send('emails.test',['content'=>$content],function($message) use($to,$subject){
                $message ->to($to)->subject($subject);
            });
            Log::info('发送邮件结果'.Mail::failures());
        }catch (\Exception $e){
            Log::info('发送邮件失败'.$e->getMessage());

        }
        Log::info('发送邮件');
        return response()->json(['code'=>200]);
    }

}
