import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { AppService } from '../services/app.service';
import { GetpostService } from '../services/getpost.service';
import { environment } from '../../../src/environments/environment';
import { HttpClient } from '@angular/common/http';
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
public id = '';
baseUrl = environment.baseUrl;
public ajodetails = {};
public title = '';
public describ = '';
public type = '';
public amount = '';
public duration = '';
public ajoid = '';
public balance = '';
public requestnotify = '';
    public processing = false;
    public inviteeId = '';

constructor(public http: HttpClient, public _app: AppService, public router: Router, public _post: GetpostService) {

}
Verify() {
this.router.navigate(['/signup/emailverify'])
}
accept(value) {
        this.processing = false;
    this.inviteeId = value
if(parseInt(this.amount) > parseInt(this.balance)){
this.requestnotify = 'insufficient'
} else {

let details = {ajoid: this.ajoid, inviteeid: this.inviteeId, duration: this.duration}
this.http.post<any>(`${this.baseUrl}acceptreq.php`, JSON.stringify(details)).subscribe(
    data => {

this.processing = false;
if(data.Accepted) {
this.requestnotify = 'accepted'
}
else if(data.AlreadyAccepted){
    this.requestnotify = 'already'
}
else if (data.AlreadyRejected) {
    this.requestnotify = 'alreadyrejected'

    //REFRESHING PAGE AUTMATICALLY IN ANGULAR
    // this.router.navigateByUrl('/', {skipLocationChange: true}).then(() => {
    //     this.router.navigate(['/dashboard/invites']);
    // });
} else if (data.Filledup) {
    this.requestnotify = 'filledup'  
}
})       
}

}

    reject(value) {
        this.processing = true;
        this.inviteeId = value
        let details = { ajoid: this.ajoid, inviteeid: this.inviteeId}
this.http.post<any>(`${this.baseUrl}rejectreq.php`, JSON.stringify(details)).subscribe(
    data => {
        this.processing = false;
    if(data.Rejected) {
        this.requestnotify = 'rejected'
        }
        else if(data.AlreadyAccepted){
            this.requestnotify = 'already'
        }
        else if (data.AlreadyRejected) {
            this.requestnotify = 'alreadyrejected'
     
        }
}) 
}

    details(ajoId) {
this.requestnotify = '';
this.ajoid = ajoId
this.http.post<any>(`${this.baseUrl}ajodetails.php`, JSON.stringify(ajoId)).subscribe(
data => {
let ajodetails = data.Ajodetails;
this.title = ajodetails.title
this.describ = ajodetails.describ
this.type = ajodetails.type
this.amount = ajodetails.amount
this.duration = ajodetails.duration
})
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

this._app.mybalance.subscribe(data => {
this.balance = data;
})

}
    
reloadCurrentRoute() {
    
 
}

}
