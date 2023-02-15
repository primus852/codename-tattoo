import { ComponentFixture, TestBed } from '@angular/core/testing';

import { HyBreadcrumbComponent } from './hy-breadcrumb.component';

describe('HyBreadcrumbComponent', () => {
  let component: HyBreadcrumbComponent;
  let fixture: ComponentFixture<HyBreadcrumbComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ HyBreadcrumbComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(HyBreadcrumbComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
