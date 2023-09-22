import {Component, OnDestroy, OnInit} from '@angular/core';
import {Subscription} from "rxjs";
import {HyModalService} from "../../../service/hy-modal/hy-modal.service";
import {HyModalInfo} from "../../../model/hy-modal.model";

@Component({
  selector: 'app-hy-modal',
  templateUrl: './hy-modal.component.html',
  styleUrls: ['./hy-modal.component.scss']
})
export class HyModalComponent implements OnInit, OnDestroy {

  private _modalVisibleSubscription!: Subscription;
  private _modalInfoSubscription!: Subscription;

  public isVisible: boolean = false;
  public modalInfo!: HyModalInfo<any, any> | null;

  constructor(
    private modalService: HyModalService<any, any>
  ) {
  }

  ngOnInit() {
    this._modalVisibleSubscription = this.modalService.modalVisibleState.subscribe(isVisible => {
      this.isVisible = isVisible;
    });
    this._modalInfoSubscription = this.modalService.modalInfoState.subscribe(modalInfo => {
      this.modalInfo = modalInfo;
    });
  }

  ngOnDestroy() {
    if (this._modalVisibleSubscription) {
      this._modalVisibleSubscription.unsubscribe();
    }
    if (this._modalInfoSubscription) {
      this._modalInfoSubscription.unsubscribe();
    }
  }

  public closeModal() {
    this.modalService.closeModal();
  }

  public actionButton1() {
    this.modalInfo?.action1();
  }

  public actionButton2() {
    if (this.modalInfo !== undefined) {
      this.modalInfo?.action2?.();
    }
  }
}
