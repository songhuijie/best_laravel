<?php

namespace App\Console\Commands;

use App\Models\Cart;
use App\Models\Department;
use App\Models\Detect;
use App\Providers\AppServiceProvider;
use App\Reflex\A;
use App\Reflex\B;
use Illuminate\Support\Facades\Log;
use function Couchbase\zlibCompress;
use function GuzzleHttp\Psr7\str;
use Illuminate\Console\Command;
use Illuminate\Container\Container;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }



    public function sendStudy($time){


        $username = '18080952663';
        $tenant_code = '95';
        $type =  'learn_time';
        $course_id = '914';
        $course_subject_id = '11222';
        $learn_time = '10';
        $play_time = $time;
        $token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJhY2NvdW50IjoiMTgwODA5NTI2NjMiLCJlbWFpbCI6IjE4MDgwOTUyNjYzQGNoaW5hbWNsb3VkLmNvbSIsImV4cCI6MTYwNzEyODM4MiwibG9naW5UeXBlIjoiZGVmYXVsdCIsIm1yb2xlcyI6W3siY21jcm9sZSI6IiIsImNvZGUiOiIiLCJjcmVhdGVkX2F0IjoiMjAyMC0wMy0wNCAxMDo1MzoyMyIsImRlc2NyaXB0aW9uIjoiIiwiZGlzcGxheV9uYW1lIjoi5a2m55SfIiwiaWQiOjI2NCwiaXNkZWZhdWx0IjoiMCJ9XSwibXN0cnVjdHVyZXMiOlt7ImNvZGUiOiJjZGhhaWMiLCJjcmVhdGVkX2F0IjoiMjAyMC0xMi0wMiAxNDoxMjowNCIsImRpc3BsYXlfbmFtZSI6IuaIkOmDveWXqOWIm-enkeaKgOaciemZkOWFrOWPuCIsImlkIjoxMjc0LCJsZXZlbCI6MSwicGFyZW50Ijp7ImNvZGUiOiIiLCJjcmVhdGVkX2F0IjoiMDAwMS0wMS0wMSAwMDowMDowMCIsImRpc3BsYXlfbmFtZSI6IiIsImlkIjowLCJsZXZlbCI6MCwicGNvZGUiOiIiLCJwaWQiOjB9LCJwY29kZSI6IiIsInBpZCI6MH1dLCJwYXNzd29yZCI6IjU4YTNlMDEwMjUwNjcxYmY5MGQ2ODAwMTFlYjE0MjdjIiwicGhvbmUiOiIxODA4MDk1MjY2MyIsInJlYWxuYW1lIjoi5a6L5oWn5p2wIiwic3ViIjo0ODQxODksInRlbmFudF9pZCI6OTV9._TGNhUiZeB0GLXB_3tgNRktdMDEUukRE_TFhKiPEtSo';

        $str = '{"username":"'.$username.'","tenant_code":"'.$tenant_code.'","type":"'.$type.'","course_id":"'.$course_id.'","course_subject_id":"'.$course_subject_id.'","learn_time":'.$learn_time.',"play_time":'.$play_time.',"token":"'.$token.'"}';

        $study = exec("curl 'https://livingroom.zt100.com/courseapi/v2/course/record-create' \
  -H 'authority: livingroom.zt100.com' \
  -H 'pragma: no-cache' \
  -H 'cache-control: no-cache' \
  -H 'accept: application/json, text/plain, */*' \
  -H 'authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJhY2NvdW50IjoiMTgwODA5NTI2NjMiLCJlbWFpbCI6IjE4MDgwOTUyNjYzQGNoaW5hbWNsb3VkLmNvbSIsImV4cCI6MTYwNzEyODM4MiwibG9naW5UeXBlIjoiZGVmYXVsdCIsIm1yb2xlcyI6W3siY21jcm9sZSI6IiIsImNvZGUiOiIiLCJjcmVhdGVkX2F0IjoiMjAyMC0wMy0wNCAxMDo1MzoyMyIsImRlc2NyaXB0aW9uIjoiIiwiZGlzcGxheV9uYW1lIjoi5a2m55SfIiwiaWQiOjI2NCwiaXNkZWZhdWx0IjoiMCJ9XSwibXN0cnVjdHVyZXMiOlt7ImNvZGUiOiJjZGhhaWMiLCJjcmVhdGVkX2F0IjoiMjAyMC0xMi0wMiAxNDoxMjowNCIsImRpc3BsYXlfbmFtZSI6IuaIkOmDveWXqOWIm-enkeaKgOaciemZkOWFrOWPuCIsImlkIjoxMjc0LCJsZXZlbCI6MSwicGFyZW50Ijp7ImNvZGUiOiIiLCJjcmVhdGVkX2F0IjoiMDAwMS0wMS0wMSAwMDowMDowMCIsImRpc3BsYXlfbmFtZSI6IiIsImlkIjowLCJsZXZlbCI6MCwicGNvZGUiOiIiLCJwaWQiOjB9LCJwY29kZSI6IiIsInBpZCI6MH1dLCJwYXNzd29yZCI6IjU4YTNlMDEwMjUwNjcxYmY5MGQ2ODAwMTFlYjE0MjdjIiwicGhvbmUiOiIxODA4MDk1MjY2MyIsInJlYWxuYW1lIjoi5a6L5oWn5p2wIiwic3ViIjo0ODQxODksInRlbmFudF9pZCI6OTV9._TGNhUiZeB0GLXB_3tgNRktdMDEUukRE_TFhKiPEtSo' \
  -H 'user-agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36' \
  -H 'content-type: application/json' \
  -H 'origin: https://livingroom.zt100.com' \
  -H 'sec-fetch-site: same-origin' \
  -H 'sec-fetch-mode: cors' \
  -H 'sec-fetch-dest: empty' \
  -H 'referer: https://livingroom.zt100.com/flex?id=914&sub_id=11221&tenant_code=95' \
  -H 'accept-language: zh-CN,zh;q=0.9,en;q=0.8,zh-TW;q=0.7' \
  -H 'cookie: _token=857b3ac0a66e36f0cecdb26d51bd3de236c0c5a079d26f45449e6b3ca6031099a%3A2%3A%7Bi%3A0%3Bs%3A6%3A%22_token%22%3Bi%3A1%3Bs%3A913%3A%22eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJhY2NvdW50IjoiMTgwODA5NTI2NjMiLCJlbWFpbCI6IjE4MDgwOTUyNjYzQGNoaW5hbWNsb3VkLmNvbSIsImV4cCI6MTYwNzEyODM4MiwibG9naW5UeXBlIjoiZGVmYXVsdCIsIm1yb2xlcyI6W3siY21jcm9sZSI6IiIsImNvZGUiOiIiLCJjcmVhdGVkX2F0IjoiMjAyMC0wMy0wNCAxMDo1MzoyMyIsImRlc2NyaXB0aW9uIjoiIiwiZGlzcGxheV9uYW1lIjoi5a2m55SfIiwiaWQiOjI2NCwiaXNkZWZhdWx0IjoiMCJ9XSwibXN0cnVjdHVyZXMiOlt7ImNvZGUiOiJjZGhhaWMiLCJjcmVhdGVkX2F0IjoiMjAyMC0xMi0wMiAxNDoxMjowNCIsImRpc3BsYXlfbmFtZSI6IuaIkOmDveWXqOWIm-enkeaKgOaciemZkOWFrOWPuCIsImlkIjoxMjc0LCJsZXZlbCI6MSwicGFyZW50Ijp7ImNvZGUiOiIiLCJjcmVhdGVkX2F0IjoiMDAwMS0wMS0wMSAwMDowMDowMCIsImRpc3BsYXlfbmFtZSI6IiIsImlkIjowLCJsZXZlbCI6MCwicGNvZGUiOiIiLCJwaWQiOjB9LCJwY29kZSI6IiIsInBpZCI6MH1dLCJwYXNzd29yZCI6IjU4YTNlMDEwMjUwNjcxYmY5MGQ2ODAwMTFlYjE0MjdjIiwicGhvbmUiOiIxODA4MDk1MjY2MyIsInJlYWxuYW1lIjoi5a6L5oWn5p2wIiwic3ViIjo0ODQxODksInRlbmFudF9pZCI6OTV9._TGNhUiZeB0GLXB_3tgNRktdMDEUukRE_TFhKiPEtSo%22%3B%7D; Hm_lvt_33c7bda3efa65a44890b2247ed5c9747=1607042050; Hm_lvt_33c7bda3efa65a44890b2247ed5c9747=1607042050,1607047626; Hm_lpvt_33c7bda3efa65a44890b2247ed5c9747=1607047626; PHPSESSID=26tdaq590tgsr1pm32h5ao1mg7; Hm_lpvt_33c7bda3efa65a44890b2247ed5c9747=1607047652' \
  --data-binary '$str' \
        --compressed");


        dump($study);
    }
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $count = 0;
        for($i=1;$i<=10;$i++){
            $data = [];
            for($j=1;$j<=5000;$j++){
                $data[] = [
                    'user_id'=>3,
                    'title'=>'测试'.($i-1)*$j,
                    'inspector'=>'检测人员'.($i-1)*$j,
                    'burden_value'=>'{"p001":"\u8fa3","a00002":2,"a0002":1,"a0003":888,"a00004":999}',
                ];
            }
            Detect::insert($data);
        }
        dd('success');

        $str = '给定一个字符串 s，找到 s 中最长的回文子串。你可以假设s 的最大长度为 1000。

