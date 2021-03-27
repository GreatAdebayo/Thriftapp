import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { AppService } from '../services/app.service';

@Component({
selector: 'app-withdraw',
templateUrl: './withdraw.component.html',
styleUrls: ['./withdraw.component.css']
})
export class WithdrawComponent implements OnInit {
public auth = '';
public userId = '';
public notify = ''

Verify() {
this.router.navigate(['/signup/emailverify'])

} 
constructor(public _app: AppService, public router: Router) { }

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
