export const environment = {
  production: true,
  baseTooltip: {
    arrow: true,
    placement: 'bottom',
    interactive: true,
    animation: 'scale',
    theme: 'light-border tooltip',
    touch: ['hold', 500],
    offset: [0, 12]
  },
  url_prefix: 'https://localhost',
  mercure: 'http://localhost:5013/.well-known/mercure?topic=',
  load_prefix: '/api',
  load_excluded: [
    '/auth'
  ] as Array<string>,
};
