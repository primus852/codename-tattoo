import {AfterViewInit, Component, OnInit} from '@angular/core';
import {ActivatedRoute} from "@angular/router";
import {UserApiService} from "../../../../service/api/user-api.service";
import {FormBuilder, FormGroup, Validators} from "@angular/forms";
import {UserRole} from "../../../../model/user.model";
import {Uuid} from "../../../../model/uuid.model";

@Component({
  selector: 'app-user-edit',
  templateUrl: './user-edit.component.html',
  styleUrls: ['./user-edit.component.scss']
})
export class UserEditComponent implements OnInit, AfterViewInit {

  public pages: Array<string> = ['BREADCRUMBS.CONFIG.USER_MANAGEMENT_USER_DETAILS']
  public userUpdateForm!: FormGroup;
  public roles: Array<UserRole> = [];
  private _userId!: Uuid;

  constructor(
    private _route: ActivatedRoute,
    private _fb: FormBuilder,
    private _userApiService: UserApiService,
  ) {
    this._route.params.subscribe((params) => {
      this._userId = params['id'] as Uuid;
    });
  }

  ngOnInit() {
    for (let role in UserRole) {
      if (typeof (UserRole as any)[role] === 'string') {
        if (!this.roles.includes((UserRole as any)[role])) {
          this.roles.push((UserRole as any)[role]);
        }
      }
    }
    this.userUpdateForm = this._fb.group({
      'username': ['', Validators.required],
      'userCode': ['', Validators.required],
      'email': ['', [Validators.required, Validators.email]],
      'roles': ['', Validators.required],
      'password': ['', [Validators.required, Validators.minLength(8)]]
    });

  }

  ngAfterViewInit() {
    this._route.params.subscribe((params) => {
      this._userId = params['id'] as Uuid;
      this._userApiService.loadUserById(this._userId).then((response) => {
        this.pages.push(response.name);

        this.userUpdateForm.setValue({
          'username': response.name,
          'userCode': response.code,
          'email': response.email,
          'roles': response.roles,
          'password': ''
        });

      })
    });
  }

  submit() {

  }

  onValuesPublished(roles: Array<UserRole>) {
    this.userUpdateForm.patchValue({'roles': roles});
  }
}
