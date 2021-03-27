import { ComponentFixture, TestBed } from '@angular/core/testing';

import { CreatethriftComponent } from './createthrift.component';

describe('CreatethriftComponent', () => {
  let component: CreatethriftComponent;
  let fixture: ComponentFixture<CreatethriftComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ CreatethriftComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(CreatethriftComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