示例 1：

输入: "babad"
输出: "bab"
注意: "aba" 也是一个有效答案。
示例 2：

输入: "cbbd"
输出: "bb"

来源：力扣（LeetCode）
链接：https://leetcode-cn.com/problems/longest-palindromic-substring
著作权归领扣网络所有。商业转载请联系官方授权，非商业转载请注明出处。
';



        $s = "cbbd";
        $len = strlen($s);
        $count = 0;
        $tmp = '';
        for($i=0;$i<$len;$i++){
            $int_or_bool = substr_count($s,$s[$i]);
            if($int_or_bool > $count){
                $count = $int_or_bool;
                $tmp = substr($s, $i, $int_or_bool);
            }
        }
//        return $tmp;


        dd($tmp);
        dd('success');

//        dd(strtotime(date('Y-m-d').' 13:51') - 28800, strtotime(date('Y-m-d H:i:s')));
//        $for = 90*60/10;
//        $time = 18400;
//
//        dd($this->sendStudy($time));
//        for($i=0;$i<=$for;$i++){
//            $time+=10;
//            $this->sendStudy($time);
//        }
//        dd('success');

        dd(md5('123456'));

            $str2 = "agv项目 1.txt.Vmware虚拟机以及ubuntu 18.0.4镜像安装 项目安装部署完毕 代码正常执行 2.虚拟机网络不能ping通 agv机器 mes系统不能相互交互 设置了路由和网关并不能解决 风险：不能百分百解决问题。处理办法：跟银川沟通后将nat网络模式替换成桥接模式 尝试ping agv机器吗，问题尚未解决，其他解决方案，设置桥接自定义ip 暂未尝试 公交车 1.txt.更改途径公交,和公交详情 查询方式并处理接口数据返回数据提供给小程序 风险：无。处理办法：根据之前代码进行更改处理。诊所体检小程序 1.txt.根据测试提出的问题更改问题,ho后台demo 接入后台接口调试，改界面功能 。风险：无。处理办法：根据之前代码进行更改处理，增加逻辑,调试以及自测";
        dd(mb_strlen($str2));


        dd(md5(123456));

        $result = $this->addTwoNumbers(123,234);

        dd($result);
        return 0;
    }

    function addTwoNumbers($l1, $l2) {

        return $this->recurision($l1,$l2);

    }

    function recurision($l1, $l2, $plus = false) {
        if ($l1 && $l2) {
            $sum = $l1->val + $l2->val;
            if ($plus) {
                $plus = false;
                $sum += 1;
            }
            if ($sum > 9) {
                $sum = $sum -10;
                $plus = true;
            }
            $l1->val = $sum;
        } else {
            if ($l2 && !$l1) {
                $l1 = clone $l2;
                $l1->next = null;
            }
            if ($plus) {
                $plus = false;
                $l1->val += 1;
            }
            if ($l1->val > 9) {
                $plus = true;
                $l1->val = 0;
            }
        }
        if ($l1->next or $l2->next) {
            $l1->next = $this->recurision($l1->next, $l2->next, $plus);
        } else {
            if ($plus) {
                $next = clone $l1;
                $next->next = null;
                $next->val = 1;
                $l1->next = $next;
            } else
                $l1->next = null;

        }
        return $l1;
    }



}
