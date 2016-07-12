<?php
/**
 *@versions：CI3.0.0
 *Desc：后台广告管理
 *createTime：2015/12/30
 *Auth：Zhouting
*/
defined('BASEPATH') OR exit('No direct script access allowed');
class Advertise extends CI_Controller{
	public function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->model('admin/Advertisemanage_model');
		$this->load->helper(array('url','cookie'));
		$this->load->library(array('curl','pubfunction','session'));
	}

	/**
	*@Desc：加载广告管理界面
	*/
	public function advertising(){
		$data['name'] = $this->session->userdata('name');
		if(empty($data)){
  			redirect('admin/Login/login');
  		}
		$this->load->view('admin/advert',$data);
	}

	/**
	*@Desc：加载意外险广告管理界面
	* create by liuqi
	* 2016-04-13
	*/
	public function accid(){
		$data['name'] = $this->session->userdata('name');
		if(empty($data)){
			redirect('admin/Login/login');
		}
		$this->load->view('admin/accid',$data);
	}

	/**
	*@Desc：获得全部的广告数据
	*@Detail：界面用了Datatables
	*/
	public function getAllAdInfo(){
		$setData = json_decode($this->input->get('setData',TRUE),TRUE);
		$allow_array = array('iDisplayStart','iDisplayLength','adStatus','startTime','endTime','adShape','companyName');
		// foreach($setData as $k=>$v){
  //           if($v['name'] == "iDisplayStart"){     //开始页码
  //               $request_data['nowPage'] = $v['value'];
  //           }
  //           if($v['name'] == "iDisplayLength"){    //每页显示条数
  //               $request_data['pageNum'] = $v['value'];
  //           }
  //           if($v['name'] == "adStatus"){
  //           	$request_data['adStatus'] = $v['value'];
  //           }
  //           if($v['name'] == "adShape"){
  //           	$request_data['adShape'] = $v['value'];
  //           }
  //           if($v['name'] == "startTime"){
  //           	$request_data['createTimeStart'] = $v['value'];
  //           }
  //           if($v['name'] == "endTime"){
  //           	$request_data['createTimeEnd'] = $v['value'];
  //           }
  //           if($v['name'] == "companyName"){
  //           	$request_data['companyName'] = $v['value'];
  //           }
  //       }
		foreach($setData as $k=>$v){
			if(in_array($v['name'],$allow_array)){
				$request_data[$v['name']] = $v['value'];
			}
        }
	    echo $res = $this->Advertisemanage_model->queryAdInfo($request_data);
	}

	/**
	*@Desc：获得全部的意外险广告数据
	*create by liqui
	*2016-04-13
	*/
	public function getAllSponsorInfo(){
		$setData = json_decode($this->input->get('setData',TRUE),TRUE);
		$allow_array = array('iDisplayStart','iDisplayLength','adStatus','startTime','endTime','sponsor');
		foreach($setData as $k=>$v){
			if(in_array($v['name'],$allow_array)){
				$request_data[$v['name']] = $v['value'];
			}
        }
	    echo $res = $this->Advertisemanage_model->querySponsorInfo($request_data);
	}

	/**
	*@Desc：加载广告详情界面
	*/
	public function adDetail(){
		$get_data = $this->input->get(NULL,TRUE);
		$data['name'] = $this->session->userdata('name');
		$res = array_merge($get_data,$data);
		$this->load->view('admin/adDetail',$res);
	}

	/**
	*@Desc：加载意外险广告详情界面
	*/
	public function accidDetail(){
		$get_data = $this->input->get(NULL,TRUE);
		$data['name'] = $this->session->userdata('name');
		$res = array_merge($get_data,$data);
		$this->load->view('admin/accidDetail',$res);
	}

	/**
	*@Desc：查看广告详情
	*@param：adID
	*/
	public function getAdDetails(){
		$post_data = $this->input->post(NULL,TRUE);
		if(empty($post_data['adID'])){
			echo $this->pubfunction->pub_json('1000','参数为空');
			return ;
		}
		echo $res = $this->Advertisemanage_model->gainAdDetailInfo($post_data);
	}

		/**
	*@Desc：查看意外险广告详情
	*@param：adID
	*/
	public function getAccidDetail(){
		$post_data = $this->input->post(NULL,TRUE);
		if(empty($post_data['adID'])){
			echo $this->pubfunction->pub_json('1000','参数为空');
			return ;
		}
		echo $res = $this->Advertisemanage_model->getAccidDetailInfo($post_data);
	}

	/**
	*@Desc：根据城市编号获得对应城市名
	*@param：cityCode
	*/
	public function getCityName(){
		$post_data = $this->input->post(NULL,TRUE);
		if(empty($post_data['cityCode'])){
			echo $res = $this->pubfunction->pub_json('1000','参数为空');
			return ;
		}
		echo $res = $this->Advertisemanage_model->gainCityName($post_data);
	}


	/**
	*@Desc：处理广告申请
	*@param：adStatus(1：投放中，2：待调整(失败)，3：暂停投放)、reason、adID
	*/
	public function handleAdApply(){
		$post_data = $this->input->post(NULL,TRUE);
		if($post_data['adStatus'] == '' || empty($post_data['adID'])){
			echo $res = $this->pubfunction->pub_json('1000','参数为空');
			return ;
		}
		echo $res = $this->Advertisemanage_model->dealwithAdApply($post_data);
	}

	/**
	*@Desc：处理意外险广告申请
	*@param：adStatus(1：投放中，2：待调整(失败)，3：暂停投放)、reason、adID
	*/
	public function handleAccidApply(){
		$post_data = $this->input->post(NULL,TRUE);
		//var_dump($post_data);exit;
		if($post_data['adStatus'] == '' || empty($post_data['adID'])){
			echo $res = $this->pubfunction->pub_json('1000','参数为空');
			return ;
		}
		echo $res = $this->Advertisemanage_model->dealwithAccidApply($post_data);
	}

	public function myCurl(){
		$ch = curl_init();
        $post_data = array('aid'=>41,'cid'=>'236b7db2','appKey'=>1276016637,'time'=>1465866194,'citycode'=>360800);
        curl_setopt($ch, CURLOPT_URL, 'http://192.168.1.3:9527/adtalk/index.php/admin/advertise/adCallBack');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        $output = curl_exec($ch);
        $arr=json_decode($output,TRUE);
        curl_close($ch);
        return $arr;
    }

    public function myCurl2(){
		$ch = curl_init();
        $post_data = array('aid'=>31,'cid'=>'236b7db2','appKey'=>1276016637,'time'=>1465866194,'citycode'=>360800);
        curl_setopt($ch, CURLOPT_URL, 'http://192.168.130.37/adtest.php');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        $output = curl_exec($ch);
        $arr=json_decode($output,TRUE);
        curl_close($ch);
        return $arr;
    }


	/**
	*@Desc：播放广告时的回调
	*@param
	*/
	public function adCallBack(){
		//post传来json数据
		// $json_data = file_get_contents('php://input');
		// $json_data = '{"aid":"258","cid":"FkLUuYMLkn","report":{"vol":"15","dir":"46","speed":"3","status":"2","token":"966d1a7a2d01790d","fileurl":"","lng":"121.43944122","filetype":"","lat":"31.22505642"},"sign":"44951F372DEF342E01441CC5FBDF712B00DEE68C","time":"1453954814418","appKey":"717847885"}';
		// $json_data = '{"aid":"164","cid":"395071533772970","citycode":"nil","status":"2","time":"1453957963","appKey":"1314697670"}';
		// file_put_contents("adCallBack.txt",$json_data."\r\n",FILE_APPEND);
		// $json_data = json_encode($_POST);
		// $post_data = json_decode($json_data,true);
		$post_data = $_POST;
		if(empty($post_data['cid'])){
			echo $res = $this->pubfunction->pub_json('1000','参数为空');
			return ;
		}
		$lat = empty($post_data['report']['lat'])?'':$post_data['report']['lat'];
		$lng = empty($post_data['report']['lng'])?'':$post_data['report']['lng'];
		$speed = empty($post_data['report']['speed'])?'':$post_data['report']['speed'];
		$dir = empty($post_data['report']['dir'])?'':$post_data['report']['dir'];
 		if($lat != '' && $lng != ''){
 			$cityArr = $this->getRedisCityName($lng,$lat);
 		}else{
 			$cityArr['citycode'] = $post_data['citycode'] == 'nil'?'':$post_data['citycode'];
 			//获得广告对应区域的价格及城市名
			$citySql = "SELECT `provinceName`,`cityName` FROM `chinaCityPrice` WHERE `cityCode` = '".$cityArr['citycode']."' OR `provinceCode` = '".$cityArr['citycode']."' GROUP BY `cityName`";
			$cityArr['cityName'] = $this->db->query($citySql)->row_array();
 		}
		$data = array(
			'aid'      => $post_data['aid'],
			'cid'      => $post_data['cid'],
			'citycode' => $cityArr['citycode'],
			'cityName' => $cityArr['cityName'],
			'time'     => $post_data['time'],
			'appKey'   => $post_data['appKey'],
			'longitude'=> $lng,
			'latitude' => $lat,
			'speed'    => $speed,
			'directionAngle' => $dir
		);
		echo $res = $this->Advertisemanage_model->adCallBackHandle($data);	
	}

	/**
	*@Desc：播放意外险广告时的回调
	*@param
	*/
	public function accidCallBack(){
		//post传来json数据
		$json_data = file_get_contents('php://input');
		// $json_data = '{"aid":"258","cid":"FkLUuYMLkn","report":{"vol":"15","dir":"46","speed":"3","status":"2","token":"966d1a7a2d01790d","fileurl":"","lng":"121.43944122","filetype":"","lat":"31.22505642"},"sign":"44951F372DEF342E01441CC5FBDF712B00DEE68C","time":"1453954814418","appKey":"717847885"}';
		// $json_data = '{"aid":"164","cid":"395071533772970","citycode":"nil","status":"2","time":"1453957963","appKey":"1314697670"}';
		file_put_contents("adCallBack.txt",$json_data."\r\n",FILE_APPEND);
		// $json_data = json_encode($_POST);
		$post_data = json_decode($json_data,true);
		// var_dump($json_data);exit;
		if(empty($post_data['cid'])){
			echo $res = $this->pubfunction->pub_json('1000','参数为空');
			return ;
		}
		$lat = empty($post_data['report']['lat'])?'':$post_data['report']['lat'];
		$lng = empty($post_data['report']['lng'])?'':$post_data['report']['lng'];
		$speed = empty($post_data['report']['speed'])?'':$post_data['report']['speed'];
		$dir = empty($post_data['report']['dir'])?'':$post_data['report']['dir'];
 		if($lat != '' && $lng != ''){
 			$cityArr = $this->getRedisCityName($lng,$lat);
 		}else{
 			$cityArr['citycode'] = $post_data['citycode'] == 'nil'?'':$post_data['citycode'];
 			//获得广告对应区域的价格及城市名
			$citySql = "SELECT `provinceName`,`cityName` FROM `chinaCityPrice` WHERE `cityCode` = '".$cityArr['citycode']."' OR `provinceCode` = '".$cityArr['citycode']."' GROUP BY `cityName`";
			$cityArr['cityName'] = $this->db->query($citySql)->row_array();
 		}
		$data = array(
			'aid'      => $post_data['aid'],
			'cid'      => $post_data['cid'],
			'citycode' => $cityArr['citycode'],
			'cityName' => $cityArr['cityName'],
			'time'     => $post_data['time'],
			'appKey'   => $post_data['appKey'],
			'longitude'=> $lng,
			'latitude' => $lat,
			'speed'    => $speed,
			'directionAngle' => $dir
		);
		echo $res = $this->Advertisemanage_model->accidCallBackHandle($data);
	}

	/**
	*@Desc：根据经纬度获取城市名
	*@param：latitude纬度、longitude经度
	*@return：array
	*/
	public function getRedisCityName($longitude,$latitude){
		// $longitude = '115.9790972505124';
		// $latitude = '36.463037965453495';
		$grid_redis = new Redis();
		$config = $this->config->item('grid_redis');
		$grid_redis->connect($config['host'],$config['port']);
		if($latitude!=0&&$longitude!=0){
			//只取小数点后两位   并强制转化为整型
			$grid = (int)($longitude*100).'&'.(int)($latitude*100);
			$cityInfo = $grid_redis -> hgetall($grid);
			$cityArr = array("cityName"=>$cityInfo['cityName'],"citycode"=>$cityInfo['cityCode']);
		}else{
			$cityArr = array("cityName"=>'',"citycode"=>'');
		}
		$grid_redis -> close();
        return $cityArr;
	}
}
?>