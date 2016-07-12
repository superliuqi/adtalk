<?php
/**
*@Desc：后台广告管理
*/
class Advertisemanage_model extends CI_Model{
	
	public function __construct(){
		$this->load->database();
		$this->load->library(array('pubfunction','Curl'));
	}

	/**
	*@Desc：广告查询
	*@param：adStatus、adShape、createTimeStart、createTimeEnd、companyName、nowPage、pageNum
	*@Detail：界面用了Datatables
	*/
	public function queryAdInfo($data){
		$data['startTime'] = strtotime($data['startTime']);
		$data['endTime'] = strtotime($data['endTime']);
		$sql = "SELECT count(a.id) AS adCount FROM adtalkInfo AS a JOIN userDetailInfo AS b ON a.userID = b.userID WHERE 1 ";
		$sql_page = "SELECT a.*,b.companyName FROM adtalkInfo AS a JOIN userDetailInfo AS b ON a.userID = b.userID WHERE 1 ";
		if($data['adStatus'] != ''){
			$sql .= " AND `adStatus` = '".$data['adStatus']."'";
			$sql_page .= " AND `adStatus` = '".$data['adStatus']."'";
		}
		if($data['adShape'] != ''){
			$sql .= " AND `adShape` = '".$data['adShape']."'";
			$sql_page .= " AND `adShape` = '".$data['adShape']."'";
		}
		if($data['startTime'] != '' && $data['endTime'] != ''){
			$sql .= " AND a.createTime BETWEEN '".$data['startTime']."' AND '".$data['endTime']."'";
			$sql_page .= " AND a.createTime BETWEEN '".$data['startTime']."' AND '".$data['endTime']."'";
		}
		if($data['startTime'] != '' && $data['endTime'] == ''){
			$sql .= " AND a.createTime > '".$data['startTime']."'";
			$sql_page .= " AND a.createTime > '".$data['startTime']."'";
		}
		if($data['endTime'] != '' && $data['startTime'] == ''){
			$sql .= " AND a.createTime < '".$data['endTime']."'";
			$sql_page .= " AND a.createTime < '".$data['endTime']."'";
		}
		if($data['companyName'] != ''){
			$sql .= " AND b.companyName LIKE '%".$data['companyName']."%'";
			$sql_page .= " AND b.companyName LIKE '%".$data['companyName']."%'";
		}
		$adCount = $this->db->query($sql)->row_array();
		//分页
		$sql_page .= " ORDER BY a.createTime DESC LIMIT ".$data['iDisplayStart'].",".$data['iDisplayLength']."";
		$adInfo = $this->db->query($sql_page)->result_array();
		$result_array = array(
            "data"=>$adInfo,
            "iTotalRecords" => $adCount['adCount'],
            "iTotalDisplayRecords" => $adCount['adCount']
        );
        return json_encode($result_array);
	}

	/**
	*@Desc：意外险广告查询
	*create by liuqi
	*2014-04-13
	*/
	public function querySponsorInfo($data){
		$data['startTime'] = strtotime($data['startTime']);
		$data['endTime'] = strtotime($data['endTime']);
		$sql_page = "SELECT a.*,b.companyName FROM sponsorInfo AS a JOIN userDetailInfo AS b ON a.userID = b.userID WHERE 1 ";		
		if($data['adStatus'] != ''){
			$sql_page .= " AND `adStatus` = '".$data['adStatus']."'";
		}
		if($data['startTime'] != '' && $data['endTime'] != ''){
			$sql_page .= " AND a.createTime BETWEEN '".$data['startTime']."' AND '".$data['endTime']."'";
		}
		if($data['startTime'] != '' && $data['endTime'] == ''){
			$sql_page .= " AND a.createTime > '".$data['startTime']."'";
		}
		if($data['endTime'] != '' && $data['startTime'] == ''){
			$sql_page .= " AND a.createTime < '".$data['endTime']."'";
		}
		if($data['sponsor'] != ''){
			$sql_page .= " AND a.sponsorName LIKE '%".$data['sponsor']."%'";
		}
		//总条数
		$count = count($this->db->query($sql_page)->result_array());
		//分页
		$sql_page .= " ORDER BY a.createTime DESC LIMIT ".$data['iDisplayStart'].",".$data['iDisplayLength']."";
		$adInfo    = $this->db->query($sql_page)->result_array();
		$result_array = array(
            "data"					=> $adInfo,
            "iTotalRecords" 		=> $count,
            "iTotalDisplayRecords"  => $count
        );
        return json_encode($result_array);
	}

