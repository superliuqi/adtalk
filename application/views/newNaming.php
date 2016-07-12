<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="keywords" content="ADTALK,上海语镜,广告投放,车联网">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>新建冠名广告</title>
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
    <!-- 新建冠名广告开始 -->
    <form class="form-horizontal col-sm-10" id="advertiseForm" role="form" method="post" enctype="multipart/form-data">
      <h5>新建冠名广告</h5>
      <!-- 标题开始 -->
      <div class="form-group">
        <label for="trcNumber" class="col-sm-2 control-label"><span>* </span>广告标题</label>
        <div class="col-sm-5 advertiseTitle">
          <input type="text" id="advertiseTitle" class="form-control" name="header" placeholder="30字符以内" maxlength="30">
        </div>
      </div>
      <!-- 标题结束 -->

      <!-- 选择地区展示开始 -->
      <div class="form-group ">
        <label for="cocNumber" class="col-sm-2 control-label"><span>* </span>投放地区</label>
        <div class="col-sm-4 weekday selectArea">
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

      <!-- 广告位开始 -->
      <div class="form-group">
        <label for="cocNumber" class="col-sm-2 control-label"><span>* </span>广告位</label>
        <div class="col-sm-10 weekday">
          <input type="radio" name="adType" class="adType" id="roadVoice" value="1" data-value="">
          <label for="roadVoice">路况声音广告</label>
          <input type="radio" name="adType" class="adType" id="e-dog" value="2" data-value="">
          <label for="e-dog">电子狗LOGO广告</label>
          <input type="radio" name="adType" class="adType" id="yuLiao" value="3" data-value="">
          <label for="yuLiao">语聊LOGO广告</label>
          <input type="radio" name="adType" class="adType" id="tailedAdvert" value="4" data-value="">
          <label for="tailedAdvert">角标LOGO广告</label>
        </div>
      </div>
      <!-- 广告位结束 -->
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
      </div> -->
      <!-- 剩余库存结束-->
      
      <!-- 品牌名称开始 -->
      <div class="form-group">
        <label for="trcNumber" class="col-sm-2 control-label"><span>* </span>品牌名称</label>
        <div class="col-sm-5 brandName">
          <input type="text" class="form-control" id="brandName" placeholder="6字以内" maxlength="6" name="brandName">
        </div>
      </div>
      <!-- 品牌名称结束 -->
      <!-- 字体颜色开始 -->
      <div class="form-group bgColor none">
        <label for="trcNumber" class="col-sm-2 control-label"><span>* </span>字体颜色</label>
        <div class="col-sm-5">
          <input type="text" class="form-control" id="wordColor" placeholder="6字以内" name="wordColor" value="#FFF" disabled>
        </div>
        <div class="col-sm-1" id="pageColor"></div>
        <div class="col-sm-4">
          <a  href="javascript:;" onclick="colorSelect('wordColor','pageColor',event)">取色器</a>
        </div>
      </div>
      <!-- 字体颜色结束 -->
      <div class="headAdvert">
        <!-- 上传图标开始 -->          
        <div class="form-group">
        <label for="trcNumber" class="col-sm-2 control-label"><span>* </span>上传图标</label>
        <div class="col-sm-5">
           <p>支持jpg、jpeg、png格式的文件，大小不能超过5M</p>
          <input type="file" id="logo" class="fileSelect" name="logo" accept="image/*"/>
          <label for="logo" class="logoError"></label>
          <input type="hidden" id="logoURL">
          <img id="logoImg" src="<?php echo base_url(); ?>public/images/nopic.jpg">
          <div class="preview-pane-3 preview-pane none">
            <div class="preview-container">
              <img src="<?php echo base_url(); ?>public/images/nopic.jpg" class="target" alt=""/>
            </div>
          </div>
          <div class="preview-pane-4 preview-pane">
            <div class="circle">
            <div class="preview-container">
              <img src="<?php echo base_url(); ?>public/images/nopic.jpg" class="target" alt=""/>
            </div>
          </div>
          </div>
        </div>
      </div>
        <div class="form-group"><a class="col-sm-5 col-sm-offset-2 realEffect" href="##" data-toggle="modal" data-target="#preview">预览真实效果</a></div>
        <!-- 上传图标结束 -->
        <!-- 模态框开始 -->
        <div class="modal fade" id="preview" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                  &times;
                </button>
                <h4 class="modal-title" id="myModalLabel">预览真实效果</h4>
              </div>
              <div class="modal-body">
                <div class="adPositionId">
                  <h4>以下蓝色框内为广告位</h4>
                  <!--广告位开始 -->
                  <div class="roadLogo adBackground road ">
                    <div>
                      <img src="<?php echo base_url(); ?>public/images/nopic.jpg" class="previewLogo">
                    </div>
                  </div>
                  <!-- 广告位结束 -->
                  <!-- 角标LOGO广告开始 -->
                  <div class="markLogo none">
                  <h4>1）有实景路况时</h4>
                    <div class="mark markOne adBackground">
                      <div class="markBg">
                        <p class="markName"></p>
                        <div>
                          <img src="<?php echo base_url(); ?>public/images/nopic.jpg" class="previewLogo">
                        </div>
                      </div>
                    </div>
                    <h4>2）无实景路况时</h4>
                    <div class="mark markTwo adBackground">
                      <div class="roadCondition">
                        <p>此处还未有实景路况</p>
                        <div class="markBorder"><span class="markName"></span><span>赞助</span></div>
                        <p class="join">希望您的加入</p>
                      </div>
                      <div class="markBg">
                        <p class="markName"></p>
                        <div>
                          <img src="<?php echo base_url(); ?>public/images/nopic.jpg" class="previewLogo">
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- 角标LOGO广告结束 -->
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-info" data-dismiss="modal">关闭</button>
              </div>
            </div>
              <!-- /.modal-content -->
          </div>
        </div>
          <!-- 模态框结束 -->
        <!-- 上传图标结束 -->
      </div>
      <!-- 背景色开始 -->
      <div class="form-group bgColor none">
        <label for="trcNumber" class="col-sm-2 control-label"><span>* </span>背景色</label>
        <div class="col-sm-5">
          <input type="text" class="form-control" id="nowColor" placeholder="6字以内" name="nowColor" value="#F47923" disabled>
        </div>
        <div class="col-sm-1" id="pageColorViews"></div>
        <div class="col-sm-4">
          <a  href="javascript:;" onclick="colorSelect('nowColor','pageColorViews',event)">取色器</a>
        </div>
      </div>
      <!-- 背景色结束 -->
      <!-- 备注开始 -->
      <div class="form-group">
        <label for="trcNumber" class="col-sm-2 control-label">备注</label>
        <div class="col-sm-5 remark">
          <textarea name="remark" id="remark" cols="30" rows="9" class="form-control" placeholder="200字以内" maxlength="200"></textarea>
        </div>
      </div>
      <div class="form-group submit-title text-center"></div>
      <!-- 备注结束 -->
      <div class="footer">
        <input type="button" class="btn btn-success" value="提交审核" id="submit">
        <input type="button" class="btn btn-default none" value="保存" id="save">
      </div>
      <!-- 新建冠名广告结束 -->
    </form>
  </div>
  <input type="hidden" id="adID" value="<?php echo !empty($_GET['adID'])?$_GET['adID']:''?>"/>
  <input type="hidden" id="cityCodeList" value="1" />
  <input type="hidden" id="audioFile" value=""/>
  <input type="hidden" id="citymin" value="0"/>
  <input type="hidden" id="citymax" value="0"/>
  <input type="hidden" id="timemin" value="0"/>
  <input type="hidden" id="timemax" value="0"/>
  <input type="hidden" id="adShapePrice" value="0"/>
  <input type="hidden" size="4" id="x1" name="x1" />
  <input type="hidden" size="4" id="y1" name="y1" />
  <input type="hidden" size="4" id="x2" name="x2" />
  <input type="hidden" size="4" id="y2" name="y2" />
  <input type="hidden" size="4" id="w" name="w" />
  <input type="hidden" size="4" id="h" name="h" />
  
  <script src="<?php echo base_url();?>public/js/jquery-1.11.2.min.js"></script>
  <script src="<?php echo base_url();?>public/js/bootstrap.min.js"></script>
  <script src="<?php echo base_url();?>public/js/advertise.js"></script>
  <script src="<?php echo base_url();?>public/js/newNaming.js"></script>
  <script src="<?php echo base_url();?>public/js/colorPicker.js"></script>
  <script src="<?php echo base_url();?>public/js/jquery.validate.min.js"></script>
  <script src="<?php echo base_url();?>public/js/jquery.uploadPreview.js"></script>
  <script src="<?php echo base_url();?>public/js/jquery.Jcrop.min.js"></script>
  <script src="<?php echo base_url();?>public/js/jquery-labelauty.js"></script>
  <!--<script src="<?php echo base_url();?>public/js/lrz.all.bundle.js"></script>-->
</body>
</html>
