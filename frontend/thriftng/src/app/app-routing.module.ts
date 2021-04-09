import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { AppGuard } from './app.guard';
import { CreatethriftComponent } from './createthrift/createthrift.component';
import { DashboardComponent } from './dashboard/dashboard.component';
import { DetailsComponent } from './details/details.component';
import { EmailGuard } from './email.guard';
import { EmailverifyComponent } from './emailverify/emailverify.component';
import { FundwalletComponent } from './fundwallet/fundwallet.component';
import { HomepageComponent } from './homepage/homepage.component';
import { InvitedetailsComponent } from './invitedetails/invitedetails.component';
import { InvitesComponent } from './invites/invites.component';
import { LoginpageComponent } from './loginpage/loginpage.component';
import { MyaccountComponent } from './myaccount/myaccount.component';
import { MythriftsComponent } from './mythrifts/mythrifts.component';
import { PersonalinfoComponent } from './personalinfo/personalinfo.component';
import { ProfileComponent } from './profile/profile.component';
import { SignupComponent } from './signup/signup.component';
import { WithdrawComponent } from './withdraw/withdraw.component';
import { WrongpageComponent } from './wrongpage/wrongpage.component';

const routes: Routes = [
  {path:'', component: HomepageComponent },
  {path:'signup', component: SignupComponent, children: [
  {path:'', component: PersonalinfoComponent },
  {path: 'emailverify', canActivate:[EmailGuard], component: EmailverifyComponent}
  ]},
  { path: 'login', component: LoginpageComponent },
  {path: 'dashboard', canActivate:[AppGuard], component: DashboardComponent, children: [
  {path:'', component:MyaccountComponent},
  {path: 'mythrifts', component: MythriftsComponent },
  {path: 'details/:id', component: DetailsComponent },
  {path: 'profile', component: ProfileComponent },
  {path: 'create', component: CreatethriftComponent },
  {path: 'invites', component: InvitesComponent },
  {path: 'fund', component: FundwalletComponent },
  {path: 'withdraw', component: WithdrawComponent },
  {path: 'invitesdetails', component: InvitedetailsComponent },
  ]
  },
  {path:'**', component:WrongpageComponent},
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
