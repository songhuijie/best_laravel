<?php
/**
 * Created by PhpStorm.
 * User: songhuijie
 * Date: 2020-09-17
 * Time: 14:47
 */
namespace App\Services;

use App\Libraries\Lib_redis;
use App\Models\DetectsClass;
use Illuminate\Support\Facades\Redis;

class RedisServices{

    const EXPIRE_SECS = 180;

    private $redis;

    public function __construct()
    {
        $this->redis = new Redis();
    }

    protected static function getInfoByKey($key,$conn,$select){
        $redis_key = Lib_redis::SplicingKey($key);
        $result_json = Redis::get($redis_key);
        if($result_json){
            return json_decode($result_json,true);
        }else{
            return self::setInfoByKey($key,$conn,$select);
        }
    }

    function get($key){
        $value = $this->redis->get($key);
        if($value == null){
            //不存在，设置3min的超时，防止del操作失败的时候，下次缓存过期一直不能查询数据库
            if ($this->redis->setnx("key_mutex", 1, 3 * 60) == 1){
                $value = "";//这是查询数据库文件
                $this->redis->set('key', 'value', self::EXPIRE_SECS);
                $this->redis->del('key_mutex');
            }else{
                //这个时候代表同时候的其他线程已经查询数据库并回设到缓存了，这时候重试获取缓存值即可
                sleep(50);
                $this->get('key');  //重试
            }
        }else{
            //存在则直接返回
            return $value;
        }
    }


    //todo  检测id 和英文名
    public static function DetectsClasses($bool=true){
        if($bool == true){
            return self::getInfoByKey(Lib_redis::DETECTS_CLASSES_ALL,new DetectsClass,['id','search_name']);
        }else{
            return self::setInfoByKey(Lib_redis::DETECTS_CLASSES_ALL,new DetectsClass,['id','search_name']);
        }
    }

    /**
     * @param bool $bool
     * @return array|mixed
     */
    public static function DetectsClassesName($bool=true){
        if($bool == true){
            return self::getInfoByKey(Lib_redis::DETECTS_CLASSES_NAME_ALL,new DetectsClass,['id','name']);
        }else{
            return self::setInfoByKey(Lib_redis::DETECTS_CLASSES_NAME_ALL,new DetectsClass,['id','name']);
        }
    }

    protected static function setInfoByKey($key,$conn,$select){
        $redis_key = Lib_redis::SplicingKey($key);
        $data = $conn->pluck($select[1],$select[0])->toArray();
        if($data){
            Redis::set($redis_key,json_encode($data));
        }
        return $data;
    }
}
