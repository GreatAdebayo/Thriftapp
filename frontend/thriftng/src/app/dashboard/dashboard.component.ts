
import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';

@Component({
  selector: 'app-dashboard',
  templateUrl: './dashboard.component.html',
  styleUrls: ['./dashboard.component.css']
})
export class DashboardComponent implements OnInit {
  
  home() {
    this.router.navigate(['/dashboard'])
  }
  mythrifts() {
    this.router.navigate(['/dashboard/mythrifts']) 
  }
  invites() {
    this.router.navigate(['/dashboard/invites']) 
  }

  fund() {
    this.router.navigate(['/dashboard/fund']) 
  }

  withdraw() {
    this.router.navigate(['/dashboard/withdraw']) 
  }


  logout() {
    localStorage.removeItem('Token');
    this.router.navigate(['/'])
  }

 
  constructor(public router: Router) { }

  ngOnInit(): void {
 
  }

}


