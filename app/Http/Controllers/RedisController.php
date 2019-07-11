<?php


namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\ValidationData;

class RedisController extends Controller
{

    public function getkeyname($v){
        return 'mykey_'.$v;
    }

    //设置redis的时间
    public function forget(){
        $keyName1 = $this->getkeyname('redis1_1');
        $keyName2 = $this->getkeyname('redis2_2');
        $redis = Redis::connection();
        if($redis->exists($keyName1))
            $redis->expire($keyName1,1);
        if($redis->exists($keyName2))
            $redis->expire($keyName2,1);
        Log::info('----------------'.'had forgotten');
        return 'had forgotten';
    }

    //并发情况可能会出现不好的现象
    public function getRedis1(){

        $amountLimit = 100;
        $keyName = $this->getkeyname('redis1_1');

        $redis = Redis::connection();

        $incrAmount = 1;

        if(!$redis->exists($keyName)){
            $redis->set($keyName,95);
        }
        $currAmount = (int)$redis->get($keyName);
        if($currAmount + $incrAmount > $amountLimit){
            Log::info('bad luck'.'___v1');
            return ;
        }

        $redis->incrby($keyName,$incrAmount);
        Log::info('good luck'.'___v1'.$currAmount);

    }

    //防止并发情况的不良现象
    public function getRedis2(){

        $amountLimit = 100;
        $keyName = $this->getkeyname('redis2_2');

        $redis = Redis::connection();
        $incrAmount = 1;

        if(!$redis->exists($keyName)){
            $redis->setnx($keyName,95);
        }
        $currAmount = $redis->get($keyName);
        if($redis->incrby($keyName,$incrAmount) > $amountLimit){
            Log::info('bad luck'.'___v2');
            return;
        }
        Log::info('good luck'.'___v2'.$currAmount);

    }

    //使用Token的方法：

    public function set_token($user_name){
        $information['state'] = false;
        $time = time();
        $header = array( 'type'=>'JWT' );
        $array = array(
            'iss'=>'auth',//权限验证者
            'iat'=>$time,//时间截
            'exp'=>3600,//token的有效期，1小时
            'sub'=>'demo',
            'user_name'=>$user_name //用户名
        );
        $str = base64_decode(json_encode($header)).'.'.base64_decode(json_encode($array));
        $str = urldecode($str);
        $information['token'] = $str;
        //保存到用户表里 ->save_token($user_name,$information['token']);

        $information['username'] = $user_name;
        $information['state'] = true;

    }

    public function setNo(){
        $str = 'www.php.cn';//网站URL；
        $str = base64_decode($str);//加密

        $str = base64_decode($str);//解密
        echo $str;

        //中文出现：
        $str =  urlencode('hxjs还是刺激啊哈'); //加密
        echo urldecode($str);                     //解密

    }

    public function new_token(){    //创建新的JWT / JWS令牌
        $time = time();
        $token =
        (new Builder())->issuedBy('http://test.com')//iss
            ->permittedFor('http://test.org')//aud
            ->identifiedBy('dsadsa',true)//jwi
            ->canOnlyBeUsedAfter($time+60)//iat
            ->expiresAt($time+3600)//exp
            ->withClaim('uid',1)//
            ->getToken();
        $token->getHeaders();
        $token->getClaims();

        echo $token->getHeader('jti');
        echo $token->getClaim('iss');
        echo $token->getHeader('uid');
        echo $token;

        //JWT字符串创建新标记
        $token = (new Parser())->parse((string) $token);//JWT字符串创建新标记
        echo $token->getHeader('jti');
        echo $token->getClaim('iss');
        echo $token->getHeader('uid');

        //验证令牌是否有效



    }

    public function check_token(){


        $time = time();
        $token =
        (new Builder())->issuedBy('http://test.com')//iss
        ->permittedFor('http://test.org')//aud
        ->identifiedBy('dsadsa',true)//jwi
        ->canOnlyBeUsedAfter($time+60)//iat
        ->expiresAt($time+3600)//exp
        ->withClaim('uid',1)//
        ->getToken();
        $token->getHeaders();
        $token->getClaims();

        //验证令牌是否有效

        $date = new ValidationData();
        $date->setIssuer('http://test.com');
        $date->setAudience('http://test.org');
        $date->setId('dsadsa');
        var_dump($token->validate($date));

        $date->setCurrentTime($time+61);
        var_dump($token->validate($date));

        $date->setCurrentTime($time+4000);
        var_dump($token->validate($date));

        //Hmac签名
        $sing = new Sha256();
        $time = time();

        $token = (new Builder())->issuedBy('http://example.com') // Configures the issuer (iss claim)
        ->permittedFor('http://example.org') // Configures the audience (aud claim)
        ->identifiedBy('4f1g23a12aa', true) // Configures the id (jti claim), replicating as a header item
        ->issuedAt($time) // Configures the time that the token was issue (iat claim)
        ->canOnlyBeUsedAfter($time + 60) // Configures the time that the token can be used (nbf claim)
        ->expiresAt($time + 3600) // Configures the expiration time of the token (exp claim)
        ->withClaim('uid', 1) // Configures a new claim, called "uid"
        ->getToken($sing, new Key('testing')); // Retrieves the generated token


        var_dump($token->verify($sing, 'testing 1')); // false, because the key is different
        var_dump($token->verify($sing, 'testing')); // true, because the key is the same

        //私钥生成并使用公钥进行验证:
        $privateKey  =  new  Key('file://{path to your private key}');

        $token = (new Builder())->issuedBy('http://example.com') // Configures the issuer (iss claim)
        ->permittedFor('http://example.org') // Configures the audience (aud claim)
        ->identifiedBy('4f1g23a12aa', true) // Configures the id (jti claim), replicating as a header item
        ->issuedAt($time) // Configures the time that the token was issue (iat claim)
        ->canOnlyBeUsedAfter($time + 60) // Configures the time that the token can be used (nbf claim)
        ->expiresAt($time + 3600) // Configures the expiration time of the token (exp claim)
        ->withClaim('uid', 1) // Configures a new claim, called "uid"
        ->getToken($sing,  $privateKey); // Retrieves the generated token

        $publicKey = new Key('file://{path to your public key}');

        var_dump($token->verify($sing, $publicKey)); // true when the public key was generated by the private one =)

    }

}