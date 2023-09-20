import { ComponentFixture, TestBed } from '@angular/core/testing';

import { HyTagComponent } from './hy-tag.component';

describe('HyTagComponent', () => {
  let component: HyTagComponent;
  let fixture: ComponentFixture<HyTagComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      declarations: [HyTagComponent]
    });
    fixture = TestBed.createComponent(HyTagComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
