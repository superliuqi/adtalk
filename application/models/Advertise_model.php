<?php
class Advertise_model extends CI_Model{
	public function __construct(){
		$this->load->database();
		$this->load->library(array('pubfunction'));
	}

	/**
	*@Desc：3：暂停投放/0：提交审核/1：开启投放
	*@param：adID、adStatus
	*/
	public function nonUseAd($data){
		$info = $this->db->select()->where('adID',$data['adID'])->get('adtalkInfo')->row_array();
		//暂停广告
		if($data['adStatus'] == '3' && !empty($info)){//停用并且是已经播放的  将存储的广告删除
			$config = $this->config->item('ad_issued');
			$del = array(
				'aid'    => $info['receiptID'],
				'appKey' => $config['appKey'],
				'secret' => $config['secret']
			);
			$delAdConfig = $this->pubfunction->sign($del);
			$arr = $this->pubfunction->subJson($config['adcube_del'],$delAdConfig);
			$res=$this->db->where('adID',$data['adID'])->set(array('adStatus'=>$data['adStatus'],'updateTime'=>time(),'receiptID'=>''))->update('adtalkInfo');
			return $res?$this->pubfunction->pub_json('0','ok'):$this->pubfunction->pub_json('1003','暂停失败');
		}
		//开启投放
		if($data['adStatus'] == '1'){
			//便于比较余额是否大于广告花费的最大价格
			$price = explode('~',$info['advertisePrice']);
			$userInfo = $this->db->select('balance,checkStatus')->where('userID',$info['userID'])->get('userDetailInfo')->row_array();
			if($userInfo['balance'] < $price['1']){
				return $res = $this->pubfunction->pub_json('1007','用户余额不足');
			}
			if($userInfo['checkStatus'] != '2'){
				return $res = $this->pubfunction->pub_json('1007','企业资料未完善或审核未通过');
			}
			$info = $this->db->select()->where('adID',$data['adID'])->get('adtalkInfo')->row_array();
			$arr = $this->saveAd($info);
			if($arr['ERRORCODE'] != '0'){
				return $res = $this->pubfunction->pub_json('1003','存储广告失败');
			}
			$res=$this->db->where(array('adID'=>$data['adID']))->set(array('adStatus'=>$data['adStatus'],'updateTime'=>time(),'receiptID'=>$arr['RESULT']['aid']))->update('adtalkInfo');
			return $res?$this->pubfunction->pub_json('0','ok'):$this->pubfunction->pub_json('1003','开启投放失败');
		}
		$update_arr = array(
			'adStatus' => $data['adStatus'],
			'updateTime' => time()
		);
		$status = $this->db->where('adID',$data['adID'])->update('adtalkInfo',$update_arr);
		return $status?$this->pubfunction->pub_json('0','ok'):$this->pubfunction->pub_json('1003','提交审核失败');
	}

	/**
	*@Desc：3：暂停投放/0：提交审核/1：开启投放
	*@param：adID、adStatus
	*/
	public function nonUseAccid($data){
		$info = $this->db->select()->where('adID',$data['adID'])->get('sponsorInfo')->row_array();
		//暂停广告
		if($data['adStatus'] == '3' && !empty($info)){//停用并且是已经播放的  将存储的广告删除
			$config = $this->config->item('ad_issued');
			$del = array(
				'aid'    => $info['receiptID'],
				'appKey' => $config['appKey'],
				'secret' => $config['secret']
			);
			$delAdConfig = $this->pubfunction->sign($del);
			$arr = $this->pubfunction->subJson($config['adcube_del'],$delAdConfig);
			$res=$this->db->where('adID',$data['adID'])->set(array('adStatus'=>$data['adStatus'],'updateTime'=>time(),'receiptID'=>''))->update('sponsorInfo');
			return $res?$this->pubfunction->pub_json('0','ok'):$this->pubfunction->pub_json('1003','暂停失败');
		}
		//开启投放
		if($data['adStatus'] == '1'){
			//便于比较余额是否大于广告花费的最大价格
			$price = $info['advertisePrice'];
			$userInfo = $this->db->select('balance,checkStatus')->where('userID',$info['userID'])->get('userDetailInfo')->row_array();
			if($userInfo['balance'] < $price){
				return $res = $this->pubfunction->pub_json('1007','用户余额不足');
			}
			if($userInfo['checkStatus'] != '2'){
				return $res = $this->pubfunction->pub_json('1007','企业资料未完善或审核未通过');
			}
			$info = $this->db->select()->where('adID',$data['adID'])->get('sponsorInfo')->row_array();
			$arr = $this->saveAccid($info);
			if($arr['ERRORCODE'] != '0'){
				return $res = $this->pubfunction->pub_json('1003','存储广告失败');
			}
			$res=$this->db->where(array('adID'=>$data['adID']))->set(array('adStatus'=>$data['adStatus'],'updateTime'=>time(),'receiptID'=>$arr['RESULT']['aid']))->update('sponsorInfo');
			return $res?$this->pubfunction->pub_json('0','ok'):$this->pubfunction->pub_json('1003','开启投放失败');
		}
		$update_arr = array(
			'adStatus' => $data['adStatus'],
			'updateTime' => time()
		);
		$status = $this->db->where('adID',$data['adID'])->update('sponsorInfo',$update_arr);
		return $status?$this->pubfunction->pub_json('0','ok'):$this->pubfunction->pub_json('1003','提交审核失败');
	}

