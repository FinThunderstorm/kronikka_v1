<?php
session_start();
$host = "host"; /* Host name */
$user = "user"; /* User */
$password = "pw"; /* Password */
$dbname = "db"; /* Database name */

$con = new mysqli($host, $user, $password,$dbname);
$con->set_charset("utf8mb4");
/*echo 'a';
echo 'a';*/
