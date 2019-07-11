<?php


namespace App\Http\Controllers;


use App\StandardModel;
use DemeterChain\C;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class StandardController extends Controller
{
    public function qwer(){
        $sum = DB::table('mytest')->sum('age');
        var_dump($sum);
        //使用标准的model
        $sum = DB::table('mytest')->sum('age');
        var_dump($sum);

    }

    //使用标准的model数据进行书写
    public function getTest(){
        //all
        //$student = StandardModel::all();
        //var_dump($student);

        //条件查询
        $student = StandardModel::query()->where('id','>','3')
            ->orderBy('age','desc')
            ->first();
        dd($student);

        //数据太大，循环执行n跳数据查询；
        echo '<pre>';
        $student = StandardModel::query()->chunk(2,function ($asd){
            var_dump($asd);
        });
        dd($student);
        //model的create方法：
        $result = StandardModel::query()->create(
            ['name'=>'yd','age'=>18]
        );
        dd($result);
        //先找，在没有的情况下，新建一条记录
        StandardModel::query()->firstOrCreate(
            ['name'=>'yd','age'=>19]
        );
        //先找，在没有的情况下，内存加一条记录，需要手动save一下；
        //        StandardModel::query()->firstOrNew(
        //            ['name'=>'yd','age'=>19]
        //        );
        //        StandardModel::save();

    }

    public function insertThing(){
        $result = StandardModel::query()->insert(
            ['name'=>'yd','age'=>18]
        );
        /*create 需要设置增加的字段符合批量构建   在model里写 //protected $fillable = ['name','age'];
        $result = StandardModel::query()->create(
            ['name'=>'yd','age'=>18]
        );*/
        echo $result;
    }

    public function updateThing(){

        //通过模型更新数据//save不太好用，因为后面要跟上createAt和updateAt；
        $result = new StandardModel();
        //$result = StandardModel::query()->find(1);
        $result->name = '123';
        $result->id = 2;
        $result->age = 3;
        //$result->setAttribute('name','kitty');
        $bool = $result->save();
        var_dump($bool);
        //dd($result);

        //直接类来更新
        $num = StandardModel::query()->where('id','>',4)->update(['age'=>14]);
        var_dump($num);
    }
    //删除数据的方法
    public function deleteThing(){
        //通过模型删除
        $result = StandardModel::query()->find(2);
        //$result不可以是空，不然报错
        //$bool = $result->delete();
        var_dump($result);

        //通过主键删除//可以为空
        $num = StandardModel::destroy(12);
        var_dump($num);

    }

    //接收request url的数据
    public function request1(Request $request){

        if($request->has('name'))
        {
            echo $request->input('name');
        }else{
            echo 'no thing';
        }

        echo '<pre/>';

        //判断类型
        if($request->isMethod('GET')){
            echo 'yes';
        }else{
            echo 'no';
        }

        echo '<pre/>';
        //判断是不是ajax
        $res = $request->ajax();
        var_dump($res);

        echo '<pre/>';

        //获取当前u URL
        echo $request->url();

        //输出所有
        dd($request->all());


    }

    //接收session的数据
    public function session1(Request $request){
        //http request   1
        $request->session()->put('key2','cascsa2');
        //session()辅助   2
        session()->put('key1','xsaceeq');
        //Session类调用静态方法=   3
        Session::put('key3','hgjkgui1');

        Session::put('key4','hgjkguiqwe');
        //放入数组       4
        Session::push('qwe','sean');
        Session::push('qwe','imooc');

        //Session::flush();
        //$request->session()->flush();
        //Session::remove('key3');
        Session::forget('key2');

        //暂存数据：
        Session::flash('keyy','hjkhkj');//第一次访问出来，后续数据丢失。

        $res = Session::all();
        var_dump($res);

    }
    public function session2(Request $request){
        // 1
        echo $request->session()->get('key1','no thing');
        echo '<br/>';
        echo '<br/>';
        // 2
        echo session('key2','default13');
        echo '<br/>';
        echo '<br/>';
        // 3
        echo Session::get('key3','default');
        echo '<br/>';
        echo '<br/>';
        // 4
        //dd(Session::get('qwe','defaultqwe'));
        //pull删除掉，再进入push，再pull也取不出来了
        $res = Session::pull('qwe','defaultqwe');
        var_dump($res);
        echo '<br/>';
        echo '<br/>';
        //取出所有
        $resq = Session::all();
        var_dump($resq);

    }

    public function session3(){
        //判断session有无
        if(Session::has('key1')){
            echo Session::get('key1','defaultasd');
        }else{
            echo '<h1>你们老大忙</h1>';
        }

        $res = Session::all();
        var_dump($res);
        echo '<br/>';
        echo '<br/>';
        //忘记一个键值对的值；
        Session::forget('key2');
        $res = Session::all();
        var_dump($res);

        if(Session::has('key2')){
            echo Session::get('key2','defaultasd');
        }else{
            echo '<h1>你们老大忙</h1>';
        }
        //清空所有
        Session::flush();
        $ress = Session::all();
        var_dump($ress);
    }

    public function response(){
        //重定向  有三种 redirect->action('函数@方法')；redirect->route('路由')；都可以带有闪存数据
        //return redirect('response1');
        return redirect('response1')->with('message','我是你大哥');//闪存数据
        //return redirect()->back();//返回上一级页面。
    }

    public function response1(){
        //响应json
        $data = [
            'erroCode'=>0,
            'errMessage'=>'faile',
            'data'=>'dadwqdsacsa',
        ];

        $res = Session::get('message','我还是你大哥');
        var_dump($res);
        return response()->json($data);
    }

    //验证：
    public function inputThing(Request $request){
        if($request->isMethod('POST')){

            //1。控制器验证

            $this->validate($request,[
                'Student.name'=>'required|min:2|max:20',
                'Student.age'=>'required|integer',
            ],[
                'required'=>':attribute 为必填项',
                'min'=>':attribute 长度过小',
                'max'=>':attribute 长度过大',
                'integer'=>':attribute 必须是整数',
            ],[
                'Student.name'=>'姓名',
                'Student.age'=>'年龄',
            ]);
            //第一个数组是验证，后面的数组是错误信息自定义提示。
            //一旦验证不成功，则该方法下的语句都不进行了。会回溯到之前的页面，并且把错误抛出截获到error里

            //2。validator验证
            $validator = Validator::make($request->input(),[
                'Student.name'=>'required|min:2|max:20',
                'Student.age'=>'required|integer',
            ],[
                'required'=>':attribute 为必填项',
                'min'=>':attribute 长度过小',
                'max'=>':attribute 长度过大',
                'integer'=>':attribute 必须是整数',
            ],[
                'Student.name'=>'姓名',
                'Student.age'=>'年龄',
            ]);

            if($validator->fails()){
                //错误的时候，没有错误信息，需要手动注册
                return redirect()->back()->withErrors($validator);
            }


        }
    }

    //cache数据缓存：
    public function cache1(){
        //put
        //Cache::put('key2','value1',2);
        //add
        $res = Cache::add('key1','value1',10);//存在返回false;秒
        var_dump($res);

        //forever永久保存
        Cache::forever('key2','zxc key2');

        //forget
        Cache::forget('key2');

        Cache::forever('key2','zxcvbvnmcn  key2');

        //has()
        if(Cache::has('key2')){
            $res = Cache::get('key2');
            var_dump('1111111111111111');
            var_dump($res);
        }else{
            echo 'no key in cache  ';
        }


        return '        存值';
    }
    public function cache2(){

        $res =  Cache::get('key1','大哥的大哥1');
        var_dump($res);

        //has()
        if(Cache::has('key2')){
            $res1 = Cache::get('key2');
            var_dump($res1);
        }else{
            echo 'no key in cache  ';
        }
        //pull()
        $res2 =  Cache::pull('key2','大哥的大哥2');//取出并删除
        var_dump($res2);

        if(Cache::has('key2')){
            $res = Cache::get('key2');
            var_dump($res);
        }else{
            echo 'no key in cache  ';
        }

        $res3 = Cache::get('key2');
        var_dump($res3);
    }

    public function logging(){

        Log::info('这是一个info级别的日志');

        return '日志已写好';
    }

}