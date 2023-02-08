import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {LoginComponent} from "./component/page/login/login.component";
import {DashboardComponent} from "./component/page/dashboard/dashboard.component";
import {AuthGuardService} from "./service/auth/auth-guard.service";

const routes: Routes = [
  {path: '', redirectTo: '/login', pathMatch: 'full'},
  {
    path: 'dashboard',
    component: DashboardComponent,
    canActivate: [AuthGuardService]
  },
  {path: '**', component: LoginComponent}
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule {
}
