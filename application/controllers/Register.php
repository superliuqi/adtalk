<?php
/**
 *@versions：CI3.0.2
 *Desc：注册、登录、忘记密码
 *createTime：2015/12/08
 *Auth：Zhouting
*/
defined('BASEPATH') OR exit('No direct script access allowed');
class Register extends CI_Controller{			
	public function __construct(){
  		parent::__construct();
  		$this->load->model('Register_model');
  		$this->load->model('Common_model');
  		$this->load->helper(array('url','form','cookie','captcha'));
  		$this->load->library(array('curl','email','session','pubfunction','form_validation'));
  	}

  	/**
	*@Desc：加载注册页面 
	*/
  	public function register(){
		$this->load->view('registers');
	}

	/**
	*@Desc：注册时验证邮箱是否存在
	*@param：email
	*/
	public function validateEmail(){
		$post_data=$this->input->post(NULL, TRUE);
		if(!$post_data){
			echo $res = $this->pubfunction->pub_json('1000','邮箱为空');
			return FALSE;
		}else{
			echo $status=$this->Register_model->emailvalidata($post_data);
		}
	}

	/**
	*@Desc：注册获取验证码
	*/
	public function getAuthCode(){
		//设置验证码属性
		$code=$this->config->item('image_auth_code');
		$cap = create_captcha($code);
		//存入缓存
		$this->session->set_userdata('word',$cap['word']);
		$path=base_url().'captcha/'.$cap['filename'];
		echo $this->pubfunction->pub_json('0',$path);
	}

	/**
	*@Desc：鼠标离开时验证验证码
	*@param：code 验证码
	*/
	public function validateCode(){
		$post_data=$this->input->post(NULL, TRUE);
		if(empty($post_data)){
			echo $res = $this->pubfunction->pub_json('1000','验证码为空');
		}else{
			//读取缓存中的验证码
			$session=$this->session->userdata('word');
			if($session == $post_data['code']){
				echo $res = $this->pubfunction->pub_json('0','验证码正确');
			}else{
				echo $res = $this->pubfunction->pub_json('1002','验证码错误');
			}
		}
	}

	/**
	*@Desc：提交注册向邮箱发送邮件
	*@param：email、password
	*/
	public function sendEmail(){
		$post_data=$this->input->post(NULL, TRUE);
		if (empty($post_data)) {
			echo $res = $this->pubfunction->pub_json('1000','参数为空');
			return FALSE;
		}
		//将信息放入数据库，并发送邮件
		$post_data['token']=sha1($post_data['email']).rand(10,99);
		$post_data['tokenExptime']=time();
		$post_data['status']='0';
		$post_data['password']=md5($post_data['password']);
		$arr=$this->Register_model->baseuserinfo($post_data);
		if ($arr == '1003') {
			echo $this->pubfunction->pub_json($arr,'操作数据库失败');
			return FALSE;
		}
		if ($arr == '1001') {
			echo $this->pubfunction->pub_json($arr,'该邮箱已激活');
			return FALSE;
		}
		//数据库的数据操作成功，邮件
		$url = base_url().'index.php/Register/validateToken?token='.$post_data['token'];
		$message = '<div><p>你好!</p><p>感谢注册广告管理注册平台。<br>'.
					'你的登录邮箱为：<a href="mailto:'.$post_data['email'].'">'.$post_data['email'].'</a>。请点击以下链接激活帐号：</p>'.
					'<p style="word-wrap:break-word;word-break:break-all;"><a href="'.$url.'" target="_blank">'.$url.'</a></p>'.
					'<p>如果以上链接无法点击，请将上面的地址复制到你的浏览器(如IE)的地址栏进行激活。 （该链接在24小时内有效，24小时后需要重新注册）</p>'.		
					'</div>';
		$this->email->from($this->config->item("from_email"), $this->config->item("from_name"));
		$this->email->to($post_data['email']);
		$this->email->subject($this->config->item("email_subject"));
		$this->email->message($message);
		if($this->email->send()){
			echo $res = $this->pubfunction->pub_json('0','ok');
		}else{
			echo $res = $this->pubfunction->pub_json('1004','发送邮件失败');
		}
	}

	/**
	*@Desc：注册 验证邮箱中的token后，跳转到完善资料界面
	*@param：token
	*/
	public function validateToken(){
		$data=$this->input->get(NULL, TRUE);//获取到token的值，验证token值，跳转页面
		if (empty($data)) {
			show_error('请求参数有误',200,'出错');
			return FALSE;
		}
		$status=$this->Register_model->tokenvalidata($data);
		if ($status=='0') {
			//token错误
			show_error("请使用邮箱中正确的url地址",200,"出错");
		}else{
			//token正确，返回完善资料的页面
			if ($status=='1001') {
				show_error("此邮箱已经激活",200,"出错");
			}
			$data=$this->Register_model->getEmail($data);
			$this->load->view('message',$data);
		}
	}

