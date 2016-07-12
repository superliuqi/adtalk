<?php
/**
 *@versions：CI3.0.2
 *Desc：我的广告
 *createTime：2015/12/11
 *Auth：Zhouting
*/
defined('BASEPATH') OR exit('No direct script access allowed');
class Advertise extends CI_Controller{
	public function __construct(){
  		parent::__construct();
  		$this->load->model('Advertise_model');
  		$this->load->model('Common_model');
  		$this->load->helper(array('url','cookie','form'));
  		$this->load->library(array('curl','session','pubfunction'));
  		$email=$this->session->userdata('email');
  		if(empty($email)){
  			redirect('Register/login');
  		}
  	}

  	/**
	*@Desc：新建开机广告界面
  	*/
  	public function newAdvertise(){
  		$data = $this->Common_model->getUserSessionInfo();
  		$this->load->view('newStarting',$data);
  	}

  	/**
    *@Desc：新建意外险广告界面
    */
    public function newAccid(){
      $data = $this->Common_model->getUserSessionInfo();
      $this->load->view('accidInsurance',$data);
    }

    /**
    *@Desc：新建冠名广告界面
    */
    public function newNaming(){
      $data = $this->Common_model->getUserSessionInfo();
      $this->load->view('newNaming',$data);
    }

    /**
    *@Desc：新建尾标广告界面
    */
    public function newTrailer(){
      $data = $this->Common_model->getUserSessionInfo();
      $this->load->view('newTrailer',$data);
    }
    
    /**
    *@Desc：新建路况看板广告界面
    */
    public function newRoad(){
      $data = $this->Common_model->getUserSessionInfo();
      $this->load->view('newRoad',$data);
    }

    /**
     * 新建意外险广告提交
     * @author liuqi
     * at 2016-04-07
     */
    public function addAccidInsurance(){
  		if(empty($_POST['userID'])){
  			echo $res = $this->pubfunction->pub_json('1000','参数为空');
  			return ;
  		}
  		$insert = array(
  			'userID'		=>	$_POST['userID'],
  			'adID'			=>	hash('crc32', $this->pubfunction->createAdID(time())),
  			'sponsorUrl'	=>	$_POST['adurl'],
  			'sponsorName'	=>	$_POST['sponsor'],
  			'sponsorLogo'	=>	$_POST['sponsorLogo'],
  			'cityCode'		=>	$_POST['cityCode'],
  			'remark'		=>	$_POST['remark'],
  			'createTime'	=>	time(),
  			'updateTime'	=>	time()
  			);
		echo $result = $this->Advertise_model->insertAccid($insert);
    }

    /**
	*@Desc：新建广告提交审核与暂时保存
  	*/
  	public function adSubmitCheck(){
  		if(empty($_POST['userID'])){
  			echo $res = $this->pubfunction->pub_json('1000','参数为空');
  			return ;
  		}
  		if(isset($_POST['audioURL']) && isset($_POST['audioContent']) && $_POST['audioURL'] != ''){
  			//用CI自带接收方式会转义
  			$audioURL = $_POST['audioURL'];
  			list($type, $data) = explode(',', $audioURL);
  			unset($_POST['audioURL']);
  			// 判断类型  并设置图片的后缀名
			if(strstr($type,'audio/mp3')!==''){
			    $ext = '.mp3';
			}else{
				echo $res = $this->pubfunction->pub_json('2001','音频格式不正确');
				return ;
			}
			//生成音频名
			$audio = $this->pubfunction->remittanceCode(8,3).$ext;
			$audioName = substr($audio, 2);
			file_put_contents("./public/voices/".$audioName, base64_decode($data), true);
			$resUrl = $this->config->item('upload_voices');
			$fileInfo = array(
				'audioName' => $audioName,//音频名
				'length'    => $_POST['fileSize']//音频大小
			);
			//MP3转amr
			$url = $this->curlRequest($resUrl,$fileInfo,$resUrl['saveSound'],$resUrl['analog_url']);
			if($url['ERRORCODE'] != '0'){
				print_r($url);
				return ;
			}
			$_POST['mp3Url'] = base_url().'public/voices/'.$audioName;
			$_POST['audioURL'] = $url['RESULT']['url'];
  		}
		unset($_POST['fileSize']);
		$_POST['adID'] = hash('crc32', $this->pubfunction->createAdID(time()));
		$_POST['createTime'] = time();
		$_POST['updateTime'] = time();
		echo $result = $this->Advertise_model->insertAd($_POST);
  	}

