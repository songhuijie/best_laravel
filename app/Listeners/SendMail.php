<?php

namespace App\Listeners;

use App\Events\Mails;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
class SendMail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Mails  $event
     * @return void
     */
    public function handle(Mails $event)
    {
        //
        $to       = $event->email;
        $subject  = $event->subject;
        $content  = $event->content;

        try{
            Mail::send('emails.test',['content'=>$content],function($message) use($to,$subject){
                $message ->to($to)->subject($subject);
            });
            Log::info('发送邮件结果'.Mail::failures());
        }catch (\Exception $e){
            Log::info('发送邮件失败'.$e->getMessage());

        }


    }
}
