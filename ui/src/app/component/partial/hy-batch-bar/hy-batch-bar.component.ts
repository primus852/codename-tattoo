import {Component, EventEmitter, Input, Output} from '@angular/core';
import {Uuid} from "../../../model/uuid.model";

@Component({
  selector: 'app-hy-batch-bar',
  templateUrl: './hy-batch-bar.component.html',
  styleUrls: ['./hy-batch-bar.component.scss']
})
export class HyBatchBarComponent {

  @Input()
  public identifier!: string;

  @Input()
  public showBar: boolean = false;

  @Input()
  public itemsSelected: Array<Uuid> = [];

  @Input()
  public action!: string;

  @Output()
  onAction = new EventEmitter<void>();

  handleAction() {
    this.onAction.emit();
  }

}
