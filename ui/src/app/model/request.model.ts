import {HttpHeaders, HttpParams} from "@angular/common/http";

export interface BackendRequest {
  method: BackendMethod,
  path: string;
}

export enum BackendMethod {
  GET = 'GET',
  POST = 'POST',
  PUT = 'PUT',
  DELETE = 'DELETE',
}

export interface BackendRequestConfig {
  urlParams?: Record<string, unknown>,
  queryString?: Record<string, unknown>,
  httpClientOptions?: HttpOptions;
}

export interface HttpOptions {
  data?: unknown,
  headers?: HttpHeaders,
  params?: HttpParams
}

export interface JWTRequestDTO {
  email: string;
  password: string;
}

export interface JWTResponseDTO {
  token: string;
}
