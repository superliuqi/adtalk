<?php
class Register_model extends CI_Model{
	public function __construct(){
		$this->load->database();
		$this->load->helper(array('cookie'));
		$this->load->library(array('pubfunction','session'));
	}

	/**
	*@Desc：注册时验证邮箱是否存在
	*@param：email
	*/
	public function emailvalidata($data){
		$arr=$this->db->select('status')->where('email',$data['email'])->get('userRegisterInfo')->row_array();
		if(empty($arr) || $arr['status'] == '0'){//未激活
			return $res = $this->pubfunction->pub_json('0','ok');
		}else{
			return $res = $this->pubfunction->pub_json('1001','邮箱已激活');
		}
	}

	/**
	*@Desc：注册发送邮件
	*@param：email
	*/
	public function baseuserinfo($data){
		$arr=$this->db->select('status')->where('email',$data['email'])->get('userRegisterInfo')->row_array();
		if(empty($arr)){
			$status = $this->db->insert('userRegisterInfo',$data);
			return $status ? '1' : '1003';
		}else{
			if($arr['status'] == '1'){
				return '1001';
			}
			$status=$this->db->where('email',$data['email'])->update('userRegisterInfo',$data);
			return $status ? '1' : '1003';
		}
	}

	/**
	*@Desc：点击邮箱的链接时验证token
	*/
	public function tokenvalidata($data){
		$arr=$this->db->select('email,status,tokenExptime')->where('token',$data['token'])->get('userRegisterInfo')->row_array();
		$detail=$this->db->select()->where('email',$arr['email'])->get('userDetailInfo')->row_array();
		//先检查此token是否有记录
		if (!empty($arr)) {
			//证明此token存在，返回成功并更新状态,更新
			if($arr['status'] == '1'){
				return '1001';//已经激活,禁止访问
			}
			$status = $this->db->set(array('status'=>'5','userID'=>hash('crc32', $arr['email']),'updateTime'=>time()))->where('token',$data['token'])->update('userRegisterInfo');
			if(empty($detail)){
				$insert_detail = array(
					'userID' => hash('crc32', $arr['email']),
					'email'  => $arr['email'],
					'createTime' => time()
				);
				$this->db->insert('userDetailInfo',$insert_detail);
			}
			return !empty($status)?'1':'1003';
		}else{
			//此token不存在
			return '0';
		}
	}

	/**
	*@Desc：完善信息 得到邮箱
	*@param：token
	*/
	public function getemail($data){
		return $arr=$this->db->select()->where('token',$data['token'])->get('userRegisterInfo')->row_array();
	}

	/**
	*@Desc：验证用户登录的邮箱
	*/
	public function validateEmail($data){
		$arr=$this->db->select('status')->where('email',$data['email'])->get('userRegisterInfo')->row_array();
		if(empty($arr)||$arr['status'] == '0'||$arr['status'] == '4'){
			return $res = $this->pubfunction->pub_json('1005','此邮箱未激活或已停用');
		}else{
			return $res = $this->pubfunction->pub_json('0','ok');
		}
	}

	/**
	*@Desc：验证登录信息是否正确
	*@param：email、password
	*/
	public function validatelogin($data){
		$arr=$this->db->select('status,password,userID')->where('email',$data['email'])->where('status !=','4')->get('userRegisterInfo')->row_array();
		if(empty($arr)){
			return $res = $this->pubfunction->pub_json('2003','未注册或账号被停用');
		}
		if ($arr['password'] == md5($data['password'])) {
			$this->session->set_userdata('userID',$arr['userID']);//写入缓存
			$this->db->where('email',$data['email'])->set('logintime',time())->update('userRegisterInfo');
			return $res = $this->pubfunction->pub_json('0','ok');
		}else{
			return $res = $this->pubfunction->pub_json('1006','用户名与密码不匹配');
		}
	}

	/**
	*@Desc：找回密码 更新用户信息
	*@param：email
	*/
    public function updatetoken($data){
		return $arr=$this->db->where('email',$data['email'])->update('userRegisterInfo',$data);
	}

	/**
	*@Desc：找回密码  验证
	*/
	public function tokenlosepwd($data){
		return $arr=$this->db->where('token',$data['token'])->select('id')->get('userRegisterInfo')->row_array();
	}

	/**
	*@Desc：忘记密码 -- 修改密码
	*/
	public function updatepwd($data){
		return $arr=$this->db->where('token',$data['token'])->set('password',md5($data['newpassword']))->set('updateTime',time())->update('userRegisterInfo');
	}

	/**
	*@Desc：得到完善详情信息中需要的userID
	*@param：token
	*/
	public function getUserID($data){
		return $arr=$this->db->select('email')->where('token',$data['token'])->get('userRegisterInfo')->row_array();
	}

	/**
	*@Desc：查询用户是否完善信息
	*@param：userID
	*/
	public function detectionPerfectInfo($data){
		$res = $this->db->select('checkStatus')->where('userID',$data['userID'])->get('userDetailInfo')->row_array();
		if($res['checkStatus'] == '0' || empty($res)){
			return $res = $this->pubfunction->pub_json('1007','该用户未完善信息');
		}else{
			return $res = $this->pubfunction->pub_json('0','ok');
		}
	}

	/**
	*@Desc：时时上传图片
	*@param：image、size
	*/
	public function uploadImgageInfo($data){
		$size = $data['size'];
		list($type, $data) = explode(',', $data['image']);
		if(strstr($type,'image/jpeg')!=''){
		    $ext = '.jpg';
		}elseif(strstr($type,'image/gif')!=''){
		    $ext = '.gif';
		}elseif(strstr($type,'image/png')!=''){
		    $ext = '.png';
		}else{
			$ext = '.jpg';
		}
		$name = $this->pubfunction->remittanceCode(8,3).$ext;
		file_put_contents("./public/upload/".substr($name, 2), base64_decode($data), true);
		$name = substr($name, 2);
		
		$resUrl = $this->config->item('uploadImg');
		$save = array(
			'appKey' => $resUrl['appKey'],
			'length' => $size
		);
		$save = $this->pubfunction->sign($save);
		$save['isStorage'] = 'true';
		$savep['cacheTime'] = '';
		return $data = $this->curlRequest($resUrl,$name,$save);
	}

	public function curlRequest($url,$name,$save){
		$ch = curl_init();
		//加@符号curl就会把它当成是文件上传处理
		$save = $save;
		$save['img'] = '@'.dirname(__FILE__).'/../../public/upload/'.$name;
		curl_setopt($ch,CURLOPT_URL,$url['saveImage']);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
		curl_setopt($ch,CURLOPT_POST,true);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$save);
		$result = curl_exec($ch);
		curl_close($ch);
		unlink(dirname(__FILE__).'/../../public/upload/'.$name);
		return $result;
	}

	/**
	*@Desc：获取所有的播放数量
	*/
	public function getAdPlayCount(){
		$count = $this->db->select('id')->get('adPlayInfo')->num_rows();
		$zero = 8 - strlen($count);
		//str_repeat  规定要重复的字符串
		$count = str_repeat("0",$zero).$count;
		return $res = $this->pubfunction->pub_json('0',$count);
	}
}
?>