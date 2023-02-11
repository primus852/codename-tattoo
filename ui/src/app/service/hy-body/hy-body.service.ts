import {Injectable} from '@angular/core';
import {BehaviorSubject, Observable, Subject} from "rxjs";
import {HyBodyStaus, HyUiMode} from "../../model/hy-body.model";
import {HyMenuService} from "../hy-menu/hy-menu.service";

@Injectable({
  providedIn: 'root'
})
export class HyBodyService {

  private _hyBodyStatus: HyBodyStaus = {
    backdropVisible: false,
    mode: HyUiMode.LIGHT

  };

  private bodySubject: Subject<HyBodyStaus> = new BehaviorSubject<HyBodyStaus>(this._hyBodyStatus);
  public bodyState: Observable<HyBodyStaus> = this.bodySubject.asObservable();

  constructor() {
    const _loadedScheme = this._loadScheme();
    if(_loadedScheme !== null){
      this._hyBodyStatus.mode = _loadedScheme as HyUiMode;
      this.bodySubject.next(this._hyBodyStatus);
    }
  }

  public showBackdrop() {
    this._hyBodyStatus.backdropVisible = true;
    this.bodySubject.next(this._hyBodyStatus);
  }

  public hideBackdrop() {
    this._hyBodyStatus.backdropVisible = false;
    this.bodySubject.next(this._hyBodyStatus);
  }

  public makeLightOrDarkMode(mode: HyUiMode) {
    this._hyBodyStatus.mode = mode;
    this.bodySubject.next(this._hyBodyStatus);
    localStorage.setItem('scheme', mode);
  }

  private _loadScheme(): string | null {
    return localStorage.getItem('scheme');
  }

}
