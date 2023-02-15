import {Component, OnInit} from '@angular/core';
import {TimeTrackingApiService} from "../../../service/api/time-tracking-api.service";
import {TimeTrackingDetail} from "../../../model/timetracking.model";

@Component({
  selector: 'app-time-tracking-overview',
  templateUrl: './time-tracking-overview.component.html',
  styleUrls: ['./time-tracking-overview.component.scss']
})
export class TimeTrackingOverviewComponent implements OnInit {

  public timeTrackings: Array<TimeTrackingDetail> = [];

  constructor(private _timeTrackingApi: TimeTrackingApiService) {
  }

  ngOnInit() {

    this._timeTrackingApi.loadAll().then((timeTrackings) => {
      this.timeTrackings = timeTrackings.timeTrackings;
    })

  }

}
