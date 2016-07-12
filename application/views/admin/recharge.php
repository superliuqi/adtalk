<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>充值管理</title>

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
            <li><a href="<?php echo base_url();?>index.php/admin/Advertise/advertising"><i class="fa fa-table"></i> 广告管理</a></li>
            <!-- <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-caret-square-o-down"></i>广告管理<b class="caret"></b></a>
              <ul class="dropdown-menu">
                  <li><a href="<?php echo base_url();?>index.php/admin/Advertise/advertising">广告管理</a></li>
                  <li><a href="<?php echo base_url();?>index.php/admin/Advertise/accid">意外险广告</a></li>
              </ul>
            </li> -->
            <li class="active"><a href="<?php echo base_url();?>index.php/admin/Recharge/recharge"><i class="fa fa-font"></i> 充值管理</a></li>
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
              <li><a href="<?php echo base_url();?>index.php/admin/Bench/bench"><i class="fa fa-dashboard"></i>工作台</a></li>
              <li class="active"><i class="fa fa-table"></i>充值管理</li>
            </ol>
            <!--搜索区域开始--> 
            <div class="form-group">
                <form class="form-inline"  action="./excel" method="post">
                  <div class="form-group">
                    <label for="companyName" class="control-label">企业名称</label>
                    <input type="text" class="form-control" id="companyName" name="companyName">
                  </div>
                  <div class="form-group">
                    <label for="remitIdentCode" class="control-label">汇款识别码</label>
                    <input type="text" class="form-control" id="remitIdentCode" name="remitIdentCode">
                  </div>
                  <div class="form-group">
                    <label for="rechargeStatus" class="control-label">状态</label>
                    <select name="rechargeStatus" id="rechargeStatus" class="form-control" name="rechargeStatus">
                        <option value="">全部</option>
                        <option value="1">待审核</option>
                        <option value="2">审核通过</option>
                        <option value="3">审核失败</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="runCode" class="control-label">流水号</label>
                    <input type="text" class="form-control" id="runCode" name="runCode">
                  </div>
                  <div class="form-group">
                    <label for="createTime" class="control-label">充值时间</label>
                    <div class="input-group">
                      <input type="text" class="form-control" id="startTime" name="startTime" placeholder="请选择开始时间" readonly>
                      <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                    <div class="input-group">
                      <input type="text" class="form-control" id="endTime" name="endTime" placeholder="请选择结束时间" readonly>
                      <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                  </div>
                  <button class="btn" type="button" id="search">查询</button>
                  <button class="btn btn-success" type="submit">导出</button>
                </form>
            </div>
            <!--搜索区域结束-->
          </div>
        </div><!-- /.row -->

        <div class="row">
          <div class="col-lg-10">
            <div class="form-group">
              <table class="table table-bordered table-hover tablesorter" id="rechargeTB">
                <thead>
                  <tr>
                    <th>序号</th>
                    <th>流水号</th>
                    <th>汇款识别码</th>
                    <th>充值金额（元）</th>
                    <th>状态</th>
                    <th>充值时间</th>
                    <th>企业名称</th>
                    <th>原因</th>
                    <th>处理时间</th>
                    <th>操作</th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
            </div>
          </div>
      </div><!-- /#page-wrapper -->
    </div><!-- /#wrapper -->
    </div>
    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">处理申请</h4>
          </div>
          <div class="modal-body">
            <dl class="dl-horizontal">
              <dt>审核结果：</dt>
              <dd style="width:150px;">
                  <select class="form-control" name="checkStatus" id="checkStatus"><option value="2">已收款</option><option value="3">未收款</option></select>
                </dd>           
            </dl>
            <dl class="dl-horizontal hidden" id="reason">
              <dt>原因：</dt>
              <dd style="width:350px;">
                  <textarea class="form-control" id="resonContent" rows="3"></textarea>
                </dd>           
            </dl>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="userID" id="userID">
            <input type="hidden" name="runCodeH" id="runCodeH">
            <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
            <button type="button" class="btn btn-primary" id="submit">确认</button>
          </div>
        </div>
      </div>
    </div>
    <!-- JavaScript -->
    <script src="<?php echo base_url();?>public/admin/js/jquery-1.10.2.js"></script>
    <script src="<?php echo base_url();?>public/admin/js/bootstrap.js"></script>

    <!-- Page Specific Plugins -->
    <script src="<?php echo base_url();?>public/admin/js/jquery.dataTables.min.js"></script>
     <script src="<?php echo base_url();?>public/admin/js/dataTables.bootstrap.js"></script>
    <script src="<?php echo base_url();?>public/js/laydate.js"></script>
    <script src="<?php echo base_url();?>public/js/function.js"></script>
    <script src="<?php echo base_url();?>public/admin/js/recharge.js"></script>

  </body>
</html>