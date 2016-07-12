<?php
/**
 *@versions：CI3.0.0
 *Desc：后台账户管理信息
 *createTime：2015/12/26
 *Auth：Zhouting
*/
defined('BASEPATH') OR exit('No direct script access allowed');
class Account extends CI_Controller{			
	public function __construct(){
  		parent::__construct();
  		$this->load->model('admin/Accountmanage_model');
  		$this->load->helper(array('url','cookie'));
  		$this->load->library(array('curl','pubfunction','session'));
      $data=$this->session->userdata('name');
      if(empty($data)){
        redirect('admin/Login/login');
      }
  	}

  /**
  *@Desc：加载后台账户管理界面
  */
  public function account(){
    $data['name']=$this->session->userdata('name');
    $this->load->view('admin/account',$data);
  }

  /**
  *@Desc：获取全部账户信息
  *@Detail：界面用了Datatables
  *@param：companyName、name、telephone、startTime、endTime、checkStatus、accountStatus、cityCode、iDisplayStart、iDisplayLength、
  */
  public function getAllAccountInfo(){
    $setData = json_decode($this->input->get('setData',TRUE),TRUE);
    foreach ($setData as $k => $v){
      if($v['name'] == "iDisplayStart"){
        $request_data['nowPage'] = $v['value'];
      }
      if($v['name'] == "iDisplayLength"){  //每页显示条数
        $request_data['pageNum'] = $v['value'];
      }
      if($v['name'] == "companyName"){
        $request_data['companyName'] = $v['value'];
      }
      if($v['name'] == "name"){
        $request_data['name'] = $v['value'];
      }
      if($v['name'] == "telephone"){
        $request_data['telephone'] = $v['value'];
      }
      if($v['name'] == "startTime"){
        $request_data['startTime'] = $v['value'];
      }
      if($v['name'] == "endTime"){
        $request_data['endTime'] = $v['value'];
      }
      if($v['name'] == "checkStatus"){
        $request_data['checkStatus'] = $v['value'];
      }
      if($v['name'] == "accountStatus"){
        $request_data['accountStatus'] = $v['value'];
      }
      if($v['name'] == "cityCode"){
        $request_data['cityCode'] = $v['value'];
      }
    }
    echo $res = $this->Accountmanage_model->queryAccountInfo($request_data);
  }

  /**
  *@Desc：点击停用/激活 按钮
  *@param：userID、accountStatus
  */
  public function disableAccount(){
    $post_data = $this->input->post(NULL,TRUE);
    if(empty($post_data) || empty($post_data['userID']) || empty($post_data['accountStatus'])){
      echo $res = $this->pubfunction->pub_json('1000','参数为空');
      return ;
    }
    echo $res = $this->Accountmanage_model->operateAccount($post_data);
  }

  /**
  *@Desc：加载账户详情界面
  */
  public function accountDetail(){
    $get_data = $this->input->get(NULL,TRUE);
    $data['name']=$this->session->userdata('name');
    $res = array_merge($get_data,$data);
    $this->load->view('admin/accountDetail',$res);
  }

  /**
  *@Desc：查看账户详情
  *@param：userID
  */
  public function selectAccountDetails(){
    $post_data = $this->input->post(NULL,TRUE);
    if(empty($post_data['userID'])){
      echo $res = $this->pubfunction->pub_json('1000','参数为空');
      return ;
    }
    echo $res = $this->Accountmanage_model->getAccountInfo($post_data);
  }

  /**
  *@Desc：处理申请
  *@param：userID、checkStatus、reason
  */
  public function handleApply(){
    $post_data = $this->input->post(NULL,TRUE);
    if(empty($post_data['userID']) ||$post_data['checkStatus'] == ''){
      echo $res = $this->pubfunction->pub_json('1000','参数为空');
      return ;
    }
    echo $res = $this->Accountmanage_model->dealwithApply($post_data);
  }
}
?>