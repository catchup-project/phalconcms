<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=7" />
		<style type="text/css">
		<!--
			*{ padding:0; margin:0; font-size:12px}
			a:link,a:visited{text-decoration:none;color:#0068a6}
			a:hover,a:active{color:#ff6600;text-decoration: underline}
			.showMsg{border: 1px solid #1e64c8; zoom:1; width:520px; height:172px;position:absolute;top:44%;left:50%;margin:-87px 0 0 -225px}
			.showMsg h5{background-image: (/assets/backend/images/msg.png);background-repeat: no-repeat; color:#fff; padding-left:35px; height:25px; line-height:26px;*line-height:28px; overflow:hidden; font-size:14px; text-align:left}
			.showMsg .content{ padding:46px 12px 10px 45px; font-size:15px; height:64px; text-align:left}
			.showMsg .bottom{ background:#e4ecf7; margin: 0 1px 1px 1px;line-height:26px; *line-height:30px; height:26px; text-align:center; font-size: 13px;}
			.showMsg .ok,.showMsg .guery{background: (/assets/backend/images/msg_bg.png) no-repeat 0px -560px ;}
			.showMsg .guery{ background-position: left -460px;}
		-->
		</style>
		<title>提示信息</title>
	</head>
	<body>
		<div class="showMsg" style="text-align:center">
			<h5>操作失败！</h5>
			<div class="guery" style="display:inline-block;display:-moz-inline-stack;zoom:1;*display:inline;max-width:330px;">操作失败</div>
			<div class="bottom">
				系统将在 <span style="color: blue; font-weight: bold" id="timer">3</span> 秒后自动跳转，如果不想等待，直接点击 <a href="">返回上一页</a> 。
				<script language="javascript">
					var second = 2;
					function redirect()	{
					//alert(second)
						if (second < 0)	{
							 history.go(-1);
						} else {
							if (navigator.appName.indexOf("Explorer") > -1) {
								document.getElementById('timer').innerText = second--;
							} else {
								document.getElementById('timer').textContent = second--;
							}
						}
					}
					setInterval(redirect, 1000);
    			</script>
				    		</div>
    	</div>
    </body>
</html>