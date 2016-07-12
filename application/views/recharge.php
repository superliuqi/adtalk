<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="keywords" content="ADTALK,上海语镜,广告投放,车联网">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>充值</title>
	<link rel="stylesheet" href="<?php echo base_url();?>public/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo base_url();?>public/css/recharge.css">
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
				<input type="hidden" value="<?php echo $userID;?>" id="userID">
				<a href="<?php echo base_url();?>index.php/Register/loginOut">退出</a>
			</div>
		</div>
	</div>
	<div></div>
<div class="main">
	<div class="row wrap">
		<!-- <div class="notice">您的企业信息还未完善不能新建广告，为了让用户更好的了解您请先去<a href="javascript:;">完善信息</a></div> -->
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
							<div id="accordion-element-343422" class="accordion-body collapse in">
								<div class="accordion-inner current">
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
				<h4><strong>企业汇款</strong><small>　　　（目前只支持一种支付方式,到账期为1-4个工作日）</small></h4>
				<div class="in-amount">
					<div>
						<p class="amount-error">请输入正确的金额</p>	
						<input type="text" placeholder="请输入充值金额,100起" id="amount">
						<span>账户余额:￥<em id="userBalance"></em></span>
					</div>
					
					<button class="btn btn-danger" id="btn-code">确认并获得汇款识别码</button>
				</div>
				<div class="send-code">
					<div class="recharge-info">
						<p>充值金额：<span id="number"></span>元</p>
						<p>流水号：<span id="runCode"></span></p>
						<div>汇款识别码：<span class="remitIdentCode"></span>(汇款时需将识别码填写到汇款单)<button id="phoneCode">发送至手机</button></div> 
					</div>
					<div class="attention-rule">
						<p>注意事项:汇款时需要注意以下信息,请牢记！</p>
						<p>1.您的汇款识别码为:<span class="remitIdentCode red"></span>，线下公司转账需将此汇款识别码填写至电汇凭证的【汇款用途】栏内（仅填写此15位识别码）。</p>
						<p>2.请您在<span class="red">24小时内</span>汇清款项，汇款后请进入“充值记录”点击“确认已打款”进行汇款确认，否则24小时系统会取消充值申请。</p>
						<p>3.线下公司转账，一个识别码对应一个流水号和相应的金额，请勿多转账或者少转账。</p>
					</div>
					<div class="account-info">
						<p><strong>账户信息：</strong></p>
						<p><strong>户名：</strong>上海语镜汽车信息技术有限公司</p>
						<p><strong>账户：</strong>1001 2074 0920 6894 588 </p>
						<p><strong>开户行：</strong>中国工商银行上海市南京西路支行</p>
						<p><strong>汇款识别码：<span class="remitIdentCode"></span></strong></p>
					</div>
				</div>		
			</div>
			<!-- 主体内容结束 -->
		</div>
	</div>
	<script src="<?php echo base_url();?>public/js/jquery-1.11.2.min.js"></script>
	<script src="<?php echo base_url();?>public/js/bootstrap.min.js"></script>
	<script src="<?php echo base_url();?>public/js/recharge.js"></script>
</body>
</html>