	/**
	*@Desc：发送手机验证码,完善资料
	*@param：phone
	*/
	public function getPhoneAuthCode(){
		$data=$this->input->post(NULL, TRUE);
		if(empty($data['phone'])){
			echo $res = $this->pubfunction->pub_json('1000','参数为空');
			return ;
		}
		$url=$this->config->item('phone_auth_code');
		$rand=rand(1000,9999);
		$data=array(
			'params'=>$rand.$url['params'],
			'tempID'=> $url['tempID'],
			'sendType'=>$url['sendType'],
			'secret'=>$url['secret'],
			'phone'	=> $data['phone'],
			'appKey'=>$url['appKey']
		);
		$data['sign']=$this->pubfunction->get_sign_array($data);
		$body=$this->curl->simple_post($url['messageCode'],$data);
		$arr=json_decode($body,TRUE);
		if ($arr['ERRORCODE'] == '0') {
			$this->session->set_userdata('phone_code',$rand);
			echo $res = $this->pubfunction->pub_json('0','ok');
		}else{
    		echo $res = $this->pubfunction->pub_json('1004','验证码发送失败');
		}
	}

	/**
	*@Desc：验证手机验证码，完善资料
	*@param：phonecode
	*/
	public function validatePhoneCode(){
		$data=$this->input->post(NULL, TRUE);
		$phone=$this->session->userdata('phone_code');//取出此手机号对应的session
		if ($phone == $data['phonecode']) {
			echo $res = $this->pubfunction->pub_json('0','ok');
		}else{
			echo $res = $this->pubfunction->pub_json('1004','验证不通过');
		}
	}

	/**
	*@Desc：图片裁剪
	*@param：url size
	*@author liuqi
	*/
	public function imageCut(){
		list($type, $data) = explode(',', $_POST['url']);
		if(strstr($type,'image/jpeg')!=''){
		    $ext = '.jpg';
		}elseif(strstr($type,'image/gif')!=''){
		    $ext = '.gif';
		}elseif(strstr($type,'image/png')!=''){
		    $ext = '.png';
		}
		$name 		= $this->pubfunction->remittanceCode(8,3).$ext;
		$photo_name = "./public/upload/".substr($name, 2);
		file_put_contents($photo_name, base64_decode($data), true);
		$src 	 = $_POST['url'];
		$start_x = $_POST['x1'];
		$start_y = $_POST['y1'];
		$weight  = $_POST['w'];
		$high	 = $_POST['h'];
		//创建一个画布
	    $new_img = imagecreatetruecolor($weight,$high);	    
	    //打开图片
	    if($ext == '.jpg'){
	    	$src_img = imagecreatefromjpeg($photo_name);
	    }elseif($ext == '.png'){
	    	$src_img = imagecreatefrompng($photo_name);
	    }elseif($ext == '.gif'){
	    	$src_img = imagecreatefromgif($photo_name);
	    }
	    //复制图片到画布
	    imagecopyresampled($new_img,$src_img,0,0,$start_x,$start_y,$weight,$high,$weight,$high);
	    if($ext == '.jpg'){
	    	imagejpeg($new_img,$photo_name);
	    }elseif($ext == '.png'){
	    	imagepng($new_img,$photo_name);
	    }elseif($ext == '.gif'){
	    	imagegif($new_img,$photo_name);
	    }
	    //销毁
	    imagedestroy($new_img);
	    imagedestroy($src_img);
	    $file = $photo_name;  
		$type = getimagesize($file);//取得图片的大小，类型等
		$fp   = fopen($file,'r')or die("Can't open file");  
		$file_content = chunk_split(base64_encode(fread($fp,filesize($file))));//base64编码  
		switch($type[2]){//判断图片类型  
			case 1:$img_type = "gif";break;  
			case 2:$img_type = "jpg";break;  
			case 3:$img_type = "png";break;  
		}  
		$img = 'data:image/'.$img_type.';base64,'.$file_content;//合成图片的base64编码  
		fclose($fp);
		$send['size'] 	= filesize($photo_name);
		$send['image']	= $img;
		unlink($photo_name);
		return $this->uploadCutImage($send);
	}

