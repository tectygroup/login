<?php
class config{
	public $mysqlAddress='localhost';
	public $mysqlUserName='root';
	public $mysqlPassword='';
	public $mysqlDbName='test';
	public $testMode=FALSE;
	function testmodeEcho($string,$valueName){
		//if the site enter the test mode echo the given string
		if ($GLOBALS['testMode']==true){
			echo $valueName.':'.$string.'<br />';
	
		}
	}
}