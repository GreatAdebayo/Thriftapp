import { HttpClient } from '@angular/common/http';
import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { environment } from '../../../src/environments/environment';

@Component({
selector: 'app-profile',
templateUrl: './profile.component.html',
styleUrls: ['./profile.component.css']
})
export class ProfileComponent implements OnInit {
public auth = '';
public firstname = ''
public lastname = ''
public middlename = ''
public phone = ''
public dob = ""
public email = ''
public gender = ''
public profilepic = ''
public selectedFile: File
public status = ""
public baseUrl = environment.baseUrl;
public baseurl = `${this.baseUrl}uploads/`;
public uploadInfo = ''

onFileChanged(e) {
this.selectedFile = e.target.files[0];
this.uploadInfo = 'File Chosen, click upload'
}
uploadFile() {
let auth = JSON.parse(atob(this.auth.split('.')[1]));
let userId = auth.user;
let upload = new FormData();
upload.append('myFile', this.selectedFile, this.selectedFile.name);
this.http.post<any>(`${this.baseUrl}profilepics.php`, upload,{
headers: {
'Authorization':  userId
}
}).subscribe(
data => {
if(data.Upload) {
this.status = 'good'
} else if (data.Notuploaded) {
this.status = 'bad'
} else if (data.Large) {
this.status = 'large'
}
 setTimeout(()=>this.router.navigateByUrl('/', {skipLocationChange: true}).then(() => {
   this.router.navigate(['/dashboard/profile']);
}), 300)
   
}
)


}

// user
constructor(public router: Router, public http: HttpClient) { }


ngOnInit(): void {
this.auth = localStorage.Token
this.http.post<any>(`${this.baseUrl}profile.php`, (this.auth)).subscribe(
data => {
this.firstname = data.Firstname
this.lastname = data.Lastname
this.middlename = data.Middle
this.phone = data.Phone
this.dob = data.Dob
this.email = data.Email
this.gender = data.Gender
this.profilepic = data.Profilepic


})
}

}
