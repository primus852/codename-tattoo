import {Uuid} from "./uuid.model";

export interface ConfiguredRateHour {
  id: Uuid;
  hourFrom: string; // hh:mm
  hourTo: string; // hh:mm
  priceNet: number;
  category: string;
}
