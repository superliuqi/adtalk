<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>账户管理</title>

    <!-- Bootstrap core CSS -->
    <link href="<?php echo base_url();?>public/css/bootstrap.min.css" rel="stylesheet">

    <!-- Add custom CSS here -->
    <link href="<?php echo base_url();?>public/admin/css/sb-admin.css" rel="stylesheet">
    <link href="<?php echo base_url();?>public/admin/css/jquery.dataTables.min.css"  rel="stylesheet">
    <link href="<?php echo base_url();?>public/admin/css/base.css?v=20160114" rel="stylesheet">
  </head>

  <body>
    <div id="wrapper">
      <!-- Sidebar -->
      <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="<?php echo base_url();?>index.php/admin/Bench/bench">Adtalk管理平台</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse navbar-ex1-collapse">
          <ul class="nav navbar-nav side-nav">
            <li><a href="<?php echo base_url();?>index.php/admin/Bench/bench"><i class="fa fa-bar-chart-o"></i> 工作台</a></li>
            <li class="active"><a href="<?php echo base_url();?>index.php/admin/Account/account"><i class="fa fa-table"></i> 账户管理</a></li>
            <li><a href="<?php echo base_url();?>index.php/admin/Advertise/advertising"><i class="fa fa-table"></i> 广告管理</a></li>
            <!-- <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-caret-square-o-down"></i>广告管理<b class="caret"></b></a>
              <ul class="dropdown-menu">
                  <li><a href="<?php echo base_url();?>index.php/admin/Advertise/advertising">广告管理</a></li>
                  <li><a href="<?php echo base_url();?>index.php/admin/Advertise/accid">意外险广告</a></li>
              </ul>
            </li> -->
            <li><a href="<?php echo base_url();?>index.php/admin/Recharge/recharge"><i class="fa fa-font"></i> 充值管理</a></li>
            <li><a href="bootstrap-elements.html"><i class="fa fa-desktop"></i> 交易记录</a></li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-caret-square-o-down"></i>报表管理<b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li><a href="<?php echo base_url();?>index.php/admin/Report/adCtatistics">广告统计</a></li>
                    <li><a href="<?php echo base_url();?>index.php/admin/Report/companyStatistics">企业统计</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-caret-square-o-down"></i>价格配置<b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li><a href="#">地区价格配置</a></li>
                    <li><a href="#">时间价格配置</a></li>
                </ul>
            </li>
        </ul>   
        <ul class="nav navbar-nav navbar-right navbar-user">
            <div class="personal">您好,
              <span class="userName"><?php echo $name;?></span>
              <span class="vertical">|</span>
              <a href="<?php echo base_url();?>index.php/admin/Login/loginOut" class="">退出</a>
            </div>
        </ul>   
        </div><!-- /.navbar-collapse -->
      </nav>

        <div id="page-wrapper">
          <div class="row accountDetail">
            <div class="col-lg-12">
              <ol class="breadcrumb">
                <li><a href="<?php echo base_url();?>index.php/admin/Bench/bench"><i class="fa fa-dashboard"></i>账户管理</a></li>
                <li class="active"><i class="fa fa-table"></i>查看详情</li>
              </ol>
              <!--账户信息开始--> 
              <div class="form-group col-sm-12" id="accountInfo">
                <h3 class="text-info">账户信息</h3>
                  <dl class="dl-horizontal col-sm-4">
                    <dt>邮箱：</dt>
                    <dd>...</dd>
                  </dl>
                  <dl class="dl-horizontal col-sm-4">
                    <dt>注册时间：</dt>
                    <dd>...</dd>
                  </dl>
              </div>
              <!--账户信息结束--> 
              <hr class="col-sm-12" />
              <!--企业资料开始--> 
               <div class="form-group" id="companyData">
                <h3 class="text-info">企业资料</h3>
                  <dl class="dl-horizontal col-sm-4">
                    <dt>企业名称：</dt>
                    <dd>...</dd>
                  </dl>
                  <dl class="dl-horizontal col-sm-4">
                    <dt>行业类别：</dt>
                    <dd>...</dd>
                  </dl>
                  <dl class="dl-horizontal col-sm-4">
                    <dt>公司电话：</dt>
                    <dd>...</dd>
                  </dl>
                  <dl class="dl-horizontal col-sm-4">
                    <dt>营业执照：</dt>
                    <dd>...</dd>
                    <dd><img src=""></dd>
                  </dl>
                  <dl class="dl-horizontal col-sm-4">
                    <dt>组织机构代码证：</dt>
                    <dd>...</dd>
                    <dd><img src=""></dd>
                  </dl>
                  <dl class="dl-horizontal col-sm-4">
                    <dt>税务登记证：</dt>
                    <dd>...</dd>
                    <dd><img src=""></dd>
                  </dl>
                  <dl class="dl-horizontal col-sm-4">
                    <dt>所在地区：</dt>
                    <dd>...</dd>
                  </dl>
                  <dl class="dl-horizontal col-sm-4">
                    <dt>公司地址:</dt>
                    <dd>...</dd>
                  </dl>
              </div>
              <!--企业资料开始--> 
              <hr class="col-sm-12"  />
              <!--运营者信息结束--> 
               <div class="form-group col-sm-12" id="operatorInfo">
                <h3 class="text-info">运营者信息</h3>
                  <dl class="dl-horizontal  col-sm-4">
                    <dt>运营者姓名：</dt>
                    <dd>...</dd>
                  </dl>
                  <dl class="dl-horizontal  col-sm-4">
                    <dt>运营者身份证号：</dt>
                    <dd>...</dd>
                  </dl>
                  <dl class="dl-horizontal  col-sm-4">
                    <dt>运营者手机号码：</dt>
                    <dd>...</dd>
                  </dl>
                  <dl class="dl-horizontal  col-sm-4">
                    <dt>运营者授权证明：</dt>
                    <dd><img src=""></dd>
                  </dl>
              </div>
              <!--运营者信息结束--> 
              <hr class="col-sm-12"  />
              <!--资料审核开始-->
               <div class="form-group col-sm-12" id="dataCheck">
                <h3 class="text-info">资料审核</h3>
                  <dl class="dl-horizontal">
                  <dt>审核结果：</dt>
                  <dd style="width:150px;">
                      <select class="form-control" name="checkStatus" id="checkStatus"><option value="2">通过</option><option value="3">不通过</option></select>
                    </dd>           
                </dl>
                <dl class="dl-horizontal hidden" id="reason">
                  <dt>原因：</dt>
                  <dd style="width:350px;">
                    <textarea class="form-control" id="resonContent" rows="3"></textarea>
                  </dd>           
                </dl>
                <p class="text-center">
                  <button style="width:150px;" id="submit" type="button" class="btn btn-primary btn-lg">确定</button>
                  <button style="width:150px;" type="button" class="btn btn-default btn-lg" onclick="history.back();">返回</button>
                </p>
              </div>
              <!--资料审核结束--> 
              <hr />
            </div>
          </div><!-- /.row -->
      </div><!-- /#wrapper --> 
    </div>
    <div class="hidden" id="overlay" >
      <div class="bg"></div>
      <div class="Pic">
          <img src="" class="bigPic">
      </div>
    </div>
    <input type="hidden" id="userID" value="<?php echo $userID;?>">
    <input type="hidden" id="type" value="<?php echo $type;?>">
    <!-- JavaScript -->
    <script src="<?php echo base_url();?>public/admin/js/jquery-1.10.2.js"></script>
    <script src="<?php echo base_url();?>public/admin/js/bootstrap.js"></script>
    <!-- Page Specific Plugins -->
    <script src="<?php echo base_url();?>public/js/juicer.min.js"></script>
    <script src="<?php echo base_url();?>public/js/function.js"></script>
    <script src="<?php echo base_url();?>public/admin/js/accountDetail.js?v=20160114"></script>
  </body>
</html> 