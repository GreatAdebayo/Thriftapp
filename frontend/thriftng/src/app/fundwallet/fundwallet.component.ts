import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { AppService } from '../services/app.service';
import { environment } from '../../../src/environments/environment';
import { HttpClient } from '@angular/common/http';

@Component({
selector: 'app-fundwallet',
templateUrl: './fundwallet.component.html',
styleUrls: ['./fundwallet.component.css']
})
export class FundwalletComponent implements OnInit {
public auth = '';
public userId = '';
public notify = '';
public amount = '';
public baseUrl = environment.baseUrl;
public fundadded = '';
public processing = false;
public note = '';

Verify() {
this.router.navigate(['/signup/emailverify'])
}

fundUserWallet() {
if (this.amount == '') {
    this.note = 'bad';
} else {
let fund = {user:this.userId, amount:this.amount}
this.http.post<any>(`${this.baseUrl}funduserwallet.php`, JSON.stringify(fund)).subscribe(
data => {
this.fundadded = data.Fundadded;
if (this.fundadded) {
this.processing = true;
setTimeout(() => this.processing = false, 2000)
setTimeout(() => this.note = 'good', 2000)
setTimeout(() =>  this.router.navigate(['/dashboard']), 2500)
}


}
)}   
}

constructor(public _app: AppService, public router: Router, public http: HttpClient) { }

ngOnInit(): void {
this.auth = localStorage.Token
let auth = JSON.parse(atob(this.auth.split('.')[1]));
this.userId = auth.user;
this._app.addItems(this.userId)

// RESULT FROM SERVICE
this._app.notifyme.subscribe(data => {
this.notify = data;
})


}



}