	/**
	*@Desc：查看广告详情
	*@param：adID
	*/
	public function gainAdDetailInfo($data){
		$res = $this->db->select()->where('adID',$data['adID'])->get('adtalkInfo')->result_array();
		return $res = empty($res[0])?$this->pubfunction->pub_json('1009','该编号无对应广告信息'):$this->pubfunction->pub_json('0',$res);
	}

	/**
	*@Desc：查看意外险广告详情	
	*@param：adID
	*/
	public function getAccidDetailInfo($data){
		$res = $this->db->select()->where('adID',$data['adID'])->get('sponsorInfo')->result_array();
		return $res = empty($res[0])?$this->pubfunction->pub_json('1009','该编号无对应广告信息'):$this->pubfunction->pub_json('0',$res);
	}

	/**
	*@Desc：根据城市编号获得对应的城市名
	*@param：cityCode
	*/
	public function gainCityName($data){
		if(substr($data['cityCode'], -1) == '|'){
			$data['cityCode'] = str_replace("|", "','", substr($data['cityCode'],0,-1));
		}		
		$citySql = "SELECT `provinceName`,`cityName`,`price` FROM `chinaCityPrice` WHERE `cityCode` IN ('".$data['cityCode']."') OR `provinceCode` IN ('".$data['cityCode']."') GROUP BY `cityName`";
		$cityCodePrice  =$this->db->query($citySql)->result_array();
		return empty($cityCodePrice)?$this->pubfunction->pub_json('2000','无查询结果'):$this->pubfunction->pub_json('0',$cityCodePrice);
	}

