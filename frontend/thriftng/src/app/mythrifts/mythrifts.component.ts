import { HttpClient } from '@angular/common/http';
import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { environment } from '../../../src/environments/environment';
import { GetpostService } from '../services/getpost.service';

@Component({
selector: 'app-mythrifts',
templateUrl: './mythrifts.component.html',
styleUrls: ['./mythrifts.component.css']
})
export class MythriftsComponent implements OnInit {
public baseUrl = environment.baseUrl;
public auth = '';
public userId = '';
public ajopost = [];
public great = ''

details(ajo_id) {
this.router.navigate([`/dashboard/details/${ajo_id}`]);
}
constructor(public _post: GetpostService, public router: Router, public http: HttpClient) { }

ngOnInit(): void {
//  SEND TO SERVICES 
this.auth = localStorage.Token
let auth = JSON.parse(atob(this.auth.split('.')[1]));
this.userId = auth.user;
this._post.getajopost(this.userId)



// RECEIVE FROM SERVICE
this._post.allpost.subscribe(data => {
this.ajopost = data;
})
}




}
