import {Component, OnDestroy, OnInit} from '@angular/core';
import {TimeTrackingApiService} from "../../../service/api/time-tracking-api.service";
import {TimeTrackingDetail} from "../../../model/timetracking.model";
import {ConfiguredPrice} from "../../../model/config.backend.model";
import {UserApiService} from "../../../service/api/user-api.service";
import {UserShort} from "../../../model/user.model";
import {AuthService} from "../../../service/auth/auth.service";
import {AuthUser} from "../../../model/auth.model";
import {Uuid} from "../../../model/uuid.model";
import {SseService} from "../../../service/sse/sse.service";
import {Subscription} from "rxjs";
import {HyUtilsService} from "../../../service/hy-utils/hy-utils.service";

@Component({
  selector: 'app-time-tracking-overview',
  templateUrl: './time-tracking-overview.component.html',
  styleUrls: ['./time-tracking-overview.component.scss']
})
// TODO: CONTINUE HERE IN THE LIST WITH THE HOURS OVERVIEW
export class TimeTrackingOverviewComponent implements OnInit, OnDestroy {

  public timeTrackings: Array<TimeTrackingDetail> = [];
  public users: Array<UserShort> = [];
  public loggedUser: AuthUser | null = null;
  public overwriteSelected: string | null = null;
  public categories: Array<string> = [];
  private _sseSubscription: Subscription | null = null;

  constructor(
    private _timeTrackingApi: TimeTrackingApiService,
    private _userApi: UserApiService,
    private _authService: AuthService,
    private _sseService: SseService
  ) {
  }

  ngOnInit() {

    this.loggedUser = this._authService.loggedInUser();

    this._timeTrackingApi.loadAll().then((timeTrackings) => {
      this.timeTrackings = timeTrackings.timeTrackings;
      if (this.timeTrackings.length > 0) {
        this.categories = this._consolidate_categories(timeTrackings.configuredPrices);
      }
    });

    this._userApi.loadAllShort().then((users) => {
      this.users = users.users;
    });

    /**
     * Subscribe to TimeTracking Events
     */
    this._sseSubscription = this._sseService.getServerSentEvent<TimeTrackingDetail>('/topics/time-tracking/new').subscribe((data) => {
      this.timeTrackings = [data].concat(this.timeTrackings);
    });

  }

  public ngOnDestroy() {
    if (this._sseSubscription) {
      this._sseSubscription.unsubscribe();
    }
  }

  private _consolidate_categories(configuredHours: Array<ConfiguredPrice>) {
    const categories: Array<string> = [];
    configuredHours.forEach((cfgHour) => {
      if (!categories.includes(cfgHour.category)) {
        categories.push(cfgHour.category);
      }
    });
    return categories;
  }

  public selectOverwriteCategory(category: string): void {

    if (this.overwriteSelected === category) {
      this.overwriteSelected = null;
    } else {
      this.overwriteSelected = category;
    }
  }

  public overwriteTimetrackingPrice(category: string, timeTrackingId: Uuid) {
    alert(category + ' overwritten (ID needed) for TT ' + timeTrackingId);
  }
}
