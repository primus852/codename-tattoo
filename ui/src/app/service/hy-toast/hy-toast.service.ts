import {Injectable} from '@angular/core';
import {BehaviorSubject, Observable, Subject} from "rxjs";
import {HyToastConfig, HyToastInfo} from "../../model/hy-toast.model";

@Injectable({
  providedIn: 'root'
})
export class HyToastService {

  private toastSubject: Subject<HyToastInfo> = new BehaviorSubject<HyToastInfo>({
    title: '',
    context: '',
    visible: false
  });
  public toastState: Observable<HyToastInfo> = this.toastSubject.asObservable();

  constructor() {
  }

  public showToast(title: string, context: string, settings: HyToastConfig = {}) {
    this.toastSubject.next({
      title, context, visible: true
    });
    if (settings.autoClose) {
      let autoCloseTimeout = 5000;
      if (settings.autoCloseTimeout) {
        autoCloseTimeout = settings.autoCloseTimeout;
      }

      setTimeout(() => {
        this.toastSubject.next({
          title: '', context: '', visible: false
        });
      }, autoCloseTimeout);
    }
  }

  public hideToast() {
    this.toastSubject.next({
      title: '', context: '', visible: false
    });
  }

}
