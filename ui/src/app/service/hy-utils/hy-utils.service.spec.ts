import { TestBed } from '@angular/core/testing';

import { HyUtilsService } from './hy-utils.service';

describe('HyUtilsService', () => {
  let service: HyUtilsService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(HyUtilsService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
