<?php
function encode($Password,$salt=NULL){
	if ($salt==null) {
		$salt=bin2hex(openssl_random_pseudo_bytes(12));;
		echo 'encode salt:'.$salt.'<br />';
	}
	//this function is encode the password with salt
	//$saltLength=12;
	$Password=hash('sha256', $Password.$salt);
	$Password1=substr($Password, 0,21);
	$Password2=substr($Password, 21);
	$Password=$Password1.$salt.$Password2;
// 	echo $Password;
	return $Password;

}
function decode($code,$Password){
	//this function will return if the password is correct
	//code is the var of the hashed password
	echo $salt=substr($code, 21,24);
	
	echo 'decode salt:'.$salt.'<br />';
	encode($Password,$salt);
	$decodePW=encode($Password,$salt);
	echo 'decodePW'.$decodePW.'<br />';
	if ($decodePW==$code){
		return true;
	}
	return false;
}
function connDB(){
	if (empty($GLOBALS['GLOBALSmysql'])){
		$GLOBALS['GLOBALSmysql']=new mysqli('localhost','root','','test');
		return $GLOBALS['GLOBALSmysql'];
	}
	return $GLOBALS['GLOBALSmysql'];
}
function sqlQuery($sql){
	//send the sql sentence as a function
	if ($GLOBALS['testMode']==true){
		echo $sql;
	}
	return connDB()->query($sql);
}

$Password=123;
echo $code=encode($Password).'<br />';
// echo $code;
if (decode($code, $Password)){
	echo 'the decode and encode part have no error';
}
