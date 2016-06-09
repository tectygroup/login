<?php
require_once 'loginCore.php';

if (loginStatus()){
	
	echo 'you have logged in<br />';
}
if (loginSecure()){
	echo 'you can do some sensitive action';
}
else {
	echo loginSecure();
}