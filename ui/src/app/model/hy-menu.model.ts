export enum HyMenuStatus {
  VISIBLE = 'visible',
  HIDDEN = 'hidden',
}

export enum HyMenuType {
  DEFAULT = 'default',
  ICON = 'icon',
  WIDE = 'wide'
}

export interface HyMenuSettings {
  visibility: HyMenuStatus;
  type: HyMenuType;
}

