<?php
/**
 *@versions：CI3.0.2
 *Desc：后台报表管理记录
 *createTime：2016/01/05
 *Auth：Zhouting
*/
class Reportmanage_model extends CI_Model{
	public function __construct(){
		$this->load->database();
		$this->load->library(array('pubfunction'));
	}

	/**
	*@Desc：查询广告统计数据
	*@Detail：广告统计
	*@param：iDisplayStart、iDisplayLength、startTime、endTime、city、advertiseTitle、adShape、
	*/
	public function getAllAdStatisticsInfo($data){
		$data['startTime'] = strtotime($data['startTime']);
		$data['endTime'] = strtotime($data['endTime']);
		$sql = "SELECT count(DISTINCT a.adID) AS reportCount FROM adPlayInfo AS a JOIN adtalkInfo AS b ON a.adID = b.adID WHERE 1";
		$sql_page = "SELECT a.id,a.adID,a.price,a.city,a.playTime,a.clientID,b.advertiseTitle,b.adShape FROM adPlayInfo AS a JOIN adtalkInfo AS b ON a.adID = b.adID WHERE 1 ";
		if(!empty($data['city'])){
			// $sql .= " AND a.city LIKE '%".$data['city']."%'";
			$sql .= " AND a.city = '".$data['city']."'";
			// $sql_page .= " AND a.city LIKE '%".$data['city']."%'";
			$sql_page .= " AND a.city = '".$data['city']."'";
		}
		if(!empty($data['advertiseTitle'])){
			$sql .= " AND b.advertiseTitle LIKE '%".$data['advertiseTitle']."%'";
			$sql_page .= " AND b.advertiseTitle LIKE '%".$data['advertiseTitle']."%'";
		}
		if(!empty($data['adShape'])){
			$sql .= " AND b.adShape = '".$data['adShape']."'";
			$sql_page .= " AND b.adShape = '".$data['adShape']."'";
		}
		if(!empty($data['startTime']) && !empty($data['endTime']) ){
			$sql .= " AND a.playTime BETWEEN '".$data['startTime']."' AND '".$data['endTime']."'";
			$sql_page .= " AND a.playTime BETWEEN '".$data['startTime']."' AND '".$data['endTime']."'";
		}
		if($data['startTime'] != '' && $data['endTime'] == ''){
			$sql .= " AND a.playTime > '".$data['startTime']."'";
			$sql_page .= " AND a.playTime > '".$data['startTime']."'";
		}
		if($data['endTime'] != '' && $data['startTime'] == ''){
			$sql .= " AND a.playTime < '".$data['endTime']."'";
			$sql_page .= " AND a.playTime < '".$data['endTime']."'";
		}
		$reportCount = $this->db->query($sql)->row_array();
		//分页
		$sql_page .= "  LIMIT ".$data['nowPage'].','.$data['pageNum'];
		// $sql_page .= " ORDER BY `playTime` ".$data['sort']." ";
		$info = $this->db->query($sql_page)->result_array();
		$result = array();
		foreach($info as $k=>$v){
		    $result[$v['adID']][] = $v;
		}
		$info = $this->parseDataByAd($result);
		$result_array = array(
            "data"=>$info,
            "iTotalRecords" => $reportCount['reportCount'],
            "iTotalDisplayRecords" => $reportCount['reportCount']
        );
        return json_encode($result_array);
	}

	public function parseDataByAd($data){
		$info=array();
		foreach ($data as $key => $value) {
			$price = 0;
			$playCount = count($value);
			$city = array();
			$clientID = array();
			for ($i=0; $i < $playCount; $i++) {
				$price+=$value[$i]['price'];
				$adShape = $value[$i]['adShape'];
				array_push($city, $value[$i]['city']);
				array_push($clientID,$value[$i]['clientID']);
			}
			$advertiseTitle = $value[0]['advertiseTitle'];
			$result = array(
				'adID'  	 => $key,
				'price'      => sprintf("%0.2f", $price),
				'city'  	 => array_count_values($city),
				'clientCount'=> count(array_count_values($clientID)),
				'playCount'  => $playCount,
				'adShape'    => $adShape,
				'advertiseTitle' => $advertiseTitle
			);
			$res = array_push($info,$result);
		}
		return $info;
	}

	/**
	*@Desc：获得所有的企业统计数据
	*@param：nowPage、pageNum、cityCode、companyName、
	*/
	public function getCompanyStatisticsInfo($data){
		$sql = "SELECT c.id,c.userID,c.companyName,c.cityCode,b.adShape,b.adID,b.receiptID,a.price FROM adPlayInfo AS a JOIN adtalkInfo AS b ON a.adID = b.adID JOIN userDetailInfo AS c ON a.userID = c.userID";
		if(!empty($data['cityCode'])){
			$sql .= " AND c.cityCode = '".$data['cityCode']."'";
		}
		if(!empty($data['companyName'])){
			$sql .= " AND c.companyName LIKE '%".$data['companyName']."%'";
		}
		$all = $this->db->query($sql)->result_array();
		$result = array();
		foreach($all as $k=>$v){
		    $result[$v['userID']][] = $v;
		}
	    $res = $this->parseDataByCompany($result);

	    $count = count($res);
		$result_array = array(
            "data"=>$res,
            "iTotalRecords" => $count,
            "iTotalDisplayRecords" => $count
        );
        return json_encode($result_array);
	}

	public function parseDataByCompany($data){
		$info=array();
		foreach ($data as $key => $value) {
			$naming = 0;
			$trailer = 0;
			$namingPrice=0;
			$trailerPrice=0;
			for ($i=0; $i < count($value); $i++) {
				$id = $value[$i]['id'];
				$cityCode = $value[$i]['cityCode'];
				$companyName = $value[$i]['companyName'];
				if($value[$i]["adShape"]==1){
					$naming+=1;
					$namingPrice+=$value[$i]['price'];
				}else{
					$trailer+=1;
					$trailerPrice+=$value[$i]['price'];
				}
			}
			$result = array(
				'id'         => $id,
				'naming'     => $naming,
				'namingPrice'=>sprintf("%0.2f", $namingPrice),
				'trailer'    => $trailer,
				'trailerPrice'=>sprintf("%0.2f", $trailerPrice),
				'userID'     => $key,
				'cityCode'   => $cityCode,
				'companyName'=> $companyName
			);
			$res = array_push($info,$result);
		}
		return $info;
	}
}
?>