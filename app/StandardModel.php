<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class StandardModel extends Model
{
    //指定表名
    protected $table = 'mytest';
    //指定id
    protected $primaryKey = 'id';
    //维护时间戳
    public $timestamps = true;


    //指定可以批量赋值的字段
    //protected $fillable = ['name','age'];
    //指定不可以批量赋值的字段
    //protected $guarded = [];

}