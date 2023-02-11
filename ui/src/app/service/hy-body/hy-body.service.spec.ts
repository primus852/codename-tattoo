import { TestBed } from '@angular/core/testing';

import { HyBodyService } from './hy-body.service';

describe('HyBodyService', () => {
  let service: HyBodyService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(HyBodyService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
