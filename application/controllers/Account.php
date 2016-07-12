<?php
/**
 *@versions：CI3.0.2
 *Desc：前台账户信息
 *createTime：2015/12/14
 *Auth：Zhouting
*/
defined('BASEPATH') OR exit('No direct script access allowed');
class Account extends CI_Controller{			
	public function __construct(){
  		parent::__construct();
  		$this->load->model('Account_model');
  		$this->load->model('Common_model');
  		$this->load->helper(array('url','cookie'));
  		$this->load->library(array('curl','session','pubfunction'));
  	  	$data = $this->session->all_userdata();
  		if(empty($data['email'])){
  			redirect('Register/login');
  		}
  	}

  	/**
	*@Desc：加载企业资料界面
  	*/
	public function enterpriseInfo(){
		$data = $this->Common_model->getUserSessionInfo();
		$this->load->view('companydata',$data);
	}

	/**
	*@Desc：获得企业资料
	*@param：userID
	*/
	public function getEnterpriseInfo(){
		$post_data = $this->input->post(NULL, TRUE);
		if(empty($post_data['userID'])){
			echo $this->pubfunction->pub_json('1000','userID 为空');
			return ;
		}
		$res = $this->Account_model->gainEnterpriseInfo($post_data);
		echo $this->pubfunction->pub_json('0',$res);
		// echo $result = empty($res)?$this->pubfunction->pub_json('1007','该企业未完善信息'):$this->pubfunction->pub_json('0',$res);
	}

	/**
	*@Desc：加载修改密码界面
	*/
	public function changePassword(){
		$data = $this->Common_model->getUserSessionInfo();
		$this->load->view('ChangePassword',$data);
	}

	/**
	*@Desc：修改密码操作
	*@param：userID、password(新密码)、oldPassword
	*/
	public function editPwd(){
		$post_data = $this->input->post(NULL, TRUE);
		if(empty($post_data)){
			echo $res = $this->pubfunction->pub_json('1000','参数为空');
			return ;
		}
		echo $res = $this->Account_model->editUserPwd($post_data);
	}
  }
?>