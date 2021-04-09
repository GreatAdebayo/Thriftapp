import { ComponentFixture, TestBed } from '@angular/core/testing';

import { InvitedetailsComponent } from './invitedetails.component';

describe('InvitedetailsComponent', () => {
  let component: InvitedetailsComponent;
  let fixture: ComponentFixture<InvitedetailsComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ InvitedetailsComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(InvitedetailsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
