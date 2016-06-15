<?php
class dbConn{
	private $mysql=null;
	private $con=new config();
	function connDB(){
		
		
		
		//always connect the Database with one link
		if (empty($this->mysql)){
			$this->mysql=new mysqli($con->mysqlAddress,$con->mysqlUserName,$con->mysqlPassword,$con->mysqlDbname);
		}
		return $this->mysql;
	}
	function sqlQuery($sql){
		//send the sql sentence as a function
		$con->testmodeEcho($sql, 'SqlSentence');
		return connDB()->query($sql);
	}
	
}