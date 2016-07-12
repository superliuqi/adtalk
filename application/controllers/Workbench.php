<?php
/**
 *@versions：CI3.0.2
 *Desc：我的广告
 *createTime：2015/12/11
 *Auth：Zhouting
*/
defined('BASEPATH') OR exit('No direct script access allowed');
class Workbench extends CI_Controller{
	public function __construct(){
  		parent::__construct();
  		$this->load->model('Workbench_model');
      $this->load->model('Common_model');
  		$this->load->helper(array('url','cookie'));
  		$this->load->library(array('curl','session','pubfunction'));
  		$data = $this->session->all_userdata();
  		if(empty($data['email'])){
  			redirect('Register/login');
  		}
  	}

  /**
  *@Desc：加载工作台界面
  */
  public function workbench(){
    $data = $this->Common_model->getUserSessionInfo();
    $this->load->view('workbench',$data);
  }

  /**
  *@Desc：加载我的工作台数据
  */
  public function companyData(){
    $userID=$this->session->userdata('userID');
    //获得用户信息
    $userInfo = $this->Common_model->getUserInfo($userID);
    if(!is_array($userInfo)){
      echo $res = $this->pubfunction->pub_json('1005','此用户不存在');
      return ;
    }
    //获得用户广告信息
    $adInfo = $this->Workbench_model->getUserAdCount($userID);
    //获得用户资金信息
    $fundInfo = $this->Workbench_model->getUserFundCount($userID);

    $res = array_merge($userInfo,$adInfo,$fundInfo);
    echo $result = $this->pubfunction->pub_json('0',$res);
  }
}
?>