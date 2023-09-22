import { ComponentFixture, TestBed } from '@angular/core/testing';

import { HyModalComponent } from './hy-modal.component';

describe('HyModalComponent', () => {
  let component: HyModalComponent;
  let fixture: ComponentFixture<HyModalComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      declarations: [HyModalComponent]
    });
    fixture = TestBed.createComponent(HyModalComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