	/**
	*@Desc：处理广告申请
	*@param：adStatus(1：投放中，2：待调整(失败)，3：暂停投放)、reason、adID
	*/
	public function dealwithAdApply($data){
		$info = $this->db->select()->where('adID',$data['adID'])->get('adtalkInfo')->row_array();
		//便于比较余额是否大于广告花费的最大价格
		$price = explode('~',$info['advertisePrice']);
		$update = array(
			'adStatus' => $data['adStatus'],
			'reason' => $data['reason'],
			'updateTime' => time()
		);

		//审核通过/开启投放
		if($data['adStatus'] == '1'){
			$userInfo = $this->db->select('balance,checkStatus')->where('userID',$info['userID'])->get('userDetailInfo')->row_array();
			//判断是否有余额或余额是否大约广告价格最大值
			if($userInfo['balance'] < $price['1']){
				return $res = $this->pubfunction->pub_json('2004','用户余额不足');
			}
			//检查账户是否审核通过
			if($userInfo['checkStatus'] != '2'){
				return $res = $this->pubfunction->pub_json('1007','企业资料未完善或审核未通过');
			}
			$info['brandName'] = $info['adShape'] == '2'?$info['audioContent']:$info['brandName'];
			if($info['adShape'] == '2'){
				$info['url'] = $info['audioURL'];
			}else if($info['adShape'] == '4'){
				$info['url'] = $info['sponsorUrl'];
			}else{
				$info['url'] = '';
			}
			$info['urlType']   = $info['adShape'] == '2'?'amr':'';
			$info['isRepeat']  = '3';
			$info['bgcolor'] = empty($info['bgcolor'])?'':$info['bgcolor'];
			$ad_issued = $this->config->item('ad_issued');
			$url = $ad_issued['adcube_set'];
			if($info['adShape'] == '1' && $info['adSpace'] == '4'){
				$adSave = array(
					'content' =>'{"url":"'.$info['url'].'","text":"'.$info["brandName"].'","bgColor":"'.$info['bgcolor'].'","isRepeat":"'.$info['isRepeat'].'","logoUrl":"'.$info['logoURL'].'","urlType":"'.$info['urlType'].'"}',
					'typ'     => $info['adShape'],
					'citycode'=> $info['cityCode'],
					'adtime'  => $info['timeInterval'],
					'cburl'   => $ad_issued['callBackUrl'],
					'appKey'  => $ad_issued['appKey'],
					'secret'  => $ad_issued['secret']
				);
			}elseif($info['adShape'] == '5'){
				$adSave = array(
					'content' =>'{"imageURL":"'.$info['logoURL'].'","voiceURL":"'.$info["audioURL"].'","clickURL":"'.$info['sponsorUrl'].'","isRepeat":"'.$info['isRepeat'].'"}',
					'typ'     => $info['adShape'],
					'citycode'=> $info['cityCode'],
					'adtime'  => $info['timeInterval'],
					'cburl'   => $ad_issued['callBackUrl'],
					'appKey'  => $ad_issued['appKey'],
					'secret'  => $ad_issued['secret']
				);
			}else{
				$adSave = array(
					'content' =>'{"url":"'.$info['url'].'","text":"'.$info["brandName"].'","isRepeat":"'.$info['isRepeat'].'","logoUrl":"'.$info['logoURL'].'","urlType":"'.$info['urlType'].'"}',
					'typ'     => $info['adShape'],
					'citycode'=> $info['cityCode'],
					'adtime'  => $info['timeInterval'],
					'cburl'   => $ad_issued['callBackUrl'],
					'appKey'  => $ad_issued['appKey'],
					'secret'  => $ad_issued['secret']
				);	
			}
			$save = $this->pubfunction->sign($adSave);
			//调接口存储广告
			$arr = $this->pubfunction->subJson($ad_issued['adcube_set'],$save);
			if($arr['ERRORCODE'] != '0'){
				return $res = $this->pubfunction->pub_json('1003','存储广告失败'.$arr['RESULT']);
			}
			//更新广告信息表 中 广告回执编号
			$update['receiptID'] = $arr['RESULT']['aid'];
			$status = $this->db->where('adID',$data['adID'])->update('adtalkInfo',$update);
			return $status?$this->pubfunction->pub_json('0','ok'):$this->pubfunction->pub_json('1003','审核失败');
		}
		//暂停广告
		if($data['adStatus'] == '0'){
			//$res = $this->deleteAd($info,$info['receiptID']);
			$res=$this->db->where('adID',$data['adID'])->set(array('adStatus'=>$data['adStatus'],'updateTime'=>time(),'receiptID'=>''))->update('adtalkInfo');
			return $res?$this->pubfunction->pub_json('0','ok'):$this->pubfunction->pub_json('1003','暂停失败');	
		}
		if($data['adStatus'] == '3'){
			$res = $this->deleteAd($info,$info['receiptID']);
			$res=$this->db->where('adID',$data['adID'])->set(array('adStatus'=>$data['adStatus'],'updateTime'=>time(),'receiptID'=>''))->update('adtalkInfo');
			return $res?$this->pubfunction->pub_json('0','ok'):$this->pubfunction->pub_json('1003','暂停失败');	
		}
		//不通过
		$status = $this->db->where('adID',$data['adID'])->update('adtalkInfo',$update);
		return $status?$this->pubfunction->pub_json('0','ok'):$this->pubfunction->pub_json('1003','审核失败');
	}

