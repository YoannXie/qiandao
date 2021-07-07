<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use App\Jobs\Sign;
use App\Models\User;

class QiandaoController extends Controller
{
	//准备奖励名额
    public function setTops(Request $request)
    {
    	Redis::set('usersign:tops',$request->input('top_num'));
    	return '名额已发布';
    }

    //准备参与签到的用户
    //1：未签到；2：普通签到；3：top签到
    public function setUsers(Request $request)
    {
    	$user_num = $request->input('user_num');
    	$user_ids = User::limit($user_num)->get()->pluck('id')->toArray();
    	Redis::set('usersign:user_ids',serialize($user_ids));

    	$pipe = Redis::connection();
    	Redis::pipeline(function($pipe) use($user_ids){
    		foreach($user_ids as $user_id){
    			$pipe->set("usersign:users:$user_id",1);
    		}
    	});
    	return '用户已部署';
    }

    //获取所有参与签到的用户的签到信息
    public function getSigns()
    {
    	//如果redis服务器有问题，可以用redis集群解决
    	//如果在redis取不到所需数据，就到MySQL查询并存储到redis
    	
    	$user_ids = Redis::get('usersign:user_ids');
    	if(!$user_ids){
    		//如果redis取不到所需数据，代码后续补充
    		return 0;
    	}else{
    		$user_ids = unserialize($user_ids);
    	}

    	$pipe = Redis::connection();
    	$signs = Redis::pipeline(function($pipe) use($user_ids){
    		foreach($user_ids as $user_id){
    			$pipe->get("usersign:users:$user_id");
    		}
    	});

    	//pipeline执行结果中，如果出现container或key不存在的情况，都会包含false
    	if(in_array(false, $signs)){
    		//如果redis取不到所需数据，代码后续补充
    		return 0;
    	}else{
    		$user_signs = array_combine($user_ids, $signs);
    	}
    	
    	return $user_signs;
    }

    //获取某用户的签到信息
    public function getSign($user_id)
    {

    }

    //用户进行签到
    public function sign(Request $request)
    {
    	$user_id = $request->input('user_id');
    	$tops = Redis::get('usersign:tops');
    	if($tops){
    		$lock = Cache::lock('sign_top',10);
    		if($lock->get()){
    			$tops = Redis::get('usersign:tops');
    			$redis = Redis::connection();
    			Redis::transaction(function($redis) use($user_id,$tops){
    				$redis->set('usersign:tops',$tops-1);
    				$redis->set("usersign:users:$user_id",'3');
    			});
    			Sign::dispatch($user_id,'3');
    			$lock->release();
    			return '奖励签到';
    		}
    	}
    	Redis::set("usersign:users:$user_id",'2');
    	Sign::dispatch($user_id,'2');
    	return '普通签到';
    }

    public function cleanTable()
    {
    	\DB::table('user_signs')->truncate();
    	return '清空表成功';
    }
}
