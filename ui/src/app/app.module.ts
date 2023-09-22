import {LOCALE_ID, NgModule} from '@angular/core';
import {BrowserModule} from '@angular/platform-browser';
import {registerLocaleData} from '@angular/common';
import localeDe from '@angular/common/locales/de';

import {AppRoutingModule} from './app-routing.module';
import {AppComponent} from './app.component';
import {DarkModeToggleComponent} from './component/partial/darkmode-toggle/dark-mode-toggle.component';
import {NgxTippyModule} from "ngx-tippy-wrapper";
import {FullscreenToggleComponent} from './component/partial/fullscreen-toggle/fullscreen-toggle.component';
import {LoginComponent} from './component/page/login/login.component';
import {ReactiveFormsModule} from "@angular/forms";
import {HTTP_INTERCEPTORS, HttpClient, HttpClientModule} from "@angular/common/http";
import {JwtHelperService, JWT_OPTIONS} from '@auth0/angular-jwt';
import {AuthInterceptor} from "./service/auth/interceptor";
import {BlankComponent} from './component/page/blank/blank.component';
import {DashboardComponent} from './component/page/dashboard/dashboard.component';
import {BrowserAnimationsModule} from "@angular/platform-browser/animations";
import {HyToastComponent} from './component/partial/hy-toast/hy-toast.component';
import {FooterComponent} from './component/partial/footer/footer.component';
import {MainComponent} from './skeleton/main/main.component';
import {SearchInputComponent} from './component/partial/search-input/search-input.component';
import {HyMenuComponent} from './component/partial/hy-menu/hy-menu.component';
import {Page404Component} from './component/page/page404/page404.component';
import {HyBreadcrumbComponent} from './component/partial/hy-breadcrumb/hy-breadcrumb.component';
import {TimeTrackingOverviewComponent} from './component/page/time-tracking-overview/time-tracking-overview.component';
import {MinutesToHoursPipe} from './pipe/minutes-to-hours.pipe';
import {UserComponent} from './component/page/config/user/user.component';
import {HyMultiSelectComponent} from './component/partial/hy-multi-select/hy-multi-select.component';
import {HyTagComponent} from './component/partial/hy-tag/hy-tag.component';
import {HyBatchBarComponent} from './component/partial/hy-batch-bar/hy-batch-bar.component';
import {HyModalComponent} from './component/partial/hy-modal/hy-modal.component';
import {TranslateLoader, TranslateModule} from "@ngx-translate/core";
import {TranslateHttpLoader} from "@ngx-translate/http-loader";
import { UserEditComponent } from './component/page/config/user-edit/user-edit.component';

registerLocaleData(localeDe);

export function HttpLoaderFactory(http: HttpClient) {
  return new TranslateHttpLoader(http);
}

@NgModule({
  declarations: [
    AppComponent,
    DarkModeToggleComponent,
    FullscreenToggleComponent,
    LoginComponent,
    BlankComponent,
    DashboardComponent,
    HyToastComponent,
    FooterComponent,
    MainComponent,
    SearchInputComponent,
    HyMenuComponent,
    Page404Component,
    HyBreadcrumbComponent,
    TimeTrackingOverviewComponent,
    MinutesToHoursPipe,
    UserComponent,
    HyMultiSelectComponent,
    HyTagComponent,
    HyBatchBarComponent,
    HyModalComponent,
    UserEditComponent
  ],
  imports: [
    BrowserModule,
    HttpClientModule,
    AppRoutingModule,
    NgxTippyModule,
    ReactiveFormsModule,
    BrowserAnimationsModule,
    TranslateModule.forRoot({
      loader: {
        provide: TranslateLoader,
        useFactory: HttpLoaderFactory,
        deps: [HttpClient]
      }
    })
  ],
  providers: [
    {provide: JWT_OPTIONS, useValue: JWT_OPTIONS},
    JwtHelperService,
    {provide: HTTP_INTERCEPTORS, useClass: AuthInterceptor, multi: true},
  ],
  bootstrap: [AppComponent]
})
export class AppModule {
}
