import { TestBed } from '@angular/core/testing';

import { HyMenuService } from './hy-menu.service';

describe('HyMenuService', () => {
  let service: HyMenuService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(HyMenuService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
