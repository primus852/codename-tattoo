import {Component, OnDestroy, OnInit} from '@angular/core';
import {AuthService} from "./service/auth/auth.service";
import {Subscription} from "rxjs";
import {HyMenuStatus, HyMenuType} from "./model/hy-menu.model";
import {HyMenuService} from "./service/hy-menu/hy-menu.service";

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss']
})
export class AppComponent implements OnInit, OnDestroy {

  private _schemeStored = localStorage.getItem('scheme');
  private _bodyTag = document.body;
  private _authSubscription!: Subscription;

  public isLoggedIn: boolean = false;

  constructor(private _auth: AuthService, private _hyMenu: HyMenuService) {
    if (this._schemeStored) {
      this._bodyTag.classList.remove('dark');
      this._bodyTag.classList.remove('light');
      this._bodyTag.classList.add(this._schemeStored);
    }
  }

  ngOnInit() {
    this._authSubscription = this._auth.loginState.subscribe((state) => {
      this.isLoggedIn = state;
    });
  }

  ngOnDestroy() {
    if (this._authSubscription) {
      this._authSubscription.unsubscribe();
    }
  }

}
