import { create } from 'zustand';

interface UiState {
  sidebarOpen: boolean;
  theme: 'light' | 'dark';
  isLoading: boolean;
  notifications: Notification[];
}

interface Notification {
  id: string;
  type: 'success' | 'error' | 'warning' | 'info';
  title: string;
  message: string;
  duration?: number;
}

interface UiActions {
  toggleSidebar: () => void;
  setSidebarOpen: (open: boolean) => void;
  toggleTheme: () => void;
  setTheme: (theme: 'light' | 'dark') => void;
  setLoading: (loading: boolean) => void;
  addNotification: (notification: Omit<Notification, 'id'>) => void;
  removeNotification: (id: string) => void;
  clearNotifications: () => void;
}

interface UiStore extends UiState, UiActions {}

export const useUiStore = create<UiStore>()((set, get) => ({
  // Estado inicial
  sidebarOpen: false,
  theme: 'light',
  isLoading: false,
  notifications: [],

  // Actions
  toggleSidebar: () => {
    set({ sidebarOpen: !get().sidebarOpen });
  },

  setSidebarOpen: (open: boolean) => {
    set({ sidebarOpen: open });
  },

  toggleTheme: () => {
    const newTheme = get().theme === 'light' ? 'dark' : 'light';
    set({ theme: newTheme });
    localStorage.setItem('theme', newTheme);
  },

  setTheme: (theme: 'light' | 'dark') => {
    set({ theme });
    localStorage.setItem('theme', theme);
  },

  setLoading: (loading: boolean) => {
    set({ isLoading: loading });
  },

  addNotification: (notification) => {
    const id = Math.random().toString(36).substr(2, 9);
    const newNotification = { ...notification, id };
    
    set({ notifications: [...get().notifications, newNotification] });

    // Auto remove após duração especificada
    if (notification.duration !== 0) {
      setTimeout(() => {
        get().removeNotification(id);
      }, notification.duration || 5000);
    }
  },

  removeNotification: (id: string) => {
    set({ 
      notifications: get().notifications.filter(n => n.id !== id) 
    });
  },

  clearNotifications: () => {
    set({ notifications: [] });
  },
}));