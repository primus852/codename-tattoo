import { ComponentFixture, TestBed } from '@angular/core/testing';

import { HyMultiSelectComponent } from './hy-multi-select.component';

describe('HyMultiSelectComponent', () => {
  let component: HyMultiSelectComponent;
  let fixture: ComponentFixture<HyMultiSelectComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      declarations: [HyMultiSelectComponent]
    });
    fixture = TestBed.createComponent(HyMultiSelectComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
