import {Pipe, PipeTransform} from '@angular/core';

@Pipe({
  name: 'minutesToHours'
})
export class MinutesToHoursPipe implements PipeTransform {

  transform(value: number, ...args: unknown[]): string {
    let hours = Math.floor(value / 60);
    let minutes = Math.floor(value % 60);
    return this._pad(hours, 2) + ':' + this._pad(minutes,2);
  }

  private _pad(num: number, size: number): string {
    let s = num + "";
    while (s.length < size) s = "0" + s;
    return s;
  }

}