		/**
	*@Desc：处理意外险广告申请
	*@param：adStatus(1：投放中，2：待调整(失败)，3：暂停投放)、reason、adID
	*/
	public function dealwithAccidApply($data){
		$info = $this->db->select()->where('adID',$data['adID'])->get('sponsorInfo')->row_array();
		// var_dump($info);exit;
		$price = $info['advertisePrice'];
		$update = array(
			'adStatus' => $data['adStatus'],
			'reason' => $data['reason'],
			'updateTime' => time()
		);
		//审核通过/开启投放
		if($data['adStatus'] == '1'){
			$userInfo = $this->db->select('balance,checkStatus')->where('userID',$info['userID'])->get('userDetailInfo')->row_array();
			//判断是否有余额或余额是否大约广告价格最大值
			if($userInfo['balance'] < $price){
				return $res = $this->pubfunction->pub_json('2004','用户余额不足');
			}
			//检查账户是否审核通过
			if($userInfo['checkStatus'] != '2'){
				return $res = $this->pubfunction->pub_json('1007','企业资料未完善或审核未通过');
			}
			$ad_issued = $this->config->item('ad_issued');
			$url = $ad_issued['adcube_set'];
			$adSave = array(
				'content' 	 =>'{"url":"'.$info['sponsorUrl'].'","text":"'.$info["sponsorName"].'","logoUrl":"'.$info['sponsorLogo'].'"}',
				'citycode'	 => $info['cityCode'],
				'typ'     	 => '4',
				'adtime'     => '06:00-06:59|07:00-07:59|08:00-08:59|09:00-09:59|10:00-10:59|11:00-11:59|12:00-12:59|13:00-13:59|14:00-14:59|15:00-15:59|16:00-16:59|17:00-17:59|18:00-18:59|19:00-19:59|20:00-10:59|21:00-21:59|22:00-22:59|23:00-23:59|00:00-00:59|01:00-01:59|02:00-02:59|03:00-03:59|04:00-04:59|05:00-05:59',
				'cburl'   	 => $ad_issued['accidCallBackUrl'],
				'appKey'  	 => $ad_issued['appKey'],
				'secret'  	 => $ad_issued['secret']
			);
			$save = $this->pubfunction->sign($adSave);
			//调接口存储广告
			$arr = $this->pubfunction->subJson($ad_issued['adcube_set'],$save);	
			if($arr['ERRORCODE'] != '0'){
				return $res = $this->pubfunction->pub_json('1003','存储广告失败'.$arr['RESULT']);
			}
			//更新广告信息表 中 广告回执编号
			$update['receiptID'] = $arr['RESULT']['aid'];
			$status = $this->db->where('adID',$data['adID'])->update('sponsorInfo',$update);
			return $status?$this->pubfunction->pub_json('0','ok'):$this->pubfunction->pub_json('1003','审核失败');
		}
		//暂停广告
		if($data['adStatus'] == '0'){
			//$res = $this->deleteAccid($info,$info['receiptID']);
			$arr = array('adStatus'=>$data['adStatus'],'updateTime'=>time(),'receiptID'=>'');
			$res=$this->db->where('adID',$data['adID'])->update('sponsorInfo',$arr);
			return $res?$this->pubfunction->pub_json('0','ok'):$this->pubfunction->pub_json('1003','暂停失败');	
		}
		if($data['adStatus'] == '3'){
			$res = $this->deleteAccid($info,$info['receiptID']);
			$arr = array('adStatus'=>$data['adStatus'],'updateTime'=>time(),'receiptID'=>'');
			$res=$this->db->where('adID',$data['adID'])->update('sponsorInfo',$arr);
			return $res?$this->pubfunction->pub_json('0','ok'):$this->pubfunction->pub_json('1003','暂停失败');	
		}
		//不通过
		$status = $this->db->where('adID',$data['adID'])->update('sponsorInfo',$update);
		return $status?$this->pubfunction->pub_json('0','ok'):$this->pubfunction->pub_json('1003','审核失败');
	}

