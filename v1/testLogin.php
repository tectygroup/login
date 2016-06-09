<?php
require_once 'loginCore.php';

if (loginStatus()){
	echo 'you have logged in<br />';
}
if (loginSecure()==1){
	echo 'you can do some sensitive action';
}
else {
	echo loginSecure();
}