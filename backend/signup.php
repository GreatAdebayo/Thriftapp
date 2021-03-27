<?php
include 'logic.php';
header("Access-Control-Allow-Origin: http://localhost:4200");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
$users = json_decode(file_get_contents('php://input'));
function trimInputs($val){
return trim($val);
} 
$firstname = trimInputs($users->firstName);
$lastname = trimInputs($users->lastName);
$middlename = trimInputs($users->middleName);
$dob = trimInputs($users->dob);
$address = trimInputs($users->address);
$phone = trimInputs($users->phone);
$gender = trimInputs($users->gender);
$email = trimInputs($users->email); 
$password = trimInputs($users->password);
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$newMyLogic = new MyLogic;
$newMyLogic->createAccount($firstname, $lastname, $middlename, $dob, $address, $phone, 
$gender, $email, $hashedPassword);

?>