import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { GetpostService } from '../services/getpost.service';
import { environment } from '../../../src/environments/environment';
import { HttpClient } from '@angular/common/http';
import { ToastrService } from 'ngx-toastr';
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
public acceptedInvites = [];



SendInvite() {
if (this.email == '') {
this.Invitenotify = 'empty'
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
} else if (data.AlreadyInvited) {
this.processing = false;
this.Invitenotify = 'alreadyinvited'
}else if (data.Invitesent) {
this.processing = false;
this.Invitenotify = 'sent';

}

}

)
}
}
startThrift() {
this.http.post<any>(`${this.baseUrl}startajo.php`, JSON.stringify
(this.ajoId)).subscribe(
data => {
this.processing = true;
if(data.Started) {
setTimeout(() => this.processing = false, 1000)
setTimeout(() => this.Invitenotify = 'started', 1000)
setTimeout(() =>  this.router.navigateByUrl('/', {skipLocationChange: true}).then(() => {
this.router.navigate([`/dashboard/details/${this.ajoId}`]);
}), 1200)


} else if(data.Startedalready) {
this.Invitenotify = '';
setTimeout(() => this.processing = false, 1000)
setTimeout(()=> this.Invitenotify = 'alreadystarted' , 1000)
} else if (data.Insufficient) {
this.Invitenotify = '';
setTimeout(() => this.processing = false, 1000)
setTimeout(()=> this.Invitenotify = 'insuff' , 1000)  
} else if (data.InsufficientInvitee) {
this.Invitenotify = '';
setTimeout(() => this.processing = false, 1000)
setTimeout(()=> this.Invitenotify = 'insuffinvite' , 1000)  
}
})
}

pay(userId) {
this.Invitenotify = '';
let details = {userid: userId, ajoid: this.ajoId }
this.http.post<any>(`${this.baseUrl}pay.php`, JSON.stringify(details)).subscribe(
data => {
this.processing = true;
if (data.Walletempty) {
setTimeout(() => this.processing = false, 1000)
setTimeout(() => this.Invitenotify = 'empty', 1000)

} else if (data.AlreadyPaid) {
setTimeout(() => this.processing = false, 1000)
setTimeout(() => this.Invitenotify = 'alreadypaid' , 1000)

} else if (data.Paid) {
setTimeout(() => this.processing = false, 1000)
setTimeout(() => this.Invitenotify = 'paid', 1000)
setTimeout(() =>  this.router.navigateByUrl('/', {skipLocationChange: true}).then(() => {
this.router.navigate([`/dashboard/details/${this.ajoId}`]);
}), 1200)


}
})
}

payUser(userId) {
let details = {userid: userId, ajoid: this.ajoId }
this.http.post<any>(`${this.baseUrl}payuser.php`, JSON.stringify(details)).subscribe(
data => {
this.processing = true;
if (data.Walletempty) {
setTimeout(() => this.processing = false, 1000)
setTimeout(() => this.Invitenotify = 'empty', 1000)

} else if (data.AlreadyPaid) {
setTimeout(() => this.processing = false, 1000)
setTimeout(() => this.Invitenotify = 'alreadypaid' , 1000)

} else if (data.Paid) {
setTimeout(() => this.processing = false, 1000)
setTimeout(() => this.Invitenotify = 'paid', 1000)
setTimeout(() =>  this.router.navigateByUrl('/', {skipLocationChange: true}).then(() => {
this.router.navigate([`/dashboard/details/${this.ajoId}`]);
}), 1100)


} 
})
}



constructor(public actRoute: ActivatedRoute, public _post: GetpostService, public http: HttpClient, public router: Router, private toastr: ToastrService) { }

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

this.http.post<any>(`${this.baseUrl}getaccepted.php`, JSON.stringify
(this.ajoId)).subscribe(
data => {
this.acceptedInvites = data.Getaccepted
}) 

}

}