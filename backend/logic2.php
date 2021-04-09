<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception; 
require('vendor/autoload.php');
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
use \Firebase\JWT\JWT;


class MyLogic2{
public $notify = array('Verified'=>'', 'Notverified'=>'', 'Email'=>'', 'Fundadded'=>'', 'Balance'=>'', 'Postgood'=>'', 'Ajopost'=>'', 'Notinvite'=>'', 'Invitesent'=>'', 'Emailnotfound'=>'', 'Allinvites'=>'', 'Myinvites'=>'', 'AlreadyInvited'=>'', 'Ajodetails'=>'', 'Accepted'=>'', 'AlreadyAccepted'=>'', 'AlreadyRejected'=>'', 'Rejected'=>'', 'Getaccepted'=>'');

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
$postAjo = $this->conn->query("INSERT INTO ajo_tb(user_id, title, describ, type, amount, duration, ajowallet, status, no_invites)VALUES ('$userid', '$title', '$describe','$type', '$amount','$duration','0', 'Pending','0')");
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
}else{
$checkAlreadyInvited = $this->conn->query("SELECT * FROM invite_tb WHERE invitee_id = '$myFetchedid' and ajo_id = '$ajoid'");
if($checkAlreadyInvited->num_rows > 0){
$notify['AlreadyInvited'] = 'bad';
}else {
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
$notify['Invitesent'] = 'good';

$thriftInvitee = $this->conn->query("INSERT INTO invite_tb(ajo_id, invitee_id, invitee_name, user_id, inviter_name, status)VALUES ('$ajoid', '$myFetchedid', '$myFetchedinvitee', '$userid', '$fetchedname', 'Pending')");
if($thriftInvitee){
$notify['Invitesent'] = 'good';
$codeSql = $this->conn->query("SELECT * FROM ajo_tb WHERE ajo_id = '$ajoid'");
if($codeSql->num_rows > 0){
$myFetcheddata = $codeSql->fetch_assoc();
$noofInvite =  $myFetcheddata['no_invites'];
$newnumber = $noofInvite + 1;
$noofInvites = $this->conn->query("UPDATE ajo_tb SET no_invites = '$newnumber' WHERE ajo_id = $ajoid");
}
}

} catch (Exception $e) {
echo 'error';
}

}
}

}
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

public function ajodetails($ajoId){
$checkId = $this->conn->query("SELECT * FROM ajo_tb WHERE ajo_id = '$ajoId'");
if($checkId ->num_rows > 0){
$myFetcheddata = $checkId->fetch_assoc();
$title =  $myFetcheddata['title'];
$describ = $myFetcheddata['describ'];
$type = $myFetcheddata['type'];
$amount = $myFetcheddata['amount'];
$duration = $myFetcheddata['duration'];
$ajodetails = array('title'=>$title, 'describ'=>$describ, 'type'=>$type, 'amount'=>$amount, 'duration'=>$duration);
$notify['Ajodetails'] = $ajodetails;
echo json_encode($notify);
}
}


public function acceptreq($inviteeid, $ajoId){
$checkStatus = $this->conn->query("SELECT * FROM invite_tb WHERE ajo_id = '$ajoId' and invitee_id = '$inviteeid'");
if($checkStatus ->num_rows > 0){
$myFetcheddata = $checkStatus->fetch_assoc();
$status =  $myFetcheddata['status'];
if($status == 'Pending'){
$checkId = $this->conn->query("UPDATE invite_tb SET status = 'Accepted' WHERE ajo_id = '$ajoId' and invitee_id = '$inviteeid'");
if($checkId){
$notify['Accepted'] = 'accepted';

}
}
elseif($status == 'Accepted'){
$notify['AlreadyAccepted'] = 'already';
}elseif($status == 'Rejected'){
$notify['AlreadyRejected'] = 'rejected';
}

echo json_encode($notify);
}  

}



