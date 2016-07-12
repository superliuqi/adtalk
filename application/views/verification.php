<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="keywords" content="ADTALK,上海语镜,广告投放,车联网">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>修改密码</title>
	<link rel="stylesheet" href="<?php echo base_url();?>public/css/bootstrap.min.css">
	<script type="text/javascript" src="<?php echo base_url();?>public/js/respond.min.js"></script>
	<link rel="stylesheet" href="<?php echo base_url();?>public/css/verification.css">
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
		<li><span>3</span><p>设置密码</p></li>
		<li><span>4</span><p>完成</p></li>
		</ul>
	
	<!-- 邮箱激活开始 -->
		<div class="wrap2">
			<h4>修改adtalk账号密码</h4>
			<p>确认邮件已发送至你的注册邮箱 : <span id="userID"><?php echo $email;?></span>。请进入邮箱查看邮件，并激活adtalk帐号。</p>
			<dl>
				<dt>没有收到邮件</dt>
				<dd>1、请检查邮箱地址是否正确，你可以返回<a href="<?php echo base_url();?>index.php/Register/writeAccountInfo">重新填写</a>。</dd>
				<dd>2、检查你的邮件垃圾箱</dd>
				<dd>3、若仍未收到确认，请尝试<a href="javascript:;" id="resend">重新发送</a></dd>
			</dl>
		</div>
		<!-- 邮箱激活结束 -->
	</div>
	<script src="<?php echo base_url();?>public/js/jquery-1.11.2.min.js"></script>
	<script src="<?php echo base_url();?>public/js/bootstrap.min.js"></script>
	<script src="<?php echo base_url();?>public/js/jquery.validate.min.js"></script>
</body>
</html>