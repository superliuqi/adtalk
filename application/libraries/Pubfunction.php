<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pubfunction {

	/**
	*将传来的元素组合成json格式
	*@param $code 错误码 $body 提示语
	*@return 组成之后的json格式
	*/
	public function pub_json($code,$body){
		$array = array(
			"ERRORCODE"=>$code,
			"RESULT"   =>$body
		);
		return $bigbody=json_encode($array);
	}

	/**
	*@Desc：生成随机数
	*@param：位数
	*@return：number、type(随机数的类型)
	*/
	public function remittanceCode($num,$type){
		$type = isset($type)?$type:1;
		switch ($type)
		{
			case 1:
			{
				$chars = '1234567890';
				break;
			}
			case 2:
			{
				$chars = '1234567890abcdefghijklmnopqrstuvwxyz';
				break;
			}
			case 3:
			{
				$chars = '1234567890qwertyuioplkjhgfdsazxcvbnmQWERTYUIOPLKJHGFDSAZXCVBNM';
				break;
			}
			case 4:
			{
				$chars = '1234567890QWERTYUIOPLKJHGFDSAZXCVBNM';
				break;
			}
			default:
			{
				$chars = '1234567890';
				break;
			}
		}
		$hash = '';
		$max = strlen($chars) - 1;
		mt_srand((double)microtime() * 1000000);
		for($i = 0; $i < $num; $i++){
			$hash = $hash.$chars[mt_rand(0, $max)];
        }
		return $hash;
	}

	/**
    * 创建广告编号
    * @param goodsID
    * @return tradeID
    */
    public function createAdID($goodsID){
        $identify = substr($goodsID, 0, 1);
		list($usec) = explode(" ", microtime());
		$msec=substr($usec,2,3);
		return $identify.date('ymdhis').$msec;
    }

	/**
	*@Desc：生成流水号
	*/
	public function serialNumber(){
		if (function_exists('com_create_guid')){
	        return com_create_guid();
	    }else{
	        mt_srand((double)microtime()*10000);
	        $charID = strtoupper(md5(uniqid(rand(), true)));
	        $uuid = date('YmdHis').substr($charID, 0, 8).substr($charID, 8, 4).substr($charID,12, 4);
	            return $uuid;
        }
	}

	/**
	*@Desc：计算sign
	*@return：数组
	*/
	public function sign($info){
		foreach ($info as $key=>$value){
			$arr[$key] = $key;
		}		
		sort($arr);
		$str = ""; 
		foreach ($arr as $k => $v){
			$str = $str.$arr[$k].$info[$v];
		}
		$info['sign'] = strtoupper(sha1($str));
		unset($info['secret']);
		return $info;
	}

	/**
	*@Desc：计算签名(发送手机验证码)
	*/
	public function get_sign_array($array){
		unset($array['sign']);
		if(empty($array['sendType'])){
			unset($array['sendType']);
		}
		foreach ($array as $key=>$value){
			$arr[$key] = $key;
		}	
		sort($arr);
		$str = ""; 
		foreach ($arr as $k => $v){
			$str = $str.$arr[$k].$array[$v];
		}
		$sign = strtoupper(sha1($str));
		return $sign;
	}

	/**
	*@Desc：curl模拟post提交json数据
	*@param：$url：请求的地址 、 $data 请求的参数(数组)
	*@return：数组
	*/
	public function subJson($url,$data){
		$data_string = json_encode($data);
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		    'Content-Type: application/json',
		    'Content-Length: ' . strlen($data_string))
		);
		$result = curl_exec($ch);
		return $arr = json_decode($result,true);
	}

	/**
	*@Desc：生成随机数
	*@param：位数
	*@return：number
	*/
	public function createRandNumberBySize($number){
	    $number = (int)$number;
	    if($number === 0) {
	        return '';
	    }else{
	        $rankNumberString = "";
	        for ($i = 0; $i < $number + 1; $i++){
	            if ($i !== 0 && $i % 2 === 0) {
	                $rankNumberString .= mt_rand(11, 99);
	            }
	        }	 
	        if ($number % 2 === 0) {
	            return $rankNumberString;
	        }else{
	            return $rankNumberString . mt_rand(1, 9);
	        }
	    }
	}

	/**
	*@Desc：开始时间
	*/
	public function timeType($type){
		switch ($type) {
			case '1':
				$start = '';
				$end = '';
				break;
			case '2':
				//获得今日开始与结束时间
				$t = time();
				$start = mktime(0,0,0,date("m",$t),date("d",$t),date("Y",$t));
				$end = mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
				break;
			case '3':
				//获得昨日开始与结束时间
				$t = strtotime("-1 day");
				$start = mktime(0,0,0,date("m",$t),date("d",$t),date("Y",$t));
				$end = mktime(0,0,0,date('m'),date('d'),date('Y'))-1;
				break;
			case '4':				
				//获得本周开始时间与结束时间
				$date=date('Y-m-d');  //当前日期
				$first=1; 			  //$first =1 表示每周星期一为开始日期 0表示每周日为开始日期
				$w=date('w',strtotime($date));  //获取当前周的第几天 周日是 0 周一到周六是 1 - 6
				$start=strtotime("$date -".($w ? $w - $first : 6).' days'); //获取本周开始日期，如果$w是0，则表示周日，减去 6 天
				
				$now_start=date('Ymd',strtotime("$date -".($w ? $w - $first : 6).' days'));
				$end=strtotime("$now_start +7 days");
				break;
			case '5':
				//获得最近30天的开始时间与结束时间
				$t = strtotime('-30day');
				$start = mktime(0,0,0,date("m",$t),date("d",$t),date("Y",$t));
				$end = time();
				break;
		}
		return $res = array('start'=>$start,'end'=>$end);		
	}
}
?>