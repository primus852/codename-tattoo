import {Injectable, NgZone} from '@angular/core';
import {Observable} from "rxjs";
import {environment} from "../../../environments/environment";

@Injectable({
  providedIn: 'root'
})
export class SseService {

  constructor(private _zone: NgZone) {
  }

  private static _getEventSource(url: string): EventSource {
    return new EventSource(url);
  }


  public getServerSentEvent<T>(url: string):Observable<T> {
    return new Observable((observer: any) => {
      const eventSource = SseService._getEventSource(environment.mercure + url);

      eventSource.onmessage = event => {
        this._zone.run(() => {
          observer.next(JSON.parse(event.data))
        });
      };

      eventSource.onerror = error => {
        this._zone.run(() => {
          observer.error(error);
        });
      };

    });
  }
}
