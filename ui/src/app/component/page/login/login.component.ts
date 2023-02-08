import {Component} from '@angular/core';
import {FormBuilder, FormControl, FormGroup, Validators} from "@angular/forms";
import {Router} from "@angular/router";
import {AuthService} from "../../../service/auth/auth.service";
import {ToastrService} from "ngx-toastr";

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss']
})
export class LoginComponent {
  public formLogin: FormGroup;
  public passwordVisible: boolean = false;
  public inputType: string = 'password';
  public isLoading: boolean = false;

  constructor(
    private _fb: FormBuilder,
    private _router: Router,
    private _authService: AuthService,
    private _toastr: ToastrService
  ) {
    this.formLogin = this._fb.group({
      email: ['', Validators.required],
      password: ['', Validators.required]
    })
  }

  public doLogin() {
    const val = this.formLogin.value;
    if (val.email && val.password) {
      this.isLoading = true;
      this._authService.login(val.email, val.password).then((jwt) => {
        this._authService.setSession(jwt.token);
        this._router.navigate(['dashboard']);
      }).finally(() => {
        this.isLoading = false;
      }).catch(() => {
        this._toastr.success('Hello world!', 'Toastr fun!');
        this._authService.logout();
      })
    }
  }

  public togglePassword() {
    if (this.passwordVisible) {
      this.inputType = 'password';
      this.passwordVisible = false;
    } else {
      this.inputType = 'text';
      this.passwordVisible = true;
    }
  }
}
