import {Injectable} from '@angular/core';
import {RequestService} from "../common/request.service";
import {TimeTrackingAllResponseDTO, TimeTrackingCreateDTO, TimeTrackingDetail} from "../../model/timetracking.model";
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

  /**
   * Create a new TimeTracking Entry
   * @param data
   */
  public create(data: TimeTrackingCreateDTO): Promise<TimeTrackingDetail> {
    const requestParam: BackendRequestConfig = {
      httpClientOptions: {
        data: data
      }
    }

    return this._requestService.makeRequest<TimeTrackingDetail>(
      {
        path: '/time-tracking',
        method: BackendMethod.POST
      }, requestParam
    );

  }
}
