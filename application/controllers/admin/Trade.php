<?php
/**
 *@versions：CI3.0.0
 *Desc：后台交易记录
 *createTime：2016/01/05
 *Auth：Zhouting
*/
defined('BASEPATH') OR exit('No direct script access allowed');
class Trade extends CI_Controller{
	public function __construct(){
		parent::__construct();
		$this->load->model('admin/Trademanage_model');
		$this->load->model('Common_model');
		$this->load->helper(array('url','cookie'));
  		$this->load->library(array('curl','pubfunction','session'));
  		$data = $this->session->userdata('name');
		if(empty($data)){
  			redirect('admin/Login/login');
  		}
	}

	/**
	*@Desc：加载交易记录界面
	*/
	public function trade(){
		// $this->load->view('');
	}

	/**
	*@Desc：获得所有的交易记录数据(包含分页)
	*@param：iDisplayStart、iDisplayLength、companyName、adShape、advertiseTitle、startTime、endTime、adID、city、
	*/
	public function getAllTradeInfo(){
		$setData = json_decode($this->input->get('setData',TRUE),TRUE);
		if(empty($setData)){
			echo $res = $this->pubfunction->pub_json('1000','参数为空');
			return ;
		}
		foreach($setData as $k=>$v){
            if($v['name'] == "iDisplayStart"){     //开始页码
                $request_data['nowPage'] = $v['value'];
            }
            if($v['name'] == "iDisplayLength"){    //每页显示条数
                $request_data['pageNum'] = $v['value'];
            }
            if($v['name'] == "companyName"){
            	$request_data['companyName'] = $v['value'];	
            }
            if($v['name'] == "adShape"){
            	$request_data['adShape'] = $v['value'];
            }
            if($v['name'] == "adID"){
            	$request_data['adID'] = $v['value'];
            }
            if($v['name'] == "advertiseTitle"){
            	$request_data['advertiseTitle'] = $v['value'];
            }
            if($v['name'] == "advertiseTitle"){
            	$request_data['advertiseTitle'] = $v['value'];
            }
            if($v['name'] == "startTime"){
            	$request_data['startTime'] = $v['value'];	
            }
            if($v['name'] == "endTime"){
            	$request_data['endTime'] = $v['value'];	
            }
            if($v['name'] == "endTime"){
            	$request_data['endTime'] = $v['value'];	
            }
            if($v['name'] == "city"){
            	$request_data['city'] = $v['value'];	
            }
        }
	    echo $res = $this->Trademanage_model->getAllTransactionInfo($request_data);
	}
}
?>