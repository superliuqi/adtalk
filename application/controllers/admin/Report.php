<?php
/**
 *@versions：CI3.0.0
 *Desc：后台报表管理记录(已经播放的广告)
 *createTime：2016/01/05
 *Auth：Zhouting
*/
defined('BASEPATH') OR exit('No direct script access allowed');
class Report extends CI_Controller{
	public function __construct(){
		parent::__construct();
		$this->load->model('admin/Reportmanage_model');
		$this->load->model('Common_model');
		$this->load->helper(array('url','cookie'));
  		$this->load->library(array('curl','pubfunction','session'));
  		$data = $this->session->userdata('name');
		if(empty($data)){
  			redirect('admin/Login/login');
  		}
	}

	/**
	*@Desc：加载广告统计界面
	*/
	public function adCtatistics(){
		$data['name'] = $this->session->userdata('name');
		$this->load->view('admin/adCtatistics',$data);
	}

	/**
	*@Desc：加载企业统计界面
	*/
	public function companyStatistics(){
		$data['name'] = $this->session->userdata('name');
		$this->load->view('admin/companyStatistics',$data);
	}

	/**
	*@Desc：获得所有的广告统计数据包含查询
	*@Detail：广告统计
	*@param：iDisplayStart、iDisplayLength、startTime、endTime、city、advertiseTitle、adShape、
	*/
	public function getAllAdStatistics(){
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
			if($v['name'] == "sSortDir_0"){
				$request_data['sort'] = $v['value'];  //排序规则
			}
            if($v['name'] == "startTime"){
            	$request_data['createTimeStart'] = $v['value'];
            }
            if($v['name'] == "endTime"){
            	$request_data['createTimeEnd'] = $v['value'];
            }
            if($v['name'] == "city"){
            	$request_data['city'] = $v['value'];
            }
            if($v['name'] == "advertiseTitle"){
            	$request_data['advertiseTitle'] = $v['value'];
            }
            if($v['name'] == "adShape"){
            	$request_data['adShape'] = $v['value'];
            }
            if($v['name'] == "startTime"){
            	$request_data['startTime'] = $v['value'];
            }
            if($v['name'] == "endTime"){
            	$request_data['endTime'] = $v['value'];
            }
        }
	    echo $res = $this->Reportmanage_model->getAllAdStatisticsInfo($request_data);
	}

	/**
	*@Desc：获得所有的企业统计数据
	*@Detail：企业统计
	*@param：nowPage、pageNum、cityCode、companyName、
	*/
	public function getCompanyStatistics(){
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
            if($v['name'] == "cityCode"){
            	$request_data['cityCode'] = $v['value'];
            }
            if($v['name'] == "companyName"){
            	$request_data['companyName'] = $v['value'];
            }
        }
	    echo $res = $this->Reportmanage_model->getCompanyStatisticsInfo($request_data);
	}
}
?>