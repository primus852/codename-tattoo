import {Injectable} from '@angular/core';
import {RequestService} from "../common/request.service";
import {TimeTrackingAllResponseDTO} from "../../model/timetracking.model";
import {BackendMethod, BackendRequestConfig} from "../../model/request.model";
import {UserShortAllResponseDTO} from "../../model/user.model";

@Injectable({
  providedIn: 'root'
})
export class UserApiService {

  constructor(
    private _requestService: RequestService
  ) {
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
