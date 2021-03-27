import { ComponentFixture, TestBed } from '@angular/core/testing';

import { MythriftsComponent } from './mythrifts.component';

describe('MythriftsComponent', () => {
  let component: MythriftsComponent;
  let fixture: ComponentFixture<MythriftsComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ MythriftsComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(MythriftsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
