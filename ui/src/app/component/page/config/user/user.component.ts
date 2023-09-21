import {Component, OnInit} from '@angular/core';
import {UserCreate, UserRole, UserShort} from "../../../../model/user.model";
import {FormBuilder, FormGroup, Validators} from "@angular/forms";
import {HyToastService} from "../../../../service/hy-toast/hy-toast.service";
import {UserApiService} from "../../../../service/api/user-api.service";
import {Uuid} from "../../../../model/uuid.model";

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
    private _fb: FormBuilder,
    private _hyToast: HyToastService,
    private _userApiService: UserApiService
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
    this._userApiService.loadAllShort().then((result) => {
      this.userList = result.users;
    }).catch((error) => {
      this._hyToast.showToast('Benutzerliste nicht geladen!', error.message, {
        autoClose: false,
      });
    })
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
      this._hyToast.showToast('User nicht erstellt!', err, {
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
      this._hyToast.showToast('Erfolg!', 'Benutzer <strong>' + response.name + '</strong> erstellt.', {
        autoClose: true,
      });
      this.userList.push(response);
    }).catch((error: Error) => {
      this._hyToast.showToast('Benutzer nicht erstellt!', error.message, {
        autoClose: false,
      });
    })
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
      const deletedIds = response.deleted;

      this.userList = this.userList.filter(user => !deletedIds.includes(user.id));
      this.itemsSelected = this.itemsSelected.filter(id => !deletedIds.includes(id));

      this.hasItemsSelected = this.itemsSelected.length > 0;

      this._hyToast.showToast('Erfolg!', 'Benutzer gelöscht', {
        autoClose: true,
      });
    }).catch((err) => {
      this._hyToast.showToast('Benutzer nicht gelöscht!', err.message, {
        autoClose: false,
      });
    });
  }
}