	public function uploadCutImage($data){
		if(empty($data['image'])){
			echo $res = $this->pubfunction->pub_json('1000','参数为空');
			return ;
		}
		echo $res = $this->Register_model->uploadImgageInfo($data);
	}
	/**
	*@Desc：时时上传图片
	*@param：image、size
	*/
	public function uploadImage(){
		if(empty($_POST['image'])){
			echo $res = $this->pubfunction->pub_json('1000','参数为空');
			return ;
		}
		echo $res = $this->Register_model->uploadImgageInfo($_POST);
	}

	/**
	*	上传文件接口
	*/
	 public function uploadFile()
    {
        $file = $_FILES['userfile'];
        $file_size = $file['size'];
        if(floor($file_size/(1024*1024)) > 2){
        	echo $this->pubfunction->pub_json('1000','上传文件不能超过2M');
        	return;
        }
        $allow_type=array('jpg','gif','png','jpeg','zip');
        if($file['error']){
            echo $this->pubfunction->pub_json('1001','上传错误');
            return;
        }
        $suffix = pathinfo($file['name'],PATHINFO_EXTENSION);
        if(! in_array($suffix, $allow_type)){
        	echo $this->pubfunction->pub_json('1002','上传类型错误');
        	return;
        }
        $filename = date('ymd').uniqid().rand(1,9999).'.'.$suffix;
        $save_path = './public/upload';
        if(!file_exists($save_path)){
        	mkdir($save_path,0777,true);
    	}	
        $file_path = $save_path.'/'.$filename;
        if(! is_uploaded_file($file['tmp_name'])){
            echo $this->pubfunction->pub_json('1003','上传失败');
            return;
        }
        if(! move_uploaded_file($file['tmp_name'], $file_path)){
            echo $this->pubfunction->pub_json('1004','上传文件失败');
            return;
        }
        $res = array('url'=>base_url().'public/upload/'.$filename);
        //判断上传的文件是图片还是压缩包，如果是图片返回url，否则解压文件
        $is_image=array('jpg','gif','png','jpeg');
        if(in_array($suffix, $is_image)){  	
        	echo $this->pubfunction->pub_json('0',$res);
        	return;
        }
        $dirname = './public/upload/'.date('ymd').uniqid().rand(1,9999).'/';
        mkdir($dirname);
        chmod($dirname, 0777);
        $result_zip = $this->getZip($file_path,$dirname);
        $result_zip = json_decode($result_zip,true);
        $result = array('url'=>base_url().substr($result_zip['RESULT'], 2));//	./public
        if($result_zip['ERRORCODE'] == '0'){
        	unlink($file_path);
        	echo $this->pubfunction->pub_json('0',$result);
        	return;
        }else{
        	unlink($file_path);
        	echo $this->pubfunction->pub_json($result_zip['ERRORCODE'],$result_zip['RESULT']);
        	return;
        }
    }

