<?php
/**
 *@versions：CI3.0.0
 *Desc：后台充值管理
 *createTime：2015/12/31
 *Auth：Zhouting
*/
defined('BASEPATH') OR exit('No direct script access allowed');
class Recharge extends CI_Controller{
	public function __construct(){
		parent::__construct();
  		$this->load->model('admin/Rechargemanage_model');
  		$this->load->helper(array('url','cookie'));
  		$this->load->library(array('curl','pubfunction','export_excel','session'));
  		$data = $this->session->userdata('name');
		if(empty($data)){
  			redirect('admin/Login/login');
  		}
	}

	/**
	*@Desc：加载充值管理界面
	*/
	public function recharge(){
		$data['name'] = $this->session->userdata('name');
		$this->load->view('admin/recharge',$data);
	}

	/**
	*@Desc：获得所有充值数据
	*@param：companyName、remitIdentCode、rechargeStatus、runCode、startTime、endTime
	*@Detail：界面用了Datatables
	*/
	public function getAllRecharge(){
		$setData = json_decode($this->input->get('setData',TRUE),TRUE);
		foreach ($setData as $k=>$v) {
			if($v['name'] == "iDisplayStart"){ // 开始页码
				$request_data['nowPage'] = $v['value'];
			}
			if($v['name'] == "iDisplayLength"){  //每页显示条数
				$request_data['pageNum'] = $v['value'];
			}
			if($v['name'] == "companyName"){
				$request_data['companyName'] = $v['value'];
			}
			if($v['name'] == "remitIdentCode"){
				$request_data['remitIdentCode'] = $v['value'];
			}
			if($v['name'] == "rechargeStatus"){
				$request_data['rechargeStatus'] = $v['value'];
			}
			if($v['name'] == "runCode"){
				$request_data['runCode'] = $v['value'];	
			}
			if($v['name'] == "startTime"){
				$request_data['startTime'] = $v['value'];
			}
			if($v['name'] == "endTime"){
				$request_data['endTime'] = $v['value'];
			} 
		}
	    echo $res = $this->Rechargemanage_model->getAllRechargeInfo($request_data);
	}

	/**
	*@Desc：处理充值申请
	*@param：rechargeStatus、reason、runCode、userID
	*/
	public function handleRecharge(){
		$post_data = $this->input->post(NULL,TRUE);
		if(empty($post_data) || empty($post_data['userID'])){
			echo $res = $this->pubfunction->pub_json('1000','参数为空');
			return ;
		}
		echo $res = $this->Rechargemanage_model->handleRechargeApply($post_data);
	}

	/**
	*@Desc：根据查询结果导出excel
	*@param：companyName、remitIdentCode、rechargeStatus、runCode、startTime、endTime
	*/
	public function excel(){
		$post_data = $this->input->post(NULL,TRUE);
		$res = $this->Rechargemanage_model->exportExcel($post_data);
		$fileName = time();
		//参数：查询的条件、生成的Excel的文件名
		$res=$this->export_excel->export($res,$fileName);
	}
}
?>