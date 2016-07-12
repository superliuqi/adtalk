<?php
class Account_model extends CI_Model{
	public function __construct(){
		$this->load->database();
		$this->load->library(array('pubfunction'));
	}

	/**
	*@Desc：获取企业信息
	*@param：userID
	*/
	public function gainEnterpriseInfo($data){
		return $info = $this->db->select()->where('userID',$data['userID'])->get('userDetailInfo')->row_array();
	}

	/**
	*@Desc：修改密码
	*@param：userID、password、oldPassword
	*/
	public function editUserPwd($data){
		$res = $this->db->select('id')->where(array('userID'=>$data['userID'],'password'=>md5($data['oldPassword'])))->get('userRegisterInfo')->row_array();
		if(empty($res)){
			return $this->pubfunction->pub_json('1002','密码错误');
		}
		$result = $this->db->set(array('password'=>md5($data['password']),'updateTime'=>time()))->where('userID',$data['userID'])->update('userRegisterInfo');
		return $result?$this->pubfunction->pub_json('0','ok'):$this->pubfunction->pub_json('1008','修改密码失败');
	}
}
?>