import {Component, OnInit} from '@angular/core';
import {Subscription} from "rxjs";
import {HyMenuService} from "../../../service/hy-menu/hy-menu.service";
import {HyMenuStatus, HyMenuType} from "../../../model/hy-menu.model";

@Component({
  selector: 'app-menu',
  templateUrl: './menu.component.html',
  styleUrls: ['./menu.component.scss']
})
export class MenuComponent implements OnInit {
  public menuIsVisible: boolean = true;
  public menuIconOnly: boolean = false;
  public menuWide: boolean = false;

  private _hyMenuSubscription!: Subscription;

  constructor(private _hyMenu: HyMenuService) {

  }

  ngOnInit() {
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
  }


}
