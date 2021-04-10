<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception; 
require('vendor/autoload.php');
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
use \Firebase\JWT\JWT;


class MyLogic2{
public $notify = array('Verified'=>'', 'Notverified'=>'', 'Email'=>'', 'Fundadded'=>'', 'Balance'=>'', 'Postgood'=>'', 'Ajopost'=>'', 'Notinvite'=>'', 'Invitesent'=>'', 'Emailnotfound'=>'', 'Allinvites'=>'', 'Myinvites'=>'', 'AlreadyInvited'=>'', 'Ajodetails'=>'', 'Accepted'=>'', 'AlreadyAccepted'=>'', 'AlreadyRejected'=>'', 'Rejected'=>'', 'Getaccepted'=>'', 'Filledup'=>'','Started'=>'', 'Startedalready'=>'', 'Insufficient'=>'', 'InsufficientInvitee'=>'','Walletempty'=>'','Paid'=>'', 'AlreadyPaid'=>'');

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


public function acceptreq($inviteeid, $ajoId, $duration){
$checkDuration = $this->conn->query("SELECT * FROM ajo_tb WHERE ajo_id = '$ajoId'");
if($checkDuration->num_rows > 0){
$myFetcheddata = $checkDuration->fetch_assoc();
$duration =  $myFetcheddata['duration'];
$noofPart =  $myFetcheddata['no_part'];
if($noofPart == $duration){
$notify['Filledup'] = 'filledup';
}else{
$checkStatus = $this->conn->query("SELECT * FROM invite_tb WHERE ajo_id = '$ajoId' and invitee_id = '$inviteeid'");
if($checkStatus ->num_rows > 0){
$myFetcheddata = $checkStatus->fetch_assoc();
$status =  $myFetcheddata['status'];
if($status == 'Pending'){
$checkId = $this->conn->query("UPDATE invite_tb SET status = 'Accepted' WHERE ajo_id = '$ajoId' and invitee_id = '$inviteeid'");
if($checkId){
$notify['Accepted'] = 'accepted';
$codeSql = $this->conn->query("SELECT * FROM ajo_tb WHERE ajo_id = '$ajoId'");
if($codeSql->num_rows > 0){
$myFetcheddata = $codeSql->fetch_assoc();
$noofpart =  $myFetcheddata['no_part'];
$newnumber = $noofpart + 1;
$noofParts = $this->conn->query("UPDATE ajo_tb SET no_part = '$newnumber' WHERE ajo_id = $ajoId");
}
}
}
elseif($status == 'Accepted'){
$notify['AlreadyAccepted'] = 'already';
}elseif($status == 'Rejected'){
$notify['AlreadyRejected'] = 'rejected';
}


}  
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
$balanceuser = $myFetchedInviter['wallet'];
if($amount > $balanceuser){
$notify['Insufficient'] = 'insufficient';
}else{
$check = $this->conn->query("SELECT * FROM invite_tb JOIN users_tb ON invite_tb.invitee_id = users_tb.user_id WHERE ajo_id = '$ajoId' and status = 'Accepted'");
if($check ->num_rows > 0){
$counter = 0;
while($row = $check->fetch_array()){
$balance = $row['wallet'];
$inviteeid = $row['invitee_id'];
$lastname = $row['lastname'];
$counter++;
if($amount > $balance){
$notify['InsufficientInvitee'] = 'insufficient';
}else{
$deduct = $balance - $amount;
$updateWallet = $this->conn->query("UPDATE users_tb SET wallet = '$deduct' WHERE user_id = '$inviteeid'");
$deductUser = $balanceuser - $amount;
$updateInviterWallet = $this->conn->query("UPDATE users_tb SET wallet = '$deductUser' WHERE user_id = '$userid'");
$updatetAll = $counter+1; 
$updater = $updatetAll*$amount;
$updateThriftWallet = $this->conn->query("UPDATE ajo_tb SET ajowallet = '$updater', status = 'Ongoing' WHERE ajo_id = '$ajoId'");
if($updateThriftWallet){
$notify['Started'] = 'started';
}
}
}
}
}
}

}else{
$notify['Startedalready'] = 'alreadystarted';
}

}

echo json_encode($notify);
}

public function pay($userid, $ajoid){
$check = $this->conn->query("SELECT * FROM invite_tb WHERE ajo_id = '$ajoid' and invitee_id = '$userid'");
$myFetcheddata = $check->fetch_assoc();
$paymentstatus = $myFetcheddata['payment'];
if($paymentstatus=='notpaid'){
$credit = $this->conn->query("SELECT * FROM ajo_tb WHERE ajo_id = '$ajoid'");
$myFetcheddata = $credit->fetch_assoc();
$balance = $myFetcheddata['ajowallet'];
if($balance == 0){
$notify['Walletempty'] = 'walletempty';
}else{
$userwallet = $this->conn->query("SELECT * FROM users_tb WHERE user_id = '$userid'");
$myFetchedwallet = $userwallet->fetch_assoc();
$wallet = $myFetchedwallet['wallet'];
$add = $wallet + $balance;
$updateuser = $this->conn->query("UPDATE users_tb SET wallet = '$add' WHERE user_id = '$userid'");
if($updateuser){
$updateajowallet = $this->conn->query("UPDATE ajo_tb SET ajowallet = 0 WHERE ajo_id = '$ajoid'");
$updateinvite = $this->conn->query("UPDATE invite_tb SET payment = 'paid' WHERE ajo_id = '$ajoid' and invitee_id = '$userid'");
$notify['Paid'] = 'paid';
}
}
}else {
$notify['AlreadyPaid'] = 'alreadypaid';
}
echo json_encode($notify);
}

public function payuser($userid, $ajoid){
$check = $this->conn->query("SELECT * FROM ajo_tb WHERE ajo_id = '$ajoid' and user_id = '$userid'");
$myFetcheddata = $check->fetch_assoc();
$paymentstatus = $myFetcheddata['payment'];
$balance = $myFetcheddata['ajowallet'];
if($paymentstatus=='notpaid'){
if($balance == 0){
$notify['Walletempty'] = 'walletempty';
}else{
$userwallet = $this->conn->query("SELECT * FROM users_tb WHERE user_id = '$userid'");
$myFetchedwallet = $userwallet->fetch_assoc();
$wallet = $myFetchedwallet['wallet'];
$add = $wallet + $balance;
$updateuser = $this->conn->query("UPDATE users_tb SET wallet = '$add' WHERE user_id = '$userid'");
if($updateuser){
$updateajowallet = $this->conn->query("UPDATE ajo_tb SET ajowallet = 0, payment = 'paid' WHERE ajo_id = '$ajoid'");
$notify['Paid'] = 'paid';
}
}
}else {
$notify['AlreadyPaid'] = 'alreadypaid';
}
echo json_encode($notify);
}



}

?>