	/**
	*@Desc：调att接口存广告
	*@return：array
	*/
	public function saveAd($info){
		$info['brandName'] = $info['adShape'] == '1'?$info['brandName']:$info['audioContent'];
		$info['url'] = $info['adShape'] == '1'?'':$info['audioURL'];
		$info['urlType'] = $info['adShape'] == '1'?'':'amr';

		$ad_issued = $this->config->item('ad_issued');
		$adSave = array(
			'content' => '{"url":"'.$info['url'].'","text":"'.$info["brandName"].'","urlType:"'.$info['urlType'].'"}',
			'typ'     => $info['adShape'],
			'citycode'=> $info['cityCode'],
			'adtime'  => $info['timeInterval'],
			'cburl'   => $ad_issued['callBackUrl'],
			'appKey'  => $ad_issued['appKey'],
			'secret'  => $ad_issued['secret']
		);
		$save = $this->pubfunction->sign($adSave);
		//调接口存储广告
		return $arr = $this->pubfunction->subJson($ad_issued['adcube_set'],$save);
	}

	/**
	*@Desc：调att接口存意外险广告
	*@return：array
	*/
	public function saveAccid($info){
		$ad_issued = $this->config->item('ad_issued');
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
		return $arr = $this->pubfunction->subJson($ad_issued['adcube_set'],$save);
	}

	/**
	*@Desc：获得广告详情数据
	*@param：adID
	*/
	public function copyreaderAd($data){
		$adInfo = $this->db->select()->where('adID',$data['adID'])->get('adtalkInfo')->row_array();
		return $res = !empty($adInfo)?$this->pubfunction->pub_json('0',$adInfo):$this->pubfunction->pub_json('1009','该广告编号无对应信息');
	}

	/**
	*@Desc：广告查询
	*@param：adStatus、advertiseTitle、adShape、startTime、endTime、userID、iDisplayStart、iDisplayLength
	*@Detail：拼接where条件
	*/
	public function getAllAdData($data){
		$data['startTime'] = strtotime($data['startTime']);
		$data['endTime'] = strtotime($data['endTime']);
		$sql_count= "SELECT count(id) as total FROM `adtalkInfo` WHERE `userID` = '".$data['userID']."'";
		$sql_page = "SELECT * FROM `adtalkInfo` WHERE `userID` = '".$data['userID']."'";
		if($data['adStatus'] != ''){
			$sql_count.= " AND `adStatus` = '".$data['adStatus']."'";
			$sql_page .= " AND `adStatus` = '".$data['adStatus']."'";
		}
		if(!empty($data['advertiseTitle'])){
			$sql_count .= " AND `advertiseTitle` LIKE '%".$data['advertiseTitle']."%'";
			$sql_page .= " AND `advertiseTitle` LIKE '%".$data['advertiseTitle']."%'";
		}
		if(!empty($data['adShape'])){
			$sql_count .= " AND `adShape` = '".$data['adShape']."'";
			$sql_page .= " AND `adShape` = '".$data['adShape']."'";
		}
		if(!empty($data['startTime']) && !empty($data['endTime'])){
			$sql_count .= " AND `createTime` BETWEEN '".$data['startTime']."' AND '".$data['endTime']."'";
			$sql_page .= " AND `createTime` BETWEEN '".$data['startTime']."' AND '".$data['endTime']."'";
		}
		if($data['startTime'] != '' && $data['endTime'] == ''){
			$sql_count .= " AND `createTime` > '".$data['startTime']."'";
			$sql_page .= " AND `createTime` > '".$data['startTime']."'";
		}
		if($data['endTime'] != '' && $data['startTime'] == ''){
			$sql_count .= " AND `createTime` < '".$data['endTime']."'";
			$sql_page .= " AND `createTime` < '".$data['endTime']."'";
		}
		//总查询条数
		$count = $this->db->query($sql_count)->row_array();
		//查询数据 分页
		$sql_page .= " ORDER BY `updateTime` DESC LIMIT ".$data['iDisplayStart'].",".$data['iDisplayLength']."";
		$info = $this->db->query($sql_page)->result_array();
		$result_array = array(
            'data'					=> $info,
            'iTotalRecords' 		=> $count['total'],
            'iTotalDisplayRecords'  => $count['total']
        );
        return json_encode($result_array);
	}

