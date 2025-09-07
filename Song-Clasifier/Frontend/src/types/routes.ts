export interface RouteConfig {
  path: string;
  component: React.ComponentType;
  exact?: boolean;
  private?: boolean;
  admin?: boolean;
  title?: string;
  description?: string;
}

export interface NavigationItem {
  name: string;
  href: string;
  icon?: React.ComponentType;
  current?: boolean;
  children?: NavigationItem[];
}

// frontend/src/types/theme.ts

export interface Theme {
  colors: {
    primary: Record<number, string>;
    secondary: Record<number, string>;
    accent: Record<number, string>;
    gray: Record<number, string>;
  };
  fonts: {
    sans: string;
    display: string;
  };
  breakpoints: {
    xs: string;
    sm: string;
    md: string;
    lg: string;
    xl: string;
    '2xl': string;
    '3xl': string;
  };
}