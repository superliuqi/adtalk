<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="keywords" content="ADTALK,上海语镜,广告投放,车联网">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>企业资料</title>
	<link rel="stylesheet" href="<?php echo base_url();?>public/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo base_url();?>public/css/companydata.css">
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
				<a href="<?php echo base_url();?>index.php/Register/loginOut">退出</a>
			</div>
		</div>
	</div>
	<div><input id="userID" type="hidden" value="<?php  echo $userID;?>"></div>
<div class="main">
	<div class="row wrap">
		<div class="notice">您的企业资料正在审核中。。。</div>
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
								<div class="accordion-inner current">
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
		</div>
		<!-- 侧边nav结束 -->
			
			<div class="help">
				<p>客服中心</p>
				<span>021-54972641</span>
			</div>
		</div>
		<form class="form-horizontal col-sm-10" id="messageForm" role="form" action="<?php echo base_url();?>index.php/Register/detailInfo" method="post" 
enctype="multipart/form-data">
			<!-- 标题开始 -->
			<h5>企业资料　　<span class="null">没有填写资料，请尽快填写以免影响正常使用</span></h5>
			<!-- 标题结束 -->
			<!-- 企业名称开始 -->
					<div class="form-group">
						<label for="companyName" class="col-sm-2 control-label">企业名称</label>
						<div class="col-sm-4">						
							<P id="companyName"></P>
						</div>
					</div>
					<!-- 企业名称结束 -->

					<!-- 营业执照开始 -->
					<div class="form-group">
						<label for="brNumber" class="col-sm-2 control-label">营业执照注册号</label>
						<div class="col-sm-4">
							<P id="licenseNumber"></P>
						</div>
					</div> 
					<!-- 营业执照结束 -->
					
					<!-- 组织机构代码证开始 -->
					<div class="form-group">
						<label for="cocNumber" class="col-sm-2 control-label">组织机构代码证号</label>
						<div class="col-sm-4">
							<P id="orgCode"></P>
						</div>
					</div>
					<!-- 组织机构代码证结束 -->

					<!-- 税务登记证开始 -->
					<div class="form-group">
						<label for="trcNumber" class="col-sm-2 control-label">税务登记证号</label>
						<div class="col-sm-4">
							<p id="registrationNumber"></p>
						</div>
					</div>
					<!-- 组织机构代码证结束 -->

					<!-- 行业开始 -->
					<div class="form-group">
						<label for="trade" class="col-sm-2 control-label">行业类别</label>
						<div class="col-sm-4">
							<p id="companyType"></p>
						</div>
					</div>
					<!-- 行业结束 -->

					<!-- 所在地区开始 -->
					<div class="form-group">
						<label for="province" class="col-sm-2 control-label">所在地区</label>
						<div class="col-sm-4">
                       		<p id="city"></p>
                        </div>
					</div>
					<!-- 所在地区结束 -->

					<!-- 公司地址开始 -->
					<div class="form-group">
						<label for="address" class="col-sm-2 control-label">公司地址</label>
						<div class="col-sm-4">
							<p id="companyAddress"></p>
						</div>
					</div>
					<!-- 公司地址结束 -->
					<!-- 公司地址开始 -->
					<div class="form-group">
						<label for="telephone" class="col-sm-2 control-label">公司电话</label>
						<div class="col-sm-4">
							<p id="companyPhone"></p>
						</div>
					</div>
					<!-- 公司地址结束 -->
					<div class="main operators">
				<h5>运营者信息登记</h5>
				<div class="form-horizontal" id="messageForm2" role="form">
					<!-- 运营者开始 -->
					<div class="form-group">
						<label for="operators" class="col-sm-2 control-label">运营者姓名</label>
						<div class="col-sm-4">							
							<p id="operators"></p>
							<p class="col-sm-14">请填写该帐号运营者的姓名，如果名字包含分隔号“·”，请勿省略。</p>
						</div>
					</div>
					<!-- 运营者结束 -->

					<!-- 身份证开始 -->
					<div class="form-group">
						<label for="IDnumber" class="col-sm-2 control-label">运营者身份证号</label>
						<div class="col-sm-4">							
							<p id="IDnumber"></p>
							<p class="col-sm-14">请填写运营者的身份证号码</p>
						</div>
					</div>
					<!-- 身份证结束 -->

					<!-- 手机号开始 -->
					<div class="form-group form-inline">
						<label for="phoneNumber" class="col-sm-2 control-label">运营者手机号码</label>
						<div class="col-sm-4">							
							<p id="phoneNumber"></p>
							<span class="glyphicon glyphicon-ok col-sm-2 codeSuccess" aria-hidden="true"></span>
							<label class="error codeError">验证码发送失败，请稍后再试</label>
							<p class="col-sm-14">请填写您的手机号码</p>
						</div>
					</div>
					<!-- 手机号结束 -->

					<!-- 验证码开始 -->
					<div class="form-group hidden phone-code">
						<label for="code" class="col-sm-2 control-label">请填写验证码</label>
						<div class="col-sm-2 por">
							<input type="text" class="form-control" id="code" name="code">
							<span class="glyphicon glyphicon-ok col-sm-2 ok" aria-hidden="true"></span>
							<p class="col-sm-14">请填写验证码</p>
						</div>
					</div>
					<!-- 验证码结束 -->
					<input type="hidden" name="userID" value="<?php echo $userID;?>" />
					<!-- 授权证明开始 -->
					<div class="form-group prove-file">
						<label for="prove" class="col-sm-2 control-label">上传运营者授权证明</label>
						<div class="col-sm-4 por">
							<p>请下载<a href="<?php echo base_url();?>public/file/authorize.docx">运营者授权证明</a>,上传加盖企业公章的原件照片或扫描件支持.jpg .jpeg .bmp .gif .png格式照片，大小不超过5M。</p>
							<input type="file" class="fileSelect" accept="image/*">
							<input type="hidden"  name="prove"/>
							<img src="" alt="" class="hidden" id="prove">
							<!-- <div id="localImag"><img id="preview" width=-1 height=-1 style="diplay:none" /></div> -->
						</div>
					</div>
					<!-- 授权证明结束 -->

					<!-- 企业基本资料开始 -->
					<h5>企业基本资料</h5>
					<!-- 营业执照开始 -->
					<div class="form-group">
						<label for="brPic" class="col-sm-2 control-label">企业工商营业执照</label>
						<div class="col-sm-4 por">
							<p>只支持中国大陆工商局或市场监督管理局颁发的工商营业执照，且必须在有效期内。格式要求：原件照片、扫描件或者加盖公章的复印件，支持.jpg .jpeg .bmp .gif .png格式照片，大小不超过5M。</p>
							<input type="file" class="fileSelect" accept="image/*">
							<input type="hidden"  name="brPic"/>
							<img src="" alt="" class="hidden" id="brPic">
						</div>
					</div>
					<!-- 营业执照结束 -->

					<!-- 组织结构代码开始 -->
					<div class="form-group">
						<label for="cocPic" class="col-sm-2 control-label">组织结构代码证</label>
						<div class="col-sm-4 por">
							<p>组织机构代码证必须在有效期范围内。格式要求：原件照片、扫描件或加盖公章的复印件支持.jpg .jpeg .bmp .gif .png格式照片，大小不超过5M。</p>
							<input type="file" class="fileSelect" accept="image/*">
							<input type="hidden"  name="cocPic"/>
							<img src="" alt="" class="hidden" id="cocPic">
						</div>
					</div>
					<!-- 组织结构代码结束 -->

					<!-- 税务登记证开始 -->
					<div class="form-group">
						<label for="trcPic" class="col-sm-2 control-label">税务登记证</label>
						<div class="col-sm-4 por">
							<p>支持jpg、jpeg、bmp、gif、png格式，大小不超过5M。</p>
							<input type="file" class="fileSelect" accept="image/*">
							<input type="hidden"  name="trcPic"/>
							<img src="" alt="" class="hidden" id="trcPic">
						</div>
					</div>
					<!-- 税务登记证结束 -->

					<!-- 企业基本资料结束 -->
					<div class="sub">
						<!-- <button class="btn btn-success" id="submitBtn" type="submit">提交信息</button> -->
						<input type="submit" class="btn btn-success" id="submitBtn" value="提交信息" disabled="disabled">
					</div>
				</div>
			</div>
		</form>
	</div>
</div>	

	<script src="<?php echo base_url();?>public/js/jquery-1.11.2.min.js"></script>
	<script src="<?php echo base_url();?>public/js/bootstrap.min.js"></script>
	<script src="<?php echo base_url();?>public/js/jquery.validate.min.js"></script>
	<script src="<?php echo base_url();?>public/js/function.js"></script>
	<script src="<?php echo base_url();?>public/js/juicer.min.js"></script>
	<script src="<?php echo base_url();?>public/js/companydata.js"></script>
</body>
</html>