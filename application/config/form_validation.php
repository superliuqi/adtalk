<?php
$config = array(
	'editPwd'	=>	array(
		array(
        	'field'   => 'userID', 
        	'label'   => '用户编号', 
        	'rules'   => 'trim|required|xss_clean'
    	),
    	array(
    		'field'   => 'password',
    		'label'   => '新密码',
    		'rules'   => 'trim|required|xss_clean'
    	),
    	array(
    		'field'  => 'oldPassword',
    		'label'  => '原密码',
    		'rules'  => 'trim|required|xss_clean'
    	)
	),
	//完善信息
	'perfectInfo' => array(
		array(
        	'field'   => 'brPic', 
        	'label'   => '营业执照', 
        	'rules'   => 'trim|required|xss_clean'
		),
		array(
        	'field'   => 'cocPic', 
        	'label'   => '阻止机构代码', 
        	'rules'   => 'trim|required|xss_clean'
		),
		array(
        	'field'   => 'trcPic', 
        	'label'   => '税务登记', 
        	'rules'   => 'trim|required|xss_clean'
		),
		array(
        	'field'   => 'prove', 
        	'label'   => '授权证明', 
        	'rules'   => 'trim|required|xss_clean'
		),
		array(
			'field'   => 'IDNumber',
			'label'   => '运营人身份证号',
			'rules'   => 'trim|required|xss_clean'
		),
		array(
			'field'   => 'telephone',
			'label'   => '运营人的手机号码',
			'rules'   => 'trim|required|xss_clean'
		),
		array(
			'field'   => 'companyName',
			'label'   => '公司名称',
			'rules'   => 'trim|required|xss_clean'
		),
		array(
			'field'   => 'companyAddress',
			'label'   => '公司地址',
			'rules'   => 'trim|required|xss_clean'
		),
		array(
			'field'   => 'companyPhone',
			'label'   => '公司电话',
			'rules'   => 'trim|required|xss_clean'
		),
		array(
			'field'   => 'city',
			'label'   => '地区编号',
			'rules'   => 'trim|required|xss_clean'
		),
		array(
			'field'   => 'companyType',
			'label'   => '行业类别',
			'rules'   => 'trim|required|xss_clean'
		)
	)
);
?>	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	