import { ComponentFixture, TestBed } from '@angular/core/testing';

import { HyToastComponent } from './hy-toast.component';

describe('HyToastComponent', () => {
  let component: HyToastComponent;
  let fixture: ComponentFixture<HyToastComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ HyToastComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(HyToastComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
