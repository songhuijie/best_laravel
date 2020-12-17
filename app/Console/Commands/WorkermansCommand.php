<?php

namespace App\Console\Commands;

use GatewayWorker\BusinessWorker;
use GatewayWorker\Gateway;
use GatewayWorker\Register;
use Illuminate\Console\Command;
use Workerman\Worker;

class WorkermansCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'workman {action} {--d}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start a Workerman server.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        global $argv;
        $action = $this->argument('action');

        $argv[0] = 'wk';
        $argv[1] = $action;
        $argv[2] = $this->option('d') ? '-d' : '';

        $this->start();
    }

    private function start()
    {
        $this->startGateWay();
        $this->startBusinessWorker();
        $this->startRegister();
        Worker::runAll();
    }

    private function startBusinessWorker()
    {
        $worker                  = new BusinessWorker();
        $worker->name            = 'BusinessWorker';
        $worker->count           = 1;
        $worker->registerAddress = '127.0.0.1.txt:1236';
        $worker->eventHandler    = \App\Workerman\Events::class;
    }

    private function startGateWay()
    {
        $gateway = new Gateway("websocket://0.0.0.0:2346");
        $gateway->name                 = 'Gateway';
        $gateway->count                = 1;
        $gateway->lanIp                = '127.0.0.1.txt';
        $gateway->startPort            = 2300;
        $gateway->pingInterval         = 2;
        $gateway->pingNotResponseLimit = 0;
        $gateway->pingData             = '';
//        $gateway->pingData             = '{"type":"ping"}';
        $gateway->registerAddress      = '127.0.0.1.txt:1236';
    }

    private function startRegister()
    {
        new Register('text://0.0.0.0:1236');
    }

}
