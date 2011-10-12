<?php

require("Constant.php");

$con = mysql_connect($host,$username,$password );
echo "con=$con<br>";
if (!$con)
  {
	 die('Could not connect: ' . mysql_error());
  }

$val = mysql_select_db("db_29df75ea") or die(mysql_error()); 
echo "val=$val<br>";
?>