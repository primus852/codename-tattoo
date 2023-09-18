import {Uuid} from "./uuid.model";
import {JsonLD} from "./jsonld/jsonld";
import {JsonLDCollection} from "./jsonld/jsonld.collection";

export interface ClientModelMinimalDEPRECATED {
  id: Uuid;
  name: string;
  nameShort: string;
  clientNumber: string;
}

export interface Client extends JsonLD {
  id: Uuid;
  name: string;
  nameShort: string;
  timeTrackings: string[];
  clientNumber: string;
}

type ClientCollection = JsonLDCollection<Client>;
