<?php 
include_once('dbConn.class.php');
class login {
	function __constructor(){
		echo 1;
	}
	
	
	
}
class loginCore{
	//to set up the varibles for protal in the programme
	private $Password='';
	private $Username='';
	private $ConfirmPassword='';
	//loginCode is to store the code can be explain in the function and show the correct data on the right place.
	private $loginCode='';
	function __construct(){
		//this file contain the basic function which provide the feature of login
		session_start();
	}
	
	function login($Username, $Password){
		//loginCore has three different stage 0 is fail, 1 is successful, 2 is password incorrect
		//to serch the data if there has the user name and find the password
		$sql='Select password from user where username="'.$Username.'"';
		$result=sqlQuery($sql);
		$codePW=$result->fetch_array();
		$Crypt=new encrypt();
		if (!empty($codePW)){
			$codePW=$codePW['password'];
		}
	
		if ($Crypt->decodePassword($codePW, $Password)){
			//login successfully
			//sent the login code
			$this->loginCode=10;
			return 1;
		}
		else{
			//login fail
			if ($result->num_rows==1){
				if($codePW==null){
					//this is third party user
					$this->loginCode=13;
					return 3;
				}
				//password is incorrect
				//log out in case some specific situation.
				logout();
					
				//return the login code
				$this->loginCode=12;
				return 2;
			}
			elseif($result->num_rows==0){
				//user haven't found
				$this->loginCode=11;
				return 0;
			}
		}
	}
	//login status() method has abandon
	function isLogin(){
		//this function is to get the user info via GLOBAL var
		if (isset($_SESSION['Password'])&isset($_SESSION['Username'])) {
			$this->Password=$_SESSION['Password'];
			$this->Username=$_SESSION['Username'];
			return true;
		}
		return false;
	}
	function isloginInSecure() {
		//give the authority to the sensitive action.
		isLogin();
		return login($this->Username, $this->Password);
	}
	function logout(){
		// this function is to logout the system
		session_destroy();
	}
	
	
	
}
class encrypt{
	function encodePassword($Password,$salt=NULL){
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
	function decodePassword($code,$Password){
		//this function will return if the password is correct
		$salt=substr($code, 21,24);
		encode($Password,$salt);
		if (encode($Password,$salt)==$code){
			return true;
		}
		return false;
	}
	
}
?>