import { TestBed } from '@angular/core/testing';

import { TimeTrackingApiService } from './time-tracking-api.service';

describe('TimeTrackingApiService', () => {
  let service: TimeTrackingApiService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(TimeTrackingApiService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
