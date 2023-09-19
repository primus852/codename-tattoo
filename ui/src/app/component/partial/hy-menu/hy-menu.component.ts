import {Component, OnInit} from '@angular/core';
import {Subscription} from "rxjs";
import {HyMenuService} from "../../../service/hy-menu/hy-menu.service";
import {HyMenuStatus, HyMenuType, HySubMenu} from "../../../model/hy-menu.model";
import {AuthService} from "../../../service/auth/auth.service";
import {HyBodyService} from "../../../service/hy-body/hy-body.service";
import {NavigationEnd, Router} from "@angular/router";

@Component({
  selector: 'app-hy-menu',
  templateUrl: './hy-menu.component.html',
  styleUrls: ['./hy-menu.component.scss']
})
export class HyMenuComponent implements OnInit {
  public menuIsVisible: boolean = true;
  public menuIconOnly: boolean = false;
  public menuWide: boolean = false;
  public subMenuOpen: HySubMenu | null = null;
  public hySubMenu = HySubMenu;
  public linkActive: boolean = false;

  public userCode: string | undefined;
  public fullName: string | undefined;
  public roles: string | undefined;

  private _hyMenuSubscription!: Subscription;
  private _authUserSubscription!: Subscription;
  private _hySubMenuSubscription!: Subscription;

  constructor(
    private _hyMenu: HyMenuService,
    private _hyBody: HyBodyService,
    private _auth: AuthService,
    private _router: Router
  ) {
  }

  ngOnInit() {

    this._authUserSubscription = this._auth.loginState.subscribe((authUser) => {
      this.userCode = authUser?.code;
      this.fullName = authUser?.name;
      this.roles = authUser?.roles.join(', ')
    });

    this._hyMenuSubscription = this._hyMenu.menuState.subscribe((menuSettings) => {
      if (menuSettings) {
        this.menuIsVisible = menuSettings.visibility !== HyMenuStatus.HIDDEN;
        if (menuSettings.type === HyMenuType.ICON) {
          this.menuIconOnly = true;
          this.menuWide = false;
        } else if (menuSettings.type === HyMenuType.WIDE) {
          this.menuIconOnly = false;
          this.menuWide = true;
        } else {
          this.menuIconOnly = false;
          this.menuWide = false;
        }
      }
    });

    /**
     * Workaround: Hide Submenu when Route changes
     */
    this._router.events.subscribe((event) => {
      if (event instanceof NavigationEnd) {
        this._hyMenu.hideSubMenu();
        if (!this.menuWide) {
          this._hyBody.hideBackdrop();
        }
      }
    });

    /**
     * Debugging only, it messes with the Responsive Logic
     * this._hyMenu.setMenuType(HyMenuType.DEFAULT);
     */

    this._hySubMenuSubscription = this._hyMenu.submenuState.subscribe((subMenuSettings) => {
      this.subMenuOpen = subMenuSettings;
      this.linkActive = this.subMenuOpen === null;
    });

  }


  public toggleSubMenu(submenu: HySubMenu | null) {
    if (submenu === null) {
      this._hyMenu.hideSubMenu();
      if (!this.menuWide) {
        this._hyBody.hideBackdrop();
      }
    } else {
      if (this.subMenuOpen === null) {
        if (!this.menuWide) {
          this._hyMenu.showSubMenu(submenu);
          this._hyBody.showBackdrop();
        } else {
          this._hyMenu.showSubMenu(submenu);

        }
      } else {
        this._hyMenu.hideSubMenu();
        if (!this.menuWide) {
          this._hyBody.hideBackdrop();
        }
      }
    }
  }
}
