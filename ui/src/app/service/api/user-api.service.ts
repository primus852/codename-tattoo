import {Injectable} from '@angular/core';
import {RequestService} from "../common/request.service";
import {TimeTrackingAllResponseDTO} from "../../model/timetracking.model";
import {BackendMethod, BackendRequestConfig} from "../../model/request.model";
import {
  UserCreate,
  UserCreateResponse,
  UsersDelete,
  UsersDeleteResponse,
  UserShortAllResponseDTO
} from "../../model/user.model";

@Injectable({
  providedIn: 'root'
})
export class UserApiService {

  constructor(
    private _requestService: RequestService
  ) {
  }

  public batchDeleteUsers(users: UsersDelete): Promise<UsersDeleteResponse>{
    const requestParam: BackendRequestConfig = {
      httpClientOptions: {
        data: users
      }
    };

    return this._requestService.makeRequest<UsersDeleteResponse>(
      {
        path: '/admin/users',
        method: BackendMethod.PATCH
      }, requestParam
    );
  }

  public createUser(user: UserCreate): Promise<UserCreateResponse> {
    const requestParam: BackendRequestConfig = {
      httpClientOptions: {
        data: user
      }
    };

    return this._requestService.makeRequest<UserCreateResponse>(
      {
        path: '/admin/user',
        method: BackendMethod.POST
      }, requestParam
    );
  }

  public loadAllShort(): Promise<UserShortAllResponseDTO> {
    const requestParam: BackendRequestConfig = {};

    return this._requestService.makeRequest<UserShortAllResponseDTO>(
      {
        path: '/users/short',
        method: BackendMethod.GET
      }, requestParam
    );
  }
}
