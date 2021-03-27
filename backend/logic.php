<?php 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception; 
require('vendor/autoload.php');
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
use \Firebase\JWT\JWT;


class MyLogic{
public $notify = array('Exists'=>'', 'Success'=>'', 'Failed'=>'', 'Code'=>'', 'Codefail'=>'', 'Emailnotexist'=>'', 
'Codevalid'=>'','Codeinvalid'=>'', 'Verified'=>'', 'LoginSuccess'=>'', 'LoginFailed'=>'', 'Empty'=>'', 'Auth'=>'',
'Lastname'=>'', 'Balance'=>'', 'Firstname'=>'', 'Middle'=>'', 'Phone'=>'', 'Dob'=>'', 'Email'=>'', 
'Gender'=>'', 'Upload'=>'', 'Notupload'=>'', 'Large'=>'', 'CanReset'=>'', 'NotReset'=>'', 'PwdChanged'=>'', 'Otpwrong'=>'', 'Otpexpired'=>'');

public function __construct(){
$servername = $_ENV['SERVERNAME'];
$username = $_ENV['USERNAME'];
$password =$_ENV['PASSWORD'];
$dbname = $_ENV['DBNAME'];
$this->conn = new mysqli($servername, $username, $password, $dbname); 
if(!$this->conn){
die();
}
}

public function createAccount($firstname, $lastname, $middlename, $dob, $address, $phone, 
$gender, $email, $password){
$aleadyExistsSql = "SELECT * FROM users_tb WHERE email = '$email' or phone = '$phone'";
$check = $this->conn->query($aleadyExistsSql);
if($check->num_rows > 0){
$notify['Exists'] = 'Email or phone number already exists';}
else{
$successSubmission = $this->conn->query("INSERT INTO users_tb(firstname, lastname, middlename, dob, address, phone, gender, email, password, wallet) 
VALUES ('$firstname', '$lastname', '$middlename', '$dob', '$address', '$phone', 
'$gender', '$email', '$password', '0')"); 
if($successSubmission){
$notify['Success'] = 'Account created, Click to verify your email';
}else{
$notify['Failed'] = 'Error';}   
}
echo json_encode($notify);

}

public function sendCode($email){
$code = rand();
$codeSql = "SELECT * FROM users_tb WHERE email = '$email'";
$check = $this->conn->query($codeSql);
if($check->num_rows > 0){
$myFetchedUser = $check->fetch_assoc();
$userId = $myFetchedUser['user_id'];
$name = $myFetchedUser['lastname'];
$codestatus = 'act';
$sendCode = $this->conn->query("INSERT INTO emailverify(user_id, vericode, status)VALUES ('$userId', '$code', '$codestatus')");
$mail = new PHPMailer(true);
try {
//Server settings
$mail->SMTPDebug = SMTP::DEBUG_SERVER;                     
$mail->isSMTP();                                           
$mail->Host       = 'smtp.gmail.com';                     
$mail->SMTPAuth   = true;                               
$mail->Username   = 'thriftappng@gmail.com';                    
$mail->Password   = 'Libertycity2020$';                              
$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         
$mail->Port       = 465;                                   
$mail->SMTPOptions = array(
'ssl' => array(
'verify_peer' => false,
'verify_peer_name' => false,
'allow_self_signed' => true
)
);
$mail->SMTPDebug = 0;
//Recipients
$mail->setFrom('thriftappng@gmail.com', 'Thriftapp.ng');
$mail->addAddress($email, $name);    
$mail->addAddress($email);             

$mail->isHTML(true);                                  
$mail->Subject = 'Confirmation code';
$mail->Body    = 'Your verification code is'.' '.$code;
$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

$mail->send();
$notify['Code'] = 'codesent';
} catch (Exception $e) {
$notify['Codefail'] = 'Code error';
}
if($sendCode){
$notify['Code'] = 'codesent';
}
}
echo json_encode($notify);
}


public function verifyCode($email, $code){
$x = [];
$veremailSql = "SELECT * FROM users_tb WHERE email = '$email'";
$check = $this->conn->query($veremailSql);
if($check->num_rows > 0){
$myFetchedUser = $check->fetch_assoc();
$fetchedUser = $myFetchedUser['user_id'];
$veruserSql = "SELECT * FROM emailverify WHERE user_id = '$fetchedUser'";
$checkUser = $this->conn->query($veruserSql);
if($checkUser->num_rows > 0){
while($row = $checkUser->fetch_array()){
$x[] = $row; 
foreach($x as $p);
$fetchedcode = $p['vericode'];
$fetchedaccstatus = $p['accstatus'];
if($fetchedaccstatus == 'verfied'){
$notify['Verified'] = 'Account already verified';
}
else if($code == $fetchedcode){
$notify['Codevalid'] = 'valid';
$ver = 'verfied';
$updateSql = "UPDATE emailverify SET accstatus = '$ver' WHERE vericode = $code";
$update = $this->conn->query($updateSql);
}else{
$notify['Codeinvalid'] = 'invalid';  
} 
}}
}
echo json_encode($notify);

}

public function resendCode($myemail){
$code = rand();
$codeSql = "SELECT * FROM users_tb WHERE email = '$myemail'";
$check = $this->conn->query($codeSql);
if($check->num_rows > 0){
$myFetchedUser = $check->fetch_assoc();
$userId = $myFetchedUser['user_id'];
$name = $myFetchedUser['lastname'];
$codestatus = 'act';
$sendCode = $this->conn->query("INSERT INTO emailverify(user_id, vericode, status)VALUES ('$userId', '$code', '$codestatus')");
$mail = new PHPMailer(true);
try {
//Server settings
$mail->SMTPDebug = SMTP::DEBUG_SERVER;                     
$mail->isSMTP();                                           
$mail->Host       = 'smtp.gmail.com';                     
$mail->SMTPAuth   = true;                               
$mail->Username   = 'thriftappng@gmail.com';                    
$mail->Password   = 'Libertycity2020$';                              
$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         
$mail->Port       = 465;                                   
$mail->SMTPOptions = array(
'ssl' => array(
'verify_peer' => false,
'verify_peer_name' => false,
'allow_self_signed' => true
)
);
$mail->SMTPDebug = 0;
//Recipients
$mail->setFrom('thriftappng@gmail.com', 'Thriftapp.ng');
$mail->addAddress($myemail, $name);    
$mail->addAddress($myemail);             

$mail->isHTML(true);                                  
$mail->Subject = 'Confirmation code';
$mail->Body    = 'Your verification code is'.' '.$code;
$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

$mail->send();
$notify['Code'] = 'codesent';
} catch (Exception $e) {
$notify['Codefail'] = 'Code error';
}
if($sendCode){
$notify['Code'] = 'codesent';
}else{
$notify['Codefail'] = 'Code error';
}
}else{
$notify['Emailnotexist'] = 'Email not found';
}
echo json_encode($notify);
}


public function loginUser($email, $password){
$notify['Empty'] = 'empty';
$userSql = "SELECT * FROM users_tb WHERE email = '$email'";
$checkEmail = $this->conn->query($userSql);
if($checkEmail->num_rows > 0){ 
$myFetcheduser = $checkEmail->fetch_assoc();
$fetchedpass = $myFetcheduser['password'];
$fetcheduserId = $myFetcheduser['user_id'];
$verifyPassword = password_verify($password, $fetchedpass);
if($verifyPassword){
$data = [
'iss'=>'localhost/4200',
'iat'=>time(),
'exp'=>time() + 1800,
'user'=>$fetcheduserId
];
$auth= JWT::encode($data, $_ENV['SECRET']);
$notify['Auth'] = json_encode($auth); 
$notify['LoginSuccess'] = 'success';  
}else{
$notify['LoginFailed'] = 'fail';}
}else{
$notify['LoginFailed'] = 'fail';
}
echo json_encode($notify);

}

public function getUserinfo($auth){
$decoded = JWT::decode($auth, $_ENV['SECRET'], array('HS256'));
$userId = $decoded->user;
$codeSql = "SELECT * FROM users_tb WHERE user_id = '$userId'";
$check = $this->conn->query($codeSql);
if($check->num_rows > 0){
$myFetcheduser = $check->fetch_assoc(); 
$lastname = $myFetcheduser['lastname'];
$balance = $myFetcheduser['wallet'];
$profile = $myFetcheduser['profilepic'];
$notify['Lastname'] = $lastname;
$notify['Balance'] = $balance;
$notify['Profilepic'] = $profile;
echo json_encode($notify);}
}


public function profile($auth){
$decoded = JWT::decode($auth, $_ENV['SECRET'], array('HS256'));
$userId = $decoded->user;
$codeSql = "SELECT * FROM users_tb WHERE user_id = '$userId'";
$check = $this->conn->query($codeSql);
if($check->num_rows > 0){
$myFetcheduser = $check->fetch_assoc();
$firstname = $myFetcheduser['firstname'];
$lastname = $myFetcheduser['lastname'];
$middlename = $myFetcheduser['middlename']; 
$mobile = $myFetcheduser['phone'];
$dob = $myFetcheduser['dob']; 
$email = $myFetcheduser['email'];
$gender = $myFetcheduser['gender'];
$profile = $myFetcheduser['profilepic'];
$notify['Firstname'] = $firstname;
$notify['Middle'] = $middlename;
$notify['Phone'] = $mobile;
$notify['Dob'] = $dob;
$notify['Email'] = $email;
$notify['Lastname'] = $lastname;
$notify['Gender'] = $gender;
$notify['Profilepic'] = $profile;
echo json_encode($notify);
} }



public function uploadPics($tmpLocation, $filename, $ext, $currentTime, $arr, $authorize){
$codeSql = "SELECT * FROM users_tb WHERE user_id = '$authorize'";
$check = $this->conn->query($codeSql);
if($check->num_rows > 0){ 
if($_FILES['myFile']['size']>5242880){
$notify['Large'] = 'large';
}else{
if(in_array($ext, $arr)){
$realFilename = $authorize."pics".$currentTime.".".$ext;
$finalLocation = "/Applications/XAMPP/xamppfiles/htdocs/thriftapp/backend/uploads/". $realFilename;
$checkUpload = move_uploaded_file($tmpLocation, $finalLocation);
if($checkUpload){
$updateSql = "UPDATE users_tb SET profilepic = '$realFilename' WHERE user_id = $authorize";
$update = $this->conn->query($updateSql);
if($update){
$notify['Upload'] = 'good';
}else{
$notify['Notupload'] = 'bad';
}      
}
}} }
echo json_encode($notify);
}



public function resetPassword($newemail){
$code = rand();
$checkEmail = "SELECT * FROM users_tb WHERE email = '$newemail'";
$check = $this->conn->query($checkEmail);
if($check->num_rows > 0){
$myFetchedUser = $check->fetch_assoc();
$userId = $myFetchedUser['user_id'];
$name = $myFetchedUser['lastname'];
$codestatus = 'act';
$sendCode = $this->conn->query("INSERT INTO info_change_tb(user_id, code, status, type)VALUES ('$userId', '$code', '$codestatus', 'pwd')");
$mail = new PHPMailer(true);
try {//Server settings
$mail->SMTPDebug = SMTP::DEBUG_SERVER;                     
$mail->isSMTP();                                           
$mail->Host       = 'smtp.gmail.com';                     
$mail->SMTPAuth   = true;                               
$mail->Username   = 'thriftappng@gmail.com';                    
$mail->Password   = 'Libertycity2020$';                              
$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         
$mail->Port       = 465;                                   
$mail->SMTPOptions = array(
'ssl' => array(
'verify_peer' => false,
'verify_peer_name' => false,
'allow_self_signed' => true
)
);
$mail->SMTPDebug = 0;
//Recipients
$mail->setFrom('thriftappng@gmail.com', 'Thriftapp.ng');
$mail->addAddress($newemail, $name);    
$mail->addAddress($newemail);             

$mail->isHTML(true);                                  
$mail->Subject = 'You just made an attempt to change your password';
$mail->Body    = 'OTP'.' '.$code;
$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

$mail->send();
$notify['CanReset'] = 'good';
} catch (Exception $e) {
$notify['Codefail'] = 'Code error';
}

} 
else{
$notify['NotReset'] = 'bad'; 
}
echo json_encode($notify);
}


public function updatePass($newpass, $newotp, $email){
$x = [];
$veremailSql = "SELECT * FROM users_tb WHERE email = '$email'";
$check = $this->conn->query($veremailSql);
if($check->num_rows > 0){
$myFetchedUser = $check->fetch_assoc();
$fetchedUser = $myFetchedUser['user_id'];
$veruserSql = "SELECT * FROM info_change_tb WHERE user_id = '$fetchedUser'";
$checkUser = $this->conn->query($veruserSql);
if($checkUser->num_rows > 0){
while($row = $checkUser->fetch_array()){
$x[] = $row; 
foreach($x as $p); 
$act = 'act';
$type = 'pwd';
if($newotp == $p['code'] and $p['status'] == $act and $p['type']== $type){
$updateSql = "UPDATE users_tb SET password = '$newpass' WHERE user_id = $fetchedUser";
$update = $this->conn->query($updateSql);
if($update){
$notify['PwdChanged'] = 'good';
$updateStatus = "UPDATE  info_change_tb SET status = 'used' WHERE code = $newotp";
$updatestat = $this->conn->query($updateStatus);
}
}else{
$notify['Otpwrong'] = 'wrong';
}

}

}
}
echo json_encode($notify);
}


  




}




?>