	/**
	*@Desc：广告播放后的回调
	*/
	public function adCallBackHandle($data){
		$res = $this->db->select('adShape,userID,receiptID,adID')->where('receiptID',$data['aid'])->get('adtalkInfo')->row_array();	
		if(empty($res)){
			return $res = $this->pubfunction->pub_json('2000','无查询结果');
		}
		//根据广告形式获得对应价格
		if(!empty($res['adShape'])){
			$adType = $this->db->select('price')->where('adType',$res['adShape'])->get('adShapePrice')->row_array();
		}
		//$adTypePrice = empty($adType)?'0':$adType['price'];	
		//获得广告对应区域的价格及城市名
		$citySql = "SELECT `provinceName`,`cityName`,`price` FROM `chinaCityPrice` WHERE `cityCode` = '".$data['citycode']."' OR `provinceCode` = '".$data['citycode']."' GROUP BY `cityName`";
		$cityArr = $this->db->query($citySql)->row_array();
		$cityArr = is_array($cityArr)?$cityArr:array('provinceName'=>'','cityName'=>'','price'=>'');
		$cityArr['price'] = $cityArr['price'] == ""?"0.01":$cityArr['price'];
	
		//参数传来时间戳为13位了
		$data['time'] = (strlen($data['time'])>'10')?mb_substr($data['time'], 0,10):$data['time'];	
		//获得播放时间对应时间段的价格
		$timePrice = $this->db->select('price')->where('time',date('H', $data['time']))->get('timeSlotPrice')->row_array();
		
		$citycode = (empty($data['citycode'])||$data['citycode'] == 'nil')?'':$data['citycode'];
		$cityArr['price'] = $data['citycode'] == 'nil'?'0.01':$cityArr['price'];
		//组合广告播放记录表所需参数
		$insert_arr = array(
			'userID'   => $res['userID'],
			'adID'     => $res['adID'],
			'price'    => $adType['price'] + $cityArr['price'] + $timePrice['price'],//此次回调广告的价格
			'city'     => $cityArr['cityName'],
			'receiptID'=> $data['aid'],
			'clientID' => $data['cid'],//客户端编号
			'playTime' => $data['time'],
			'appKey'   => $data['appKey'],
			'cityCode' => $citycode,
			'longitude'=> $data['longitude'],
			'latitude' => $data['latitude'],
			'speed'    => $data['speed'],
			'directionAngle' => $data['directionAngle']
		);
		//获得用户的余额
		// 	 如果 A 1） 金额剩余足够，执行扣减费用
		//          2） 返回原始金额[0]，返回剩余金额[1]，返回费用扣除状态[2] 1 成功
		//        B 1） 金额不足
		//          2） 返回原始金额[0]，返回剩余金额[1]为0（不能用于判断是否成功），返回扣除失败状态为0 ，失败
			$userMoney = $this->getPrice($res['userID'],$insert_arr['price']);
			//获得用户资金变化表		
			$amountInfo = $this->db->select('amountAfterChanged')->where(array('userID'=>$res['userID'],'moneyStatus'=>'2'))->where('userID',$res['userID'])->order_by('id','desc')->get('userAmountchangedInfo')->row_array();
			//判断余额是否满足此次的广告价格
			if((float)$userMoney[0] < $insert_arr['price'] || empty($userMoney[0])){
				//删除广告
				$arr = $this->deleteAd($res,$data['aid']);
				return $res = $this->pubfunction->pub_json('2004','用户余额不足！');
			}
			//更改用户资金变化表
			if(!empty($amountInfo)){
				//先读取之前的余额
				if((float)$userMoney[1] < (float)$insert_arr['price'] || (float)$userMoney[1] < '0'){
					//更新广告状态 投放中->暂停投放
					$this->deleteAd($res,$data['aid']);
				}
				//金额足  扣款成功
				if($userMoney[2]){
					$update_amount = array(
						'amountBeforeChanged' => (float)$userMoney[0],
						'changedAmount'       => '-'.$insert_arr['price'],
						'amountAfterChanged'  => (float)$userMoney[1],
						'adID'                => $res['adID'],
						'userID'              => $res['userID'],
						'createTime'          => time(),
						'updateTime'          => $data['time'],
						'remark'              => date('His',$data['time']).'-'.$insert_arr['city'],
						'moneyStatus'         => '2'//扣款成功
					);
					$amountStatus = $this->db->insert('userAmountchangedInfo',$update_amount);
					//更改用户详细信息表(改余额)
					$status=$this->db->where('userID',$res['userID'])->set(array('updateTime'=>time(),'balance'=>(float)$userMoney[1]))->update('userDetailInfo');
					$playStaus = $this->db->insert('adPlayInfo',$insert_arr);
					return ($status&&$playStaus&&$amountStatus)?$this->pubfunction->pub_json('0','ok'):$this->pubfunction->pub_json('1003','操作数据库失败');
				}
			}
			//删除广告
			$arr = $this->deleteAd($res,$data['aid']);
			return $res = $this->pubfunction->pub_json('2004','用户余额不足！');
	}

