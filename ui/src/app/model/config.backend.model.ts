import {Uuid} from "./uuid.model";

export interface ConfiguredPrice {
  id: Uuid;
  hourFrom: string; // hh:mm
  hourTo: string; // hh:mm
  priceNet: number;
  category: string;
}
