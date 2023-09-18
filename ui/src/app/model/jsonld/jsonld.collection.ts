import {ContextObject} from "./jsonld";

export interface JsonLDCollection<T> {
  '@context': string | ContextObject;
  '@id': string;
  '@type': string;
  'hydra:totalItems': number;
  'hydra:member': T[];
  'hydra:view': {
    '@id': string;
    '@type': string;
    'hydra:first': string;
    'hydra:last': string;
    'hydra:next': string;
  };
}
