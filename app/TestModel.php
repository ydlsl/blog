<?php


namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class TestModel extends Model
{

    public static function modee(){

        return 'all ready';
    }

    //删除数据的方式
    public function deleteModel(){

        //DB::table('mytest')->where('id',19)->delete();
        $num = DB::table('mytest')->where('id','>=',19)->delete();
        var_dump($num);
    }
    //插入数据的方法
    public function insertModel(){
        $bool = DB::table('mytest')->insert([
            ['id'=>1,'name'=>'name1'],
            ['id'=>2,'name'=>'name2'],
            ['id'=>3,'name'=>'name3'],
            ['id'=>4,'name'=>'name4']
        ]);
        var_dump($bool);
    }

    //构造器来处理

    //获取第一条数据
    public function getFirst(){
        //first
        $student = DB::table('mytest')->orderBy('id','desc')->first();
        //get,所有
        $student1 = DB::table('mytest')->where('id','>=',12)->get();
        //get,过个条件
        $student2 = DB::table('mytest')->whereRaw('id >= ? and age > ?',[2,3])->get();

        dd($student,$student1,$student2);

        //pluck 返回具体字段
        $student = DB::table('mytest')->pluck('name');
        //lists 可以指定下标 貌似不能用了
        //$student1 = DB::table('mytest')->lists('name','id');

        //select 选择一些字段
        $student1 = DB::table('mytest')->select('id','name')->get();

        //chunk 每次查询两条，会循环查找，直到结束，减少服务器查询负载
        DB::table('mytest')->chunk(2,function ($people){
            var_dump($people);
            //if(...);
            //return false;//停止查询
        });
        dd($student,$student1,$student2);

        //聚合函数
        $count = DB::table('mytest')->count();//返回记录条数；
        $max = DB::table('mytest')->max('age');//返回max；min最小
        $avg = DB::table('mytest')->avg('age');//返回平均值；
        $sum = DB::table('mytest')->sum('age');//返回相加值；
        var_dump($count,$max,$avg,$sum);


    }


}