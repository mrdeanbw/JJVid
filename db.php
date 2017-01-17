<?php
$username = "jvids_video";
$password = "RAC22@@";
$db = "jvids_vs_survey";


$link = mysql_connect("localhost", $username, $password);
mysql_select_db($db) or die(mysql_error());
mysql_query("SET NAMES 'utf8'");
?>