<?php
/**
* @Desc：后台登录管理
*/
class Loginmanage_model extends CI_Model{
	public function __construct(){
		$this->load->library('pubfunction');
	}

	/**
	*@Desc：判断登录
	*/
	public function judgeLogin($data){
		if($data['name']=='adtalk'&&$data['password']=="30d3567dc8ba2d32"){
			$this->session->set_userdata('name',$data['name']);//写入缓存
			return '0';
		}else{
			return '1006';
		}
	}
}
?>