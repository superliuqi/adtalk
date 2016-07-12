<?php
/**
 *@versions：CI3.0.0
 *Desc：后台工作台
 *createTime：2015/12/31
 *Auth：Zhouting
*/
class Bench extends CI_Controller{
	public function __construct(){
  		parent::__construct();
  		$this->load->model('admin/Benchmanage_model');
  		$this->load->helper(array('url','cookie'));
  		$this->load->library(array('curl','pubfunction','session'));
  		$data = $this->session->userdata('name');
		if(empty($data)){
  			redirect('admin/Login/login');
  		}
	}

	/**
	*@Desc：加载工作台界面
	*/
	public function bench(){
		$data['name'] = $this->session->userdata('name');
		$this->load->view('admin/index',$data);
	}

	/**
	 *@Desc： 获得所有的工作台数据
	 *@param：type  1：全部、2 今日、3：昨天、4：最近7日、5:最近30日
	*/
	public function getAllBenchInfo(){
		$post_data = $this->input->post(NULL, TRUE);
		if(empty($post_data['type'])){
			echo $this->pubfunction->pub_json('1000','参数错误');
			return ;
		}
		echo $data = $this->Benchmanage_model->getAllInfo($post_data['type']);
	}

	/**
	 *@Desc：获得城市排名
	*/
	public function getCityRanking(){
		echo $data = $this->Benchmanage_model->getAllCityRanking();
	}
}
?>