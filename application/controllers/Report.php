<?php
/**
 *@versions：CI3.0.2
 *Desc：报表管理
 *createTime：2015/12/08
 *Auth：Zhouting
*/
defined('BASEPATH') OR exit('No direct script access allowed');
class Report extends CI_Controller{			
	public function __construct(){
  		parent::__construct();
  		// $this->load->model('Report_model');
  		$this->load->helper(array('url','cookie'));
  		$this->load->library(array('curl','session','pubfunction'));
      $data = $this->session->all_userdata();
      if(empty($data['email'])){
        redirect('Register/login');
      }
  	}

  	/**
	*@Desc：加载报表管理界面
  	*/
  	public function reportManage(){
  		$this->load->view('reportManage');
  	}
  }
?>