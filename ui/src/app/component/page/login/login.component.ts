import {Component, OnInit} from '@angular/core';
import {FormBuilder, FormGroup, Validators} from "@angular/forms";
import {Router} from "@angular/router";
import {AuthService} from "../../../service/auth/auth.service";
import {HyToastService} from "../../../service/hy-toast/hy-toast.service";

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss']
})
export class LoginComponent implements OnInit{
  public formLogin: FormGroup;
  public passwordVisible: boolean = false;
  public inputType: string = 'password';
  public isLoading: boolean = false;

  constructor(
    private _fb: FormBuilder,
    private _router: Router,
    private _authService: AuthService,
    private _hyToast: HyToastService
  ) {
    this.formLogin = this._fb.group({
      email: ['', Validators.required],
      password: ['', Validators.required]
    })
  }

  ngOnInit() {
    this._authService.logout();
  }

  public doLogin() {
    const val = this.formLogin.value;
    if (val.email && val.password) {
      this.isLoading = true;
      this._authService.login(val.email, val.password).then((jwt) => {
        this._authService.setSession(jwt.token);
        this._router.navigate(['blank']);
      }).finally(() => {
        this.isLoading = false;
      }).catch(() => {
        this._hyToast.showToast('Error', 'Could not log in', {
          autoClose: true,
        });
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
