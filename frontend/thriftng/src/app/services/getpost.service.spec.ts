import { TestBed } from '@angular/core/testing';

import { GetpostService } from './getpost.service';

describe('GetpostService', () => {
  let service: GetpostService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(GetpostService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
