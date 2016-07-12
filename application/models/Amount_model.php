<?php
class Amount_model extends CI_Model{
	public function __construct(){
		$this->load->database();
		$this->load->library(array('pubfunction'));
	}

	/**
	*@Desc：获取用户余额
	*@param：userID
	*/
	public function getUserBalance($data){
		$resAmount = $this->db->where('userID',$data['userID'])->select('balance')->get('userDetailInfo')->row_array();
		return $resAmount = empty($resAmount['balance'])?$this->pubfunction->pub_json('0',array('balance'=>'0')):$this->pubfunction->pub_json('0',array('balance'=>$resAmount['balance']));
	}

	/**
	*@Desc：立即充值 - 记录充值记录
	*@param：userID
	*/
	public function insertWaterBill($data){
	    $insert_arr = array(
        	'userID'              => $data['userID'],
        	'remitIdentCode'      => $this->pubfunction->remittanceCode(15,3),//汇款识别码
        	'money'               => $data['money'],
        	'runCode' 			  => $this->pubfunction->serialNumber(),//流水号
        	'updateTime' 		  => time(),
        	'createTime' 		  => time(),
        	'rechargeStatus' 		  => '0'
        );
        $phone =$this->db->where('userID',$data['userID'])->select('telephone')->get('userDetailInfo')->row_array();
        //数据展示到汇款信息页面
        $return_arr = array(
        	'money'          => $insert_arr['money'],
        	'runCode'        => $insert_arr['runCode'],
        	'remitIdentCode' => $insert_arr['remitIdentCode'],
        	'telephone'      => $phone['telephone']
        );
        $status = $this->db->insert('rechargeRecordInfo',$insert_arr);
        return $res = $status?$this->pubfunction->pub_json('0',$return_arr):$this->pubfunction->pub_json('1003','充值失败');
	}

	/**
	*@Desc：点击确认已打款
	*@param：userID、remitIdentCode
	*/
	public function editPayStauus($data){
		$res=$this->db->where(array('userID'=>$data['userID'],'remitIdentCode'=>$data['remitIdentCode']))->set(array('rechargeStatus'=>'1','updateTime'=>time()))->update('rechargeRecordInfo');
		return $res?$this->pubfunction->pub_json('0','ok'):$this->pubfunction->pub_json('1003','修改充值状态失败');
	}

	/**
	*@Desc：充值记录查询
	*@param：userID、remitIdentCode、runCode、startTime、endTime、pageNum、nowPage、rechargeStatus
	*/
	public function getAllRecharge($data){
		$data['startTime'] = strtotime($data['startTime']);
		$data['endTime'] = strtotime($data['endTime']);
		$sql = "SELECT count(id) AS rechargeCount FROM `rechargeRecordInfo` WHERE `userID` = '".$data['userID']."'";
		$sql_page = "SELECT * FROM `rechargeRecordInfo` WHERE `userID` = '".$data['userID']."'";
		if(!empty($data['rechargeStatus'])){
			$sql .= " AND `rechargeStatus` = '".$data['rechargeStatus']."'";
			$sql_page .= " AND `rechargeStatus` = '".$data['rechargeStatus']."'";
		}
		if(!empty($data['remitIdentCode'])){
			$sql .= " AND `remitIdentCode` LIKE '%".$data['remitIdentCode']."%'";
			$sql_page .= " AND `remitIdentCode` LIKE '%".$data['remitIdentCode']."%'";
		}
		if(!empty($data['runCode'])){
			$sql .= " AND `runCode` LIKE '%".$data['runCode']."%'";
			$sql_page .= " AND `runCode` LIKE '%".$data['runCode']."%'";
		}
		if(!empty($data['startTime']) && !empty($data['endTime'])){
			$sql .= " AND `createTime` BETWEEN '".$data['startTime']."' AND '".$data['endTime']."'";
			$sql_page .= " AND `createTime` BETWEEN '".$data['startTime']."' AND '".$data['endTime']."'";
		}
		if($data['startTime'] != '' && $data['endTime'] == ''){
			$sql .= " AND createTime > '".$data['startTime']."'";
			$sql_page .= " AND createTime > '".$data['startTime']."'";
		}
		if($data['endTime'] != '' && $data['startTime'] == ''){
			$sql .= " AND createTime < '".$data['endTime']."'";
			$sql_page .= " AND createTime < '".$data['endTime']."'";
		}
		//查询总条数
		$pay = $this->db->query($sql)->row_array();

		//查询数据 分页
		$sql_page .= " ORDER BY `updateTime` DESC LIMIT ".$data['nowPage'].",".$data['pageNum']."";
		$payLog = $this->db->query($sql_page)->result_array();
		$result_array = array(
            "data"=>$payLog,
            "iTotalRecords" => $pay['rechargeCount'],
            "iTotalDisplayRecords" => $pay['rechargeCount']
        );
        return json_encode($result_array);
	}

	/**
	*@Desc：查询资金明细
	*@param：userID、adID、runCode、moneyType(1 收入 2支出)、nowPage、pageNum、
	*/
	public function selectMoneyDetail($data){
		$sql = "SELECT count(id) AS detailCount FROM `userAmountchangedInfo` WHERE `userID` = '".$data['userID']."'";
		$sql_page = "SELECT * FROM `userAmountchangedInfo` WHERE `userID` = '".$data['userID']."'";
		if($data['adID'] != ''){
			$sql .= " AND `adID` LIKE '%".$data['adID']."%'";
			$sql_page .= " AND `adID` LIKE '%".$data['adID']."%'";
		}
		if($data['runCode'] != ''){
			$sql .= " AND `runCode` LIKE '%".$data['runCode']."%'";
			$sql_page .= " AND `runCode` LIKE '%".$data['runCode']."%'";
		}
		if($data['moneyType'] == '1'){
			$sql .= " AND `changedAmount` > 0";
			$sql_page .= " AND `changedAmount` > 0";
		}
		if($data['moneyType'] == '2'){
			$sql .= " AND `changedAmount` < 0";
			$sql_page .= " AND `changedAmount` < 0";
		}
		//总查询条数
		$moneytNum = $this->db->query($sql)->row_array();

		//查询数据 分页
		$sql_page .= " ORDER BY createtime DESC,id DESC LIMIT ".$data['nowPage'].",".$data['pageNum']."";
		$info = $this->db->query($sql_page)->result_array();
		$result_array = array(
            "data"=>$info,
            "iTotalRecords" => $moneytNum['detailCount'],
            "iTotalDisplayRecords" => $moneytNum['detailCount']
        );
        return json_encode($result_array);
	}
}
?>