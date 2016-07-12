<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="keywords" content="ADTALK,上海语镜,广告投放,车联网">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>新建广告</title>
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
    <div class="notice">您的企业信息还未完善不能新建意外险广告，为了让用户更好的了解您请先去<a href="<?php echo base_url();?>index.php/Account/enterpriseInfo">完善信息</a></div>
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
                  <div class="accordion-inner newRoad">
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
      </div>
      <div class="help">
        <p>客服中心</p>
        <span>021-54972641</span>
      </div>
    </div>
    <!-- 侧边nav结束 -->
    <form class="form-horizontal col-sm-10" id="accidForm" role="form" method="post" enctype="multipart/form-data">
      <!-- 新建意外险广告开始 -->
      <h5>新建意外险广告</h5>
      <!-- 赞助商名称开始 -->
      <div class="form-group">
        <label for="sponsor" class="col-sm-2 control-label"><span>* </span>赞助商名称</label>
        <div class="col-sm-5">
          <input type="text" class="form-control" id="sponsor" placeholder="赞助商名称" name="sponsorName">
        </div>
      </div>
      <!-- 赞助商名称结束 -->
      <!-- 投放地区开始 -->
      <div class="form-group ">
        <label for="cocNumber" class="col-sm-2 control-label"><span>* </span>投放地区</label>
        <div class="col-sm-4 weekday">
          <input type="radio" name="aiArea" id="aiNationwide" checked>
          <label for="aiNationwide">全国</label>
          <input type="radio" name="aiArea" id="aiPartialArea" data-toggle="modal" data-target="#myModal">
          <label for="aiPartialArea">部分地区</label>
        </div>
      </div>
      <div class="form-group dn">
        <label for="cocNumber" class="col-sm-2 control-label"></label>
        <div class="col-sm-8 choose">选择的地区有:
        <span></span>
        </div>
      </div>
      <!-- 投放地区结束 -->
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
      <!-- 赞助商图标开始 -->          
      <div class="form-group">
        <label for="sponsorLogo" class="col-sm-2 control-label"><span>* </span>赞助商图标</label>
        <div class="col-sm-10">
          <p>支持jpg、jpeg、png格式的文件，大小不能超过5M</p>
          <input type="file" id="sponsorLogo" class="fileSelect" name="sponsorLogo" />
          <label for="sponsorLogo" id="sponsorLogo-error" class="error"></label>
          <input type="hidden" id="sponsorLogoURL" name="sponsorLogo">
          <img id="sponsorLogoImg" src="<?php echo base_url(); ?>public/images/nopic.jpg">
          <div id="preview-pane">
            <div class="preview-container">
              <img src="<?php echo base_url(); ?>public/images/nopic.jpg" id="target" alt="正在加载中"/>
            </div>
          </div>
        </div>
         <!-- <button class="btn btn-success clippingBtn">确定</button> -->
      </div>
      <!-- 赞助商图标结束 -->
      <!-- 广告URL开始 -->
      <div class="form-group">
        <label for="adurl" class="col-sm-2 control-label"><span>* </span>广告URL</label>
        <div class="col-sm-5">
          <input type="url" class="form-control" id="adurl" placeholder="URL" name="adurl">
        </div>
      </div>
      <!-- 广告URL结束 -->
      <!-- 备注开始 -->
      <div class="form-group">
        <label for="trcNumber" class="col-sm-2 control-label">备注</label>
        <div class="col-sm-5">
          <textarea name="remark" id="remark" cols="30" rows="9" class="form-control" placeholder="200字以内" maxlength="200"></textarea>
        </div>
      </div>  
      <div class="form-group submit-title text-center"></div>
      <!-- 备注结束 -->
      <div class="form-group submit-title text-center"></div>
      <!-- 新建意外险广告结束 -->
      <div class="footer">
        <input type="button" class="btn btn-success" value="提交审核" id="submit">
        <!-- <input type="button" class="btn btn-default" value="暂时保存" id="save"> -->
      </div>
      <!-- 剪裁图片开始 -->
       <!--  <div class="none" id="picClipping">
       <div class="bg">
         <div class="picBody">
           <h3>剪裁图片</h3>
           <div class="">
             <img src="<?php echo base_url(); ?>public/images/nopic.jpg" id="target" alt="正在加载中"/>
           </div>
           <div class="col-sm-12">
             <input type="button" class="btn btn-success clippingBtn" value="确定">
           </div><img src="<?php echo base_url(); ?>public/images/nopic.jpg" id="target" alt="正在加载中"/>
         </div>
       </div>
             </div> -->
      <!-- 剪裁图片结束 -->
    </form>
    
    
  </div>
  <input type="hidden" id="cityCodeList" value="1" />
  <input type="hidden" id="audioFile" value=""/>
  <input type="hidden" id="citymin" value="0"/>
  <input type="hidden" id="citymax" value="0"/>
  <input type="hidden" size="4" id="x1" name="x1" />
  <input type="hidden" size="4" id="y1" name="y1" />
  <input type="hidden" size="4" id="x2" name="x2" />
  <input type="hidden" size="4" id="y2" name="y2" />
  <input type="hidden" size="4" id="w" name="w" />
  <input type="hidden" size="4" id="h" name="h" />
  
  <script src="<?php echo base_url();?>public/js/jquery-1.11.2.min.js"></script>
  <script src="<?php echo base_url();?>public/js/bootstrap.min.js"></script>
  <script src="<?php echo base_url();?>public/js/jquery.Jcrop.min.js"></script>
  <script src="<?php echo base_url();?>public/js/accidInsurance.js"></script>
  <script src="<?php echo base_url();?>public/js/jquery.validate.min.js"></script>
  <script src="<?php echo base_url();?>public/js/jquery.uploadPreview.js"></script>
  <script src="<?php echo base_url();?>public/js/jquery-labelauty.js"></script>
  <!--<script src="<?php echo base_url();?>public/js/lrz.all.bundle.js"></script>-->
</body>
</html>
