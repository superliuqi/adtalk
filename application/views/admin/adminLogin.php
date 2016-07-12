<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="keywords" content="ADTALK,上海语镜,广告投放,车联网">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <title>adtalk登录</title>
         <link rel="stylesheet" href="<?php echo base_url();?>public/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="<?php echo base_url();?>public/admin/css/base.css">
    </head>
    <body>
        <!-- 后台管理登录开始 -->
        <div class="manage">
            <div class="main">
            <div class="login">
                <h2>Adtalk后台管理系统</h2>
                <hr/>
                <form class="form-horizontal" role="form" id="adminLoginForm" action="<?php echo base_url();?>index.php/admin/Login/doLogin" method="post">                     
                    <!-- 用户名或密码错误提示 -->
                    <div class="errorHint">
                        <span class="error">用户名或密码错误，请重新输入</span>
                            <!-- <input type="email" class="form-control" id="email" name="email" placeholder="输入邮箱"> -->
                    </div>
                    <!-- 用户名开始 -->
                    <div class="form-group por">                                        
                        <label for="name" class="col-sm-3 control-label">用户名:</label>
                        <div class="col-sm-7">                          
                            <input type="text" class="form-control" id="name" name="name" placeholder="请输入用户名">
                            <!-- <span class="glyphicon glyphicon-ok col-sm-2 ok" aria-hidden="true" style="display:inline-block"></span>    -->                      
                        </div>
                    </div> 
                    <!-- 用户名称结束 -->
                    <!-- 密码开始 -->
                    <div class="form-group">
                        <label for="password" class="col-sm-3 control-label">密码:</label>
                        <div class="col-sm-7">
                            <input type="password" class="form-control" id="password" name="password" placeholder="请输入密码">
                        </div>
                    </div>
                    <!-- 密码结束 -->
                    <!-- 登录开始 --> 
                    <div class="form-group register-btn">
                        <label for="password" class="col-sm-3 control-label"></label>
                        <div class="col-sm-7">
                            <input class="btn btn-lg form-control" id="loginBtn" type="submit" value="登录">
                        </div>
                    </div>
                    <!-- 登录结束 -->
                </form>
            </div>
            </div>
        </div>
        <!-- 后台管理登录结束 -->
        <script src="<?php echo base_url();?>public/js/jquery-1.11.2.min.js"></script>
        <script src="<?php echo base_url();?>public/js/jquery.validate.min.js"></script>
        <script src="<?php echo base_url();?>public/admin/js/adminlogin.js"></script>

    </body>
</html>