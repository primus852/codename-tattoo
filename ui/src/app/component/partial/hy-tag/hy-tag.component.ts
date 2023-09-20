import {Component, EventEmitter, Input, Output} from '@angular/core';

@Component({
  selector: 'app-hy-tag',
  templateUrl: './hy-tag.component.html',
  styleUrls: ['./hy-tag.component.scss']
})
export class HyTagComponent {

  @Input()
  public value: string = '';

  @Output()
  public tagRemoved = new EventEmitter<string>();

  removeTag() {
    this.tagRemoved.emit(this.value);
  }
}