	/**
	*解压文件
	*@param：filename、path
	*/
    public function getZip($filename, $path) {
		if(!file_exists($filename)){
		  return $this->pubfunction->pub_json('3001','解压文件不存在');
		}
		$resource = zip_open($filename);
		while ($dir_resource = zip_read($resource)) {
		    if (zip_entry_open($resource,$dir_resource)) {
		   		//获取当前项目的名称,即压缩包里面当前对应的文件名
		   		$file_name = $path.zip_entry_name($dir_resource);
		   		$file_name = iconv("gb2312","utf-8",$file_name);
		   		// 检测 是否含有中文   /[\x{4e00}-\x{9fa5}]/u
		  		if(preg_match('/[\x{4e00}-\x{9fa5}]/u',$file_name)){
				    return  $this->pubfunction->pub_json('3010','文件或文件夹中存在中文字符');
				}
		   		if(! is_dir($file_name)){
		   			$suffix = substr($file_name, -1)=='/'?'/':pathinfo($file_name,PATHINFO_EXTENSION);
		   			$total_suffix[] = $suffix;
		   			//压缩的文件里允许存在的文件类型
		   			$allow_suffix = array('html','css','js','jpg','png','gif','jpeg','/');
		   			if(! in_array($suffix, $allow_suffix)){
		   				return $this->pubfunction->pub_json('3002','压缩包中存在不允许上传的文件');
		   			}
		    		//读取这个文件的大小
		    		$file_size = zip_entry_filesize($dir_resource);
		    		if($file_size > (1024*1024*2)){
						return $this->pubfunction->pub_json('3003','文件大小超过2M,无法上传');
		    		}
		   		}
		   		//关闭当前
		   		zip_entry_close($dir_resource);
		  	}
		}
		if(!in_array('html', $total_suffix)){
			rmdir(substr($path, 0,-1));
			return $this->pubfunction->pub_json('3005','至少要有一个html文件');
		}
		//关闭压缩包
		zip_close($resource);

		$resource = zip_open($filename);
		$i=0;
		while ($dir_resource = zip_read($resource)) {
		    if(zip_entry_open($resource,$dir_resource)){
		  		$i++;
		   		//获取当前项目的名称,即压缩包里面当前对应的文件名
		   		$file_name = $path.zip_entry_name($dir_resource);
		   		$file_name = str_replace('\\', '/', $file_name);
		   		$count     = count(explode('/', zip_entry_name($dir_resource)));
		   		//为了定位到上传文件 最外层的html
		   		if(pathinfo($file_name,PATHINFO_EXTENSION) == 'html' && $count==2){
		   			$html  = $file_name;
		   		}
		   		//压缩文件里只有一个html文件
		   		if(pathinfo($file_name,PATHINFO_EXTENSION) == 'html' && $i==1){
		   			$html  = $file_name;
		   		}
		   		$file_path = substr($file_name,0,strrpos($file_name, "/"));
			    //如果路径不存在，则创建一个目录
			    if(!is_dir($file_path)){
			     	mkdir($file_path);
			     	chmod($file_path, 0777);
			    }
		   		if(! is_dir($file_name)){
		    		//读取这个文件的大小
		    		$file_size    = zip_entry_filesize($dir_resource);
					$file_content = zip_entry_read($dir_resource,$file_size);
	 				file_put_contents($file_name,$file_content);
		   		}
		   		//关闭当前
		   		zip_entry_close($dir_resource);
		  	}
		}
		//关闭压缩包
		zip_close($resource);
		return  $this->pubfunction->pub_json('0',$html);
	}

	
	/**
	*@Desc：完善信息 详细信息放入数据库
	*@param：
	*/
	public function detailInfo(){
		$post_data = $this->input->post(NULL,TRUE);
		// if (!$this->form_validation->run('perfectInfo')){
		// 	show_error("请将信息填写完整",500,"完善信息出错");
		// }
		//营业执照
		$post_data['licenseScanPreview'] = $post_data['brPic'];
		//组织机构代码
		$post_data['orgScanPreview'] = $post_data['cocPic'];
		//税务登记
		$post_data['taxRegistrationPreview'] = $post_data['trcPic'];
		//授权证明
		$post_data['impowerProve'] = $post_data['prove'];
		//营业执照
		$post_data['createTime'] = time();
		unset($post_data['code']);
		$email = $this->Common_model->getUserInfo($post_data['userID']);
		$post_data['email'] = $email['email'];
		unset($post_data['brPic']);unset($post_data['cocPic']);unset($post_data['trcPic']);unset($post_data['prove']);
		$status = $this->Common_model->addUserDetailInfo($post_data);
		if($status){
			// $data = $this->Common_model->getUserSessionInfo();
			redirect('Workbench/workbench');
			// $this->load->view('login',$data);
		}else{
			show_error("请登录后重新完善信息",500,"新增数据出错");
		}
	}

	/**
	*@Desc：查询用户是否完善信息
	*@param：userID
	*/
	public function checkPerfectInfo(){
		$post_data = $this->input->post(NULL,TRUE);
		if(empty($post_data['userID'])){
			echo $res = $this->pubfunction->pub_json('1000','参数为空');
			return ;
		}
		echo $res = $this->Register_model->detectionPerfectInfo($post_data);
	}

	/**
	*@Desc：用户登录页面的展示
	*/
	public function login(){
		$data='';
		$infomation=array('data'=>$data);
		$this->load->view('login',$infomation);
	}

	/**
	*@Desc：验证用户登录的邮箱
	
	*@param：email
	*/
	public function validateLoginEmail(){
		$post_data=$this->input->post(NULL, TRUE);
		if (!$post_data['email']) {
			echo $res = $this->pubfunction->pub_json('1000','邮箱为空');
			return ;
		}
		if(preg_match('#[a-z0-9&\-_.]+@[\w\-_]+([\w\-.]+)?\.[\w\-]+#is', $post_data['email'])){
			echo $status=$this->Register_model->validateEmail($post_data);//验证邮箱是否可以登录
		}else{
			echo $res = $this->pubfunction->pub_json('1004','邮箱格式不正确');
		}
    }

