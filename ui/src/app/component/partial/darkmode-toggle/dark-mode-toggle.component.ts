import {Component, OnInit} from '@angular/core';
import {NgxTippyProps} from "ngx-tippy-wrapper";
import {environment} from "../../../../environments/environment";
import {HyBodyService} from "../../../service/hy-body/hy-body.service";
import {HyUiMode} from "../../../model/hy-body.model";
import {Subscription} from "rxjs";

@Component({
  selector: 'app-darkmode-toggle',
  templateUrl: './dark-mode-toggle.component.html',
  styleUrls: ['./dark-mode-toggle.component.scss']
})
export class DarkModeToggleComponent implements OnInit {

  public tooltip = environment.baseTooltip as NgxTippyProps;
  public isDarkMode: boolean = false;

  public _bodySubscription!: Subscription;

  constructor(private _hyBody: HyBodyService) {
  }

  ngOnInit() {
    this._bodySubscription = this._hyBody.bodyState.subscribe((hyBodyStatus) => {
      this.isDarkMode = hyBodyStatus.mode === HyUiMode.DARK;
    });
  }

  /**
   * Toggle DarkMode
   */
  public toggleDarkMode() {
    if (this.isDarkMode) {
      this._hyBody.makeLightOrDarkMode(HyUiMode.LIGHT);
    } else {
      this._hyBody.makeLightOrDarkMode(HyUiMode.DARK);
    }
  }
}
