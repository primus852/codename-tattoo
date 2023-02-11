import {Injectable} from '@angular/core';
import {BehaviorSubject, Observable, Subject} from "rxjs";
import {HyMenuSettings, HyMenuStatus, HyMenuType, HySubMenu} from "../../model/hy-menu.model";

@Injectable({
  providedIn: 'root'
})
export class HyMenuService {

  private _hyMenuSettings: HyMenuSettings = {
    visibility: HyMenuStatus.VISIBLE,
    type: HyMenuType.DEFAULT
  }
  private menuSubject: Subject<HyMenuSettings | null> = new BehaviorSubject<HyMenuSettings | null>(null);
  public menuState: Observable<HyMenuSettings | null> = this.menuSubject.asObservable();

  private submenuSubject: Subject<HySubMenu | null> = new BehaviorSubject<HySubMenu | null>(null);
  public submenuState: Observable<HySubMenu | null> = this.submenuSubject.asObservable();

  constructor() {
    this.menuSubject.next(this._menuSavedState());
  }

  public showMenu(): void {
    localStorage.setItem('menu_visible', HyMenuStatus.VISIBLE);
    this._hyMenuSettings.visibility = HyMenuStatus.VISIBLE;
    this.menuSubject.next(this._hyMenuSettings);
  }

  public hideMenu(): void {
    localStorage.setItem('menu_visible', HyMenuStatus.HIDDEN);
    this._hyMenuSettings.visibility = HyMenuStatus.HIDDEN;
    this.menuSubject.next(this._hyMenuSettings);
  }

  public showSubMenu(subMenu: HySubMenu) {
    this.submenuSubject.next(subMenu);
  }

  public hideSubMenu() {
    this.submenuSubject.next(null);
  }

  public setMenuType(type: HyMenuType): void {
    localStorage.setItem('menu_type', type);
    this._hyMenuSettings.type = type;
    this.menuSubject.next(this._hyMenuSettings);
  }

  private _menuSavedState(): HyMenuSettings {
    const menuVisible: string | null = localStorage.getItem('menu_visible');
    let visibility;

    /**
     * TODO: Get Viewport, the menu needs to be hidden on mobile always on load
     */
    if (menuVisible) {
      visibility = menuVisible as HyMenuStatus;
    } else {
      visibility = HyMenuStatus.VISIBLE;
    }

    const menuType: string | null = localStorage.getItem('menu_type');
    let type;

    if (menuType) {
      type = menuType as HyMenuType;
    } else {
      type = HyMenuType.DEFAULT;
    }

    return {
      visibility: visibility,
      type: type
    }
  }

}