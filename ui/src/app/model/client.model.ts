import {Uuid} from "./uuid.model";

export interface ClientModelMinimal {
  id: Uuid;
  name: string;
  nameShort: string;
  clientNumber: string;
}
