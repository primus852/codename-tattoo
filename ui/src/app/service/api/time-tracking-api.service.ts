import {Injectable} from '@angular/core';
import {RequestService} from "../common/request.service";
import {TimeTrackingAllResponseDTO} from "../../model/timetracking.model";
import {BackendMethod, BackendRequestConfig} from "../../model/request.model";

@Injectable({
  providedIn: 'root'
})
export class TimeTrackingApiService {

  constructor(
    private _requestService: RequestService
  ) {
  }

  public loadAll(): Promise<TimeTrackingAllResponseDTO> {
    const requestParam: BackendRequestConfig = {};

    return this._requestService.makeRequest<TimeTrackingAllResponseDTO>(
      {
        path: '/time-trackings',
        method: BackendMethod.GET
      }, requestParam
    );
  }
}
