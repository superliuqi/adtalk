<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>工作台</title>
    <!-- Bootstrap core CSS -->
    <link href="<?php echo base_url();?>public/css/bootstrap.min.css" rel="stylesheet">
    <!-- Add custom CSS here -->
    <link href="<?php echo base_url();?>public/admin/css/sb-admin.css" rel="stylesheet">
    <!--<link rel="stylesheet" href="<?php echo base_url();?>public/admin/font-awesome/css/font-awesome.min.css">-->
    <!-- Page Specific CSS -->
    <link rel="stylesheet" href="<?php echo base_url();?>public/admin/css/morris-0.4.3.min.css">
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
                    <li class="active"><a href="<?php echo base_url();?>index.php/admin/Bench/bench"><i class="fa fa-bar-chart-o"></i> 工作台</a></li>
                    <li><a href="<?php echo base_url();?>index.php/admin/Account/account"><i class="fa fa-table"></i> 账户管理</a></li>
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
            </div>
            <!-- /.navbar-collapse -->
        </nav>
    <!-- </div> -->
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1>工作台</h1>
                   <!--  <ol class="breadcrumb">
                       <li class="active"><i class="fa fa-dashboard"></i></li>
                   </ol> -->
                </div>
            </div>
            <div class="col-sm-12 btn-data">
                <button class="btn all" type="button">全部</button>
                <button class="btn today" type="button">今日</button>
                <button class="btn yesterday" type="button">昨日</button>
                <button class="btn last-7" type="button">最近7日</button>
                <button class="btn last-30" type="button">最近30日</button>
            </div>
            <!-- /.row -->
            <div class="Row">
                <div class="chunk">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="text-left">
                                    <p class="announcement-text">用户/企业数</p>
                                    <p class="announcement-heading" id="userCount"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="chunk">
                    <div class="panel panel-warning">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="text-left">
                                    <p class="announcement-text">广告数</p>
                                    <p class="announcement-heading" id="adCount"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="chunk">
                    <div class="panel panel-danger">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="text-left">
                                    <p class="announcement-text">投放广告数</p>
                                    <p class="announcement-heading" id="adPlayCount"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="chunk">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-6">
                                    <i class="fa fa-comments fa-5x"></i>
                                </div>
                                <div class="text-left">
                                    <p class="announcement-heading">56</p>
                                    <p class="announcement-text">广告点击数</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->
                <div class="chunk">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="text-left">
                                    <p class="announcement-text">投放金额</p>
                                    <p class="announcement-heading" id="money"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
            <div class="col-lg-3">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-clock-o"></i>投放广告数最高的地区(TOP10)</h3>
                    </div>
                    <div class="panel-body">
                        <div class="list-group" id="city">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-clock-o"></i>投放金额最高的地区(TOP10)</h3>
                    </div>
                    <div class="panel-body">
                        <div class="list-group" id="moneyCity">
                            <!-- <a href="#" class="list-group-item">
                                <span class="badge">just now</span>
                                <i class="fa fa-calendar"></i> Calendar updated
                            </a> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </div>
    <!-- /#page-wrapper -->
    <!-- /#wrapper -->
    <!-- JavaScript -->
    <script src="<?php echo base_url();?>public/admin/js/jquery-1.10.2.js"></script>
    <script src="<?php echo base_url();?>public/admin/js/bootstrap.js"></script>
    <!-- Page Specific Plugins -->
    <script src="<?php echo base_url();?>public/admin/js/raphael-min.js"></script>
    <script src="<?php echo base_url();?>public/js/juicer.min.js"></script>
    <script src="<?php echo base_url();?>public/admin/js/index.js"></script>
    <!--<script src="<?php echo base_url();?>public/admin/js/morris-0.4.3.min.js"></script>
    <script src="<?php echo base_url();?>public/admin/js/morris/chart-data-morris.js"></script>-->
    <!--<script src="<?php echo base_url();?>public/admin/js/tablesorter/jquery.tablesorter.js"></script>
    <script src="<?php echo base_url();?>public/admin/js/tablesorter/tables.js"></script>-->
</body>

</html>
