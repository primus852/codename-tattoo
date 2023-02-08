export interface HyToastInfo {
  title: string;
  context: string;
  visible: boolean;
}

export interface HyToastConfig {
  autoClose?: boolean;
  autoCloseTimeout?: number;
}
