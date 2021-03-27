import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { GetpostService } from '../services/getpost.service';
import { environment } from '../../../src/environments/environment';
import { HttpClient } from '@angular/common/http';

@Component({
selector: 'app-details',
templateUrl: './details.component.html',
styleUrls: ['./details.component.css']
})
export class DetailsComponent implements OnInit {
public ajopost = [];
public auth = '';
public userId = '';
public ajoId = ''
public notice = false;
public noOfinvites = false;
public noOfFriends = null
public email = '';
public Invitenotify = '';
public processing = false;
public baseUrl = environment.baseUrl;
public myInvites = [];

checkCondition(duration) {
this.noOfFriends = duration-1;
this.notice = true;
this.noOfinvites = true;
}

SendInvite() {
if (this.email == '') {
alert('dd')
} else {
let details = { email: this.email, userid: this.userId, ajoid:this.ajoId}
this.processing = true;
this.http.post<any>(`${this.baseUrl}thriftinvite.php`, JSON.stringify(details)).subscribe(
data => {
if (data.Notinvite) {
this.processing = false;
this.Invitenotify = 'bad';
} else if (data.Emailnotfound) {
this.processing = false;
this.Invitenotify = 'notfound';  
} else if (data.Invitesent) {
this.processing = false;
this.Invitenotify = 'sent';
}
})      
}


}
constructor(public actRoute: ActivatedRoute, public _post: GetpostService, public http: HttpClient, public router: Router) { }

ngOnInit(): void {
//  SEND TO SERVICES 
this.auth = localStorage.Token
let auth = JSON.parse(atob(this.auth.split('.')[1]));
this.userId = auth.user;
this._post.getajopost(this.userId)
this._post.getInvites(this.userId)



// // RECEIVE FROM SERVICE
this._post.allpost.subscribe(data => {
this.ajopost = data;
})

this.actRoute.params.subscribe(param => {
this.ajoId = param.id;
})


this.http.post<any>(`${this.baseUrl}myinvites.php`, JSON.stringify
(this.userId)).subscribe(
    data => {
    this.myInvites = data.Myinvites

})
}
}