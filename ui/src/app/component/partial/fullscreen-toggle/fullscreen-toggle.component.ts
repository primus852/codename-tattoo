import {Component, Inject, OnInit} from '@angular/core';
import {environment} from "../../../../environments/environment";
import {NgxTippyProps} from "ngx-tippy-wrapper";
import {DOCUMENT} from "@angular/common";

@Component({
  selector: 'app-fullscreen-toggle',
  templateUrl: './fullscreen-toggle.component.html',
  styleUrls: ['./fullscreen-toggle.component.scss']
})
export class FullscreenToggleComponent implements OnInit {

  public tooltip = environment.baseTooltip as NgxTippyProps;

  private _elem: any;
  public isFullscreen: boolean = false;

  constructor(@Inject(DOCUMENT) private document: any) {
  }

  ngOnInit() {
    this._elem = document.documentElement;
  }

  private _openFullscreen() {
    if (this._elem.requestFullscreen) {
      this._elem.requestFullscreen();
    } else if (this._elem.mozRequestFullScreen) {
      /* Firefox */
      this._elem.mozRequestFullScreen();
    } else if (this._elem.webkitRequestFullscreen) {
      /* Chrome, Safari and Opera */
      this._elem.webkitRequestFullscreen();
    } else if (this._elem.msRequestFullscreen) {
      /* IE/Edge */
      this._elem.msRequestFullscreen();
    }
    this.isFullscreen = true;
  }

  private _closeFullscreen() {
    if (this.document.exitFullscreen) {
      this.document.exitFullscreen();
    } else if (this.document.mozCancelFullScreen) {
      /* Firefox */
      this.document.mozCancelFullScreen();
    } else if (this.document.webkitExitFullscreen) {
      /* Chrome, Safari and Opera */
      this.document.webkitExitFullscreen();
    } else if (this.document.msExitFullscreen) {
      /* IE/Edge */
      this.document.msExitFullscreen();
    }
    this.isFullscreen = false;
  }

  /**
   * Toggle Fullscreen
   */
  public toggleFullscreen() {
    if (this.isFullscreen) {
      this._closeFullscreen();
    } else {
      this._openFullscreen();
    }
  }
}
