import { HttpClient } from '@angular/common/http';
import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { environment } from '../../../src/environments/environment';

@Component({
selector: 'app-myaccount',
templateUrl: './myaccount.component.html',
styleUrls: ['./myaccount.component.css']
})
export class MyaccountComponent implements OnInit {
public auth = '';
public lastname = '';
public balance = '';
public profilepic = ''
public baseUrl = environment.baseUrl;
public baseurl = `${this.baseUrl}uploads/`;

profile() {
this.router.navigate(['/dashboard/profile'])
}

creatThrift() {
this.router.navigate(['/dashboard/create'])   
}



fund() {
this.router.navigate(['/dashboard/fund']) 
}
constructor(public router: Router, public http: HttpClient) { }

ngOnInit(): void {
this.auth = localStorage.Token
this.http.post<any>(`${this.baseUrl}getuserinfo.php`, (this.auth)).subscribe(
data => {
this.lastname = data.Lastname
this.balance = data.Balance
this.profilepic = data.Profilepic
})
}
}
