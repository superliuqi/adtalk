<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>修改密码</title>
	<meta name="keywords" content="ADTALK,上海语镜,广告投放,车联网">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<link rel="stylesheet" href="<?php echo base_url();?>public/css/bootstrap.min.css">
	<script type="text/javascript" src="<?php echo base_url();?>public/js/respond.min.js"></script>
	<link rel="stylesheet" href="<?php echo base_url();?>public/css/password.css">
	<!--[if lt IE 9]>
      <script src="//cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
   <![endif]-->

</head>
<body>
	<div class="header">
		<div class="inheader">
			<h1>ADTALK</h1>
			<div class="user-number">
				<a href="<?php echo base_url();?>index.php/Register/login">返回登录</a>
			</div>
		</div>
	</div>
	<div class="main">
		<div class="uu"></div>
		<ul class="step">
		<li class="current"><span>1</span><p>填写账户信息</p></li>
		<li><span>2</span><p>身份证验证</p></li>
		<li><span>3</span><p>设置密码</p></li>
		<li><span>4</span><p>完成</p></li>
		</ul>
		<div class="wrap1">
		<!-- 表单开始 -->
		<form id="losepwdForm" class="form-horizontal">
			<!-- 邮箱开始 -->
			<div class="form-group email">
				<label for="inputEmail3" class="col-sm-2 control-label">邮箱</label>
				<div class="col-sm-3 por">
					<p class="error ajax-error">发送失败，请稍后再试</p>
					<p class="email-error error">邮箱格式不正确</p>
					<input type="email" id="email" class="form-control col-sm-10" name="email" placeholder="请填写adtalk注册的邮箱">
					<span class="glyphicon glyphicon-ok col-sm-2 emails" aria-hidden="true"></span>
				</div>
			</div>
			<div class="form-group email">
				<label for="inputEmail3" class="col-sm-2 control-label"></label>
				<div class="col-sm-3 por">
					<p class="glyphicon glyphicon-remove emaile validate" style="display:none;">邮箱未注册</p>
				</div>
			</div>
			<!-- 邮箱结束 -->
			
			<!-- 验证码开始 -->
			<!-- <div class="form-group code">
				<label for="inputCode" class="col-sm-2 control-label">验证码</label>
				<div class="col-sm-1 inputw">
					<input type="text" class="form-control" maxlength="4" id="inputCode" name="inputCode">
					<p class="glyphicon glyphicon-remove codee validate" style="display:none;">验证码错误</p>
				</div>
				<span class="glyphicon glyphicon-ok codes" aria-hidden="true"></span>
				<img src=""> 
				<a href="javascript:;" id="changeCode">换一张</a>
			</div> -->

			<!-- 验证码结束 -->

			<!-- 注册开始 -->
			<div class="form-group register-btn">
				<div class="col-sm-offset-2 col-sm-10">
					<button class="btn col-sm-3 btn-success" type="button" id="registersBtn" disabled="disabled">下一步</button>
				</div>
			</div>
			<!-- 注册结束 -->
	</div>
	</form>
	</div>
	<script src="<?php echo base_url();?>public/js/jquery-1.11.2.min.js"></script>
	<script src="<?php echo base_url();?>public/js/bootstrap.min.js"></script>
	<script src="<?php echo base_url();?>public/js/jquery.validate.min.js"></script>
	<script src="<?php echo base_url();?>public/js/password.js"></script>
</body>
</html>