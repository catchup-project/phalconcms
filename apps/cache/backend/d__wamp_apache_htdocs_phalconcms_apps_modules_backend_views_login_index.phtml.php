<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<LINK rel="Bookmark" href="/favicon.ico" >
<LINK rel="Shortcut Icon" href="/favicon.ico" />
<!--[if lt IE 9]>
<script type="text/javascript" src="http://libs.useso.com/js/html5shiv/3.7/html5shiv.min.js"></script>
<script type="text/javascript" src="http://libs.useso.com/js/respond.js/1.4.2/respond.min.js"></script>
<script type="text/javascript" src="http://cdn.bootcss.com/css3pie/2.0beta1/PIE_IE678.js"></script>
<![endif]-->
<link href="/assets/backend/css/H-ui.css" rel="stylesheet" type="text/css" />
<link href="/assets/backend/css/H-ui.login.css" rel="stylesheet" type="text/css" />
<!--[if IE 6]>
<script type="text/javascript" src="js/DD_belatedPNG_0.0.8a-min.js" ></script>
<script>DD_belatedPNG.fix('*');</script>
<![endif]-->
<title>后台登录 - H-ui.admin v2.0</title>
<meta name="keywords" content="H-ui.admin v2.0,H-ui网站后台模版,后台模版下载,后台管理系统模版,HTML后台模版下载">
<meta name="description" content="H-ui.admin v2.0，是一款由国人开发的轻量级扁平化网站后台模板，完全免费开源的网站后台管理系统模版，适合中小型CMS后台系统。">
</head>
<body>
<input type="hidden" id="TenantId" name="TenantId" value="" />
<div class="header"></div>
<div class="loginWraper">
  <div id="loginform" class="loginBox">
    <form action="" method="post" >
      <div class="formRow user">
        <input id="" name="username" type="text" placeholder="账户" class="input_text input-big">
      </div>
      <div class="formRow password">
        <input id="" name="password" type="password" placeholder="密码" class="input_text input-big">
      </div>
      <div class="formRow yzm">
        <input name="code" class="input_text input-big" type="text" placeholder="验证码" onblur="if(this.value==''){this.value='验证码:'}" onclick="if(this.value=='验证码:'){this.value='';}" value="验证码:" style="width:150px;">
        <img src="<?=$this->url->get('admin/login/code')?>" id="code"> <a id="kanbuq" href="javascript:;">看不清，换一张</a> </div>
      <div class="formRow">
        <label for="online">
          <input type="checkbox" name="online" id="online" value="">
          使我保持登录状态</label>
      </div>
      <div class="formRow">
        <input name="" type="button" id="login-form" class="btn radius btn-success btn-big" value="&nbsp;登&nbsp;&nbsp;&nbsp;&nbsp;录&nbsp;">
        <input name="" type="reset" class="btn radius btn-default btn-big" value="&nbsp;取&nbsp;&nbsp;&nbsp;&nbsp;消&nbsp;">
      </div>
    </form>
  </div>
</div>
<div class="footer">H-ui网站后台模版 V2.0</div>
<script type="text/javascript" src="http://cdn.bootcss.com/jquery/2.1.3/jquery.min.js"></script>
<script type="text/javascript" src="/assets/backend/js/H-ui.js"></script>
<script type="text/javascript">
$("#kanbuq").click(function(){
	$("#code").attr('src','/admin/login/code?'+Math.random());
});
$("#login-form").click(function(){
	$.ajax({
		url:'/admin/login/index',
		type:'POST',
		dataType:"json",
		data:$("form").serialize(),
		success:function(data){
			if(data.status==1){
				window.location.href=data.url;
			}else{
				$("#code").prop("src","/admin/login/code");
				alert(data.msg);
			}
		}
	});
	
});
</script>
</body>
</html>