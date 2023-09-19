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

export enum HySubMenu {
  UI = 'ui',
  PAGES = 'pages',
  APPS = 'apps',
  MENU = 'menu',
  CONFIG = 'config',
}

