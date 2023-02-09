import {Component, OnDestroy, OnInit} from '@angular/core';
import {HyMenuService} from "../../service/hy-menu/hy-menu.service";
import {Subscription} from "rxjs";
import {HyMenuStatus} from "../../model/hy-menu.model";

@Component({
  selector: 'app-main',
  templateUrl: './main.component.html',
  styleUrls: ['./main.component.scss']
})
export class MainComponent implements OnInit, OnDestroy {

  private _hyMenuSubscription!: Subscription;
  private _isMenuVisible: boolean = true;

  constructor(private _hyMenu: HyMenuService) {
  }

  ngOnInit() {
    this._hyMenuSubscription = this._hyMenu.menuState.subscribe((menuSettings) => {
      if (menuSettings) {
        this._isMenuVisible = menuSettings.visibility !== HyMenuStatus.HIDDEN;
      }
    });

  }

  ngOnDestroy() {
    if (this._hyMenuSubscription) {
      this._hyMenuSubscription.unsubscribe();
    }
  }

  toggleMenu() {
    if (this._isMenuVisible) {
      this._hyMenu.hideMenu();
    } else {
      this._hyMenu.showMenu();
    }
  }


}
