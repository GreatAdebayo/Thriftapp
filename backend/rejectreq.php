<?php
include 'logic2.php';
header("Access-Control-Allow-Origin: http://localhost:4200");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
$details = file_get_contents('php://input');
$mydetails = json_decode($details);
$inviteeid = $mydetails->inviteeid;
$ajoId =  $mydetails->ajoid;
$newMyLogic2 = new MyLogic2;
$newMyLogic2->rejectreq($inviteeid, $ajoId);
?>