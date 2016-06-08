<?php
require_once 'loginCore.php';

session_start();

if(isset($_SESSION['views']))
	$_SESSION['views']=$_SESSION['views']+1;

	else
		$_SESSION['views']=1;
		echo "Views=". $_SESSION['views'];
	

echo 111;
if (loginStatus()){
	
	echo 'you have logged in';
}
if (loginSecure()){
	echo 'you can do some sensitive action';
}
else {
	echo loginSecure();
}