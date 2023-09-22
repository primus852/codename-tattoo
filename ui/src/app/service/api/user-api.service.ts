import {Injectable} from '@angular/core';
import {RequestService} from "../common/request.service";
import {TimeTrackingAllResponseDTO} from "../../model/timetracking.model";
import {BackendMethod, BackendRequestConfig} from "../../model/request.model";
import {
  UserCreate,
  UserCreateResponse,
  UsersDelete,
  UsersDeleteResponse, UserShort,
  UserShortAllResponseDTO
} from "../../model/user.model";
import {Uuid} from "../../model/uuid.model";

@Injectable({
  providedIn: 'root'
})
export class UserApiService {

  constructor(
    private _requestService: RequestService
  ) {
  }

  public deleteUserById(id: Uuid): Promise<void>{
    const requestParam: BackendRequestConfig = {
      urlParams: {id}
    };

    return this._requestService.makeRequest<void>(
      {
        path: '/user/{id}',
        method: BackendMethod.DELETE
      }, requestParam
    );
  }

  public loadUserById(id: Uuid): Promise<UserShort>{
    const requestParam: BackendRequestConfig = {
      urlParams: {id}
    };

    return this._requestService.makeRequest<UserShort>(
      {
        path: '/user/{id}',
        method: BackendMethod.GET
      }, requestParam
    );
  }

  public batchDeleteUsers(users: UsersDelete): Promise<UsersDeleteResponse>{
    const requestParam: BackendRequestConfig = {
      httpClientOptions: {
        data: users
      }
    };

    return this._requestService.makeRequest<UsersDeleteResponse>(
      {
        path: '/users',
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
