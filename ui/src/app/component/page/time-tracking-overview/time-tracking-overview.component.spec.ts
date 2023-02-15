import { ComponentFixture, TestBed } from '@angular/core/testing';

import { TimeTrackingOverviewComponent } from './time-tracking-overview.component';

describe('HoursOverviewComponent', () => {
  let component: TimeTrackingOverviewComponent;
  let fixture: ComponentFixture<TimeTrackingOverviewComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ TimeTrackingOverviewComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(TimeTrackingOverviewComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
