import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {LoginComponent} from "./component/page/login/login.component";
import {DashboardComponent} from "./component/page/dashboard/dashboard.component";
import {AuthGuardService} from "./service/auth/auth-guard.service";
import {BlankComponent} from "./component/page/blank/blank.component";
import {MainComponent} from "./skeleton/main/main.component";
import {Page404Component} from "./component/page/page404/page404.component";
import {HoursOverviewComponent} from "./component/page/hours-overview/hours-overview.component";

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
    component: HoursOverviewComponent,
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
