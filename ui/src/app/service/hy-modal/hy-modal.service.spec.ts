import { TestBed } from '@angular/core/testing';

import { HyModalService } from './hy-modal.service';

describe('HyModalService', () => {
  let service: HyModalService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(HyModalService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
