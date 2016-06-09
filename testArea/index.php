<?php
session_start();
$_SESSION['var']=1;
echo 'you shou see on the <a href="secondPage.php">next page </a>of this '.$_SESSION['var'];

		
		