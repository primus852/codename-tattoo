import {Injectable} from '@angular/core';
import {RequestService} from "../common/request.service";
import {BackendMethod, BackendRequestConfig, JWTResponseDTO} from "../../model/request.model";
import {JwtHelperService} from "@auth0/angular-jwt";
import * as moment from 'moment/moment';
import {BehaviorSubject, Observable, Subject} from "rxjs";


@Injectable({
  providedIn: 'root'
})
export class AuthService {

  private loginSubject: Subject<boolean> = new BehaviorSubject<boolean>(false);
  public loginState: Observable<boolean> = this.loginSubject.asObservable();

  constructor(
    private _requestService: RequestService,
    private _jwtHelper: JwtHelperService
  ) {
    this.loginSubject.next(this.isLoggedIn());
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
    const decodedToken = this._jwtHelper.decodeToken(token)

    localStorage.setItem('id_token', token);
    localStorage.setItem('expires_at', JSON.stringify(decodedToken.exp));
    this.loginSubject.next(true);
  }

  public logout() {
    localStorage.removeItem('id_token');
    localStorage.removeItem('expires_at');
    this.loginSubject.next(false);
    /**
     * TODO: Invalidate Token
     */
  }

  public isLoggedIn() {
    return moment().isBefore(this.getExpiration());
  }

  public isLoggedOut() {
    return !this.isLoggedIn();
  }

  getExpiration() {
    const expiration: string | null = localStorage.getItem('expires_at');
    return moment.unix(Number(expiration));
  }

}