	/**
	*@Desc：用户登录
	*@param：email、password
	*/
	public function doLogin(){
		$post_data = $this->input->post(NULL,TRUE);
		if (empty($post_data)) {
			echo $res = $this->pubfunction->pub_json('1000','参数为空');
		}else{
			$this->session->set_userdata('email',$post_data['email']);
			echo $status=$this->Register_model->validatelogin($post_data);
		}
	}

	/**
	*@Desc：加载完善信息界面
	*/
	public function perfectInfo(){
		$data = $this->Common_model->getUserSessionInfo();
		if(is_array($data)){
			$this->load->view('message',$data);
		}else{
  			$this->load->view('login');
		}
	}

	/**
	*@Desc：用户退出
	*/
	public function loginOut(){
		$this->session->sess_destroy();
		redirect('Register/login');
	}

	/**
	*@Desc：加载忘记密码界面 -- 填写账户信息
	*/
	public function writeAccountInfo(){
		$this->load->view('losepwd');
	}

	/**
	*@Desc：用户忘记密码，发送邮件(必须是以前的邮箱)
	*@param：email、
	*/
	public function forgetPwdSendEmail(){
		$data=$this->input->post(NULL, TRUE);
		$this->session->set_userdata('email',$data['email']);
		if(empty($data)){
			echo $res = $this->pubfunction->pub_json('1000','参数为空');
			return ;
		}
		$status=$this->Register_model->emailvalidata($data);
		$res = json_decode($status,TRUE);
		if($res['ERRORCODE'] == '1001'){//已激活状态
			$data['tokenExptime']=time();
			$data['token']=sha1($data['email']).rand(10,999);
			$result=$this->Register_model->updatetoken($data);//更新数据库的token信息
			if($result){
				$url = base_url().'index.php/Register/verifyToken?token='.$data['token'];
				$message = '<div><p>你好!</p><p>感谢使用广告管理平台的找回密码。<br>'.
							'你的登录邮箱为：<a href="mailto:'.$data['email'].'">'.$data['email'].'</a>。请点击以下链接找回密码：</p>'.
							'<p style="word-wrap:break-word;word-break:break-all;"><a href="'.$url.'" target="_blank">'.$url.'</a></p>'.
							'<p>如果以上链接无法点击，请将上面的地址复制到你的浏览器(如IE)的地址栏进行激活。 （该链接在24小时内有效，24小时后需要重新注册）</p>'.		
							'</div>';
				$this->email->from($this->config->item("from_email"), $this->config->item("from_name"));
				$this->email->to($data['email']);
				$this->email->subject($this->config->item("email_subject"));
				$this->email->message($message);
				echo $this->email->send()?$res = $this->pubfunction->pub_json('0','ok'):$res = $this->pubfunction->pub_json('1004','发送邮件失败');
			}else{
				echo $res = $this->pubfunction->pub_json('1003','操作数据库失败');
			}
		}else{
			echo $res = $this->pubfunction->pub_json('1005','此邮箱不存在或未激活');
		}
	}

	/**
	*@Desc：加载忘记密码  -- 身份验证界面
	*/
	public function verification(){
		$data = $this->session->all_userdata();
		$this->load->view('verification',$data);
	}

	/**
	*@Desc：忘记密码 验证token
	*/
	public function verifyToken(){
		$data=$this->input->get(NULL, TRUE);//获取到token的值，验证token值，跳转页面
		if (empty($data['token'])) {
			echo $res = $this->pubfunction->pub_json('1000','token 为空');
			return FALSE;
		}
		$status=$this->Register_model->tokenlosepwd($data);
		if ($status) {
			$infomation=array('token'=>$data['token']);
			$this->load->view('setpassword',$infomation);
		}
	}

	/**
	*@Desc：验证邮箱中的token后(修改密码)
	*@param：token、newpassword
	*/
	public function updatePwd(){
		$data=$this->input->post(NULL, TRUE);//获取到token的值，验证token值，跳转页面
		$status=$this->Register_model->updatepwd($data);
		echo $status?$this->pubfunction->pub_json('0','ok'):$this->pubfunction->pub_json('1003','操作数据库失败');
	}

	/**
	*@Desc：修改密码成功界面
	*/
	public function accomplish(){
		$this->load->view('accomplish');
	}

	/**
	*@Desc：adtalk协议
	*/
	public function adtalkService(){
		$this->load->view('adtalk_service');
	}

	/**
	*@Desc：adtalk协议
	*/
	public function mirrtalkService(){
		$this->load->view('mirrtalk_service');
	}

	/**
  	*@Desc：查询全部的广告播放数量
  	*/
	public function getPlayCount(){
	    echo $res = $this->Register_model->getAdPlayCount();
	}
}
?>