<?php
/**
*@Desc：后台充值管理
*/
class Rechargemanage_model extends CI_Model{
	public function __construct(){
		$this->load->database();
		$this->load->library(array('pubfunction'));
	}

	/**
	*@Desc：获得所有充值记录
	*@param：companyName、remitIdentCode、rechargeStatus、runCode、startTime、endTime
	*/
	public function getAllRechargeInfo($data){
		$data['startTime'] = strtotime($data['startTime']);
		$data['endTime'] = strtotime($data['endTime']);
		$sql = "SELECT count(a.id) AS rechargeCount FROM rechargeRecordInfo AS a JOIN userDetailInfo AS b ON a.userID = b.userID WHERE 1 ";
		$sql_page = "SELECT a.*,b.companyName FROM rechargeRecordInfo AS a JOIN userDetailInfo AS b ON a.userID = b.userID WHERE 1 ";		
		if($data['companyName'] != ''){
			$sql .= " AND b.companyName LIKE '%".$data['companyName']."%'";
			$sql_page .= " AND b.companyName LIKE '%".$data['companyName']."%'";
		}
		if($data['remitIdentCode'] != ''){
			$sql .= " AND a.remitIdentCode LIKE '%".$data['remitIdentCode']."%'";
			$sql_page .= " AND a.remitIdentCode LIKE '%".$data['remitIdentCode']."%'";
		}
		if($data['rechargeStatus'] != ''){
			$sql .= " AND a.rechargeStatus = '".$data['rechargeStatus']."'";
			$sql_page .= " AND a.rechargeStatus = '".$data['rechargeStatus']."'";
		}
		if($data['runCode'] != ''){
			$sql .= " AND a.runCode LIKE '%".$data['runCode']."%'";
			$sql_page .= " AND a.runCode LIKE '%".$data['runCode']."%'";
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
		$rechargeCount = $this->db->query($sql)->row_array();

		//分页
		$sql_page .= " ORDER BY a.createTime DESC LIMIT ".$data['nowPage'].",".$data['pageNum']."";
		$adInfo = $this->db->query($sql_page)->result_array();
		$result_array = array(
            "data"=>$adInfo,
            "iTotalRecords" => $rechargeCount['rechargeCount'],
            "iTotalDisplayRecords" => $rechargeCount['rechargeCount']
        );
        return json_encode($result_array);
	}

	/**
	*@Desc：处理充值申请
	*@param：rechargeStatus、reason、runCode、userID
	*/
	public function handleRechargeApply($data){
		$info = $this->db->select('money,remitIdentCode')->where('runCode',$data['runCode'])->get('rechargeRecordInfo')->row_array();
		//修改充值记录表
		$recordStatus = $this->db->where('runCode',$data['runCode'])->set(array('rechargeStatus'=>$data['rechargeStatus'],'updateTime'=>time(),'remark'=>$data['reason']))->update('rechargeRecordInfo');

		//审核通过才增加余额及资金变化
		if($data['rechargeStatus'] == '2'){
			//修改用户余额
			$balance = $this->db->where('userID',$data['userID'])->get('userDetailInfo')->row_array();
			$money = $balance['balance'] + $info['money'];

			//将用户的资金添加进redis
			$private_redis = new Redis();
			$redis = $this->config->item('redis');
			$private_redis->connect($redis['host'],$redis['port']);
			$status = $private_redis->INCRBYFLOAT($data['userID'].':adtalk',$info['money']);
			if($status){
				$userStatus = $this->db->where('userID',$data['userID'])->set(array('updateTime'=>time(),'balance'=>$money))->update('userDetailInfo');
				//修改用户资金变化表
				$changed = $this->db->where(array('userID'=>$data['userID'],'moneyStatus'=>'2'))->order_by('updateTime','desc')->get('userAmountchangedInfo')->row_array();
				if(empty($changed)){
					$changed['amountAfterChanged'] = '';
				}
				$update_changed = array(
					'userID'              => $data['userID'],
					'amountBeforeChanged' => $changed['amountAfterChanged'],
					'changedAmount'       => $info['money'],
					'amountAfterChanged'  => $changed['amountAfterChanged'] + $info['money'],
					'remitIdentCode'      => $info['remitIdentCode'],
					'runCode'             => $data['runCode'],
					'createTime'          => time(),
					'updateTime'          => time(),
					'remark'              => '充值',
					'moneyStatus'         => '2'//$data['rechargeStatus']
				);
				$changedStatus = $this->db->insert('userAmountchangedInfo',$update_changed);
				return ($recordStatus && $userStatus && $changedStatus)?$res = $this->pubfunction->pub_json('0','ok'):$this->pubfunction->pub_json('2003','处理充值失败');
			}else{
				return $res = $this->pubfunction->pub_json('2005','存入缓存失败');
			}
		}
		return $recordStatus?$this->pubfunction->pub_json('0','ok'):$this->pubfunction->pub_json('2003','处理申请充值失败');
	}

	/**
	*@Desc：将查询结果导出到excel
	*/
	public function exportExcel($data){
		$data['startTime'] = strtotime($data['startTime']);
		$data['endTime'] = strtotime($data['endTime']);
		$sql = "SELECT a.*,b.companyName FROM rechargeRecordInfo AS a JOIN userDetailInfo AS b ON a.userID = b.userID WHERE 1 ";		
		if($data['companyName'] != ''){
			$sql .= " AND b.companyName LIKE '%".$data['companyName']."%'";
		}
		if($data['remitIdentCode'] != ''){
			$sql .= " AND a.remitIdentCode LIKE '%".$data['remitIdentCode']."%'";
		}
		if($data['rechargeStatus'] != ''){
			$sql .= " AND a.rechargeStatus = '".$data['rechargeStatus']."'";
		}
		if($data['runCode'] != ''){
			$sql .= " AND a.runCode LIKE '%".$data['runCode']."%'";
		}
		if($data['startTime'] != '' && $data['endTime'] != ''){
			$sql .= " AND a.createTime BETWEEN '".$data['startTime']."' AND '".$data['endTime']."'";
		}
		if($data['startTime'] != '' && $data['endTime'] == ''){
			$sql .= " AND a.createTime > '".$data['startTime']."'";
		}
		if($data['endTime'] != '' && $data['startTime'] == ''){
			$sql .= " AND a.createTime < '".$data['endTime']."'";
		}
		$info = $this->db->query($sql)->result_array();
		$i = 1;
		foreach ($info as $k => $v) {
			$arr[$k]['index']          = $i++;
			$arr[$k]['runCode']        = $v['runCode'];
			$arr[$k]['remitIdentCode'] = $v['remitIdentCode'];
			$arr[$k]['money']          = $v['money'];
			$arr[$k]['rechargeStatus'] = $v['rechargeStatus'];
			$arr[$k]['createTime']     = $v['createTime'];
			$arr[$k]['updateTime']     = $v['updateTime'];
			$arr[$k]['companyName']    = $v['companyName'];
		}
		return $arr;
	}
}
?>