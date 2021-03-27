<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception; 
require('vendor/autoload.php');
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
use \Firebase\JWT\JWT;


class MyLogic2{
public $notify = array('Verified'=>'', 'Notverified'=>'', 'Email'=>'', 'Fundadded'=>'', 'Balance'=>'', 'Postgood'=>'', 'Ajopost'=>'', 'Notinvite'=>'', 'Invitesent'=>'', 'Emailnotfound'=>'', 'Allinvites'=>'', 'Myinvites'=>'');

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



public function confirmemail($userId){
$x = [];
$emailSql = "SELECT * FROM users_tb WHERE user_id = '$userId'";
$checkemail = $this->conn->query($emailSql);
if($checkemail->num_rows > 0){
$myFetchedEmail = $checkemail->fetch_assoc();
$email =  $myFetchedEmail['email'];
$notify['Email'] = $email;
$balance = $myFetchedEmail['wallet'];
$notify['Balance'] = $balance;
}
$codeSql = "SELECT * FROM emailverify WHERE user_id = '$userId'";
$check = $this->conn->query($codeSql);
if($check->num_rows > 0){
while($row = $check->fetch_array()){
$x[] = $row; 
foreach($x as $p);
$status = $p['accstatus'];
if($status == 'verfied'){
$notify['Verified'] = 'good';


}else {
$notify['Notverified'] = 'bad';
}
}

}else{
$notify['Notverified'] = 'bad';   
}
echo json_encode($notify);
}

public function fundUserWallet($userid, $amount){
$checkSql = "SELECT * FROM users_tb WHERE user_id = '$userid'";
$check = $this->conn->query($checkSql);
if($check->num_rows > 0){
$myFetcheduser = $check->fetch_assoc();
$fetchedamount = $myFetcheduser['wallet'];
$updateAmount = $fetchedamount + $amount;
$updateSql = "UPDATE users_tb SET wallet = '$updateAmount' WHERE user_id = $userid";
$update = $this->conn->query($updateSql);
if($update){
$notify['Fundadded'] = 'good';
echo json_encode($notify);
}
}
}


public function ajoPost($userid, $title, $describe, $amount, $duration, $type, 
$member){
$postAjo = $this->conn->query("INSERT INTO ajo_tb(user_id, title, describ, type, amount, duration, ajowallet, status)VALUES ('$userid', '$title', '$describe','$type', '$amount','$duration','0', 'ongoing')");
if($postAjo){
$notify['Postgood'] = 'good';
echo json_encode($notify);
}
}

public function getmythrifts($userId){
$x = [];
$checkSql = "SELECT * FROM ajo_tb WHERE user_id = '$userId'";
$check = $this->conn->query($checkSql);
if($check->num_rows > 0){
while($row = $check->fetch_array()){
$x[] = $row;
$notify['Ajopost'] = $x;
}
} 
echo json_encode($notify);
}


public function thriftInvite($email, $userid, $ajoid){
$checkSql = "SELECT * FROM users_tb WHERE email = '$email'";
$check = $this->conn->query($checkSql);
if($check->num_rows > 0){
$myFetchedDetails = $check->fetch_assoc();
$myFetchedinvitee = $myFetchedDetails['lastname'];
$myFetchedid = $myFetchedDetails['user_id'];
$checkSendername = "SELECT * FROM users_tb WHERE user_id = '$userid'";
$checkname = $this->conn->query($checkSendername);
if($checkname->num_rows > 0){
$myFetcheduser = $checkname->fetch_assoc();
$fetchedname = $myFetcheduser['lastname'];
$fetchedemail = $myFetcheduser['email'];
if($email == $fetchedemail){
$notify['Notinvite'] = 'bad';
}
else {
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
$mail->addAddress($email, $myFetchedinvitee);    
$mail->addAddress($email);             

$mail->isHTML(true);                                  
$mail->Subject = 'Thrift Invitation';
$mail->Body    = 'Your Friend'.' '.$fetchedname.' '. 'invites you to join his/her thrift, check your invite section on your dashboard to accept or reject' ;
$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

$mail->send();
$thriftInvitee = $this->conn->query("INSERT INTO invite_tb(ajo_id, invitee_id, invitee_name, user_id, inviter_name, status)VALUES ('$ajoid', '$myFetchedid', '$myFetchedinvitee', '$userid', '$fetchedname', 'pending')");
if($thriftInvitee){
$notify['Invitesent'] = 'good';

}
$notify['Invitesent'] = 'good';

} catch (Exception $e) {
echo 'error';
}

}}
}else {
$notify['Emailnotfound'] = 'bad';
}
echo json_encode($notify);
}


public function getInvites($userId){
$x = [];
$checkId = "SELECT * FROM invite_tb WHERE invitee_id = '$userId'";
$check = $this->conn->query($checkId);
if($check->num_rows > 0){
while($row = $check->fetch_array()){
 $x[] = $row;
 $notify['Allinvites'] = $x;
}

echo json_encode($notify);
}

}

public function myInvites($userId){
$x = [];
$checkId = "SELECT * FROM invite_tb WHERE user_id = '$userId'";
$check = $this->conn->query($checkId);
if($check->num_rows > 0){
while($row = $check->fetch_array()){
$x[] = $row;
$notify['Myinvites'] = $x;
}

echo json_encode($notify);
}

}

}


?>