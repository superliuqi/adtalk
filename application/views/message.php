<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="keywords" content="ADTALK,上海语镜,广告投放,车联网">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <title>adtalk注册完善信息</title>
        <link rel="stylesheet" href="<?php echo base_url();?>public/css/bootstrap.min.css">
        <script type="text/javascript" src="<?php echo base_url();?>public/js/respond.min.js"></script>
		<link rel="stylesheet" href="<?php echo base_url();?>public/css/message.css">
		<meta name="renderer" content="webkit">
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
					<span><?php echo $email;?></span>
					<input type="hidden" id="userID" value="<?php echo $userID;?>">
					<a href="<?php echo base_url();?>index.php/Register/loginOut">退出</a>
				</div>
			</div>
		</div>
		<div class="wrap">
			<ul class="step">
				<li class="col-md-4 bg">1.基本信息</li>
				<li class="col-md-4">2.邮箱激活</li>
				<li class="col-md-4 current">3.完善信息</li>
			</ul>
			<div class="main">
				<h5>公司信息登记</h5>
				<form class="form-horizontal" id="messageForm" role="form" action="<?php echo base_url();?>index.php/Register/detailInfo" method="post" 
enctype="multipart/form-data">
					<!-- 企业名称开始 -->
					<div class="form-group">
						<label for="companyName" class="col-sm-2 control-label">企业名称</label>
						<div class="col-sm-4">
							<input type="text" id="companyName" class="form-control" name="companyName">
							<p class="col-sm-14">需与营业执照上的名称完全一致，信息审核成功后，企业名称不可修改。</p>
						</div>
					</div>
					<!-- 企业名称结束 -->

					<!-- 营业执照开始 -->
					<div class="form-group">
						<label for="brNumber" class="col-sm-2 control-label">营业执照注册号</label>
						<div class="col-sm-4">
							<input type="text" class="form-control" id="brNumber" name="licenseNumber">
							<p class="col-sm-14">请输入15位营业执照注册号或18位的统一社会信用代码</p>
						</div>
					</div> 
					<!-- 营业执照结束 -->

					<!-- 组织机构代码证开始 -->
					<div class="form-group">
						<label for="cocNumber" class="col-sm-2 control-label">组织机构代码证号</label>
						<div class="col-sm-4">
							<input type="text" class="form-control" id="cocNumber" name="orgCode">
							<p class="col-sm-14">请输入组织机构代码证号</p>
						</div>
					</div>
					<!-- 组织机构代码证结束 -->

					<!-- 税务登记证开始 -->
					<div class="form-group">
						<label for="trcNumber" class="col-sm-2 control-label">税务登记证号</label>
						<div class="col-sm-4">
							<input type="text" class="form-control" id="trcNumber" name="registrationNumber">
							<p class="col-sm-14">请输入税务登记证号</p>
						</div>
					</div>
					<!-- 组织机构代码证结束 -->

					<!-- 行业开始 -->
					<div class="form-group">
						<label for="trade" class="col-sm-2 control-label">行业类别</label>
						<div class="col-sm-4">
							<!-- <input type="email" class="form-control" id="inputEmail3">
							<p class="col-sm-14">请输入行业类别</p> -->
							<select name="companyType" id="trade" class="col-sm-12 form-control">
						    	<option value=""  >请选择</option>
					            <option value="计算机硬件及网络设备"  >计算机硬件及网络设备</option>
					            <option value="计算机软件"  >计算机软件</option>
					            <option value="IT服务（系统/数据/维护）/多领域经营"  >IT服务（系统/数据/维护）/多领域经营</option>
					            <option value="互联网/电子商务"  >互联网/电子商务</option>
					            <option value="网络游戏"  >网络游戏</option>
					            <option value="通讯（设备/运营/增值服务）"  >通讯（设备/运营/增值服务）</option>
					            <option value="电子技术/半导体/集成电路"  >电子技术/半导体/集成电路</option>
					            <option value="仪器仪表及工业自动化"  >仪器仪表及工业自动化</option>
					            <option value="金融/银行/投资/基金/证券"  >金融/银行/投资/基金/证券</option>
					            <option value="保险"  >保险</option>
					            <option value="房地产/建筑/建材/工程"  >房地产/建筑/建材/工程</option>
					            <option value="家居/室内设计/装饰装潢"  >家居/室内设计/装饰装潢</option>
					            <option value="物业管理/商业中心"  >物业管理/商业中心</option>
					            <option value="广告/会展/公关/市场推广"  >广告/会展/公关/市场推广</option>
					            <option value="媒体/出版/影视/文化/艺术"  >媒体/出版/影视/文化/艺术</option>
					            <option value="印刷/包装/造纸"  >印刷/包装/造纸</option>
					            <option value="咨询/管理产业/法律/财会"  >咨询/管理产业/法律/财会</option>
					            <option value="教育/培训"  >教育/培训</option>
					            <option value="检验/检测/认证"  >检验/检测/认证</option>
					            <option value="中介服务"  >中介服务</option>
					            <option value="贸易/进出口"  >贸易/进出口</option>
					            <option value="零售/批发"  >零售/批发</option>
					            <option value="快速消费品（食品/饮料/烟酒/化妆品"  >快速消费品（食品/饮料/烟酒/化妆品</option>
					            <option value="耐用消费品（服装服饰/纺织/皮革/家具/家电）"  >耐用消费品（服装服饰/纺织/皮革/家具/家电）</option>
					            <option value="办公用品及设备"  >办公用品及设备</option>
					            <option value="礼品/玩具/工艺美术/收藏品"  >礼品/玩具/工艺美术/收藏品</option>
					            <option value="大型设备/机电设备/重工业"  >大型设备/机电设备/重工业</option>
					            <option value="加工制造（原料加工/模具）"  >加工制造（原料加工/模具）</option>
					            <option value="汽车/摩托车（制造/维护/配件/销售/服务）"  >汽车/摩托车（制造/维护/配件/销售/服务）</option>
					            <option value="交通/运输/物流"  >交通/运输/物流</option>
					            <option value="医药/生物工程"  >医药/生物工程</option>
					            <option value="医疗/护理/美容/保健"  >医疗/护理/美容/保健</option>
					            <option value="医疗设备/器械"  >医疗设备/器械</option>
					            <option value="酒店/餐饮"  >酒店/餐饮</option>
					            <option value="娱乐/体育/休闲"  >娱乐/体育/休闲</option>
					            <option value="旅游/度假"  >旅游/度假</option>
					            <option value="石油/石化/化工"  >石油/石化/化工</option>
					            <option value="能源/矿产/采掘/冶炼"  >能源/矿产/采掘/冶炼</option>
					            <option value="电气/电力/水利"  >电气/电力/水利</option>
					            <option value="航空/航天"  >航空/航天</option>
					            <option value="学术/科研"  >学术/科研</option>
					            <option value="政府/公共事业/非盈利机构"  >政府/公共事业/非盈利机构</option>
					            <option value="环保"  >环保</option>
					            <option value="农/林/牧/渔"  >农/林/牧/渔</option>
					            <option value="跨领域经营"  >跨领域经营</option>
					            <option value="其它"  >其它</option>
							</select>
						</div>
					</div>
					<!-- 行业结束 -->

					<!-- 所在地区开始 -->
					<div class="form-group">
						<label for="province" class="col-sm-2 control-label">所在地区</label>
						<div class="col-sm-2">
                        <!--省-->
                          <select class="form-control" id="province" name="province">
                          </select>
                        <!--市-->
                        </div>
                        <div class="col-sm-2">
                          <select id="city" class="form-control" name="city">
                          </select>
                        </div>
							<!-- <p class="col-sm-14">请输入所在地区</p> -->
					</div>
					<!-- 所在地区结束 -->

					<!-- 公司地址开始 -->
					<div class="form-group">
						<label for="address" class="col-sm-2 control-label">公司地址</label>
						<div class="col-sm-4">
							<input type="text" class="form-control" id="address" name="companyAddress">
							<p class="col-sm-14">请输入公司地址</p>
						</div>
					</div>
					<!-- 公司地址结束 -->
					<!-- 公司地址开始 -->
					<div class="form-group">
						<label for="telephone" class="col-sm-2 control-label">公司电话</label>
						<div class="col-sm-4">
							<input type="text" class="form-control" id="telephone" name="companyPhone">
							<p class="col-sm-14">请输入公司电话</p>
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
							<input type="text" class="form-control" id="operators" name="name">
							<p class="col-sm-14">请填写该公众帐号运营者的姓名，如果名字包含分隔号“·”，请勿省略。</p>
						</div>
					</div>
					<!-- 运营者结束 -->

					<!-- 身份证开始 -->
					<div class="form-group">
						<label for="IDnumber" class="col-sm-2 control-label">运营者身份证号</label>
						<div class="col-sm-4">
							<input type="text" class="form-control" id="IDnumber" name="IDNumber">
							<p class="col-sm-14">请输入运营者的身份证号码</p>
						</div>
					</div>
					<!-- 身份证结束 -->

					<!-- 手机号开始 -->
					<div class="form-group form-inline">
						<label for="phoneNumber" class="col-sm-2 control-label">运营者手机号码</label>
						<div class="col-sm-4">
							<input type="text" class="form-control" id="phoneNumber" name="telephone">
							<!-- <button class="btn">获取验证码</button> -->
							<input type="button" id="phoneCodebtn" value="获取验证码">
							<span class="glyphicon glyphicon-ok col-sm-2 codeSuccess" aria-hidden="true"></span>
							<label class="error codeError">验证码发送失败，请稍后再试</label>
							<p class="col-sm-14">请输入您的手机号码</p>
						</div>
					</div>
					<!-- 手机号结束 -->

					<!-- 验证码开始 -->
					<div class="form-group">
						<label for="code" class="col-sm-2 control-label">请输入验证码</label>
						<div class="col-sm-2 por">
							<input type="text" class="form-control" id="code" name="code">
							<span class="glyphicon glyphicon-ok col-sm-2 ok" aria-hidden="true"></span>
							<p class="col-sm-14">请输入验证码</p>
						</div>
					</div>
					<!-- 验证码结束 -->
						 <input type="hidden" name="userID" value="<?php echo $userID;?>" />
					<!-- 授权证明开始 -->
					<div class="form-group">
						<label for="prove" class="col-sm-2 control-label">上传运营者授权证明</label>
						<div class="col-sm-4 por">
							<p>请下载<a href="<?php echo base_url();?>public/file/authorize.docx">运营者授权证明</a>,上传加盖企业公章的原件照片或扫描件支持.jpg .jpeg .bmp .gif .png格式照片，大小不超过5M。</p>
							<input type="file" id="prove" class="fileSelect" name="prove">
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
							<input type="file" id="brPic" class="fileSelect" name="brPic">
						</div>
					</div>
					<!-- 营业执照结束 -->

					<!-- 组织结构代码开始 -->
					<div class="form-group">
						<label for="cocPic" class="col-sm-2 control-label">组织结构代码证</label>
						<div class="col-sm-4 por">
							<p>组织机构代码证必须在有效期范围内。格式要求：原件照片、扫描件或加盖公章的复印件支持.jpg .jpeg .bmp .gif .png格式照片，大小不超过5M。</p>
							<input type="file" id="cocPic" class="fileSelect" name="cocPic">
						</div>
					</div>
					<!-- 组织结构代码结束 -->

					<!-- 税务登记证开始 -->
					<div class="form-group">
						<label for="trcPic" class="col-sm-2 control-label">税务登记证</label>
						<div class="col-sm-4 por">
							<p>税务登记证税务登记证税务登记证税务登记证</p>
							<input type="file" class="fileSelect" id="trcPic" name="trcPic">
						</div>
					</div>
					<!-- 税务登记证结束 -->

					<!-- 企业基本资料结束 -->
					<div class="sub">
						<input type="submit" class="btn btn-success" id="submitBtn" value="提交信息" disabled="disabled">
						<a href="<?php echo base_url();?>index.php/Register/login">跳过完善信息，完成注册</a>
					</div>
				</div>
			</div>
				</form>
			</div>
		</div>
		<script src="<?php echo base_url();?>public/js/jquery-1.11.2.min.js"></script>
		<script src="<?php echo base_url();?>public/js/jquery.validate.min.js"></script>
		<script src="<?php echo base_url();?>public/js/jquery.uploadPreview.js"></script>
		<script src="<?php echo base_url();?>public/js/juicer.min.js"></script>
		<script src="<?php echo base_url();?>public/js/message.js"></script>
    </body>
</html>