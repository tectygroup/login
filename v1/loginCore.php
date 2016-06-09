<?php
//this file contain the basic function which provide the feature of login
session_start();

//to set up the varibles for protal in the programme
$Password='';
$Username='';
$ConfirmPassword='';
//loginCode is to store the code can be explain in the function and show the correct data on the right place.
$loginCode='';


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
	$codePW=encode($Password);
	$result=$mysql->query('Select username from user where username="'.$Username.'" and password="'.$codePW.'"');
	echo $codePW;
	if ($result->num_rows==1){
		//sent the login code
		$GLOBALS['loginCode']=10;
		return 1;
	}
	else{
		$result=$mysql->query("select password from user where username='".$Username."'");
		
		if ($result->num_rows==1){
			if($result->fetch_object()==null){
				//this is third party user
				$GLOBALS['loginCode']=13;
				return 3;
			}
			//password is incorrect
			//return the login code
			$GLOBALS['loginCode']=12;
			return 2;
		}
		else{
			//user haven't found
			$GLOBALS['loginCode']=11;
			return 0;
		}
	}
}
function loginStatus(){
	//give the authority to unsensitive action
	//like keep the login alive
	if (isset($_SESSION['Password'])&isset($_SESSION['Username'])){
		return true;
	}
	return false;
}
function getSession(){
	//this function is to get the user info via GLOBAL var
	if ($status=loginStatus()) {
		echo 1;
		$GLOBALS['Password']=$_SESSION['Password'];
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