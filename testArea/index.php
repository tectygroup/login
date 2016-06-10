<?php
function encode($Password,$salt=NULL){
	if ($salt==null) {
		$salt=bin2hex(openssl_random_pseudo_bytes(12));;
	}
	//this function is encode the password with salt
	//$saltLength=12;
	$Password=hash('sha256', $Password.$salt);
	$Password1=substr($Password, 0,21);
	$Password2=substr($Password, 21);
	$Password=$Password1.$salt.$Password2;
	echo $Password;
	return $Password;

}
function decode($code,$Password){
	//this function will return if the password is correct
	$salt=substr($Password, 21,24);
	encode($Password,$salt);
	if (encode($Password,$salt)==$code){
		return true;
	}
	return false;
}