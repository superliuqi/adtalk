<?php
class Common_model extends CI_Model{
	public function __construct(){
		$this->load->database();
		$this->load->library(array('pubfunction'));
	}

	/**
	*@Desc：完善信息
	*/
	public function addUserDetailInfo($data){
		unset($data['province']);
		$data['cityCode'] = $data['city'];
		unset($data['city']);
		$data['checkStatus'] = '1';//待审核
		$arr=$this->db->select('id')->where('userID',$data['userID'])->get('userDetailInfo')->row_array();//查询当前用户是否存在
		if (!empty($arr)) {
			//有记录,更新信息
			$data['updateTime']=time();
			return $status = $this->db->where('userID',$data['userID'])->update('userDetailInfo',$data);
		}else{
			//无记录,执行添加
			$data['createTime']=time();
			return $status = $this->db->insert('userDetailInfo',$data);
		}
	}

	/**
	*@Desc：获取用户的信息
	*/
	public function getUserSessionInfo(){
		$data = $this->session->all_userdata();
		if(empty($data['userID'])){
			return '1005';
		}else{
			return $data;
		}
	}

	/**
	*@Desc：获取用户邮箱
	*@param：userID
	*/
	public function getUserInfo($userID){
		return $email = $this->db->select('email,logintime')->where('userID',$userID)->get('userRegisterInfo')->row_array();
	}

	/**
	*@Desc：获取页码设置
	*@param：界面传来的所有页码
	*/
	public function getPage($data){
		$data['nowPage'] = empty($data['nowPage'])?1:$data['nowPage'];  //当前页码
		$data['pageNum'] = empty($data['pageNum'])?20:$data['pageNum'];   //每页显示条数
		$data['startNumber'] = ($data['nowPage']-1)*$data['pageNum'];
		return $data;
	}
}
?>