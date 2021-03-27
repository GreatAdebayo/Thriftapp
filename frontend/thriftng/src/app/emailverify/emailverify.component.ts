import { HttpClient } from '@angular/common/http';
import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { environment } from '../../../src/environments/environment';

@Component({
selector: 'app-emailverify',
templateUrl: './emailverify.component.html',
styleUrls: ['./emailverify.component.css']
})
export class EmailverifyComponent implements OnInit {
public email = '';
public code = '';
public details = {};
public response = '';
public processing = false;
public baseUrl = environment.baseUrl

verifyCode() {
this.details = {email:this.email, code: this.code };
this.http.post<any>(`${this.baseUrl}verifycode.php`, JSON.stringify(this.details)).subscribe(
data => {
if (data.Codevalid) {
this.processing = true;
setTimeout(() => this.processing = false, 2000)
setTimeout(() => this.response = 'correct', 2000)
setTimeout(() => this.router.navigate(['/login']), 3000) 
}
else if (data.Codeinvalid) {
this.processing = true;
setTimeout(() => this.processing = false, 1000)
setTimeout(() => this.response = 'incorrect', 1000)
}
else if (data.Emailnotexist) {
this.processing = true;
setTimeout(() => this.processing = false, 1000)
setTimeout(() => this.response = 'notexist', 1000)
}
else if (data.Verified) {
this.processing = true;
setTimeout(() => this.processing = false, 1000)
setTimeout(() => this.response = 'verified', 1000)
}
})
}

resendCode() {
this.http.post<any>(`${this.baseUrl}resendcode.php`, JSON.stringify(this.email)).subscribe(
data =>{
if (data.Code) {
this.processing = true;
setTimeout(() => this.processing = false, 1000)
setTimeout(() => this.response = 'codesent', 1000)
}
else if (data.Emailnotexist) {
this.processing = true;
setTimeout(() => this.processing = false, 1000)
setTimeout(() => this.response = 'notexits', 1000)
}
else if (data.Codefail) {
this.response = 'failed';

}
})

}


constructor(public actRoute: ActivatedRoute, public http: HttpClient, public router: Router) { }

ngOnInit(): void {
this.email = localStorage.Email;

}

}
