<?php
/**
 *@versions：CI3.0.0
 *Desc：后台登录管理
 *createTime：2016/01/11
 *Auth：Zhouting
*/
defined('BASEPATH') OR exit('No direct script access allowed');
class Login extends CI_Controller{
	public function __construct(){
		parent::__construct();
		$this->load->model('admin/Loginmanage_model');
		$this->load->helper(array('url','cookie'));
		$this->load->library(array('pubfunction','session'));
	}

	/**
	*@Desc：加载后台登录界面
	*/
	public function login(){
		$this->load->view('admin/adminLogin');
	}

	/**
	*@Desc：判断登录
	*@param：name、password
	*/
	public function doLogin(){
		$post_data = $this->input->post(NULL,TRUE);
		if(empty($post_data) || empty($post_data['name'])){
			echo $res = $this->pubfunction->pub_json('1000','参数为空');
			return ;
		}
		$res = $this->Loginmanage_model->judgeLogin($post_data);
		if($res == '0'){
			redirect('admin');
		}else{
			redirect('admin/Login/login');
		}
	}

	/**
	*@Desc：用户退出
	*/
	public function loginOut(){
		$this->session->sess_destroy();
		redirect('admin/Login/login');
	}
}
?>