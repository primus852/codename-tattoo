import {Uuid} from "./uuid.model";
import {UserShort} from "./user.model";
import {ClientModelMinimal} from "./client.model";
import {ConfiguredPrice} from "./config.backend.model";

/**
 * All TimeTrackings
 */
export interface TimeTrackingAllResponseDTO {
  timeTrackings: Array<TimeTrackingDetail>;
  configuredPrices: Array<ConfiguredPrice>;
}

export interface TimeTrackingDetail {
  id: Uuid;
  dateStart: Date;
  dateEnd: Date;
  description: string;
  status: TimeTrackingStatus;
  user: UserShort;
  client: ClientModelMinimal;
  minutesPerSlot: MinutesPerSlot;
  overrideToPriceId?: Uuid;
}

export interface MinutesPerSlot {
  slots: Array<TimeTrackingSlot>;
  unallocated: number;
  categories: Array<string>;
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
