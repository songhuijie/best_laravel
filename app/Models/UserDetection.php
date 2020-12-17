<?php


namespace App\Models;

use App\Services\RedisServices;
use Illuminate\Database\Eloquent\Model;

class UserDetection extends Model
{
    //
    protected $guarded = [];

    protected $appends = ['detect_name'];

    public function userInfo()
    {

        return $this->hasOne(User::class, 'id', 'user_id');

    }

    public function ScopeUser($query, $user_id)
    {
        return $query->where('user_id', $user_id);
    }

    public function ScopeShow($query)
    {
        return $query->where('is_show', 1);
    }

    public function getDetectNameAttribute()
    {
        $detects = RedisServices::DetectsClassesName();
        return optional($detects)[$this->detect_id];
    }
}
