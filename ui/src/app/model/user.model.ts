import {Uuid} from "./uuid.model";

export interface UserShortAllResponseDTO {
  users: Array<UserShort>;
}

export interface UserShort {
  id: Uuid;
  name: string;
  email: string;
  code: string;
  roles: Array<UserRole>;
}

export interface UserCreate {
  email: string;
  code: string;
  name: string;
  password: string;
  roles: Array<UserRole>;
}

export interface UserCreateResponse {
  email: string;
  code: string;
  name: string;
  id: Uuid;
  roles: Array<UserRole>;
}

export enum UserRole {
  ADMIN = 'Admin',
  USER = 'User',
  FINANCE = 'Finance',
  APPROVER = 'Approver'
}
