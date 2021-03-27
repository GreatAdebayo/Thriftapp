<?php
include 'logic.php';
header("Access-Control-Allow-Origin: http://localhost:4200");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, Email,  X-Requested-With");
$password = json_decode(file_get_contents('php://input'));
$otp = getallheaders()['Authorization'];
$email = getallheaders()['Email'];
function trimInputs($val){
return trim($val);
}  
$newpass = trimInputs($password);
$newotp = trimInputs($otp);
$email = trimInputs($email);
$hashedPassword = password_hash($newpass, PASSWORD_DEFAULT);
$newMyLogic = new MyLogic;
$newMyLogic->updatePass($hashedPassword,$newotp, $email);
?>