public function rejectreq($inviteeid, $ajoId){
$checkStatus = $this->conn->query("SELECT * FROM invite_tb WHERE ajo_id = '$ajoId' and invitee_id = '$inviteeid'");
if($checkStatus ->num_rows > 0){
$myFetcheddata = $checkStatus->fetch_assoc();
$status =  $myFetcheddata['status'];
if($status == 'Pending'){
$checkId = $this->conn->query("UPDATE invite_tb SET status = 'Rejected' WHERE ajo_id = '$ajoId' and invitee_id = '$inviteeid'");
if($checkId){
$notify['Rejected'] = 'rejected';
}
}
elseif($status == 'Accepted'){
$notify['AlreadyAccepted'] = 'already';
}elseif($status == 'Rejected'){
$notify['AlreadyRejected'] = 'rejected';
}


}  
echo json_encode($notify);   
}

public function getaccepted($ajoId){
$x=[];
$check = $this->conn->query("SELECT * FROM invite_tb WHERE ajo_id = '$ajoId' and status = 'Accepted'");
if($check ->num_rows > 0){
 while($row = $check->fetch_array()){
 $x[] = $row;
  $notify['Getaccepted'] = $x; 
}
echo json_encode($notify);
}}


public function startajo($ajoId){
$checkInviter = $this->conn->query("SELECT * FROM ajo_tb WHERE ajo_id = '$ajoId'");
if($checkInviter->num_rows > 0){
$myFetcheddata = $checkInviter->fetch_assoc();
$status =  $myFetcheddata['status'];
$userid =  $myFetcheddata['user_id']; 
$amount =  $myFetcheddata['amount'];
if($status == "Pending"){
  $debitInviter = $this->conn->query("SELECT * FROM users_tb WHERE user_id = '$userid'"); 
if($debitInviter->num_rows > 0){
$myFetchedInviter = $debitInviter->fetch_assoc();
$balance = $myFetchedInviter['wallet'];
$deduct = $balance - $amount; 
$updateWallet = $this->conn->query("UPDATE users_tb SET wallet = '$deduct' WHERE user_id = '$userid'");
if($updateWallet){
  $check = $this->conn->query("SELECT * FROM invite_tb JOIN users_tb ON invite_tb.invitee_id = users_tb.user_id WHERE ajo_id = '$ajoId' and status = 'Accepted'");
  if($check ->num_rows > 0){
  $counter = 0;
  while($row = $check->fetch_array()){
  $balance = $row['wallet'];
  $userId = $row['invitee_id'];
  $deduct = $balance - $amount;
  $counter++;
  $checkWallet = $this->conn->query("UPDATE users_tb SET wallet = '$deduct' WHERE user_id = '$userId'");
}
if($checkWallet){
$noOfParticipant = $counter+1;
$ajobalance = $amount * $noOfParticipant; 
$this->conn->query("UPDATE ajo_tb SET status = 'Ongoing', ajowallet = '$ajobalance' WHERE ajo_id = '$ajoId'");
  }
}
  }
}
}else{
echo "started already";
}


}


}


// //  $notify['Getaccepted'] = $x;
//   // }
// }}
  // $y = array_column($x, 'wallet');
 
  // $z = $y - 1000;
// if($deduct->num_rows > 0){
// $myFetcheddata = $deduct->fetch_assoc();
// $wallet =  $myFetcheddata['wallet'];

// }
 

  
  // echo json_encode($notify); 

// $invitee=[];
// $userid=[];
// $check = $this->conn->query("SELECT * FROM invite_tb WHERE ajo_id = '$ajoId' and status = 'Accepted'");
// if($check ->num_rows > 0){ 
//   while($row = $check->fetch_array()){
// $invitee[] = $row;
// $checkId = $this->conn->query("SELECT * FROM users_tb WHERE user_id = 24");
// if($checkId->num_rows > 0){
//   echo 'good';
  // while($row = $checkId->fetch_array()){
  //   $userid[] = $row;
  //      $notify['Getaccepted'] = $userid;
  
  // echo json_encode($notify);





   // $notify['Getaccepted'] = $invitee ;
  


  


  }

?>