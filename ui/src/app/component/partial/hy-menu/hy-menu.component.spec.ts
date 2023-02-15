import { ComponentFixture, TestBed } from '@angular/core/testing';

import { HyMenuComponent } from './hy-menu.component';

describe('MenuComponent', () => {
  let component: HyMenuComponent;
  let fixture: ComponentFixture<HyMenuComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ HyMenuComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(HyMenuComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
