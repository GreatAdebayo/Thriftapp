import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { environment } from '../../../src/environments/environment';
import { BehaviorSubject } from 'rxjs';
@Injectable({
  providedIn: 'root'
})
export class GetpostService {
  public baseUrl = environment.baseUrl;
  public ajopost = [];
  public allpost = new BehaviorSubject([""]);
  public invites = [];
  public allinvites = new BehaviorSubject([""]);
  
  public getajopost(userid) {
    this.http.post<any>(`${this.baseUrl}getmythrifts.php`, JSON.stringify
    (userid)).subscribe(
      data => {
        this.ajopost = data.Ajopost;
        this.allpost.next(this.ajopost);
   
     
    })
  }

  public getInvites(userid) {
    this.http.post<any>(`${this.baseUrl}getinvites.php`, JSON.stringify
    (userid)).subscribe(
      data => {
        this.invites = data.Allinvites;
        this.allinvites.next(this.invites);
    })
  }
  constructor(public http: HttpClient) { }
}
