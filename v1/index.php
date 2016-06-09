<?php
//to require the loggin Core feature
require 'loginCore.php';

function addValue($data,$value){
		if ($value!=null) {
			return $data.'value="'.$value.'"';
		}
		return $data;
}
function returnInputTable(){
	//this function is to ruturn the information input table
	//Use Global portal to get the data
	$Username=$GLOBALS['Username'];$Password=$GLOBALS['Password'];
	$inputTable='<form action="index.php" method="post">
	username:<input name="Username" type="text" ';
	//insert the Username while they are trying to sign up
	$inputTable=addValue($inputTable,$Username).'/><br />';
	$inputTable=$inputTable.'password:<input name="Password" type="password" ';
	//insert the Insered password
	$inputTable=addValue($inputTable,$Password).'/><br />';
	
	if ($Password!=null) {
		//use an not null value of password to confirm this is a sing up mode
		//add the correciton of password blank 
		$inputTable=$inputTable.'Confirm password:'.'<input name="ConfirmPassword" type="password" /><br />';
	}
	//finish the input blank
	$inputTable=$inputTable.'<input type="submit" name="submit"/></form>';
	return $inputTable;
}

function setSession($Username,$Password){
	// this function is to input the userdata into session after they login
	if (!loginStatus()){
		$_SESSION['Username']=$Username;
		$_SESSION['Password']=$Password;
	}
}

function login($Username,$Password){
	$result=loginCore($Username, $Password);
	//loginCore has 3 stage to use
	if ($result==1) {
		echo setSession($Username, $Password);
		return 'login successfully<br />
				<a href="testLogin.php">you have the right to visit the sensitive part</a>';
		
	}
	else {
		if ($result==2) {
			//because we use the GOLBAL as a portal, so we should clear to have our appearance.
			$GLOBALS['Password']='';
			return 'uncorrect password'.returnInputTable();
		}
		else {
			return 'you dont have an account, plesae create one<br />'.returnInputTable();
		}
	}
}
function signUp($Username,$Password,$ConfirmPassword){
	//do the signup with the condition of both password is equal and there is not this user in the system
	if ($ConfirmPassword!=$Password) {
		//the confirm password isn't correct
		$GLOBALS['loginCode']=12;
	}
	elseif (loginCore($Username, $Password)==0) {
		$mysql=connDB();
		$mysql->query('insert into user (username, password) values ("'.$Username.'", "'.encode($Password).'")');
		//login successfully
		$GLOBALS['loginCode']=20;
		return 'sign up successfully';
	}
	elseif ($GLOBALS['loginCode']==12){
		//The username have been used
		$GLOBALS['loginCode']=21;
	}
	else {
		$GLOBALS['loginCode']=23;
		return 'You can\'t force to sign up'.returnInputTable();
	}
}



function explainMessageCode(){
	//this function is to excute the explaintion of the login code and use show on the proper place
	if ($GLOBALS['loginCode']!=null) {
		$code=$GLOBALS['loginCode'];
		switch ($code) {
			case 10:return 'Login Successfully';break;
			case 11:return 'Please sign up.'; break;
			case 12:return 'Password incorrect'; break;
			case 13:return 'Unknown Error';break;
			case 14:return 'Please filling the blank';break;
			case 20:return 'Sign Up Successfully';break;
			case 21:return 'Username have been used.';break;
			case 22:return 'The two passwords doesn\'t same'; break;
			case 23:return 'Unknown Error';break;
			case 30:return 'Login Successfully';break;
			default:
				return 'Unkown Error';
			break;
		};
	}
}

if (getSession()==false){
	//if the user haven't login do this steps
	//the getSession will be false if the user haven't logged in
	@$GLOBALS['Password']=$_POST['Password'];
	@$GLOBALS['Username']=$_POST['Username'];
	@$GLOBALS['ConfirmPassword']=$_POST['ConfirmPassword'];
}
//the user may logged in and do these steps
if ($ConfirmPassword!=null) {
	//try to sign up
	if ($Password==null|$Username==null|$ConfirmPassword==null) {
		$GLOBALS['loginCode']=13;
		echo 'Please filling the blank<br />'.returnInputTable();
	}
	elseif($Password==$ConfirmPassword) {
		echo signUp($Username, $Password, $ConfirmPassword);
	}
}
else{
	//try to login
	if ($Password!=null&$Username!=null){
		echo login($Username, $Password);
	}
	elseif($Password==null&$Username==null)
	{
		//echo the table to input the login info
		echo returnInputTable() ;
	}elseif($Password==null|$Username==null){
		//the table haven't finish
		$GLOBALS['loginCode']=14;
		echo 'Plese filling the blank<br />'.returnInputTable();

	}
}
echo '<br />Explain Message:'.explainMessageCode().'<br />';
echo 'Explain Code:'.$GLOBALS['loginCode'].'<br />';
echo '<a href="logout.php">logout</a>';
?>