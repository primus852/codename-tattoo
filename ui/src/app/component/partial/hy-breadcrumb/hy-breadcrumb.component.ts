import {Component, Input} from '@angular/core';

@Component({
  selector: 'app-hy-breadcrumb',
  templateUrl: './hy-breadcrumb.component.html',
  styleUrls: ['./hy-breadcrumb.component.scss']
})
export class HyBreadcrumbComponent {

  @Input()
  public pageTitle!: string;

  @Input()
  public pageRootLink!: string;

  @Input()
  public pages!: Array<string>;

}
