export interface AuthUser {
  token: string;
  expiresAt: string;
  username: string;
  name: string;
  code: string;
  roles: Array<string>;
}
