<?php


namespace App\Http\Controllers;

use App\StandardModel;
use App\TestModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Overtrue\LaravelSocialite\Socialite;

//测试使用的
class TestController extends  Controller
{

    public function test(){

        $github_user = Socialite::driver('github')->user();

        $db = new UserInfoController();
        
        echo '<pre/>';

        $user = $github_user;
        var_dump($user);
        echo '<pre/>';

        //$db->addUserInfo();

        //return view('home');
    }

    public function test1(){
        Log::info('ajax__asasd');
        return response()->json(array('msg'=> 'asdsadadsa'), 200);
    }

    public function testThing(){

        $info = StandardModel::query()->get();

        $save = new StandardModel();
        $save->id = 6;
        $save->name = 'xsace';
        $save->age = 17;

        //echo '<script>alert(123)</script>';

        if($save->save()){

           // echo '<script>alert(123)</script>';
            return redirect('ajax');//redirect 会出现两次请求，这步会进行两次处理，虽然alter一次；
            //return view('test/ajax');
        }

        return view('test/test',[
            'info'=>$info,
        ]);
    }

    public function ajax(){
        return view('test/ajax');
    }

    public function qwe(){

        $inserty = DB::select('select * from mytest');

        dd($inserty);

        return 'DB Test my thing';
    }

    public function useModel(){
        $thing = TestModel::modee();
        return $thing;
    }

}