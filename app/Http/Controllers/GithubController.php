<?php


namespace App\Http\Controllers;


use Overtrue\LaravelSocialite\Socialite;

class GithubController extends Controller
{

    public function gotoGit(){

        /*$usr_id = 'bd79802636784b0373fe';
        $usr_code = '1d5289711dc4630516a6e596240b43bdcf07f4fa';
        $usr_callback = 'http://127.0.0.1:8080/blog/public/test';

        $url = 'https://github.com/login/oauth/authorize';
        $state = 0;//oauthService.genState();
        $param = 'response_type=code&'.'client_id='.$usr_id
	       .'&redirect_uri='.$usr_callback;
        //return redirect($url.'?'.$param);*/


        return Socialite::driver('github')->redirect();
    }

}