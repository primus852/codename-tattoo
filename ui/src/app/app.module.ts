import {NgModule} from '@angular/core';
import {BrowserModule} from '@angular/platform-browser';

import {AppRoutingModule} from './app-routing.module';
import {AppComponent} from './app.component';
import {DarkmodeToggleComponent} from './component/partial/darkmode-toggle/darkmode-toggle.component';
import {NgxTippyModule} from "ngx-tippy-wrapper";
import { FullscreenToggleComponent } from './component/partial/fullscreen-toggle/fullscreen-toggle.component';

@NgModule({
  declarations: [
    AppComponent,
    DarkmodeToggleComponent,
    FullscreenToggleComponent
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    NgxTippyModule
  ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule {
}