	/**
	*@Desc：意外险广告查询
	*create by liuqi
	*2016-04-18
	*/
	public function getAllAccidData($data){
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
	*@Desc：动态获取广告价格明细
	*@param：cityCode、adShape、timeSlot
	*/
	public function selectPriceList($data){
		//获得广告形式价格
		$adShapePrice = $this->db->select('adType,price')->where('adType',$data['adShape'])->get('adShapePrice')->row_array();
		$adShapePrice = !empty($adShapePrice)?array('adShapePrice'=>$adShapePrice):array('adShapePrice'=>'');
		
		//时间段不限
		if($data['timeSlot'] == ''){
			$timeSql = "SELECT * FROM `timeSlotPrice` WHERE 1";
		}else{
			// 去掉最后的'|' 将字符串中的'|'替换为' ',' '
			$data['timeSlot'] = str_replace("|", "','", substr($data['timeSlot'],0,-1));
			$timeSql = "SELECT * FROM `timeSlotPrice` WHERE `timeSlot` IN ('".$data['timeSlot']."')";
		}
		$timeSlotPrice = $this->db->query($timeSql)->result_array();
		$timeSlotPrice = !empty($timeSlotPrice)?array('timeSlotPrice'=>$timeSlotPrice):array('timeSlotPrice'=>'');

		//获得城市价格
		if($data['cityCode'] == '1'){
			$cityCodePrice = array('cityCodePrice'=>array('0'=>array('price'=>'0.00-0.02','cityName'=>'全国','provinceName'=>'全国')));
		}else{
			$data['cityCode'] = str_replace("|", "','", substr($data['cityCode'],0,-1));
			$citySql = "SELECT `provinceName`,`cityName`,`price` FROM `chinaCityPrice` WHERE `cityCode` IN ('".$data['cityCode']."') OR `provinceCode` IN ('".$data['cityCode']."') GROUP BY `cityName`";
			$cityCodePrice  =$this->db->query($citySql)->result_array();
			$cityCodePrice = !empty($cityCodePrice)?array('cityCodePrice'=>$cityCodePrice):array('cityCodePrice'=>'');
		}
		$res = array_merge($adShapePrice,$timeSlotPrice,$cityCodePrice);
		return $res = $this->pubfunction->pub_json('0',$res);
	}

	/**
	*@Desc：新建广告 添加数据
	*/
	public function insertAd($data){
		//时间段不限
		if($data['timeType'] == '1'){
			$timeSlotStr = '';
			$timeSlot = $this->db->select('timeSlot')->get('timeSlotPrice')->result_array();
			foreach ($timeSlot as $k => $v) {
				$timeSlotStr .= $v['timeSlot'].'|';
			}
			$data['timeInterval'] = $timeSlotStr;
		}
		unset($data['type']);
		//全国
		if($data['cityCode'] == '1'){
			$cityCode = '';
			$cityCodeArr = $this->db->select('cityCode')->group_by('cityName')->get('chinaCityPrice')->result_array();
			foreach ($cityCodeArr as $k => $v) {
				$cityCode .= $v['cityCode'].'|';
			}
			$data['cityCode'] = $cityCode;
		}
		//返回城市的最大与最小价格
		$cityPrice = $this->cityContrastPrice($data);
		$cityPrice = json_decode($cityPrice,TRUE);

		//返回时间段的最大值与最小值
		$timePrice = $this->timeContrastPrice($data);
		$timePrice = json_decode($timePrice,TRUE);

		//获得广告形式对应价格
		$shapePrice = $this->getAdShapePrice($data);
		$shapePrice = json_decode($shapePrice,TRUE);

		//开始获得区间
		$minPrice = $cityPrice['RESULT']['minPrice'] + $timePrice['RESULT']['minPrice'] + $shapePrice['RESULT']['price'];
		$maxPrice = $cityPrice['RESULT']['maxPrice'] + $timePrice['RESULT']['maxPrice'] + $shapePrice['RESULT']['price'];
		//sprintf("%0.2f", $maxPrice);  将1.1 转为1.10
		$minPrice = sprintf("%0.2f", $minPrice);
		$maxPrice = sprintf("%0.2f", $maxPrice);
		if($minPrice == $data['minPrice'] &&  $maxPrice == $data['maxPrice']){
			unset($data['maxPrice']);unset($data['minPrice']);
			$data['advertisePrice'] = $minPrice.'~'.$maxPrice;
			$status = $this->db->insert('adtalkInfo',$data);
			return $status?$this->pubfunction->pub_json('0','ok'):$this->pubfunction->pub_json('1003','添加广告失败');
		}else{
			return $res = $this->pubfunction->pub_json('2002',array('maxPrice'=>$maxPrice,'minPrice'=>$minPrice));
		}
	}

	/**
	  * 新建意外险广告
	  * @author liuqi 
	  * 2016-04-07
	  */
	public function insertAccid($data){
		//全国
		if($data['cityCode'] == '1'){
			$cityCode = '';
			$cityCodeArr = $this->db->select('cityCode')->group_by('cityName')->get('chinaCityPrice')->result_array();
			foreach ($cityCodeArr as $k => $v) {
				$cityCode .= $v['cityCode'].'|';
			}
			$data['cityCode'] = $cityCode;
		}
		$result = $this->db->insert('sponsorInfo',$data);
		return $result?$this->pubfunction->pub_json('0','ok'):$this->pubfunction->pub_json('1003','添加意外险广告失败');
	}

	/**
	*@Desc：获取区域的代码返回对应的价格最大值与最小值、全国
	*@Detail：共用方法
	*@param；cityCode
	*/
	public function cityContrastPrice($data){
		//全国
		if($data['cityCode'] == '1'){
			return $res = $this->pubfunction->pub_json('0',array_merge(array('maxPrice'=>'0.02'),array('minPrice'=>'0.00')));
		}else{
			$data['cityCode'] = str_replace("|", "','", substr($data['cityCode'],0,-1));
			$citySql = "SELECT `provinceName`,`cityName`,`price` FROM `chinaCityPrice` WHERE `cityCode` IN ('".$data['cityCode']."') OR `provinceCode` IN ('".$data['cityCode']."') GROUP BY `cityName`";
		}
		$cityCodePrice  =$this->db->query($citySql)->result_array();
		for($i=0;$i<count($cityCodePrice);$i++){
			$cityPrice[$i] = $cityCodePrice[$i]['price'];
		}
		//最大值
		$maxPrice = array_search(max($cityPrice), $cityPrice);
		$maxPrice = array('maxPrice'=>$cityPrice[$maxPrice]);
		//最小值
		$minPrice = array_search(min($cityPrice), $cityPrice);
		$minPrice = array('minPrice'=>$cityPrice[$minPrice]);
		return $res = $this->pubfunction->pub_json('0',array_merge($maxPrice,$minPrice));
	}

	/**
	*@Desc：查询对应广告形式的价格
	*@param：adShape
	*/
	public function getAdShapePrice($data){
		$shapePrice = $this->db->select('price')->where('adType',$data['adShape'])->get('adShapePrice')->row_array();
		return $res = $this->pubfunction->pub_json('0',$shapePrice);
	}

	/**
	*@Desc：获取时间段返回对应的价格最大值与最小值
	*@param：timeInterval
	*/
	public function timeContrastPrice($data){
		//时间段不限
		if($data['timeType'] == '1'){
			$citySql = "SELECT `timeSlot`,`price` FROM `timeSlotPrice` WHERE 1";
			// return $res = $this->pubfunction->pub_json('0',array_merge(array('maxPrice'=>'0.00'),array('minPrice'=>'0.01')));
		}else{
			$data['timeInterval'] = str_replace("|", "','", substr($data['timeInterval'],0,-1));
			$citySql = "SELECT `timeSlot`,`price` FROM `timeSlotPrice` WHERE `timeSlot` IN ('".$data['timeInterval']."')";
		}
		$timePrice  =$this->db->query($citySql)->result_array();
		for($i=0;$i<count($timePrice);$i++){
			$timePrice[$i] = $timePrice[$i]['price'];
		}
		//最大值
		$maxPrice = array_search(max($timePrice), $timePrice);
		$maxPrice = array('maxPrice'=>$timePrice[$maxPrice]);
		//最小值
		$minPrice = array_search(min($timePrice), $timePrice);
		$minPrice = array('minPrice'=>$timePrice[$minPrice]);
		$res = array_merge($maxPrice,$minPrice);
		return $res = $this->pubfunction->pub_json('0',array_merge($maxPrice,$minPrice));
	}

	/**
	*@Desc：用于页面循环时间段、广告形式
	*/
	public function showPrice(){
		//获得时间段对应价格
		$timePrice = $this->db->select()->get('timeSlotPrice')->result_array();
		$timePrice = !empty($timePrice)?array('timeSlotInfo'=>$timePrice):array('timeSlotInfo'=>'');

		//获得广告形式对应价格
		$adShapePrice = $this->db->select()->get('adShapePrice')->result_array();
		$adShapePrice = !empty($adShapePrice)?array('adShapePrice'=>$adShapePrice):array('adShapePrice'=>'');

		return $res = $this->pubfunction->pub_json('0',array_merge($timePrice,$adShapePrice));
	}

	/**
	*@Desc：查看广告详情
	*@param：adID
	*/
	public function getAdDetailInfo($data){
		$res = $this->db->select()->where('adID',$data['adID'])->get('adtalkInfo')->result_array();
		return $res = empty($res[0])?$this->pubfunction->pub_json('1009','该编号无对应广告信息'):$this->pubfunction->pub_json('0',$res);
	}

	/**
	*@Desc：修改广告 添加数据
	*/
	public function updateAd($data){
		//时间段不限
		if($data['timeType'] == '1'){
			$timeSlotStr = '';
			$timeSlot = $this->db->select('timeSlot')->get('timeSlotPrice')->result_array();
			foreach ($timeSlot as $k => $v) {
				$timeSlotStr .= $v['timeSlot'].'|';
			}
			$data['timeInterval'] = $timeSlotStr;
		}
		unset($data['type']);
		//全国
		if($data['cityCode'] == '1'){
			$cityCode = '';
			$cityCodeArr = $this->db->select('cityCode')->group_by('cityName')->get('chinaCityPrice')->result_array();
			foreach ($cityCodeArr as $k => $v) {
				$cityCode .= $v['cityCode'].'|';
			}
			$data['cityCode'] = $cityCode;
		}
		//返回城市的最大与最小价格
		$cityPrice = $this->cityContrastPrice($data);
		$cityPrice = json_decode($cityPrice,TRUE);

		//返回时间段的最大值与最小值
		$timePrice = $this->timeContrastPrice($data);
		$timePrice = json_decode($timePrice,TRUE);

		//获得广告形式对应价格
		$shapePrice = $this->getAdShapePrice($data);
		$shapePrice = json_decode($shapePrice,TRUE);

		//开始获得区间
		$minPrice = $cityPrice['RESULT']['minPrice'] + $timePrice['RESULT']['minPrice'] + $shapePrice['RESULT']['price'];
		$maxPrice = $cityPrice['RESULT']['maxPrice'] + $timePrice['RESULT']['maxPrice'] + $shapePrice['RESULT']['price'];
		//sprintf("%0.2f", $maxPrice);  将1.1 转为1.10
		$minPrice = sprintf("%0.2f", $minPrice);
		$maxPrice = sprintf("%0.2f", $maxPrice);
		if($minPrice == $data['minPrice'] &&  $maxPrice == $data['maxPrice']){
			unset($data['maxPrice']);unset($data['minPrice']);
			$data['advertisePrice'] = $minPrice.'~'.$maxPrice;
			$status = $this->db->where('adID',$data['adID'])->update('adtalkInfo',$data);
			return $status?$this->pubfunction->pub_json('0','ok'):$this->pubfunction->pub_json('1003','修改广告失败');
		}else{
			return $res = $this->pubfunction->pub_json('2002',array('maxPrice'=>$maxPrice,'minPrice'=>$minPrice));
		}
	}
}
?>