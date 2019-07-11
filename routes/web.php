<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::any('/', function () {
    return view('welcome');
});

Route::any('/test','TestController@test');

Route::get('/ajax','TestController@ajax');

Route::get('/model','TestController@useModel');

Route::get('/insert','StandardController@insertThing');

Route::get('/update','StandardController@updateThing');

Route::get('/delete','StandardController@deleteThing');

Route::get('/cache1','StandardController@cache1');

Route::get('/cache2','StandardController@cache2');

Route::get('/request','StandardController@request1');

Route::get('/log','StandardController@logging');

Route::group(['middleware'=>['web']],function (){
    //直接在根目录了
    Route::get('/session1',['uses'=>'StandardController@session1']);

    Route::get('/session2',['uses'=>'StandardController@session2']);

    Route::get('/session3',['uses'=>'StandardController@session3']);

    Route::get('/response1',['uses'=>'StandardController@response1']);

    Route::get('/response',['uses'=>'StandardController@response']);
});

Route::get('/redis1','RedisController@getRedis1');

Route::get('/redis2','RedisController@getRedis2');

Route::get('/redis0','RedisController@forget');

Route::get('/githubLogin','GithubController@gotoGit');




Route::get('/test00','UserInfoController@getUserInfo');




Auth::routes();

Route::get('/register', 'HomeController@index');

Route::get('/home', 'HomeController@index')->name('home');

