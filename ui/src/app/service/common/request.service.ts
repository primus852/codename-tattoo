import {Injectable} from '@angular/core';
import {HttpClient, HttpErrorResponse} from "@angular/common/http";
import {environment} from "../../../environments/environment";
import {catchError, firstValueFrom, Observable, tap, throwError} from "rxjs";
import {BackendMethod, BackendRequest, BackendRequestConfig, HttpOptions} from "../../model/request.model";

@Injectable({
  providedIn: 'root'
})
export class RequestService {

  constructor(
    private _http: HttpClient
  ) {
  }

  private static _prefixUrl(endpoint: BackendRequest, formattedUrl: string): string {
    return environment.url_prefix + RequestService._loadPrefixUrl(endpoint, formattedUrl);
  }

  private static _loadPrefixUrl(endpoint: BackendRequest, formattedUrl: string): string {
    return environment.load_excluded.includes(endpoint.path) ? formattedUrl : environment.load_prefix + formattedUrl;
  }

  /**
   * Diese Methode soll ab sofort für alle Calls ans Backend genutzt werden, um eine höhere Abstraktionsebene als
   * den HttpClient zu haben und Tests ggf. einfacher zu gestalten. Direkte Injections des HttpClients sind deprecated (bzw.
   * konnte nur formatEndpointUrl als deprecated markiert werden) und müssen refactored werden.
   * @param endpoint {BackendRequest}: Endpoint inm Backend, inkl. verwendeter Method
   * @param requestConfig
   * @param requestConfig.urlParams {any = {}}: Url Params, welche in der URL zu ersetzen sind
   *  * @example params:
   *        Endpoint: /user/{id}/activate/{account}
   *        params: {
   *          id: '123456',
   *          account: '9874697'
   *        }
   *        Result: /user/123456/activate/9874697
   * @param requestConfig.data?: Body des Requests
   * @param requestConfig.headers {HttpHeaders}: Zusätzliche Header. Role wird hier mit angehängt
   * @param requestConfig.params {HttpParams}: Evtl. benötigte HttpParams
   */
  public makeRequest<T>(endpoint: BackendRequest, requestConfig: BackendRequestConfig): Promise<T> {
    return firstValueFrom(this.makeRequestObservable(endpoint, requestConfig));
  }

  public makeRequestObservable<T>(endpoint: BackendRequest, requestConfig: BackendRequestConfig): Observable<T> | Observable<never> {
    const formattedUrl = this._formatEndpointUrl(endpoint, requestConfig);
    const httpClientOptions: HttpOptions = requestConfig.httpClientOptions || {};

    switch (endpoint.method) {
      case BackendMethod.POST:
        return this._makePostRequest<T>(formattedUrl, httpClientOptions);
      case BackendMethod.PUT:
        return this._makePutRequest<T>(formattedUrl, httpClientOptions);
      case BackendMethod.GET:
        return this._makeGetRequest<T>(formattedUrl, httpClientOptions);
      case BackendMethod.DELETE:
        return this._makeDeleteRequest<T>(formattedUrl, httpClientOptions);
      default: {
        const error = new HttpErrorResponse({
          error: 'Method' + endpoint.method + ' is not yet implemented',
          headers: httpClientOptions.headers,
          status: 500
        });
        return throwError(() => {
          new Error(JSON.stringify(error));
        });
      }
    }
  }

  private _makePostRequest<T>(url: string, httpClientOptions: HttpOptions): Observable<T> {

    return this._http.post<T>(url, httpClientOptions.data, httpClientOptions).pipe(
      tap(result => {
        return result;
      }),
      catchError((error: HttpErrorResponse) => {
        return throwError(() => {
          new Error(JSON.stringify(error));
        });
      })
    );

  }

  private _makeDeleteRequest<T>(url: string, httpClientOptions: HttpOptions): Observable<T> {

    return this._http.delete<T>(url, httpClientOptions).pipe(
      tap(result => {
        return result;
      }),
      catchError((error: HttpErrorResponse) => {
        return throwError(() => {
          new Error(JSON.stringify(error));
        });
      })
    );

  }

  private _makePutRequest<T>(url: string, httpClientOptions: HttpOptions): Observable<T> {

    return this._http.put<T>(url, httpClientOptions.data, httpClientOptions).pipe(
      tap(result => {
        return result;
      }),
      catchError((error: HttpErrorResponse) => {
        return throwError(() => {
          new Error(JSON.stringify(error));
        });
      })
    );

  }

  private _makeGetRequest<T>(url: string, httpClientOptions: HttpOptions): Observable<T> {

    return this._http.get<T>(url, httpClientOptions).pipe(
      tap(result => {
        return result;
      }),
      catchError((error: HttpErrorResponse) => {
        return throwError(() => {
          new Error(JSON.stringify(error));
        });
      })
    );
  }

  private _formatEndpointUrl(endpoint: BackendRequest, config: BackendRequestConfig) {
    let url = endpoint.path;

    if (config.urlParams) {
      url = this._insertURLParams(url, config.urlParams);
    }

    if (config.queryString) {
      url = this._insertQueryParams(url, config.queryString);
    }

    return RequestService._prefixUrl(endpoint, url);
  }

  /**
   * Replace {*} in endpoint with matching params
   */
  private _insertURLParams(e: string, params: Record<string, unknown> = {}) {
    return e.split(/({\w+?})/g).map(function (v) {
      const replaced = v.replace(/{(\w+?)}/g, '$1');
      return params[replaced] || v;
    }).join('');
  }

  /**
   * Insert query parameters at end of the url
   */
  private _insertQueryParams(url: string, params: Record<string, unknown> = {}) {
    return url + '?' + Object.keys(params).map((key) => {
      return [key, params[key]].join('=');
    }).join('&');
  }
}
