<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="keywords" content="ADTALK,上海语镜,广告投放,车联网">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>设置密码</title>
	<link rel="stylesheet" href="<?php echo base_url();?>public/css/bootstrap.min.css">
	<script type="text/javascript" src="<?php echo base_url();?>public/js/respond.min.js"></script>
	<link rel="stylesheet" href="<?php echo base_url();?>public/css/setpassword.css">
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
		<li><span>4</span><p>完成</p></li>
		</ul>
		<!-- 密码开始 -->
		<form id="setpswForm" class="form-horizontal" method="post">
			<div class="form-group">
				<label for="inputPassword3" class="col-sm-2 control-label"></label>
				<div class="col-sm-3">
					<input type="hidden" value="<?php echo $token;?>" id="token">
					<p class="col-sm-12"  id="failed">密码修改失败</p>
				</div>
			</div>
			<div class="form-group">
				<label for="inputPassword3" class="col-sm-2 control-label">密码</label>
				<div class="col-sm-3">
					<input type="password" id="password" class="form-control col-sm-10" name="password" placeholder="字母数字英文符号，最短6位区分大小写">
					<p class="col-sm-12"></p>
				</div>
			</div>
			<div class="form-group">
				<label for="inputPassword4" class="col-sm-2 control-label">确认密码</label>
				<div class="col-sm-3">
					<input type="password" id="confirm_password" class="form-control col-sm-10" name="confirm_password" placeholder="请再次输入密码">
					<p class="col-sm-12"></p>
				</div>
			</div>
			<!-- 密码结束 -->
			<div class="form-group register-btn">
				<div class="col-sm-offset-2 col-sm-10">
					<button class="btn col-sm-3 btn-success" id="registersBtn">下一步</button>
				</div>
			</div>
		</form>
	
	</div>
	<script src="<?php echo base_url();?>public/js/jquery-1.11.2.min.js"></script>
	<script src="<?php echo base_url();?>public/js/bootstrap.min.js"></script>
	<script src="<?php echo base_url();?>public/js/jquery.validate.min.js"></script>
	<script src="<?php echo base_url();?>public/js/setpassword.js"></script>
</body>
</html>