import { ComponentFixture, TestBed } from '@angular/core/testing';

import { FullscreenToggleComponent } from './fullscreen-toggle.component';

describe('FullscreenToggleComponent', () => {
  let component: FullscreenToggleComponent;
  let fixture: ComponentFixture<FullscreenToggleComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ FullscreenToggleComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(FullscreenToggleComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
