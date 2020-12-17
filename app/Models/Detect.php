<?php


namespace App\Models;

use App\Services\RedisServices;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Detect extends Model
{
    //
    protected $guarded = [];


    protected $casts = ['date' => 'datetime:Y-m-d H:i:s'];

    protected $appends = ['burden_value_name'];


    public function setDateAttribute($value)
    {
        $this->attributes['date'] = strtotime($value);
    }


    public function setBurdenValueAttribute($value)
    {
        $detects = RedisServices::DetectsClasses();
        $new_value = [];
        foreach ($value as $k => $v) {
            if ($v) {
                $new_value[$detects[$k]] = $v;
            }
        }
        $this->attributes['burden_value'] = json_encode($new_value);
    }

    public function getBurdenValueAttribute($value)
    {
        $id = request('id');
        if ($id) {
            $detect_ids = UserDetection::where('user_id', $id)->pluck('detect_id')->toArray();
            $detects = RedisServices::DetectsClasses();
            if (!$this->is_not_json($value)) {
                $result = json_decode($value, true);
                if ($detect_ids) {
                    $detect_arr = array_flip($detect_ids);
                    foreach ($detect_arr as $k => $v) {
                        if (!isset($result[$detects[$k]])) {
                            $detect_arr[$k] = '';
                        } else {
                            $detect_arr[$k] = $detects[$k];
                        }
                    }
                    Log::channel('export')->info($result);
                    Log::channel('export')->info($detect_arr);
                    $detect_arrs = [];
                    foreach ($detect_arr as $k => $v) {
                        $detect_arrs[$k] = $result[$v] ?? '';
                    }
                    return $detect_arrs;
                }
                return [];
            }
            return $value;
        } else {
            return json_decode($value, true);
        }
    }

    public function getBurdenValueNameAttribute()
    {

        if ($this->burden_value) {
            return $this->changeKeys($this->burden_value, RedisServices::DetectsClasses(), RedisServices::DetectsClassesName());
        }
    }

    public function ScopeUser($query, $user_id)
    {
        return $query->where('user_id', $user_id);
    }


    function is_not_json($str)
    {
        return is_null(json_decode($str));
    }


    //todo 替换多key
    function changeKeys($array, $keyEnArray, $keyZhCnArray)
    {


        if (!is_array($array)) return $array;
        $tempArray = array();
        foreach ($array as $key => $value) {
            $key = $keyZhCnArray[$key];
            $tempArray[$key] = $value;
        }
        return $tempArray;
//        if(!is_array($array)) return $array;
//        $tempArray = array();
//        foreach ($array as $key => $value){
//            // 处理数组的键，翻译成中文
//            $key = array_search($key, $keyEnArray, true) === false ? $key : $keyZhCnArray[array_search($key, $keyEnArray)];
//            if(is_array($value)){
//                $value = $this->changeKeys($value, $keyEnArray, $keyZhCnArray);
//            }
//            $tempArray[$key] = $value;
//        }
//        return $tempArray;
    }
}
