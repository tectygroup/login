<?php
//this file contain the basic function which provide the feature of login
session_start();

//to set up the varibles for protal in the programme
$Password='';
$Username='';
$ConfirmPassword='';
//loginCode is to store the code can be explain in the function and show the correct data on the right place.
$loginCode='';

//this is to set the test mode
$testMode=false;


function connDB(){
	//always connect the Database with one link
	if (empty($GLOBALS['GLOBALSmysql'])){
		$GLOBALS['GLOBALSmysql']=new mysqli('localhost','root','','test');
		return $GLOBALS['GLOBALSmysql'];
	}
	return $GLOBALS['GLOBALSmysql'];
}
function sqlQuery($sql){
	//send the sql sentence as a function
	testmodeEcho($sql, 'SqlSentence');
	return connDB()->query($sql);
}

function testmodeEcho($string,$valueName){
	//if the site enter the test mode echo the given string
	if ($GLOBALS['testMode']==true){
		echo $valueName.':'.$string.'<br />';
		
	}
}

function loginCore($Username, $Password){
	//loginCore has three different stage 0 is fail, 1 is successful, 2 is password incorrect
	//to serch the data if there has the user name and find the password
	$sql='Select password from user where username="'.$Username.'"';
	$result=sqlQuery($sql);
	$codePW=$result->fetch_array();
	if (!empty($codePW)){
		$codePW=$codePW['password'];
	}
	
	if (decode($codePW, $Password)){
		//login successfully
		//sent the login code
		$GLOBALS['loginCode']=10;
		return 1;
	}
	else{
		//login fail
		if ($result->num_rows==1){
			if($codePW==null){
				//this is third party user
				$GLOBALS['loginCode']=13;
				return 3;
			}
			//password is incorrect
			//log out in case some specific situation.
			logout();
			
			//return the login code
			$GLOBALS['loginCode']=12;
			return 2;
		}
		elseif($result->num_rows==0){
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
	$Password=$Password1.$salt.$Password2;
	return $Password;
	
}
function decode($code,$Password){
	//this function will return if the password is correct
	$salt=substr($code, 21,24);
	encode($Password,$salt);
	if (encode($Password,$salt)==$code){
		return true;
	}
	return false;
}