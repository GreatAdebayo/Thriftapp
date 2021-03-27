import { HttpClient } from '@angular/common/http';
import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup } from '@angular/forms';
import { Router } from '@angular/router';
import { AppService } from '../services/app.service';
import { environment } from '../../../src/environments/environment';
@Component({
selector: 'app-createthrift',
templateUrl: './createthrift.component.html',
styleUrls: ['./createthrift.component.css']
})
export class CreatethriftComponent implements OnInit {
public typeForm: FormGroup;
public auth = '';
public userId = '';
public notify = '';
public disabled = '';
public balance = '';
public thrifttypes = ['Choose thrift type', 'Daily', 'Weekly', 'Monthly'];
public title = '';
public describe = '';
public duration: any
public amount:any
public thrift = {};
public members: any;
public baseUrl = environment.baseUrl
public note = '';
public processing = false;


Verify() {
this.router.navigate(['/signup/emailverify'])

}

createThrift() {
let typedetails = this.typeForm.value;
let type = typedetails.typeControl;
if (this.title && this.describe && this.amount && this.duration && type) {
this.members = this.duration - 1;
this.thrift = {userid:this.userId, title: this.title, describe:this.describe, amount:this.amount, duration:this.duration, type:type, member:this.members}
this.http.post<any>(`${this.baseUrl}ajopost.php`, JSON.stringify(this.thrift)).subscribe(
data => {
if (data.Postgood) {
this.note = ''
this.processing = true;
setTimeout(() => this.processing = false, 1000)
setTimeout(() => this.note = 'good', 1000)
setTimeout(() =>  this.router.navigate(['/dashboard/mythrifts']), 1500)

} 
})
} else {
this.note = ''
this.processing = true;
setTimeout(() => this.processing = false, 1000)
setTimeout(() =>  this.note = 'bad', 1000)

}


}

constructor(public _app: AppService, public http: HttpClient, public router: Router, public fb: FormBuilder) {

}
ngOnInit(): void {
this.typeForm= this.fb.group({
typeControl: ['Choose thrift type']
});


// SEND DATA TO SERVICE
this.auth = localStorage.Token
let auth = JSON.parse(atob(this.auth.split('.')[1]));
this.userId = auth.user;
this._app.addItems(this.userId)

// RECEIVED DATA FROM SERVICE
this._app.notifyme.subscribe(data => {
this.notify = data;

})

this._app.mybalance.subscribe(data => {
this.balance = data;

})
}

}
