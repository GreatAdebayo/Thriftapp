<?php
include 'logic.php';
header("Access-Control-Allow-Origin: http://localhost:4200");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
$auth = json_decode(file_get_contents('php://input'));
$newMyLogic = new MyLogic;
$newMyLogic->getUserinfo($auth);
?>