import { NgModule } from '@angular/core';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { BrowserModule } from '@angular/platform-browser';
import { HttpClientModule } from '@angular/common/http';
import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { HomepageComponent } from './homepage/homepage.component';
import { SignupComponent } from './signup/signup.component';
import { LoginpageComponent } from './loginpage/loginpage.component';
import { DashboardComponent } from './dashboard/dashboard.component';
import { LoaderComponent } from './loader/loader.component';
import { MythriftsComponent } from './mythrifts/mythrifts.component';
import { MyaccountComponent } from './myaccount/myaccount.component';
import { PersonalinfoComponent } from './personalinfo/personalinfo.component';
import { EmailverifyComponent } from './emailverify/emailverify.component';
import { ProfileComponent } from './profile/profile.component';
import { CreatethriftComponent } from './createthrift/createthrift.component';
import { InvitesComponent } from './invites/invites.component';
import { FundwalletComponent } from './fundwallet/fundwallet.component';
import { WithdrawComponent } from './withdraw/withdraw.component';
import { WrongpageComponent } from './wrongpage/wrongpage.component';
import { DetailsComponent } from './details/details.component';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';    
import { ToastrModule } from 'ngx-toastr';
import { InvitedetailsComponent } from './invitedetails/invitedetails.component';




@NgModule({
  declarations: [
    AppComponent,
    HomepageComponent,
    SignupComponent,
    LoginpageComponent,
    DashboardComponent,
    LoaderComponent,
    MythriftsComponent,
    MyaccountComponent,
 
    PersonalinfoComponent,
    EmailverifyComponent,
    ProfileComponent,
    CreatethriftComponent,
    InvitesComponent,
    FundwalletComponent,
    WithdrawComponent,
   
    WrongpageComponent,
    DetailsComponent,
    InvitedetailsComponent,
  

  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    ReactiveFormsModule,
    FormsModule,
    HttpClientModule,
    BrowserAnimationsModule,  
    ToastrModule.forRoot(
      {
        positionClass: 'top-left'
        
      }
    )
   
    
    
  ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule { }
