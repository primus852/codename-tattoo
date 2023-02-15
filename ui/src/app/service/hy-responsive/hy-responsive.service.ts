import {Injectable, OnDestroy} from '@angular/core';
import {BehaviorSubject, debounceTime, fromEvent, Subject, takeUntil} from "rxjs";
import {HyBreakpoint} from "../../model/hy-responsive.model";

@Injectable({
  providedIn: 'root'
})
export class HyResponsiveService implements OnDestroy {

  private _unsubscriber$: Subject<any> = new Subject();
  public screenWidth$: BehaviorSubject<number | null> = new BehaviorSubject<number | null>(null);
  public mediaBreakpoint$: BehaviorSubject<HyBreakpoint | null> = new BehaviorSubject<HyBreakpoint | null>(null);

  constructor() {
    this.init();
  }

  init() {
    this._setScreenWidth(window.innerWidth);
    this._setMediaBreakpoint(window.innerWidth);
    fromEvent(window, 'resize')
      .pipe(
        debounceTime(1000),
        takeUntil(this._unsubscriber$)
      ).subscribe((evt: any) => {
      this._setScreenWidth(evt.target.innerWidth);
      this._setMediaBreakpoint(evt.target.innerWidth);
    });
  }

  ngOnDestroy() {
    this._unsubscriber$.next(null);
    this._unsubscriber$.complete();
  }

  private _setScreenWidth(width: number): void {
    this.screenWidth$.next(width);
  }

  private _setMediaBreakpoint(width: number): void {
    if (width < 576) {
      this.mediaBreakpoint$.next(HyBreakpoint.XS);
    } else if (width >= 576 && width < 768) {
      this.mediaBreakpoint$.next(HyBreakpoint.SM);
    } else if (width >= 768 && width < 992) {
      this.mediaBreakpoint$.next(HyBreakpoint.MD);
    } else if (width >= 992 && width < 1200) {
      this.mediaBreakpoint$.next(HyBreakpoint.LG);
    } else if (width >= 1200 && width < 1600) {
      this.mediaBreakpoint$.next(HyBreakpoint.XL);
    } else {
      this.mediaBreakpoint$.next(HyBreakpoint.XXL);
    }
  }

}
