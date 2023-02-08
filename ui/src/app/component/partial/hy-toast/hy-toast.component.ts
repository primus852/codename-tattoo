import {Component, OnInit} from '@angular/core';
import {animate, state, style, transition, trigger} from "@angular/animations";
import {HyToastService} from "../../../service/hy-toast/hy-toast.service";

@Component({
  selector: 'app-hy-toast',
  templateUrl: './hy-toast.component.html',
  styleUrls: ['./hy-toast.component.scss'],
  animations: [
    trigger('flyInOut', [
      state('in', style({ opacity:1,transform: 'translateX(0)' })),
      transition('void => *', [
        style({ opacity:0,transform: 'translateX(100%)' }),
        animate(200)
      ]),
      transition('* => void', [
        animate(200, style({ opacity:0,transform: 'translateX(100%)' }))
      ])
    ])
  ]
})
export class HyToastComponent implements OnInit{
  toggleFlag: boolean = false;
  context: string = '';
  title: string = '';

  constructor(private _hyToast: HyToastService) {
  }

  ngOnInit() {
    this._hyToast.toastState.subscribe((showToast) => {
      this.toggleFlag = showToast.visible;
      this.title = showToast.title;
      this.context = showToast.context;
    })
  }

  public closeToast() {
    this._hyToast.hideToast();
  }
}
