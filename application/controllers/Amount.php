<?php
/**
 *@versions：CI3.0.2
 *Desc：资金管理
 *createTime：2015/12/11
 *Auth：Zhouting
*/
defined('BASEPATH') OR exit('No direct script access allowed');
class Amount extends CI_Controller{
	public function __construct(){
  	parent::__construct();
  	$this->load->model('Amount_model');
    $this->load->model('Common_model');
  	$this->load->helper(array('url','cookie'));
  	$this->load->library(array('curl','session','pubfunction'));
  	$data = $this->session->all_userdata();
  	if(empty($data['email'])){
      redirect('Register/login');
  	}
  }

  /**
  *@Desc：加载立即充值界面
  */
  public function immediatelyPay(){
    $data = $this->Common_model->getUserSessionInfo();
    $this->load->view('recharge',$data);
  }

  /**
  *@Desc：获取用户资金余额
  *@param：userID
  */
  public function userBalance(){
    $post_data = $this->input->post(NULL, TRUE);
    if(empty($post_data['userID'])){
      echo $res = $this->pubfunction->pub_json('1000','userID为空');
      return ;
    }
    echo $res = $this->Amount_model->getUserBalance($post_data);
  }

  /**
  *@Desc：确认并获得汇款识别码
  *@param：userID、money
  */
  public function getRemitUDID(){
    $post_data = $this->input->post(NULL,TRUE);
    if(empty($post_data['userID'])){
      echo $res = $this->pubfunction->pub_json('1000','参数为空');
      return ;
    }
    if(!is_numeric($post_data['money'])){
      echo $res = $this->pubfunction->pub_json('1000','金额格式不正确');
      return ;
    }
    echo $res = $this->Amount_model->insertWaterBill($post_data);
  }

  /**
  *@Desc：加载充值记录界面
  */
  public function payRecord(){
    $get_data = $this->input->get(NULL,TRUE);
    $get_data = $get_data?array('rechargeStatus'=>$get_data['rechargeStatus']):array('rechargeStatus'=>'');
    $session = $this->Common_model->getUserSessionInfo();
    $data = array_merge($get_data,$session);
    $this->load->view('rechargeRecord',$data);
  }

  /**
  *@Desc：获得全部充值记录
  *@param：userID、remitIdentCode、runCode、startTime、endTime、pageNum、nowPage、rechargeStatus
  */
  public function getAllRechargeInfo(){
    $setData = json_decode($this->input->get('setData',TRUE),TRUE);
    foreach ($setData as $k => $v) {
      if($v['name'] == "iDisplayStart"){
        $request_data['nowPage'] = $v['value'];
      }
      if($v['name'] == "iDisplayLength"){
        $request_data['pageNum'] = $v['value'];
      }
      if($v['name'] == "userID"){
        $request_data['userID'] = $v['value'];
      }
      if($v['name'] == "runCode"){
        $request_data['runCode'] = $v['value'];
      }
      if($v['name'] == "rechargeStatus"){
        $request_data['rechargeStatus'] = $v['value'];
      }
      if($v['name'] == "remitIdentCode"){
        $request_data['remitIdentCode'] = $v['value'];
      }
      if($v['name'] == "startTime"){
        $request_data['startTime'] = $v['value'];
      }
      if($v['name'] == "endTime"){
        $request_data['endTime'] = $v['value'];
      }
    }
    if(empty($request_data['userID'])){
      echo $res = $this->pubfunction->pub_json('1000','参数为空');
      return ;
    }
    echo $res = $this->Amount_model->getAllRecharge($request_data);
  }

  /**
  *@Desc：点击确认已打款
  *@param：userID、remitIdentCode
  */
  public function confirmPay(){
    $post_data = $this->input->post(NULL,TRUE);
    if(empty($post_data['userID'])||empty($post_data['remitIdentCode'])){
      echo $res = $this->pubfunction->pub_json('1000','参数为空');
      return ;
    }
    echo $res = $this->Amount_model->editPayStauus($post_data);
  }

  /**
  *@Desc：加载资金明细界面
  */
  public function flowRecord(){
    $data = $this->Common_model->getUserSessionInfo();
    $this->load->view('flowRecord',$data);
  }

  /**
  *@Desc：获得全部资金明细数据
  *@param：userID、adID、runCode、moneyType(1 收入 2支出)、iDisplayLength、iDisplayStart、
  */
  public function getMoneyChangeInfo(){
    $setData = json_decode($this->input->get('setData',TRUE),TRUE);
    foreach ($setData as $k => $v) {
      if($v['name'] == "iDisplayStart"){
        $request_data['nowPage'] = $v['value'];
      }
      if($v['name'] == "iDisplayLength"){
        $request_data['pageNum'] = $v['value'];
      }
      if($v['name'] == "userID"){
        $request_data['userID'] = $v['value'];
      }
      if($v['name'] == "adID"){
        $request_data['adID'] = $v['value'];
      }
      if($v['name'] == "runCode"){
        $request_data['runCode'] = $v['value'];
      }
      if($v['name'] == "moneyType"){
        $request_data['moneyType'] = $v['value'];
      }
    }
    if(empty($request_data['userID'])){
      echo $res = $this->pubfunction->pub_json('1000','参数为空');
      return ;
    }
    echo $res = $this->Amount_model->selectMoneyDetail($request_data);
  }
}
?>