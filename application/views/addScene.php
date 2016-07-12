<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="keywords" content="ADTALK,上海语镜,广告投放,车联网">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>新建情景</title>
	<link rel="stylesheet" href="<?php echo base_url();?>public/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo base_url();?>public/css/advertise.css">
	<!--[if lt IE 9]>
      <script src="//cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
   <![endif]-->
</head>
<body>
<p><?php echo $content;?></p>
	<form action="<?php echo base_url();?>index.php/Scene/addsecene" method="post">
  <p>情景的名称: <input type="text" name="sceneName" /></p>
  <p>情景的价格: <input type="text" name="scenePrice" /></p>
  <input type="submit" value="Submit" />

</form>
</body>
</html>