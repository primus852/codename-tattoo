import {Component, OnInit} from '@angular/core';
import {TimeTrackingApiService} from "../../../service/api/time-tracking-api.service";
import {TimeTrackingDetail} from "../../../model/timetracking.model";
import {ConfiguredRateHour} from "../../../model/config.backend.model";

@Component({
  selector: 'app-time-tracking-overview',
  templateUrl: './time-tracking-overview.component.html',
  styleUrls: ['./time-tracking-overview.component.scss']
})
export class TimeTrackingOverviewComponent implements OnInit {

  public timeTrackings: Array<TimeTrackingDetail> = [];
  public categories: Array<string> = [];

  constructor(private _timeTrackingApi: TimeTrackingApiService) {
  }

  ngOnInit() {
    this._timeTrackingApi.loadAll().then((timeTrackings) => {
      this.timeTrackings = timeTrackings.timeTrackings;
      if (this.timeTrackings.length > 0) {
        this.categories = this._consolidate_categories(timeTrackings.timeTrackings[0].configuredRateHours);

        this.timeTrackings.forEach((tt)=>{
          
        })

      }
    });
  }

  private _consolidate_categories(configuredHours: Array<ConfiguredRateHour>) {
    const categories: Array<string> = [];
    configuredHours.forEach((cfgHour) => {
      if (!categories.includes(cfgHour.category)) {
        categories.push(cfgHour.category);
      }
    });
    return categories;
  }

}
