import {Injectable} from '@angular/core';
import {RequestService} from "../common/request.service";
import {BackendMethod, BackendRequestConfig, JWTResponseDTO} from "../../model/request.model";
import {JwtHelperService} from "@auth0/angular-jwt";
import * as moment from 'moment/moment';
import {BehaviorSubject, Observable, Subject} from "rxjs";
import {AuthUser} from "../../model/auth.model";
import {HyUtilsService} from "../hy-utils/hy-utils.service";


@Injectable({
  providedIn: 'root'
})
export class AuthService {

  private loginSubject: Subject<AuthUser | null> = new BehaviorSubject<AuthUser | null>(null);
  public loginState: Observable<AuthUser | null> = this.loginSubject.asObservable();

  constructor(
    private _requestService: RequestService,
    private _jwtHelper: JwtHelperService
  ) {
    this.loginSubject.next(this.loggedInUser());
  }

  public login(email: string, password: string): Promise<JWTResponseDTO> {
    const requestParam: BackendRequestConfig = {
      httpClientOptions: {
        data: {
          email: email,
          password: password
        }
      }
    };
    return this._requestService.makeRequest<JWTResponseDTO>(
      {
        path: '/auth',
        method: BackendMethod.POST
      }, requestParam
    );
  }

  public setSession(token: string) {
    const decodedToken = this._decodedToken(token);
    localStorage.setItem('id_token', token);
    localStorage.setItem('expires_at', JSON.stringify(decodedToken.exp));
    this.loginSubject.next(this.loggedInUser());
  }

  public logout() {
    localStorage.removeItem('id_token');
    localStorage.removeItem('expires_at');
    this.loginSubject.next(null);
    /**
     * TODO: Invalidate Token
     */
  }

  public loggedInUser(): AuthUser | null {
    const token = localStorage.getItem('id_token');
    if (token) {
      const decodedToken = this._decodedToken(token);
      const notExpired = moment().isBefore(this._getExpiration());

      const roles = this._extractRoles(decodedToken.roles);

      if (notExpired) {
        return {
          code: decodedToken.code,
          expiresAt: JSON.stringify(decodedToken.exp),
          name: decodedToken.name,
          token: token,
          username: decodedToken.username,
          roles: roles
        }
      } else {
        this.logout();
        return null;
      }
    } else {
      return null;
    }
  }

  private _extractRoles(rawRoles: Array<string>): Array<string> {

    const roles: Array<string> = [];

    rawRoles.forEach((role) => {
      let tmpRole = role.replace('ROLE_', '').toLowerCase();
      if (tmpRole !== 'user') {
        roles.push(HyUtilsService.capitalizeFirstLetter(tmpRole))
      }
    });

    if (roles.length === 0) {
      return ['User'];
    }

    return roles;

  }

  private _getExpiration() {
    const expiration: string | null = localStorage.getItem('expires_at');
    return moment.unix(Number(expiration));
  }

  private _decodedToken(token: string) {
    return this._jwtHelper.decodeToken(token);
  }

}
