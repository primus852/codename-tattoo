export interface JsonLD {
  '@context': string | ContextObject;
  '@id': string;
  '@type': string;
}

export type ContextObject = {
  '@vocab': string;
  'hydra': string;
  [key: string]: string | undefined;
};
