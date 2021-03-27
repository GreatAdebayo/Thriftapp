<?php
include 'logic2.php';
header("Access-Control-Allow-Origin: http://localhost:4200");
header("Content-Type: application/x-www-form-urlencoded");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
$ajopost = file_get_contents('php://input');
$ajo = json_decode($ajopost);
$userid = $ajo->userid;
function trimInputs($val){
return trim($val);
} 
$title = trimInputs($ajo->title);
$describe = trimInputs($ajo->describe);
$amount = trimInputs($ajo->amount);
$duration = trimInputs($ajo->duration);
$type = trimInputs($ajo->type);
$member = trimInputs($ajo->member);
$newMyLogic2 = new MyLogic2;
$newMyLogic2->ajoPost($userid, $title, $describe, $amount, $duration, $type, 
$member);

?>