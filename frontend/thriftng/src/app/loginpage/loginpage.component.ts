import { HttpClient } from '@angular/common/http';
import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { environment } from '../../../src/environments/environment';

@Component({
selector: 'app-loginpage',
templateUrl: './loginpage.component.html',
styleUrls: ['./loginpage.component.css']
})
export class LoginpageComponent implements OnInit {
public email = '';
public password = '';
public details = {};
public token = '';
public response = '';
public processing = false;
public newemail = '';
public newpassword = ''
public resetInfo = '';
public otp = "";
public newdetails = {};
public pwdchanged = '';
public otpwrong = '';
public otpexpire = '';
public dismiss = '';
public baseUrl = environment.baseUrl;

resetPassword() {
if (this.newemail == '') {
this.resetInfo = 'empty';
} else {
  this.http.post<any>(`${this.baseUrl}reset.php`, JSON.stringify(this.newemail)).subscribe(
data => {
if (data.CanReset) {
this.resetInfo = 'good';
localStorage.setItem('Email', this.newemail);
} else if (data.NotReset) {
this.resetInfo = 'bad';
}
}
)  
}

}

loginUser() {
if(this.email === '' ||  this.password === ''){
this.response = 'empty';
}else {
this.details = { email: this.email, password: this.password }
this.http.post<any>(`${this.baseUrl}login.php`, JSON.stringify(this.details)).subscribe(
data => {
this.token = data.Auth;
localStorage.setItem('Token', this.token);
if (data.LoginSuccess) {
this.processing = true;
setTimeout(() => this.processing = false, 1000)
setTimeout(() => this.router.navigate(['/dashboard']), 1000)
} else if (data.LoginFailed) {
this.processing = true;
setTimeout(() => this.processing = false, 1000)
setTimeout(() => this.response = 'bad', 1000)   
} else if(data.Empty){
this.response = 'empty'
}

})
}

}


updatePass() {
if (this.newpassword == '' || this.otp == '') {
this.otpexpire = 'expire';
} else {
let newpassword = JSON.stringify(this.newpassword);
let otp = JSON.stringify(this.otp);
let email = localStorage.Email;
this.http.post<any>(`${this.baseUrl}updatepass.php`, newpassword,{
headers: {
'Authorization': otp,
'Email' : email
}
}).subscribe(
data => {
if (data.PwdChanged) {
this.otpexpire = ''; 
this.pwdchanged = 'good';
localStorage.setItem('Email', email);  
this.otpwrong = '';
} else if (data.Otpwrong) {
this.pwdchanged = '';
this.otpwrong = 'wrong';
this.otpexpire = '';
} 




})
}

}


constructor(public http: HttpClient, public router: Router) { }

ngOnInit(): void {
localStorage.removeItem('Email');
}

}
