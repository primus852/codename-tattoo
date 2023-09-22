import {Component, EventEmitter, Input, Output} from '@angular/core';
import {NgxTippyProps, NgxTippyService} from "ngx-tippy-wrapper";

@Component({
  selector: 'app-hy-multi-select',
  templateUrl: './hy-multi-select.component.html',
  styleUrls: ['./hy-multi-select.component.scss']
})
export class HyMultiSelectComponent {

  @Input()
  public values!: any;

  @Input()
  public label!: string;

  @Output()
  public valuesPublished = new EventEmitter<Array<any>>();

  public tooltip: NgxTippyProps = {
    theme: 'light-border',
    offset: [0, 8],
    arrow: false,
    placement: "bottom-start",
    trigger: 'click',
    interactive: true,
    allowHTML: true,
    animation: 'shift-toward-extreme',
  };

  public tags: Array<any> = [];

  constructor(private readonly _tippyService: NgxTippyService) {
  }

  onTagRemoved(index: number) {
    this.tags.splice(index, 1);
  }

  addValue(value: any) {
    if (!this.tags.includes(value)) {
      this.tags.push(value);
      this._tippyService.hideAll();
      this.valuesPublished.emit(this.tags);
    }
  }
}
