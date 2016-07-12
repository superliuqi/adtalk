<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="keywords" content="ADTALK,上海语镜,广告投放,车联网">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>工作台</title>
	<link rel="stylesheet" href="<?php echo base_url();?>public/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo base_url();?>public/css/workbench.css">
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
				<span>您好,<span id="userID"></span>欢迎回到adtalk！</span>
				<a href="<?php echo base_url();?>index.php/Register/loginOut">退出</a>
			</div>
		</div>
	</div>
	<div></div>
<div class="main">
	<div class="row wrap">
		<div class="notice">您的企业信息还未完善不能新建广告，为了让用户更好的了解您请先去<a href="<?php echo base_url();?>index.php/Account/enterpriseInfo">完善信息</a></div>
		<div class="nav col-sm-2">
			<!-- 侧边nav开始 -->
			<div class="container-fluid">
			<div class="row-fluid">
				<div class="span12">
					<div class="accordion" id="accordion-975558">
						<!-- 工作台开始 -->
						<div class="accordion-group">
							<div class="accordion-heading current">
								 <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-975558" href="#accordion-element-343421">工作台</a>
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
								<div class="accordion-inner">
									<a href="<?php echo base_url();?>index.php/Account/changePassword">修改密码</a>
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
			<!-- 主体内容开始 -->
			<div class="media col-sm-10">
				<!-- 用户头像 -->
				<div class="media-left">
				    <img class="media-object" src="<?php echo base_url();?>public/images/u68.png">
				</div>
				<!-- 用户信息开始 -->
				<div class="media-body">
					<h4 class="media-heading" id="email"></h4>
					<a href="<?php echo base_url();?>index.php/Account/enterpriseInfo">企业资料</a>
					<i>|</i>
					<a href="<?php echo base_url();?>index.php/Account/changePassword">修改密码</a>
					<p class="loginTime">最近登录时间:<span></span></p>
				</div>
				<!-- 用户信息结束 -->

				<!-- 广告信息开始 -->
					<div class="row adver">
						<div class="col-sm-12">
							<div class="thumbnail adverNum col-sm-3">
								<div class="caption">
									<h3>广告数量</h3>
									<p><span id="advertisesum"></span>条</p>
									<p><a href="<?php echo base_url();?>index.php/Advertise/advertiseManage?adStatus=" class="btn btn-primary" role="button">了解更多></a></p>
								</div>
							</div>
							<div class="thumbnail col-sm-2">
								<div class="caption">
									<h3>待审核</h3>
									<p><span id="advertisewait"></span>条</p>
									<p><a href="<?php echo base_url();?>index.php/Advertise/advertiseManage?adStatus=0" class="btn btn-primary" role="button">查看详情</a></p>
								</div>
							</div>
							<div class="thumbnail col-sm-2">
								<div class="caption">
									<h3>待调整</h3>
									<p><span id="advertisefail"></span>条</p>
									<p><a href="<?php echo base_url();?>index.php/Advertise/advertiseManage?adStatus=2" class="btn btn-primary" role="button">查看详情</a></p>
								</div>
							</div>
							<div class="thumbnail col-sm-2" style="height:137px">
								<div class="caption">
									<h3>播放总条数</h3>
									<p><span id="adPlayCount" style="font-size:35px">3</span>条</p>
									<p></p>
								</div>
							</div>							
						</div>
					</div>
					<div class="row account">
						<div class="col-sm-12">
							<div class="thumbnail balance col-sm-3">
								<div class="caption">
									<h3>账户余额</h3>
									<p><span>￥</span><span id="amountfinal"></span></p>
									<p><a href="<?php echo base_url();?>index.php/Amount/immediatelyPay" class="btn btn-primary" role="button">立即充值</a></p>
								</div>
							</div>
							<div class="thumbnail col-sm-2">
								<div class="caption">
									<h3>待线下打款</h3>
									<p><span id="linePlayMoney"></span>条</p>
									<p><a href="<?php echo base_url();?>index.php/Amount/payRecord?rechargeStatus=0" class="btn btn-primary" role="button">查看详情</a></p>
								</div>
							</div>
							<div class="thumbnail col-sm-2">
								<div class="caption">
									<h3>待审核</h3>
									<p><span id="checkMoney"></span>条</p>
									<p><a href="<?php echo base_url();?>index.php/Amount/payRecord?rechargeStatus=1" class="btn btn-primary" role="button">查看详情</a></p>
								</div>
							</div>
							<div class="thumbnail col-sm-2">
								<div class="caption">
									<h3>充值失败</h3>
									<p><span id="payFail"></span>条</p>
									<p><a href="<?php echo base_url();?>index.php/Amount/payRecord?rechargeStatus=3" class="btn btn-primary" role="button">查看详情</a></p>
								</div>
							</div>
						</div>
					</div>
				<!-- 广告信息结束 -->

			</div>
			<!-- 主体内容结束 -->
		</div>
	</div>
	<script src="<?php echo base_url();?>public/js/jquery-1.11.2.min.js"></script>
	<script src="<?php echo base_url();?>public/js/function.js"></script>
	<script src="<?php echo base_url();?>public/js/bootstrap.min.js"></script>
	<script src="<?php echo base_url();?>public/js/workbench.js"></script>
</body>
</html>