<?php
/**
*@Desc：后台交易记录管理
*/
class Trademanage_model extends CI_Model{
	public function __construct(){
		$this->load->database();
		$this->load->library(array('pubfunction'));
	}

	/**
	*@Desc：获得所有的交易记录数据
	*@param：iDisplayStart、iDisplayLength、companyName、adShape、advertiseTitle、startTime、endTime、
	*/
	public function getAllTransactionInfo($data){
		echo "<pre>";
		$data['startTime'] = strtotime($data['startTime']);
		$data['endTime'] = strtotime($data['endTime']);
		//获得总条数
		$sql = "SELECT count(a.id) AS tradeCount FROM adPlayInfo AS a JOIN adtalkInfo AS b ON a.adID = b.adID JOIN userDetailInfo AS c ON a.userID = c.userID WHERE 1";

		$sql_page = "SELECT a.adID,a.playTime,a.city,a.price,b.advertiseTitle,b.adShape,c.companyName FROM adPlayInfo AS a JOIN adtalkInfo AS b ON a.adID = b.adID JOIN userDetailInfo AS c ON a.userID = c.userID WHERE 1";
		if(!empty($data['companyName'])){
			$sql .= " AND c.companyName LIKE '%".$data['companyName']."%'";
			$sql_page .= " AND c.companyName LIKE '%".$data['companyName']."%'";
		}
		if(!empty($data['adShape'])){
			$sql .= " AND b.adShape = '".$data['adShape']."'";
			$sql_page .= " AND b.adShape = '".$data['adShape']."'";
		}
		if(!empty($data['adID'])){
			$sql .= " AND a.adID LIKE '%".$data['adID']."%'";
			$sql_page .= " AND a.adID LIKE '%".$data['adID']."%'";
		}
		if(!empty($data['advertiseTitle'])){
			$sql .= " AND b.advertiseTitle LIKE '%".$data['advertiseTitle']."%'";
			$sql_page .= " AND b.advertiseTitle LIKE '%".$data['advertiseTitle']."%'";
		}
		if(!empty($data['startTime']) && !empty($data['endTime'])){
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
		if(!empty($data['city'])){
			$sql .= " AND a.city LIKE '%".$data['city']."%'";
			$sql_page .= " AND a.city LIKE '%".$data['city']."%'";
		}
		//查询总条数
		$count = $this->db->query($sql)->row_array();

		//查询数据 分页
		$sql_page .= " ORDER BY a.id DESC LIMIT ".$data['iDisplayStart'].",".$data['iDisplayLength']."";
		$info = $this->db->query($sql_page)->result_array();

		$result_array = array(
            "data"=>$info,
            "iTotalRecords" => $count['tradeCount'],
            "iTotalDisplayRecords" => $count['tradeCount']
        );
        return json_encode($result_array);
	}
}
?>