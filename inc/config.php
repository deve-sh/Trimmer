<?php
session_start();
require('./inc/connect.php');
$appname = 'Trimmer';
$sub = 'trim_';
$dbhost = 'localhost';
$dbuser = 'userr';
$dbpass = 'password';
$dbname = 'test';
$db = new dbdriver();
$db->connect($dbhost,$dbuser,$dbpass,$dbname);
?>