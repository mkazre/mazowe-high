// Mirrors backend/public/assets/css/site.css and BUILD-PLAN.md §0 design tokens.
export const colors = {
  blue: '#0b3d6b',
  blue700: '#08294a',
  blue100: '#e7eef5',
  red: '#ec3013',
  red600: '#dd2b0f',
  red100: '#fff2ef',
  ink: '#201e1d',
  ground: '#f3f2f2',
  paper: '#ffffff',
  divider: '#e2e0dd',
  white: '#ffffff',
  muted: '#4a4744',
};

export const radius = 0; // square corners everywhere, per BUILD-PLAN

export const spacing = {
  xs: 6,
  sm: 10,
  md: 16,
  lg: 24,
  xl: 32,
};

export const font = {
  heading: 'System', // swap for an Archivo custom font load once bundled
  body: 'System',
};

export const theme = { colors, radius, spacing, font };
export default theme;
