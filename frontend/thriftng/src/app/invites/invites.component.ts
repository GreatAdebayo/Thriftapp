import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { AppService } from '../services/app.service';
import { GetpostService } from '../services/getpost.service';

@Component({
selector: 'app-invites',
templateUrl: './invites.component.html',
styleUrls: ['./invites.component.css']
})
export class InvitesComponent implements OnInit {
public notify = '';
public userId = '';
public auth = '';
public myInvites = [];
constructor(public _app: AppService, public router: Router, public _post: GetpostService) {

}
Verify() {
this.router.navigate(['/signup/emailverify'])

}

ngOnInit(): void {
this.auth = localStorage.Token
let auth = JSON.parse(atob(this.auth.split('.')[1]));
this.userId = auth.user;
this._app.addItems(this.userId)
this._post.getInvites(this.userId)

// RESULT FROM SERVICE
this._app.notifyme.subscribe(data => {
this.notify = data;
})

this._post.allinvites.subscribe(data => {
this.myInvites = data;

    
})
 
}

}
