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
use App\Models\Detect;
use App\Models\DetectsClass;
use App\Services\RedisServices;
use App\Services\TnCode;
use App\Models\UserDetection;
use Illuminate\Support\Carbon;
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
//        try{
//            Mail::send('emails.test',['content'=>$content],function($message) use($to,$subject){
//                $message ->to($to)->subject($subject);
//            });
//            Log::info('发送邮件结果'.Mail::failures());
//        }catch (\Exception $e){
//            Log::info('发送邮件失败'.$e->getMessage());
//
//        }
        //分类 classification
        Log::info('发送邮件');
        $ip = request()->ip();
        $remote_ip = request()->getClientIp();
//        $ifconfig_ip = exec('curl ifconfig.me');//TODO 太慢
        return response()->json(['code'=>200,'ip'=>$ip,'remote_ip'=>$remote_ip]);
    }



    public function tnCode(){
        $tn  = new TnCode();
        if($tn->check()){
            $_SESSION['tncode_check'] = 'ok';
            echo "ok";
        }else{
            $_SESSION['tncode_check'] = 'error';
            echo "error";
        }
    }


    public function word(){

        $phpWord = new \PhpOffice\PhpWord\PhpWord();

        $section = $phpWord->addSection();
        $section->addText(
            '"Learn from yesterday, live for today, hope for tomorrow. '
            . 'The important thing is not to stop questioning." '
            . '(Albert Einstein)'
        );

        $fontStyle = new \PhpOffice\PhpWord\Style\Font();
        $fontStyle->setBold(true);
        $fontStyle->setName('Tahoma');
        $fontStyle->setSize(13);
        $myTextElement = $section->addText('"Believe you can and you\'re halfway there." (Theodor Roosevelt)');
        $myTextElement->setFontStyle($fontStyle);

//        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
//        $objWriter->save('helloWorld.docx');

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'HTML');
        $objWriter->save('helloWorld.html');

        return 'success';
    }

    public function test(){

        $excelServer = new \App\Services\ExcelDownload();
        $header = ['title'=>'Item', 'value'=>'Cost'];
        $arr = [];
        for($i=0;$i<=1000000;$i++){
            $arr[] = ['Rent',(100000+$i)];
        }

        try{
            $excelServer->download(array_values($header),$arr,'测试');
        }catch (\Exception $exception){
            dd($exception->getMessage());
        }

        dd('success');


        $start_time1 = time();
        $user_id = 3;
        $detect_ids = UserDetection::where('user_id',$user_id)->pluck('detect_id')->toArray();

        $arr = [];
        $header = $this->handleExportTitle($detect_ids);
        Detect::select('title','inspector','burden_value','created_at')->where('user_id',$user_id)->chunk(10000,function($detects) use(&$arr){
            foreach($detects as $detect){
                $arr[] = array_merge([
                    (string) Carbon::parse($detect['created_at']->toDateTimeString()),
                    $detect['inspector'],
                    $detect['title'],
                ],array_values($detect['burden_value']));
            }
            return $arr;
        });

        try{
            $start_time2 = time();
            $excelServer->download(array_values($header),$arr,'测试');
            $end_time = time();
            Log::info('导出花费时间:');
            Log::info($end_time-$start_time1);
            Log::info('花费总时间:');
            Log::info($end_time-$start_time2);
            Log::info('程序处理时间:');
            Log::info($start_time2-$start_time1);
            Log::info("\n");

            unset($arr);
        }catch (\Exception $e){
            return '导出失败';
        }

        dd(2);
    }

    //todo 处理自定义导出头
    protected function handleExportTitle($detect_ids){
        $detects_class_name = RedisServices::DetectsClassesName();
        $tmp = [];
        $title = ['created_at'=>'日期','inspector'=>'检测人员','title'=>'项目'];
        if($detect_ids){
            foreach($detect_ids as $v){
                $tmp['burden_value_'.$v] = $detects_class_name[$v] ?? '';
            }
        }
        return array_merge($title,$tmp);
    }
}
