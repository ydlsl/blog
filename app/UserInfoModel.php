<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class UserInfoModel extends Model
{
    //指定表名
    protected $table = 'user_info';
    //指定id
    protected $primaryKey = 'id';
    //维护时间戳
    public $timestamps = true;

}