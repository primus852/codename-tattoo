import { TestBed } from '@angular/core/testing';

import { HyResponsiveService } from './hy-responsive.service';

describe('HyResponsiveService', () => {
  let service: HyResponsiveService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(HyResponsiveService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