		/**
	*@Desc：广告播放后的回调
	*/
	public function accidCallBackHandle($data){
		$res = $this->db->select('advertisePrice,userID,receiptID,adID')->where('receiptID',$data['aid'])->get('sponsorInfo')->row_array();	
		if(empty($res)){
			return $res = $this->pubfunction->pub_json('2000','无查询结果');
		}

		//获得广告对应区域的价格及城市名
		$citySql = "SELECT `provinceName`,`cityName`,`price` FROM `chinaCityPrice` WHERE `cityCode` = '".$data['citycode']."' OR `provinceCode` = '".$data['citycode']."' GROUP BY `cityName`";
		$cityArr = $this->db->query($citySql)->row_array();
		$cityArr = is_array($cityArr)?$cityArr:array('provinceName'=>'','cityName'=>'','price'=>'');
		$cityArr['price'] = $cityArr['price'] == ""?"0.01":$cityArr['price'];	
		//参数传来时间戳为13位了
		$data['time'] = (strlen($data['time'])>'10')?mb_substr($data['time'], 0,10):$data['time'];			
		$citycode = (empty($data['citycode'])||$data['citycode'] == 'nil')?'':$data['citycode'];
		$cityArr['price'] = $data['citycode'] == 'nil'?'0.01':$cityArr['price'];
		//组合广告播放记录表所需参数
		$insert_arr = array(
			'userID'   => $res['userID'],
			'adID'     => $res['adID'],
			'price'    => $cityArr['price'] + $res['advertisePrice'],//此次回调广告的价格
			'city'     => $cityArr['cityName'],
			'receiptID'=> $data['aid'],
			'clientID' => $data['cid'],//客户端编号
			'playTime' => $data['time'],
			'appKey'   => $data['appKey'],
			'cityCode' => $citycode,
			'longitude'=> $data['longitude'],
			'latitude' => $data['latitude'],
			'speed'    => $data['speed'],
			'directionAngle' => $data['directionAngle']
		);
		//获得用户的余额
		// 	 如果 A 1） 金额剩余足够，执行扣减费用
		//          2） 返回原始金额[0]，返回剩余金额[1]，返回费用扣除状态[2] 1 成功
		//        B 1） 金额不足
		//          2） 返回原始金额[0]，返回剩余金额[1]为0（不能用于判断是否成功），返回扣除失败状态为0 ，失败
			// $userMoney = $this->getPrice($res['userID'],$insert_arr['price']);
			//获得用户资金变化表		
			$amountInfo = $this->db->select('amountAfterChanged')->where(array('userID'=>$res['userID'],'moneyStatus'=>'2'))->where('userID',$res['userID'])->order_by('id','desc')->get('userAmountchangedInfo')->row_array();
			//判断余额是否满足此次的广告价格
			if((float)$userMoney[0] < $insert_arr['price'] || empty($userMoney[0])){
				//删除广告
				$arr = $this->deleteAccid($res,$data['aid']);
				return $res = $this->pubfunction->pub_json('2004','用户余额不足！');
			}
			//更改用户资金变化表
			if(!empty($amountInfo)){
				//先读取之前的余额
				if((float)$userMoney[1] < (float)$insert_arr['price'] || (float)$userMoney[1] < '0'){
					//更新广告状态 投放中->暂停投放
					$this->deleteAccid($res,$data['aid']);
				}
				//金额足  扣款成功
				if($userMoney[2]){
					$update_amount = array(
						'amountBeforeChanged'  => (float)$userMoney[0],
						'changedAmount'       => '-'.$insert_arr['price'],
						'amountAfterChanged'  => (float)$userMoney[1],
						'adID'                => $res['adID'],
						'userID'              => $res['userID'],
						'createTime'          => time(),
						'updateTime'          => $data['time'],
						'remark'              => date('His',$data['time']).'-'.$insert_arr['city'],
						'moneyStatus'         => '2'//扣款成功
					);
					$amountStatus = $this->db->insert('userAmountchangedInfo',$update_amount);
					//更改用户详细信息表(改余额)
					$status=$this->db->where('userID',$res['userID'])->set(array('updateTime'=>time(),'balance'=>(float)$userMoney[1]))->update('userDetailInfo');
					$playStaus = $this->db->insert('adPlayInfo',$insert_arr);
					return ($status&&$playStaus&&$amountStatus)?$this->pubfunction->pub_json('0','ok'):$this->pubfunction->pub_json('1003','操作数据库失败');
				}
			}
			//删除广告
			$arr = $this->deleteAccid ($res,$data['aid']);
			return $res = $this->pubfunction->pub_json('2004','用户余额不足！');
	}

