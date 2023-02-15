import {Uuid} from "./uuid.model";
import {UserModelMinimal} from "./user.model";
import {ClientModelMinimal} from "./client.model";
import {ConfiguredRateHour} from "./config.backend.model";

/**
 * All TimeTrackings
 */
export interface TimeTrackingAllResponseDTO {
  timeTrackings: Array<TimeTrackingDetail>;
}

export interface TimeTrackingDetail {
  id: Uuid;
  dateStart: Date;
  dateEnd: Date;
  description: string;
  status: TimeTrackingStatus;
  user: UserModelMinimal;
  client: ClientModelMinimal;
  minutesPerSlot: MinutesPerSlot;
  overrideToRateHourId?: Uuid;
  configuredRateHours: Array<ConfiguredRateHour>;
}

export interface MinutesPerSlot {
  slots: Array<TimeTrackingSlot>;
  unallocated: number;
  total: MinutesTotalSlot;
}

export interface MinutesTotalSlot {
  minutes: number;
  priceNet: number;
}

export interface TimeTrackingSlot {
  id: Uuid;
  minutes: number;
  category: string;
  priceNet: number;
  priceNetTotal: number;
}

export enum TimeTrackingStatus {
  Open = 'OPEN',
  None = 'NONE',
  NoneShow = 'NONE_SHOW',
  Finished = 'Finished'
}
