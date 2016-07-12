<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="keywords" content="ADTALK,上海语镜,广告投放,车联网">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>adtalk注册页</title>
    <link rel="stylesheet" href="<?php echo base_url();?>public/css/bootstrap.min.css">
    <script type="text/javascript" src="<?php echo base_url();?>public/js/respond.min.js"></script>
    <link rel="stylesheet" href="<?php echo base_url();?>public/css/registers.css">
    <!--[if lt IE 9]>
      <script src="//cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
   <![endif]-->
</head>

<body>
    <div class="header">
        <div class="inheader">
            <h1>ADTALK</h1>
        </div>
    </div>
    <div class="main">
        <ul class="step">
            <li class="col-md-4 current">1.基本信息</li>
            <li class="col-md-4 bg">2.邮箱激活</li>
            <li class="col-md-4">3.完善信息</li>
        </ul>
        <div class="wrap1">
            <!-- 表单开始 -->
            <form id="registerForm" class="form-horizontal" role="form">
                <!-- 邮箱开始 -->
                <div class="form-group email">
                    <label for="inputEmail3" class="col-sm-2 control-label">邮箱</label>
                    <div class="col-sm-3 por">
                        <input type="email" id="email" class="form-control col-sm-10" name="email" placeholder="请填写未被adtalk注册的邮箱">
                        <span class="glyphicon glyphicon-ok col-sm-2 ok" aria-hidden="true"></span>
                        <p class="glyphicon glyphicon-remove">邮箱已注册</p>
                        <p class="col-sm-12"></p>
                    </div>
                </div>
                <!-- 邮箱结束 -->
                <!-- 密码开始 -->
                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-2 control-label">密码</label>
                    <div class="col-sm-3">
                        <input type="password" id="password" class="form-control col-sm-10" name="password" placeholder="字母数字英文符号，最短6位区分大小写">
                        <p class="col-sm-12"></p>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassword4" class="col-sm-2 control-label">确认密码</label>
                    <div class="col-sm-3">
                        <input type="password" id="confirm_password" class="form-control col-sm-10" name="confirm_password" placeholder="请再次输入密码">
                        <p class="col-sm-12"></p>
                    </div>
                </div>
                <!-- 密码结束 -->
                <!-- 验证码开始 -->
                <div class="form-group code">
                    <label for="inputCode" class="col-sm-2 control-label">验证码</label>
                    <div class="col-sm-1">
                        <input type="text" class="form-control" id="inputCode" name="inputCode">
                    </div>
                    <img src="">
                    <a href="javascript:;" id="changeCode">换一张</a>
                </div>
                <!-- 验证码结束 -->
                <!-- 用户协议开始 -->
                <div class="form-group agree">
                    <div class="col-sm-offset-2 col-sm-10">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="agreement" name="agreement"> 我同意并遵守<a href="<?php echo base_url();?>index.php/Register/adtalkService" target="_blank">《adtalk用户协议》</a>
                            </label>
                        </div>
                    </div>
                </div>
                <!-- 用户协议结束 -->
                <!-- 注册开始 -->
                <div class="form-group register-btn">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button class="btn col-sm-1 btn-success" id="registersBtn" disabled="disabled">注册</button>
                    </div>
                </div>
                <!-- 注册结束 -->
            </form>
            <div class="login">
                <p>已有adtalk帐号？<a href="<?php echo base_url();?>index.php/Register/login">立即登录</a></p>
            </div>
        </div>
        
        <!-- 邮箱激活开始 -->
        <div class="wrap2">
            <h4>激活adtalk账号</h4>
            <p>感谢注册！确认邮件已发送至你的注册邮箱 : <span id="userID">qdzhouyan@mirrtalk.com</span>。请进入邮箱查看邮件，并激活adtalk帐号。</p>
            <dl>
                <dt>没有收到邮件</dt>
                <dd>1、请检查邮箱地址是否正确，你可以返回<a href="">重新填写</a>。</dd>
                <dd>2、检查你的邮件垃圾箱</dd>
                <dd>3、若仍未收到确认，请尝试<a href="javascript:;" id="resend">重新发送</a></dd>
            </dl>
        </div>
        <!-- 邮箱激活结束 -->
    </div>
    <span class="glyphicon glyphicon-ok" id="resendSuccess" aria-hidden="true">重新发送成功</span>
    <script src="<?php echo base_url();?>public/js/jquery-1.11.2.min.js"></script>
    <script src="<?php echo base_url();?>public/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url();?>public/js/jquery.validate.min.js"></script>
    <script src="<?php echo base_url();?>public/js/registers.js"></script>
</body>

</html>
