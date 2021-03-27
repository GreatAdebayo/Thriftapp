<?php
header("Access-Control-Allow-Origin: http://localhost:4200");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
require('vendor/autoload.php');
include 'logic.php';
$authorize = getallheaders()['Authorization'];
// echo $authorize;
// $val = trim(substr($authorize, 7));
$tmpLocation = $_FILES['myFile']['tmp_name'];
$filename = basename($_FILES['myFile']['name']);
$ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
$currentTime = time();
$arr = ['jpg', 'png', 'jpeg'];
$newMyLogic = new MyLogic;
$newMyLogic->uploadPics($tmpLocation, $filename, $ext, $currentTime, $arr, $authorize);
?>