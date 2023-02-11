import {Injectable} from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export abstract class HyUtilsService {

  public static capitalizeFirstLetter(word: string) {
    return word.charAt(0).toUpperCase() + word.slice(1);
  }
}
