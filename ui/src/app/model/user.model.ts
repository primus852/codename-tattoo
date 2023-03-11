import {Uuid} from "./uuid.model";

export interface UserShortAllResponseDTO {
  users: Array<UserShort>;
}

export interface UserShort {
  id: Uuid;
  email: string;
  code: string;
}
