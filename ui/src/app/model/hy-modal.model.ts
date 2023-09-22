export interface HyModalInfo<T, U> {
  title: string;
  context: string;
  action1: (arg?: T) => U;
  action2?: (arg?: T) => U;
  actionButtonText1: string;
  actionButtonText2?: string;
  actionButton1Class: string;
  actionButton2Class?: string;
}
