<?php
//this file contain the basic function which provide the feature of login


//to set up the varibles for protal in the programme
$Password='';
$Username='';
$ConfirmPassword='';

function connDB(){
	if (empty($GLOBALS['GLOBALSmysql'])){
		$GLOBALS['mysql']=new mysqli('localhost','root','','test');
		return $GLOBALS['mysql'];
	}
	return $GLOBALS['GLOBALSmysql'];
}
function loginCore($Username, $Password){
	//loginCore has three different stage 0 is fail, 1 is successful, 2 is password incorrect
	//this funciton is to give the authority to the sensitive aciton
	$mysql=connDB();
	//to serch the data if there hase a match
	$result=$mysql->query('Select username from user where username="'.$Username.'" and password="'.encode($Password).'"');
	if ($result->num_rows==1){
		return TRUE;
	}
	else{
		$result=$mysql->query("select username from user where username='".$Username."'");
		$result=$result->num_rows;
		if ($result==1){
			//password is incorrect
			return 2;
		}
		else{
			return FALSE;
		}
	}
}
function loginStatus(){
	//give the authority to unsensitive action
	//like keep the login alive
	if (session_status()==2){
		return true;
	}
	return false;
}
function getSession(){
	//this function is to get the user info via GLOBAL var
	if ($status=loginStatus()) {
		$GLOBALS['Password']=$_Session['Password'];
		$GLOBALS['Username']=$_SESSION['Username'];
	}
	return $status;
}
function loginSecure() {
	//give the authority to the sensitive action.
	getSession();
	return loginCore($GLOBALS['Username'], $GLOBALS['Password']);
}
function logout(){
	// this function is to logout the system
	session_destroy();
}
function encode($Password,$salt=NULL){
	if ($salt==null) {
		$salt=bin2hex(openssl_random_pseudo_bytes(12));;
	}
	//this function is encode the password with salt
	//$saltLength=12;
	$Password=hash('sha256', $Password.$salt);
	$Password1=substr($Password, 0,21);
	$Password2=substr($Password, 21);
	return $Password1.$salt.$Password2;
}
function decode($code,$password){
	//this function will return if the password is correct
	$salt=substr($Password, 21,24);
	encode($password,$salt);
	if (encode($password,$salt)==$code){
		return true;
	}
	return false;
}