<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="keywords" content="ADTALK,上海语镜,广告投放,车联网">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <title>adtalk登录</title>
        <link rel="stylesheet" href="<?php echo base_url();?>public/css/bootstrap.min.css">
		<link rel="stylesheet" href="<?php echo base_url();?>public/css/flipclock.css">
		<link rel="stylesheet" href="<?php echo base_url();?>public/css/login.css">
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
					<span>没有adtalk账号？</span>
					<a href="<?php echo base_url();?>index.php/Register/register">马上注册</a>
				</div>
			</div>
		</div>
		<div class="row main">
			<div class="inmain">
				<div class="col-sm-7 bg"></div>
				<div class="login col-sm-4">
					<form class="form-horizontal" role="form" id="loginForm" action="<?php echo base_url();?>index.php/Register/doLogin" method="post">						
						<div class="form-group hd">
							<p class="col-sm-12">用户登录</p>
						</div>
						<!-- 用户名或密码错误提示 -->
						<div class="form-group">
							<label for="email" class="col-sm-3 control-label"></label>
							<div class="col-sm-6">
								<span class="error"><?php echo urldecode($data);?></span>
								<!-- <input type="email" class="form-control" id="email" name="email" placeholder="输入邮箱"> -->
							</div>
						</div>
						<!-- 用户名开始 -->
						<div class="form-group">										
							<label for="email" class="col-sm-3 control-label">用户名</label>
							<div class="col-sm-7 por">							
								<input type="email" class="form-control" id="email" name="email" placeholder="输入邮箱">
								<span class="glyphicon glyphicon-ok col-sm-2 ok" aria-hidden="true"></span>							
							</div>
							<div class="col-sm-12 por text-center">
								<p class="null-error error">用户名/密码不能为空</p>
								<p class="error email-false">邮箱格式不正确</p>
								<p class="glyphicon remove error" id="email-error">邮箱未注册或已停用</p>
								<p class="error login-error">用户名和密码不匹配</p>
							</div>	
						</div>
						<!-- 用户名称结束 -->
						<!-- 密码开始 -->
						<div class="form-group">
							<label for="password" class="col-sm-3 control-label">密码</label>
							<div class="col-sm-7">
								<input type="password" class="form-control" id="password" name="password" placeholder="输入用户密码">
							</div>
						</div>
						<!-- 密码结束 -->
						<!-- 登录开始 --> 
						<div class="form-group register-btn">
							<label for="password" class="col-sm-3 control-label"></label>
							<div class="col-sm-7">
								<input class="btn btn-success form-control" id="loginBtn" type="button" value="登录">
							</div>
						</div>
						<div class="row other">
							<div class="col-sm-12">
								<a href="<?php echo base_url();?>index.php/Register/writeAccountInfo">忘记密码？|</a>
								<a href="<?php echo base_url();?>index.php/Register/register">马上注册</a>
							</div>
						</div>
						<!-- 登录结束 -->
					</form>
				</div>
			</div>
			<div class="clock" style="width: 560px;margin: 0 auto;"></div>
		</div>
		<div class="footer">
			<p class="links">
				<a href="">道客FM</a>
				<a href="">WEME</a>
				<a href="">人人播</a>
				<a href="">声财</a>
				<a href="">帮忙拉</a>
				<a href="">道客IO</a>
				　Copyright © 2013-2014 www.adtalk.cn
			</p>
		</div>
		<script src="<?php echo base_url();?>public/js/jquery-1.11.2.min.js"></script>
		<script src="<?php echo base_url();?>public/js/jquery.validate.min.js"></script>
		<script src="<?php echo base_url();?>public/js/flipclock.min.js"></script>
		<script src="<?php echo base_url();?>public/js/login.js"></script>
    </body>
</html>