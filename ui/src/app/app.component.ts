import { Component } from '@angular/core';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss']
})
export class AppComponent {
  title = 'ui';
  private _schemeStored = localStorage.getItem('scheme');
  private _bodyTag = document.body;

  constructor() {
    if(this._schemeStored){
      this._bodyTag.classList.remove('dark');
      this._bodyTag.classList.remove('light');
      this._bodyTag.classList.add(this._schemeStored);
    }
  }

}
