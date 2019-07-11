<?php


namespace App\Http\Controllers;


use App\UserInfoModel;

class UserInfoController
{
    protected $time;

    public function addUserInfo($name,$email){
        //$user_id = 123455;
        $user_name = $name;
        $user_password = 'asdf';
        $user_email = $email;
        $res = UserInfoModel::query()->insert([
            'name'=>$user_name,
            'password'=>$user_password,
            'email'=>$user_email
        ]);
        echo $res;
    }

    public function updateUserInfo(){
        //$user_id = 123455;
        $user_name = 'qweqew';
        $user_password = 'asdf';
        $user_email = '1234556789@qq.com';
        $res = UserInfoModel::query()->where('id',2)->update([
            'name'=>$user_name,
            'password'=>$user_password,
            'email'=>$user_email
        ]);
        echo $res;
    }

    public function deleteUserInfo(){
        $res = UserInfoModel::query()->where('id',1)->delete();
        echo $res;
    }

    public function getUserInfo(){
        $res = UserInfoModel::all();
        echo $res;
    }
}