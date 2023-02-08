import {Injectable} from '@angular/core';
import {RequestService} from "../common/request.service";
import {BackendMethod, BackendRequestConfig, JWTResponseDTO} from "../../model/request.model";
import {JwtHelperService} from "@auth0/angular-jwt";
import * as moment from 'moment/moment';


@Injectable({
  providedIn: 'root'
})
export class AuthService {

  constructor(
    private _requestService: RequestService,
    private _jwtHelper: JwtHelperService
  ) {
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
  }

  public logout() {
    localStorage.removeItem('id_token');
    localStorage.removeItem('expires_at');
  }

  public isLoggedIn() {
    return moment().isBefore(this.getExpiration());
  }

  public isLoggedOut() {
    return !this.isLoggedIn();
  }

  getExpiration() {
    const expiration: string = localStorage.getItem('expires_at') as string;
    const expiresAt = JSON.parse(expiration);
    return moment(expiresAt);
  }

}
