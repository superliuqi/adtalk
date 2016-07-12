<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>广告管理</title>

    <!-- Bootstrap core CSS -->
    <link href="<?php echo base_url();?>public/css/bootstrap.min.css" rel="stylesheet">

    <!-- Add custom CSS here -->
    <link href="<?php echo base_url();?>public/admin/css/sb-admin.css" rel="stylesheet">
    <link href="<?php echo base_url();?>public/admin/css/jquery.dataTables.min.css"  rel="stylesheet">
    <link href="<?php echo base_url();?>public/admin/css/base.css" rel="stylesheet">
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
            <li><a href="<?php echo base_url();?>index.php/admin/Account/account"><i class="fa fa-table"></i> 账户管理</a></li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-caret-square-o-down"></i>广告管理<b class="caret"></b></a>
                <ul class="listContent">
                    <li><a href="<?php echo base_url();?>index.php/admin/Advertise/advertising">广告管理</a></li>
                    <li class="active"><a href="<?php echo base_url();?>index.php/admin/Advertise/accid">意外险广告</a></li>
                </ul>
            </li>
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
          <div class="row">
            <div class="col-lg-12">
              <ol class="breadcrumb">
                <li><a href="<?php echo base_url();?>index.php/admin/Bench/bench"><i class="fa fa-dashboard"></i>意外险广告管理</a></li>
                <li class="active"><i class="fa fa-table"></i>意外险详情</li>
              </ol>
              <!--搜索区域开始--> 
              <div class="form-group" id="adDetail">
                <h3 class="text-info">新建意外险内容</h3>
                <dl class="dl-horizontal">
                  <dt>赞助商名称：</dt>
                  <dd>...</dd>
                </dl>
                <dl class="dl-horizontal">
                  <dt>投放地区：</dt>
                  <dd>...</dd>
                </dl>
                <dl class="dl-horizontal">
                  <dt>赞助商图标：</dt>
                  <dd>...</dd>
                </dl>
                <dl class="dl-horizontal">
                  <dt>广告URL：</dt>
                  <dd>...</dd>
                </dl>
                <dl class="dl-horizontal">
                  <dt>备注：</dt>
                  <dd>...</dd>
                </dl>
              </div>
            <hr />
          <div class="form-group" id="checkDetial">
            <h3 class="text-info">内容审核</h3>
            <dl class="dl-horizontal">
            <dt>审核结果：</dt>
            <dd style="width:150px;">
                <select class="form-control" name="checkStatus" id="checkStatus"><option value="1">通过</option><option value="2">不通过</option></select>
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
              <!--搜索区域结束-->
            </div>
          </div><!-- /.row -->
      </div><!-- /#wrapper -->
    </div>
    <input type="hidden" id="adID" value="<?php echo $adID;?>">
    <input type="hidden" id="type" value="<?php echo $type;?>">
    <!-- JavaScript -->
    <script src="<?php echo base_url();?>public/admin/js/jquery-1.10.2.js"></script>
    <script src="<?php echo base_url();?>public/admin/js/bootstrap.js"></script>
    <!-- Page Specific Plugins -->
    <script src="<?php echo base_url();?>public/js/juicer.min.js"></script>
    <script src="<?php echo base_url();?>public/js/function.js"></script>
    <script src="<?php echo base_url();?>public/admin/js/accidDetail.js"></script>
  </body>
</html>