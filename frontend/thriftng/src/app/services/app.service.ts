import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { BehaviorSubject } from 'rxjs';
import { environment } from '../../../src/environments/environment';

@Injectable({
providedIn: 'root'
})
export class AppService {
public notify =''
public notifyme = new BehaviorSubject("");
public baseUrl = environment.baseUrl;
public email = '';
public balance = '';
public mybalance = new BehaviorSubject("");

public addItems(notify) {
this.http.post<any>(`${this.baseUrl}confirmemailverify.php`, JSON.stringify(notify)).subscribe(
data => {
this.balance = data.Balance
this.mybalance.next(this.balance);//to be used later
        
this.email = data.Email;
if(data.Verified){
this.notify = 'good' 
}
else if (data.Notverified) {
this.notify = 'bad'
localStorage.setItem('Email', this.email);
}
this.notifyme.next(this.notify);      
}
)

}
    
constructor(public http: HttpClient) {

}
}
