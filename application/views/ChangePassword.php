<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="keywords" content="ADTALK,上海语镜,广告投放,车联网">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>修改密码</title>
	<link rel="stylesheet" href="<?php echo base_url();?>public/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo base_url();?>public/css/ChangePassword.css">
	<!--[if lt IE 9]>
      <script src="//cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
   <![endif]-->
</head>
<body>

	<div class="header">
		<div class="inheader row">
			<h1 class="col-sm-8">ADTALK</h1>
			<div class="user-number col-sm-4">
				<span>您好,<span><?php echo $email;?></span>欢迎回到adtalk！</span>
				<input type="hidden" id="userID" value="<?php echo $userID;?>">
				<a href="<?php echo base_url();?>index.php/Register/loginOut">退出</a>
			</div>
		</div>
	</div>
	<div></div>
<div class="main">
	<div class="row wrap">
		<div class="notice">您的企业信息还未完善不能新建广告，为了让用户更好的了解您请先去<a href="javascript:;">完善信息</a></div>
		<div class="nav col-sm-2">
			<!-- 侧边nav开始 -->
			<div class="container-fluid">
			<div class="row-fluid">
				<div class="span12">
					<div class="accordion" id="accordion-975558">
						<!-- 工作台开始 -->
						<div class="accordion-group">
							<div class="accordion-heading">
								 <a href="<?php echo base_url();?>index.php/Workbench/workbench">工作台</a>
							</div>
						</div>
						<!-- 工作台结束 -->

						<!-- 我的广告开始 -->
							<div class="accordion-group"> 
								<div class="accordion-heading">
									 <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-975558" href="#accordion-element-28350">我的广告</a>
								</div>
								<div id="accordion-element-28350" class="accordion-body in collapse">
									<div class="accordion-inner">
										<a href="<?php echo base_url();?>index.php/Advertise/newAdvertise">新建开机广告</a>
									</div>
									<div class="accordion-inner">
                    <a href="<?php echo base_url();?>index.php/Advertise/newNaming">新建冠名广告</a>
                  </div>
                  <div class="accordion-inner">
                    <a href="<?php echo base_url();?>index.php/Advertise/newTrailer">新建尾标广告</a>
                  </div>
                  <div class="accordion-inner">
                    <a href="<?php echo base_url();?>index.php/Advertise/newRoad">新建路况看板广告</a>
                  </div>
									<div class="accordion-inner">
										<a href="<?php echo base_url();?>index.php/Advertise/advertiseManage">广告管理</a>
									</div>
								</div>
							</div>
							<!-- 我的广告结束 -->

						<!-- 资金管理开始 -->
						<div class="accordion-group">
							<div class="accordion-heading">
								 <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-975558" href="#accordion-element-343422">资金管理</a>
							</div>
							<div id="accordion-element-343422" class="accordion-body in collapse">
								<div class="accordion-inner">
									<a href="<?php echo base_url();?>index.php/Amount/immediatelyPay">立即充值</a>
								</div>
								<div class="accordion-inner">
									<a href="<?php echo base_url();?>index.php/Amount/payRecord">充值记录</a>
								</div>
								<div class="accordion-inner">
									<a href="<?php echo base_url();?>index.php/Amount/flowRecord">资金明细</a>
								</div>
							</div>
						</div>
						<!-- 资金管理结束 -->

						<!-- 账户信息开始 -->
						<div class="accordion-group">
							<div class="accordion-heading">
								 <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-975558" href="#accordion-element-343424">账户信息</a>
							</div>
							<div id="accordion-element-343424" class="accordion-body in collapse">
								<div class="accordion-inner">
									<a href="<?php echo base_url();?>index.php/Account/enterpriseInfo">企业资料</a>
								</div>
								<div class="accordion-inner current">
									<a href="<?php echo base_url();?>/index.php/Account/changePassword">修改密码</a>
								</div>
							</div>
						</div>
						<!-- 账户信息开始 -->
					</div>
				</div>
			</div>
			<!-- 侧边nav结束 -->
		</div>
			
			<div class="help">
				<p>客服中心</p>
				<span>021-54972641</span>
			</div>
		</div>
		<form class="form-horizontal col-sm-10" id="changePasswordForm" role="form" method="post">
			<!-- 标题开始 -->
			<h5>修改密码</h5>
			<!-- 标题结束 -->
			<div class="form-group">
				
				<label for="trcNumber" class="col-sm-2 control-label"></label>
				<div class="col-sm-4">
					<p class="ajaxerror">修改密码失败,请稍后再试！</p>
				</div>
			</div>
			<div class="form-group">
				<label for="trcNumber" class="col-sm-2 control-label"><span>* </span>原密码</label>
				<div class="col-sm-4">
					<input type="password" class="form-control" name="old" id="old" placeholder="请填写原来的密码">
					<p class="passworderror">*原密码输入错误</p>
				</div>
			</div>

			<div class="form-group">
				<label for="trcNumber" class="col-sm-2 control-label"><span>* </span>请输入密码</label>
				<div class="col-sm-4">
					<input type="password" class="form-control" id="password" name="password" placeholder="6-20位字符，区分大小写">
				</div>
			</div>

			<div class="form-group">
				<label for="trcNumber" class="col-sm-2 control-label"><span>* </span>确认密码</label>
				<div class="col-sm-4">
					<input type="password" class="form-control" name="confirm_password" placeholder="请再次输入密码">
				</div>
			</div>
			<div class="form-group">
				<label for="trcNumber" class="col-sm-2 control-label"></label>
				<div class="col-sm-4">
				<input type="submit" value="确认修改" class="btn btn-success col-sm-4">
				</div>
			</div>
		</form>
	</div>
</div>	


	<!-- 遮罩层开始 -->
	<div id="fullbg"></div>
	<div id="dialog">
		<p>密码修改成功</p>
		<a href="<?php echo base_url();?>index.php/Servicer/login">重新登录</a>
	</div>
	<!-- 遮罩层结束 -->
	<script src="<?php echo base_url();?>public/js/jquery-1.11.2.min.js"></script>
	<script src="<?php echo base_url();?>public/js/bootstrap.min.js"></script>
	<script src="<?php echo base_url();?>public/js/jquery.validate.min.js"></script>
	<script src="<?php echo base_url();?>public/js/ChangPassword.js"></script>
</body>
</html>