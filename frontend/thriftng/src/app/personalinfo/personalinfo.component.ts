import { HttpClient } from '@angular/common/http';
import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { Router } from '@angular/router';
import { environment } from '../../../src/environments/environment';
@Component({
selector: 'app-personalinfo',
templateUrl: './personalinfo.component.html',
styleUrls: ['./personalinfo.component.css']
})
export class PersonalinfoComponent implements OnInit {
public userForm: FormGroup;
public processing = false;
public notvalid = false;
public exists = '';
public showexists = false;
public success = '';
public showsuccess = false;
public dbemail = '';
public verifyCodeSent = '';
public baseUrl = environment.baseUrl;


    


constructor(public fb: FormBuilder, public http: HttpClient, public router: Router){
this.userForm = this.fb.group({
firstName: ['', [Validators.required]],
lastName: ['', [Validators.required]],
middleName: ['', [Validators.required]],
dob: ['', [Validators.required]],
address: ['', [Validators.required]],
phone: ['', [Validators.required, Validators.minLength(11)]],
gender: ['', [Validators.required]],
email: ['', [Validators.required]],
password: ['', [Validators.required]]
})
}


firstForm() {

if (this.userForm.valid) {
this.notvalid = false;
this.processing = true;
this.showexists = false;
let userDetails = this.userForm.value;
let email = userDetails.email
this.http.post<any>(`${this.baseUrl}signup.php`, JSON.stringify(userDetails)).subscribe(
data => {
this.exists = data.Exists;
this.success = data.Success;
if (this.exists) {
setTimeout(() => this.processing = false, 2000)
setTimeout(() => this.showexists = true, 2000)
}
else if (this.success) {
localStorage.setItem('Email', email);
setTimeout(() => this.processing = false, 2000)
setTimeout(() => this.showsuccess = true, 2000)  
}
}
)

} else {
this.notvalid = true;
}
}

sendCode() {
let users = this.userForm.value
let email = users.email
this.http.post<any>(`${this.baseUrl}sendcode.php`, JSON.stringify(email)).subscribe(
data => {
if(data.Code){
this.verifyCodeSent = 'sent'

} else if(data.Codefail){
this.verifyCodeSent = 'error'
}
else if(data.Emailnotexist) {
this.verifyCodeSent = 'Email not found'
}

})

this.router.navigate(['/signup/emailverify']);
}



ngOnInit(): void {
}

}


