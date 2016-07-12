<?php
/**
*@Desc：后台账户管理
*/
class Accountmanage_model extends CI_Model{
	public function __construct(){
		$this->load->database();
		$this->load->library('pubfunction');
	}

	/**
	*@Desc：查询账户
	*/
	public function queryAccountInfo($data){
		$data['startTime'] = strtotime($data['startTime']);
		$data['endTime'] = strtotime($data['endTime']);
		$sql = "SELECT count(id) AS accountCount FROM userDetailInfo WHERE 1 ";
		$sql_page = "SELECT * FROM userDetailInfo WHERE 1 ";
		if($data['companyName'] != ''){
			$sql .= " AND `companyName` LIKE '%".$data['companyName']."%'";
			$sql_page .= " AND `companyName` LIKE '%".$data['companyName']."%'";
		}
		if($data['name'] != ''){
			$sql .= " AND `name` LIKE '%".$data['name']."%'";
			$sql_page .= " AND `name` LIKE '%".$data['name']."%'";
		}
		if($data['telephone'] != ''){
			$sql .= " AND `telephone` = '".$data['telephone']."'";
			$sql_page .= " AND `telephone` = '".$data['telephone']."'";
		}
		if($data['checkStatus'] != ''){
			$sql .= " AND `checkStatus` = '".$data['checkStatus']."'";
			$sql_page .= " AND `checkStatus` = '".$data['checkStatus']."'";
		}
		if(!empty($data['accountStatus'])){
			$sql .= " AND `accountStatus` = '".$data['accountStatus']."'";
			$sql_page .= " AND `accountStatus` = '".$data['accountStatus']."'";
		}
		if(!empty($data['cityCode'])){
			$sql .= " AND `cityCode` = '".$data['cityCode']."'";
			$sql_page .= " AND `cityCode` = '".$data['cityCode']."'";
		}
		if($data['startTime'] != '' && $data['endTime'] != ''){
			$sql .= " AND `createTime` BETWEEN '".$data['startTime']."' AND '".$data['endTime']."'";
			$sql_page .= " AND `createTime` BETWEEN '".$data['startTime']."' AND '".$data['endTime']."'";
		}
		if($data['startTime'] != '' && $data['endTime'] == ''){
			$sql .= " AND `createTime` > '".$data['startTime']."'";
			$sql_page .= " AND `createTime` > '".$data['startTime']."'";
		}
		if($data['endTime'] != '' && $data['startTime'] == ''){
			$sql .= " AND `createTime` < '".$data['endTime']."'";
			$sql_page .= " AND `createTime` < '".$data['endTime']."'";
		}
		$accountCount = $this->db->query($sql)->row_array();

		//查询数据 分页
		$sql_page .= " ORDER BY createTime DESC LIMIT ".$data['nowPage'].",".$data['pageNum']."";
		$accountInfo = $this->db->query($sql_page)->result_array();
		$result_array = array(
            "data"=>$accountInfo,
            "iTotalRecords" => $accountCount['accountCount'],
            "iTotalDisplayRecords" => $accountCount['accountCount']
        );
        return json_encode($result_array);
	}

	/**
	*@Desc：停用或激活账户
	*@param：userID、accountStatus
	*/
	public function operateAccount($data){
		$update_regiter = array(
			'status' => $data['accountStatus'],
			'updateTime' => time()
		);
		$register_status = $this->db->where('userID',$data['userID'])->update('userRegisterInfo',$update_regiter);
		$update_user = array(
			'accountStatus' => $data['accountStatus'],
			'updateTime' => time()
		);
		$user_status = $this->db->where('userID',$data['userID'])->update('userDetailInfo',$update_user);
		return ($register_status && $user_status)?$this->pubfunction->pub_json('0','ok'):$this->pubfunction->pub_json('1003','操作失败');
	}

	/**
	*@Desc：查询账户详情
	*@param：userID
	*/
	public function getAccountInfo($data){
		$info = $this->db->select()->where('userID',$data['userID'])->get('userDetailInfo')->row_array();
		return empty($info)?$this->pubfunction->pub_json('1009','该用户无账户信息'):$this->pubfunction->pub_json('0',$info);
	}

	/**
	*@Desc：处理申请
	*@param：userID
	*/
	public function dealwithApply($data){
		$update = array(
			'checkStatus' => $data['checkStatus'],
			'reason' => $data['reason'],
			'updateTime' => time()
		);
		$res = $this->db->where('userID',$data['userID'])->update('userDetailInfo',$update);
		return $res?$this->pubfunction->pub_json('0','ok'):$this->pubfunction->pub_json('1003','操作数据库失败');
	}
}
?>