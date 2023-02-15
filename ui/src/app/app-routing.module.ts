import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {LoginComponent} from "./component/page/login/login.component";
import {DashboardComponent} from "./component/page/dashboard/dashboard.component";
import {AuthGuardService} from "./service/auth/auth-guard.service";
import {BlankComponent} from "./component/page/blank/blank.component";
import {Page404Component} from "./component/page/page404/page404.component";
import {TimeTrackingOverviewComponent} from "./component/page/time-tracking-overview/time-tracking-overview.component";

const routes: Routes = [
  {path: '', redirectTo: '/login', pathMatch: 'full'},
  {
    path: 'dashboard',
    component: DashboardComponent,
    canActivate: [AuthGuardService]
  },
  {
    path: 'login',
    component: LoginComponent
  },
  {
    path: 'stundenzettel',
    component: TimeTrackingOverviewComponent,
    canActivate: [AuthGuardService]
  },
  {
    path: 'blank',
    component: BlankComponent,
    canActivate: [AuthGuardService]
  },
  {path: '**', component: Page404Component}
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule {
}
