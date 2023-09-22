import {Component, OnInit} from '@angular/core';
import {UserCreate, UserRole, UserShort} from "../../../../model/user.model";
import {FormBuilder, FormGroup, Validators} from "@angular/forms";
import {HyToastService} from "../../../../service/hy-toast/hy-toast.service";
import {UserApiService} from "../../../../service/api/user-api.service";
import {Uuid} from "../../../../model/uuid.model";
import {HyModalService} from "../../../../service/hy-modal/hy-modal.service";
import {HyModalInfo} from "../../../../model/hy-modal.model";
import {TranslateService} from "@ngx-translate/core";

@Component({
  selector: 'app-user',
  templateUrl: './user.component.html',
  styleUrls: ['./user.component.scss']
})
export class UserComponent implements OnInit {

  public roles: Array<UserRole> = [];
  public userCreateForm: FormGroup;
  public userList: Array<UserShort> = [];
  public hasItemsSelected: boolean = false;
  public itemsSelected: Array<Uuid> = [];

  constructor(
    private _translateService: TranslateService,
    private _fb: FormBuilder,
    private _hyToast: HyToastService,
    private _userApiService: UserApiService,
    private _hyModal: HyModalService<any, any>,
  ) {
    this.userCreateForm = this._fb.group({
      'username': ['', Validators.required],
      'userCode': ['', Validators.required],
      'email': ['', [Validators.required, Validators.email]],
      'roles': [this.roles, Validators.required],
      'password': ['', [Validators.required, Validators.minLength(8)]]
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
    this._loadUserlist();
  }

  onValuesPublished(roles: Array<UserRole>) {
    this.userCreateForm.patchValue({'roles': roles});
  }

  submit(): void {
    if (!this.userCreateForm.valid) {
      let err: string = '<strong>Details:</strong><br /><ul>';
      for (const field in this.userCreateForm.controls) {
        const control = this.userCreateForm.get(field);
        if (control?.invalid) {
          err += `<li>${field}</li>`
        }
      }

      err += '</ul>';
      this._hyToast.showToast('GENERIC.ERROR', err, {
        autoClose: true,
      });
      return;
    }

    let user: UserCreate = {
      email: this.userCreateForm.get('email')?.value || '',
      code: this.userCreateForm.get('userCode')?.value || '',
      name: this.userCreateForm.get('username')?.value || '',
      password: this.userCreateForm.get('password')?.value || '',
      roles: this.userCreateForm.get('roles')?.value || [],
    }

    this._sendToBackend(user);
  }

  private _sendToBackend(user: UserCreate) {
    this.userCreateForm.reset();
    this._userApiService.createUser(user).then((response) => {
      this._hyToast.showToast('GENERIC.SUCCESS', this._translateService.instant('USER.CREATED', { userName: response.name }), {
        autoClose: true,
      });
      this.userList.push(response);
    }).catch((err) => {
      this._hyToast.showToast(`GENERIC.ERROR`, `BACKEND.${err.error.detail}`, {
        autoClose: true,
      });
    });
  }


  toggleSelection(event: Event | null, id: Uuid) {
    // @ts-ignore
    if (event?.target.checked) {
      this.itemsSelected.push(id);
    } else {
      this.itemsSelected = this.itemsSelected.filter(itemId => itemId !== id);
    }
    this.hasItemsSelected = this.itemsSelected.length > 0;
  }

  removeItemsSelected() {
    this._userApiService.batchDeleteUsers({
      ids: this.itemsSelected
    }).then((response) => {
      const deletedIds = response.ids;

      this.userList = this.userList.filter(user => !deletedIds.includes(user.id));
      this.itemsSelected = this.itemsSelected.filter(id => !deletedIds.includes(id));

      this.hasItemsSelected = this.itemsSelected.length > 0;

      this._hyToast.showToast('GENERIC.SUCCESS', 'USER.DELETED_GENERIC', {
        autoClose: true,
      });
    }).catch((err) => {
      this._hyToast.showToast(`GENERIC.ERROR`, `BACKEND.${err.error.detail}`, {
        autoClose: true,
      });
      this._loadUserlist();
    });
  }

  triggerDelete(id: Uuid, name: string) {
    const modalInfo: HyModalInfo<any, any> = {
      title: 'USER.DELETE_QUESTION_TITLE',
      context: this._translateService.instant('USER.DELETE_QUESTION_CONTEXT', { userName: name }),
      action1: () => {
        this._userApiService.deleteUserById(id).then(() => {
          this._hyModal.closeModal();
          this._hyToast.showToast(`GENERIC.SUCCESS`, this._translateService.instant('USER.DELETED', { userName: name }), {
            autoClose: true,
          });
          this.userList = this.userList.filter(user => !id.includes(user.id));
        }).catch((err) => {
          this._hyModal.closeModal();
          this._hyToast.showToast(`GENERIC.ERROR`, `BACKEND.${err.error.detail}`, {
            autoClose: true,
          });
          this._loadUserlist();
        });
      },
      actionButton1Class: 'btn_danger',
      actionButtonText1: 'GENERIC.CONFIRM_DELETE',
      action2: () => {
        this._hyModal.closeModal();
      },
      actionButton2Class: 'btn_outlined btn_secondary',
      actionButtonText2: 'GENERIC.ABORT'
    }
    this._hyModal.openModal(modalInfo);
  }

  private _loadUserlist(){
    this._userApiService.loadAllShort().then((result) => {
      this.userList = result.users;
      this.hasItemsSelected = false;
    }).catch((err) => {
      this._hyToast.showToast(`GENERIC.ERROR`, `BACKEND.${err.error.detail}`, {
        autoClose: true,
      });
      this.hasItemsSelected = false;
    });
  }
}
