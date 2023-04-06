import {Component, OnDestroy, OnInit} from '@angular/core';
import {AuthService} from "./service/auth/auth.service";
import {Subscription} from "rxjs";
import {HyMenuService} from "./service/hy-menu/hy-menu.service";
import {HyBodyService} from "./service/hy-body/hy-body.service";
import {HyUiMode} from "./model/hy-body.model";

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss']
})
export class AppComponent implements OnInit, OnDestroy {

  private _authSubscription!: Subscription;
  private _bodySubscription!: Subscription;

  public isLoggedIn: boolean = false;
  public showBackdrop: boolean = false;
  public isLightOrDark: string = '';
  public hyUiMode = HyUiMode;

  constructor(
    private _auth: AuthService,
    private _hyMenu: HyMenuService,
    private _hyBody: HyBodyService
  ) {
  }

  ngOnInit() {

    const importTE = async () => {
      // @ts-ignore
      await import('tw-elements');
    };
    importTE();

    this._authSubscription = this._auth.loginState.subscribe((state) => {
      this.isLoggedIn = state !== null;
    });

    this._bodySubscription = this._hyBody.bodyState.subscribe((hyBodyStatus) => {
      this.showBackdrop = hyBodyStatus.backdropVisible;
      this.isLightOrDark = hyBodyStatus.mode;
    });
  }

  ngOnDestroy() {
    if (this._authSubscription) {
      this._authSubscription.unsubscribe();
    }

    if (this._bodySubscription) {
      this._bodySubscription.unsubscribe();
    }
  }

  public removeBackdrop() {
    this._hyBody.hideBackdrop();
    this._hyMenu.hideSubMenu();
  }
}
