import {NgModule} from '@angular/core';
import {BrowserModule} from '@angular/platform-browser';

import {AppRoutingModule} from './app-routing.module';
import {AppComponent} from './app.component';
import {DarkmodeToggleComponent} from './component/partial/darkmode-toggle/darkmode-toggle.component';
import {NgxTippyModule} from "ngx-tippy-wrapper";
import {FullscreenToggleComponent} from './component/partial/fullscreen-toggle/fullscreen-toggle.component';
import {LoginComponent} from './component/page/login/login.component';
import {ReactiveFormsModule} from "@angular/forms";
import {HTTP_INTERCEPTORS, HttpClientModule} from "@angular/common/http";
import {JwtHelperService, JWT_OPTIONS} from '@auth0/angular-jwt';
import {AuthInterceptor} from "./service/auth/interceptor";
import {BlankComponent} from './component/page/blank/blank.component';
import {DashboardComponent} from './component/page/dashboard/dashboard.component';
import {BrowserAnimationsModule} from "@angular/platform-browser/animations";
import { HyToastComponent } from './component/partial/hy-toast/hy-toast.component';

@NgModule({
  declarations: [
    AppComponent,
    DarkmodeToggleComponent,
    FullscreenToggleComponent,
    LoginComponent,
    BlankComponent,
    DashboardComponent,
    HyToastComponent
  ],
  imports: [
    BrowserModule,
    HttpClientModule,
    AppRoutingModule,
    NgxTippyModule,
    ReactiveFormsModule,
    BrowserAnimationsModule, // required animations module
  ],
  providers: [
    {provide: JWT_OPTIONS, useValue: JWT_OPTIONS},
    JwtHelperService,
    {provide: HTTP_INTERCEPTORS, useClass: AuthInterceptor, multi: true}
  ],
  bootstrap: [AppComponent]
})
export class AppModule {
}
