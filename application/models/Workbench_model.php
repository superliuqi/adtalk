<?php
class Workbench_model extends CI_Model{
	public function __construct(){
		$this->load->database();
		$this->load->helper(array('cookie'));
		$this->load->library(array('pubfunction','session'));
	}

	/**
	*@Desc：得到当前用户的广告数据
	*@param：userID
	*/
	public function getUserAdCount($userID){
		//获得总广告数
		$count = $this->db->select('id')->where('userID',$userID)->get('adtalkInfo')->num_rows();
		$count = empty($count)?array('adCount'=>'0'):array('adCount'=>$count);
		
		//获得待审核广告
		$checkAd = $this->db->select('id')->where(array('userID'=>$userID,'adStatus'=>'0'))->get('adtalkInfo')->num_rows();
		$checkAd = empty($checkAd)?array('adCheck'=>'0'):array('adCheck'=>$checkAd);

		//获得待调整广告
		$adModulation = $this->db->select('id')->where(array('userID'=>$userID,'adStatus'=>'2'))->get('adtalkInfo')->num_rows();
		$adModulation = empty($adModulation)?array('adModulation'=>'0'):array('adModulation'=>$adModulation);

		//获得此用户的广告播放总条数
		$adPlayCount = $this->db->select('adID')->where('userID',$userID)->get('adPlayInfo')->num_rows();
		$adPlayCount = empty($adPlayCount)?array('adPlayCount'=>'0'):array('adPlayCount'=>$adPlayCount);		

		return $info = array_merge($count,$checkAd,$adModulation,$adPlayCount);
	}

	/**
	*@Desc：获得当前用户的账户数据
	*@param：userID
	*/
	public function getUserFundCount($userID){
		//获得用户余额
		$resAmount = $this->db->where('userID',$userID)->select('balance,checkStatus')->get('userDetailInfo')->row_array();
		$resAmount = empty($resAmount['balance'])?array('balance'=>'0'):$resAmount;		

		//获得待线下打款数据
		$linePlayMoney = $this->db->where(array('userID'=>$userID,'rechargeStatus'=>'0'))->select('id')->get('rechargeRecordInfo')->num_rows();
		$linePlayMoney = empty($linePlayMoney)?array('linePlayMoney'=>'0'):array('linePlayMoney'=>$linePlayMoney);

		//获得待审核数据
		$checkMoney = $this->db->where(array('userID'=>$userID,'rechargeStatus'=>'1'))->select('id')->get('rechargeRecordInfo')->num_rows();
		$checkMoney = empty($checkMoney)?array('checkMoney'=>'0'):array('checkMoney'=>$checkMoney);
		
		//获得充值失败数据
		$payFail = $this->db->where(array('userID'=>$userID,'rechargeStatus'=>'3'))->select('id')->get('rechargeRecordInfo')->num_rows();
		$payFail = empty($payFail)?array('payFail'=>'0'):array('payFail'=>$payFail);
		return $fundInfo = array_merge($resAmount,$linePlayMoney,$checkMoney,$payFail);
	}
}
?>