  	/**
	*@Desc：将MP3转为amr格式
	*@Detail：curl模拟form表单提交
  	*/
	public function curlRequest($resUrl,$fileInfo,$saveSound,$analog_url){
		$ch = curl_init();
		$data = array(
		    'length' => $fileInfo['length'],
		    'appKey' => $resUrl['appKey'],
		    'secret' => $resUrl['secret'],
		    'Type'   => 'mp3'
		);
		$data = $this->pubfunction->sign($data);
	    $data['upload'] = "@".dirname(__FILE__).'/../../public/voices/'.$fileInfo['audioName'];
		curl_setopt($ch, CURLOPT_URL, $saveSound);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		//伪造网页来源地址,伪造来自百度的表单提交
		curl_setopt($ch, CURLOPT_REFERER, $analog_url);
		curl_setopt($ch, CURLOPT_POST, 1);
		@curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		$output = curl_exec($ch);
		$arr=json_decode($output,TRUE);
		//释放cURL句柄
		curl_close($ch);
		return $arr;
	}

	/**
	*@Desc：广告价格明细
	*@param：cityCode、adShape、timeSlot
	*/
	public function getPriceList(){
		$post_data = $this->input->post(NULL, TRUE);
		if(empty($post_data)){
			echo $res = $this->pubfunction->pub_json('1000','参数为空');
			return ;
		}
		echo $res = $this->Advertise_model->selectPriceList($post_data);
	}


	/**
	*@Desc：加载广告管理页面
	*/
	public function advertiseManage(){
		$get_data = $this->input->get(NULL,TRUE);
		$get_data = $get_data?array('adStatus'=>$get_data['adStatus']):array('adStatus'=>'');
		$session = $this->Common_model->getUserSessionInfo();
		$data = array_merge($get_data,$session);
		$this->load->view('advertiseinfos',$data);
	}

	/**
	*@Desc：加载意外险广告管理页面
	*/
	public function advertiseSponsorManage(){
		$get_data = $this->input->get(NULL,TRUE);
		$get_data = $get_data?array('adStatus'=>$get_data['adStatus']):array('adStatus'=>'');
		$session = $this->Common_model->getUserSessionInfo();
		$data = array_merge($get_data,$session);
		$this->load->view('advertisesponsor',$data);
	}

	/**
	*@Desc：获得所有广告数据
	*@param：adStatus、advertiseTitle、adShape、createTimeStart、createTimeEnd、userID
	*/
	public function getAllAdInfo(){
		$setData = json_decode($this->input->get('setData',TRUE),TRUE);
		$allow_array = array('iDisplayStart','iDisplayLength','adStatus','startTime','endTime','advertiseTitle','adShape','userID');
        foreach($setData as $k=>$v){
			if(in_array($v['name'],$allow_array)){
				$request_data[$v['name']] = $v['value'];
			}
        }
        if(empty($request_data['userID'])){
        	echo $res = $this->pubfunction->pub_json('1000','参数为空');
        	return;
        }
		echo $result = $this->Advertise_model->getAllAdData($request_data);
	}

	/**
	*@Desc：获得全部的意外险广告数据
	*create by liqui
	*2016-04-18
	*/
	public function getAllSponsorInfo(){
		$setData = json_decode($this->input->get('setData',TRUE),TRUE);
		$allow_array = array('iDisplayStart','iDisplayLength','adStatus','startTime','endTime','sponsor');
		foreach($setData as $k=>$v){
			if(in_array($v['name'],$allow_array)){
				$request_data[$v['name']] = $v['value'];
			}
        }
	    echo $res = $this->Advertise_model->getAllAccidData($request_data);
	}