	//获取redis中对应的价格
	public function getPrice($userID,$price){
		$private_redis = new Redis();
		$redis = $this->config->item('redis');
		$private_redis->connect($redis['host'],$redis['port']);
		return $arr = $private_redis->eval("local tab = {} tab[1] = redis.call('get','".$userID.":adtalk') if (tonumber(tab[1]) or -1 ) - '".$price."' > 0  then tab[2] = redis.call('INCRBYFLOAT','".$userID.":adtalk', '-".$price."') tab[3] = 1 return tab end tab[2] = 0 tab[3] = 0 return tab");
	}

	/**
	*@Desc：调接口删除广告
	*@param：
	*/
	public function deleteAd($info,$aid){
		$config = $this->config->item('ad_issued');//adcube_del
		$del = array(
			'aid'    => $info['receiptID'],
			'appKey' => $config['appKey'],
			'secret' => $config['secret']
		);
		$delAdConfig = $this->pubfunction->sign($del);
		$arr = $this->pubfunction->subJson($config['adcube_del'],$delAdConfig);
		//更新广告状态 投放中->暂停投放
		return $res=$this->db->where('receiptID',$aid)->set(array('adStatus'=>'3','updateTime'=>time(),'receiptID'=>''))->update('adtalkInfo');
	}
	/**
	*@Desc：调接口删除意外险广告
	*@param：
	*/
	public function deleteAccid($info,$aid){
		$config = $this->config->item('ad_issued');//adcube_del
		$del = array(
			'aid'    => $info['receiptID'],
			'appKey' => $config['appKey'],
			'secret' => $config['secret']
		);
		$delAdConfig = $this->pubfunction->sign($del);
		$arr = $this->pubfunction->subJson($config['adcube_del'],$delAdConfig);
		//更新广告状态 投放中->暂停投放
		return $res=$this->db->where('receiptID',$aid)->set(array('adStatus'=>'3','updateTime'=>time(),'receiptID'=>''))->update('sponsorInfo');
	}

}
?>
