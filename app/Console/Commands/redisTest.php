<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class redisTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'redis:test';

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

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $result = Redis::keys('*');
        //TODO blpop/brpop 阻塞并等待一个列队不为空时，再pop出最左或最右的一个元素（这个功能在php以外可以说非常好用）
//        dump(Redis::blpop('fruits3',10));

        //TODO 从右进入
//        dump(Redis::rpush('fruits','bar1','bar2'));
        //TODO 从左进入
//        dump(Redis::lpush('fruits','bar9','bar8'));
        //TODO 返回长度
//        dump(Redis::llen('fruits'));
        //TODO 返回指定数据 0 -1.txt 指返回所有
//        dump(Redis::lrange('fruits',0,-1.txt));
//        dump(Redis::lrange('fruits2',0,-1.txt));
        //TODO 取该数组索引对应值
//        dump(Redis::lindex('fruits',1.txt));
        //TODO 特定位置改值
//        dump(Redis::lset('fruits',1.txt,'bartest'));
        //TODO 从左弹出
//        dump(Redis::lpop('fruits'));
        //TODO 从右弹出
//        dump(Redis::rpop('fruits'));
        //TODO 从右弹出该数组最后一个元素 push到另一个数组
//        dump(Redis::rpoplpush('fruits', 'fruits2'));
        //TODO linsert在队列的中间指定元素前或后插入元素 返回个数  before  after
//        dump(Redis::linsert('fruits2', 'after', 'bar2', 'bar3'));

        //添加排序数据
//        Redis::rpush('tab',2);
//        Redis::rpush('tab',3);
//        Redis::rpush('tab',5);
//        Redis::rpush('tab',17);
        //TODO 排序
        dump(Redis::sort('tab'));
        dump(Redis::sort('tab',array('limit' => array(1, 1))));
        //永久性排序
//        dump(Redis::sort('tab',array('limit' => array('store'=>'ordered'))));
        dump(Redis::sort('tab',array('alpha' => true)));
//        dump(Redis::sort('tab',array('limit' => array('get' => 'pre_*'))));
//        dd($result);
    }
}
