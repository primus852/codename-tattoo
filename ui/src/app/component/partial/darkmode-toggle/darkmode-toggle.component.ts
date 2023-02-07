import {Component} from '@angular/core';
import {NgxTippyProps} from "ngx-tippy-wrapper";
import {environment} from "../../../../environments/environment";

@Component({
  selector: 'app-darkmode-toggle',
  templateUrl: './darkmode-toggle.component.html',
  styleUrls: ['./darkmode-toggle.component.scss']
})
export class DarkmodeToggleComponent {

  private _bodyTag = document.body;
  public isDarkMode: boolean = localStorage.getItem('scheme') === 'dark';
  public tooltip = environment.baseTooltip as NgxTippyProps;

  /**
   * Toggle DarkMode
   */
  public toggleDarkMode() {
    if (this.isDarkMode) {
      this._bodyTag.classList.remove('dark');
      this._bodyTag.classList.add('light');
      localStorage.setItem('scheme', 'light');
      this.isDarkMode = false;
    } else {
      this._bodyTag.classList.remove('light');
      this._bodyTag.classList.add('dark');
      localStorage.setItem('scheme', 'dark');
      this.isDarkMode = true;
    }
  }
}