	/**
	*@Desc：广告停用/提交审核
	*@param：adID、adStatus  3：停用 0：提交审核--待审核
	*/
	public function disableAd(){
		$post_data = $this->input->post(NULL, TRUE);
		if(empty($post_data) || empty($post_data['adID'])){
			echo $this->pubfunction->pub_json('1000','参数为空');
			return ;
		}
		echo $res = $this->Advertise_model->nonUseAd($post_data);
	}

	/**
	*@Desc：意外险广告停用/提交审核
	*@param：adID、adStatus  3：停用 0：提交审核--待审核
	*/
	public function disableAccid(){
		$post_data = $this->input->post(NULL, TRUE);
		if(empty($post_data) || empty($post_data['adID'])){
			echo $this->pubfunction->pub_json('1000','参数为空');
			return ;
		}
		echo $res = $this->Advertise_model->nonUseAccid($post_data);
	}

	/**
	*@Desc：获得广告详细数据
	*@param：adID
	*/
	public function getDetailAdvertise(){
		$post_data = $this->input->post(NULL, TRUE);
		if(empty($post_data['adID'])){
			echo $res = $this->pubfunction->pub_json('1000','参数为空');
			return ;
		}
		echo $res = $this->Advertise_model->copyreaderAd($post_data);
	}

	/**
	*@Desc：获取区域的代码返回对应的价格最大值与最小值
	*@param：cityCode
	*/
	public function comparePrice(){
		$post_data = $this->input->post(NULL,TRUE);
		if(empty($post_data['cityCode'])){
			echo $res = $this->pubfunction->pub_json('1000','参数为空');
			return ;
		}
		echo $res = $this->Advertise_model->cityContrastPrice($post_data);
	}

	/**
	*@Desc：用于页面循环时间段、广告形式
	*/
	public function showPriceList(){
		echo $res = $this->Advertise_model->showPrice();
	}

	/**
	*@Desc：加载广告详情界面
	*/
	public function showAdDetail(){
		$get_data = $this->input->get(NULL,TRUE);
		$data['name'] = $this->session->userdata('name');
		$res = array_merge($get_data,$data);
		$this->load->view('adDetail',$res);
	}

	/**
	*@Desc：获得广告详情
	*@param：adID
	*/
	public function getAdDetails(){
		$post_data = $this->input->post(NULL,TRUE);
		if(empty($post_data['adID'])){
			echo $this->pubfunction->pub_json('1000','参数为空');
			return ;
		}
		echo $res = $this->Advertise_model->getAdDetailInfo($post_data);
	}

	/**
	*@Desc：广告修改
  	*/
  	public function editAdInfo(){
  		if(empty($_POST['userID'])){
  			echo $res = $this->pubfunction->pub_json('1000','参数为空');
  			return ;
  		}
  		if(isset($_POST['audioURL']) && isset($_POST['audioContent']) && $_POST['audioURL'] != ''){
  			//用CI自带接收方式会转义
  			$audioURL = $_POST['audioURL'];
  			list($type, $data) = explode(',', $audioURL);
  			unset($_POST['audioURL']);
  			// 判断类型  并设置图片的后缀名
			if(strstr($type,'audio/mp3')!==''){
			    $ext = '.mp3';
			}else{
				echo $res = $this->pubfunction->pub_json('2001','音频格式不正确');
				return ;
			}
			//生成音频名
			$audio = $this->pubfunction->remittanceCode(8,3).$ext;
			$audioName = substr($audio, 2);
			file_put_contents("./public/voices/".$audioName, base64_decode($data), true);
			$resUrl = $this->config->item('upload_voices');
			$fileInfo = array(
				'audioName' => $audioName,//音频名
				'length'    => $_POST['fileSize']//音频大小
			);
			//MP3转amr
			$url = $this->curlRequest($resUrl,$fileInfo,$resUrl['saveSound'],$resUrl['analog_url']);
			if($url['ERRORCODE'] != '0'){
				print_r($url);
				return ;
			}
			$_POST['mp3Url'] = base_url().'public/voices/'.$audioName;
			$_POST['audioURL'] = $url['RESULT']['url'];
  		}
		unset($_POST['fileSize']);
		$_POST['updateTime'] = time();
		echo $result = $this->Advertise_model->updateAd($_POST);
  	}
}
?>