import {Injectable} from '@angular/core';
import { ActivatedRouteSnapshot, Router, RouterStateSnapshot } from "@angular/router";
import {AuthService} from "./auth.service";
import {AuthUser} from "../../model/auth.model";

@Injectable({
  providedIn: 'root'
})
export class AdminGuardService  {

  public loggedUser: AuthUser | null = null;

  constructor(
    private _router: Router,
    private _authService: AuthService
  ) {
  }

  canActivate(next: ActivatedRouteSnapshot, state: RouterStateSnapshot) {
    this.loggedUser = this._authService.loggedInUser();

    if (this.loggedUser?.roles.includes('Admin')) {
      return true;
    }
    this._router.navigate(['forbidden']);
    return false;
  }
}
