import { TestBed } from '@angular/core/testing';

import { HyToastService } from './hy-toast.service';

describe('HyToastService', () => {
  let service: HyToastService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(HyToastService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
