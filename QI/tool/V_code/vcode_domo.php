<?php 
session_start();//启动会话
$code=$_POST["passcode"];
if($code == $_SESSION['VCODE'])
{
	echo 'ok';
}else{
	echo 'no';
}
?>
<html>
<form action="" method="post">
<input type="text" name="passcode" ><br/>
<div style="padding:0;margin:0;" onclick='this.innerHTML="<img src=code.php?r="+Math.random()+"></img>"'>
<img src="code.php"></img>
</div>
<input type="submit" >
</form>
</html>