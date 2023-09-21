import { ComponentFixture, TestBed } from '@angular/core/testing';

import { HyBatchBarComponent } from './hy-batch-bar.component';

describe('HyBatchBarComponent', () => {
  let component: HyBatchBarComponent;
  let fixture: ComponentFixture<HyBatchBarComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      declarations: [HyBatchBarComponent]
    });
    fixture = TestBed.createComponent(HyBatchBarComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
