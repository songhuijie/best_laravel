<?php
callback();
function callback(){
    $data = file_get_contents("php://input");

//    此处可以打印下日志  TODO
//    $file = fopen('callback.log', "a") or self::_log('unable open file CALLBACK.LOG');
//    fwrite($file, $data);

    $result = json_decode($data,true);
    var_dump($result);

}
