<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>修改密码</title>
	<meta name="keywords" content="ADTALK,上海语镜,广告投放,车联网">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<link rel="stylesheet" href="<?php echo base_url();?>public/css/bootstrap.min.css">
	<script type="text/javascript" src="<?php echo base_url();?>public/js/respond.min.js"></script>
	<link rel="stylesheet" href="<?php echo base_url();?>public/css/accomplish.css">
	<!--[if lt IE 9]>
      <script src="//cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
   <![endif]-->
</head>
<body>
	<div class="header">
		<div class="inheader">
			<h1>ADTALK</h1>
		</div>
	</div>
	<div class="main">
		<div class="uu"></div>
		<ul class="step">
			<li class="current"><span>1</span><p>填写账户信息</p></li>
			<li class="current"><span>2</span><p>身份证验证</p></li>
			<li class="current"><span>3</span><p>设置密码</p></li>
			<li class="current"><span>4</span><p>完成</p></li>
		</ul>
		<div class="success">
			<p>新密码设置成功！</p>
			<button class="btn btn-success"><a href="<?php echo base_url();?>index.php/Register/login">返回登录</a></button>
		</div>
	</div>
	<script src="<?php echo base_url();?>public/js/jquery-1.11.2.min.js"></script>
	<script src="<?php echo base_url();?>public/js/bootstrap.min.js"></script>
	<script src="<?php echo base_url();?>public/js/jquery.validate.min.js"></script>
</body>
</html>