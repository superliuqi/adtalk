<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="keywords" content="ADTALK,上海语镜,广告投放,车联网">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>新建尾标广告</title>
  <link rel="stylesheet" href="<?php echo base_url();?>public/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?php echo base_url();?>public/css/jquery.Jcrop.min.css">
  <link rel="stylesheet" href="<?php echo base_url();?>public/css/advertise.css">
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
        <span>您好，<span><?php echo $email;?></span>欢迎回到adtalk！</span>
        <input type="hidden" value="<?php echo $userID;?>" id="userID">
        <a href="<?php echo base_url();?>index.php/Register/loginOut">退出</a>
      </div>
    </div>
  </div>
  <div></div>
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
                  <div class="accordion-inner newStart">
                    <a href="<?php echo base_url();?>index.php/Advertise/newAdvertise">新建开机广告</a>
                  </div>
                  <div class="accordion-inner newNaming">
                    <a href="<?php echo base_url();?>index.php/Advertise/newNaming">新建冠名广告</a>
                  </div>
                  <div class="accordion-inner newTrailer">
                    <a href="<?php echo base_url();?>index.php/Advertise/newTrailer">新建尾标广告</a>
                  </div>
                  <div class="accordion-inner newRoad">
                    <a href="<?php echo base_url();?>index.php/Advertise/newRoad">新建路况看板广告</a>
                  </div>
                  <div class="accordion-inner adManage">
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
      </div>
      <div class="help">
        <p>客服中心</p>
        <span>021-54972641</span>
      </div>
    </div>
    <!-- 侧边nav结束 -->
    <!-- 新建尾标广告开始 -->
    <form class="form-horizontal col-sm-10" id="advertiseForm" role="form" method="post" enctype="multipart/form-data">
      <h5>新建尾标广告</h5>
      <!-- 标题开始 -->
      <div class="form-group">
        <label for="trcNumber" class="col-sm-2 control-label"><span>* </span>广告标题</label>
        <div class="col-sm-5">
          <input type="text" id="advertiseTitle" class="form-control" name="header" placeholder="30字符以内" maxlength="30">
        </div>
      </div>
      <!-- 标题结束 -->

      <!-- 选择地区展示开始 -->
      <div class="form-group ">
        <label for="cocNumber" class="col-sm-2 control-label"><span>* </span>投放地区</label>
        <div class="col-sm-4 weekday">
          <input type="radio" name="area" id="nationwide" checked>
          <label for="nationwide">全国</label>
          <input type="radio" name="area" id="partialArea" data-toggle="modal" data-target="#myModal">
          <label for="partialArea">部分地区</label>
        </div>
      </div>
      <div class="form-group dn">
        <label for="cocNumber" class="col-sm-2 control-label"></label>
        <div class="col-sm-8 choose">选择的地区有:
        <span class="showChoose"></span>
        </div>
      </div>
      <!-- 选择地区展示结束 -->

      <!-- 模态框开始-部分地区 -->
      <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title" id="myModalLabel">投放地区</h4>
            </div>
            <div class="modal-body">
              <div class="choose"></div>
              <div class="area"></div>
              <div class="provincearea"></div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-success" id="modalBtn" data-dismiss="modal">确定</button>
            </div>
          </div><!-- /.modal-content -->
        </div><!-- /.modal -->
      </div>
      <!-- 模态框结束 -->

      <!-- 时间选择开始 -->
      <div class="form-group">
        <label for="cocNumber" class="col-sm-2 control-label"><span>* </span>投放时间段</label>
        <div class="col-sm-4 weekday">
          <input type="radio" name="genre" id="Unlimited" checked value="1">
          <label for="Unlimited">不限</label>
          <input type="radio" name="genre" id="weekday" value="2">
          <label for="weekday">自定义</label>
        </div>
      </div>
      <div class="form-group time">
        <label for="cocNumber" class="col-sm-2 control-label"></label>
        <dl class="col-sm-8" id="timeTitle"></dl>
        <dl class="dowebok col-sm-8 hidden" id="time"></dl>
      </div>

      <!-- <div  class="form-group">
        <label for="cocNumber" class="col-sm-2 control-label"></label>
        <div class="col-sm-10">
        <p>注:1.工作日与双休日的价格不同</p>
        <p>　 2.每个时段表示一个区间,如6:00表示06:00-06:59,广告播放时间在该区间任意时间。每一时段的播放频次上限为3次</p>
        </div>
      </div>
       -->
      <!-- 价格区间开始-->
      <div class="form-group">
        <label for="cocNumber" class="col-sm-2 control-label">价格区间</label>
        <div class="col-sm-4">
          <strong id="minPrice">0.00</strong>~<strong id="maxPrice">0.00</strong>元
          <button data-toggle="modal" data-target="#Price-list" type="button" id="details">查看明细</button>
        </div>
      </div>
      <!-- 价格区间结束-->

      <!-- 模态框开始 -->
      <div class="modal fade" id="Price-list" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                &times;
              </button>
              <h4 class="modal-title" id="myModalLabel">价格明细</h4>
            </div>
            <div class="modal-body">
              <dl id="city"></dl>
              <dl id="shape">
                <dt>广告形式</dt>
                <dd>
                  <p class="float-l" id="adType"></p>
                  <p class="float-r"><span id="adtypePrice"></span>元/条</p>
                </dd>
              </dl>
              <dl id="timeSlot"></dl>
            </div>
            <div class="modal-footer">
              <span>价格区间：0.00~0.00元/条</span>
              <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
            </div>
          </div>
            <!-- /.modal-content -->
        </div>
      </div>
        <!-- 模态框结束 -->
        
      <!-- 剩余库存开始-->
      <!-- <div class="form-group">
        <label for="cocNumber" class="col-sm-2 control-label">剩余库存</label>
        <div class="col-sm-4">
          <strong id="min">0.00</strong>~<strong>0.00</strong>条
        </div>
      </div>   -->
      <!-- 剩余库存结束-->
      
      <!-- 上传语音开始 -->          
      <div class="form-group voice">
        <label for="trcNumber" class="col-sm-2 control-label"><span>* </span>上传语音</label>
        <div class="col-sm-5">
          <p>支持MP3格式的音频，时间30秒以内，大小不能超过5M</p>
          <input id="upload" type="file" multiple="multiple" name="voices"/>
          <input type="hidden" id="audioFile" value=""/>
          <input type="hidden" id="fileSize" value=""/>
          <label for="upload" class="audioError"></label>
          <audio id="audio" controls="" style="display: none;" name="audio"></audio>
        </div>
      </div>
      <!-- 上传语音结束 -->
          
      <!-- 语音内容开始 -->
      <div class="form-group">
        <label for="trcNumber" class="col-sm-2 control-label"><span>* </span>语音内容</label>
        <div class="col-sm-5">
          <textarea name="audioContent" id="audioContent" cols="30" rows="10" class="form-control" placeholder="请完整的输入语音的内容" maxlength="400"></textarea>
        </div>
      </div>
      <!-- 语音内容结束 -->
      
       <!-- 备注开始 -->
      <div class="form-group">
        <label for="trcNumber" class="col-sm-2 control-label">备注</label>
        <div class="col-sm-5">
          <textarea name="remark" id="remark" cols="30" rows="9" class="form-control" placeholder="200字以内" maxlength="200"></textarea>
        </div>
      </div>
      <div class="form-group submit-title text-center"></div>
      <!-- 备注结束 -->
      <div class="footer">
        <input type="button" class="btn btn-success" value="提交审核" id="submit">
        <input type="button" class="btn btn-default none" value="保存" id="save">
      </div>
      <!-- 新建尾标广告结束 -->
    </form>
  </div>
  <input type="hidden" id="adID" value="<?php echo !empty($_GET['adID'])?$_GET['adID']:''?>"/>
  <input type="hidden" id="cityCodeList" value="1" />
  <input type="hidden" id="citymin" value="0"/>
  <input type="hidden" id="citymax" value="0"/>
  <input type="hidden" id="timemin" value="0"/>
  <input type="hidden" id="timemax" value="0"/>
  <input type="hidden" id="adShapePrice" value="0"/>
  
  <script src="<?php echo base_url();?>public/js/jquery-1.11.2.min.js"></script>
  <script src="<?php echo base_url();?>public/js/bootstrap.min.js"></script>
  <script src="<?php echo base_url();?>public/js/advertise.js"></script>
  <script src="<?php echo base_url();?>public/js/newTrailer.js"></script>
  <script src="<?php echo base_url();?>public/js/jquery.validate.min.js"></script>
  <script src="<?php echo base_url();?>public/js/jquery.uploadPreview.js"></script>
  <script src="<?php echo base_url();?>public/js/jquery.Jcrop.min.js"></script>
  <script src="<?php echo base_url();?>public/js/jquery-labelauty.js"></script>
  <!--<script src="<?php echo base_url();?>public/js/lrz.all.bundle.js"></script>-->
</body>
</html>
