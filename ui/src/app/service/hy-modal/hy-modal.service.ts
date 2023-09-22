import {Injectable} from '@angular/core';
import {HyModalInfo} from "../../model/hy-modal.model";
import {BehaviorSubject, Observable, Subject} from "rxjs";
import {HyBodyService} from "../hy-body/hy-body.service";

@Injectable({
  providedIn: 'root'
})
export class HyModalService<T, U> {

  private modalVisibleSubject: Subject<boolean> = new BehaviorSubject<boolean>(false);
  public modalVisibleState: Observable<boolean> = this.modalVisibleSubject.asObservable();

  private modalInfoSubject: Subject<HyModalInfo<T, U> | null> = new BehaviorSubject<HyModalInfo<T, U> | null>(null);
  public modalInfoState: Observable<HyModalInfo<T, U> | null> = this.modalInfoSubject.asObservable();

  public modalInfo!: HyModalInfo<T, U> | null;

  constructor(private _hyBody: HyBodyService) {
  }

  openModal(modalInfo: HyModalInfo<T, U>) {
    this.modalVisibleSubject.next(true);
    this.modalInfoSubject.next(modalInfo);
    this.modalInfo = modalInfo;
    this._hyBody.showBackdrop();
  }

  closeModal() {
    this.modalVisibleSubject.next(false);
    this.modalInfoSubject.next(null);
    this.modalInfo = null;
    this._hyBody.hideBackdrop();
  }


}
