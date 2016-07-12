<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="keywords" content="ADTALK,上海语镜,广告投放,车联网">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>资金明细</title>
	<link rel="stylesheet" href="<?php echo base_url();?>public/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>public/admin/css/jquery.dataTables.min.css">
	<link rel="stylesheet" href="<?php echo base_url();?>public/css/flowRecord.css">
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
	<div class="row wrap">
		<!-- <div class="notice">亲，余额不足，无法投放啦，现在就去<a href="javascript:;">充值</a></div> -->
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
								<div class="accordion-inner current">
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
		<div class="form-horizontal col-sm-10" id="advertiseForm" role="form" action="<?php echo base_url();?>index.php/Servicer/addadvertise" method="post" enctype="multipart/form-data" />
			<h5>资金明细</h5>
			<!-- 搜索输入框开始--> 
			<div class="searcharea">
				<div class="form-group">

					<label for="adID" class="col-sm-2 control-label">广告编号</label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control col-sm-10" id="adID" placeholder="输入广告编号">
                        <p class="col-sm-12"></p>
                    </div>
                    <label for="moneyType" class="col-sm-2 control-label">类型</label>
                    <div class="col-sm-3">
                    	<select class="form-control col-sm-10" id="moneyType">
                    		<option value="">全部</option>
                    		<option value="1">收入</option>
                    		<option value="2">支出</option>
                    	</select>
                        <p class="col-sm-12"></p>
                    </div>
        
                </div>
                <div class="form-group">
                    <label for="runCode" class="col-sm-2 control-label">流水号</label>
                	<div class="col-sm-3">
                        <input type="text" class="form-control col-sm-10" id="runCode" placeholder="请输入流水号">
                        <p class="col-sm-12"></p>
                    </div>
                    <label for="inputPassword4" class="col-sm-2 control-label"></label>
                    <div class="col-sm-3">
                    	<button class="btn btn-suceess" id="search">查询</button>
                    </div>
                </div>
            </div>
			<!-- 搜索输入框结束-->

			<!-- 广告列表开始 -->
			<div class="table-responsive">
			    <table class="table table-bordered" id="flowRecordTB">
			    	<thead>
			        	<tr class="title">
					        <th>序号</th>
					        <th>广告编号/流水号</th>
					        <th>类型</th>
					        <th>金额(元)</th>
					        <th>余额(元)</th>
					        <th>备注</th>
					        <th>时间</th>
			      		</tr>
			    	</thead>
			    	<tbody></tbody>
			  	</table>
			</div><!-- /.table-responsive -->
			<ul id="list"></ul>
			<!-- 广告列表结束 -->
			<div id="pagination"></div>
		</div>
	</div>
	<script type="text/javascript" src="<?php echo base_url();?>public/js/jquery-1.11.2.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>public/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>public/admin/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>public/admin/js/dataTables.bootstrap.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>public/js/function.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>public/js/flowRecord.js"></script>
</body>
</html>