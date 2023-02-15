import {Uuid} from "./uuid.model";

export interface UserModelMinimal {
  id: Uuid;
  email: string;
  code: string;
}
