import { create } from 'zustand';
import { persist } from 'zustand/middleware';
import { AuthState, AuthActions, LoginRequest, User } from '../types';
import { authService } from '../services/authService';
import toast from 'react-hot-toast';

interface AuthStore extends AuthState, AuthActions {}

export const useAuthStore = create<AuthStore>()(
  persist(
    (set, get) => ({
      // Estado inicial
      user: null,
      token: null,
      refreshToken: null,
      isAuthenticated: false,
      isLoading: false,

      // Actions
      login: async (credentials: LoginRequest) => {
        set({ isLoading: true });
        try {
          const response = await authService.login(credentials);
          
          // Salvar tokens
          authService.setTokens(response.token, response.refresh_token);
          
          // Atualizar estado
          set({
            user: response.user,
            token: response.token,
            refreshToken: response.refresh_token,
            isAuthenticated: true,
            isLoading: false,
          });

          toast.success('Login realizado com sucesso!');
        } catch (error: any) {
          set({ isLoading: false });
          toast.error(error.message || 'Erro ao fazer login');
          throw error;
        }
      },

      logout: () => {
        authService.logout().catch(() => {
          // Ignore error on logout
        });
        
        authService.clearTokens();
        
        set({
          user: null,
          token: null,
          refreshToken: null,
          isAuthenticated: false,
          isLoading: false,
        });

        toast.success('Logout realizado com sucesso!');
      },

      refreshToken: async () => {
        try {
          const response = await authService.refresh();
          
          authService.setTokens(response.token, response.refresh_token);
          
          set({
            token: response.token,
            refreshToken: response.refresh_token,
          });
        } catch (error) {
          get().logout();
          throw error;
        }
      },

      setUser: (user: User) => {
        set({ user });
      },

      checkAuth: async () => {
        const token = authService.getToken();
        if (!token) {
          return;
        }

        set({ isLoading: true });
        try {
          const user = await authService.me();
          set({
            user,
            token,
            refreshToken: localStorage.getItem('refresh_token'),
            isAuthenticated: true,
            isLoading: false,
          });
        } catch (error) {
          authService.clearTokens();
          set({
            user: null,
            token: null,
            refreshToken: null,
            isAuthenticated: false,
            isLoading: false,
          });
        }
      },
    }),
    {
      name: 'auth-storage',
      partialize: (state) => ({
        user: state.user,
        token: state.token,
        refreshToken: state.refreshToken,
        isAuthenticated: state.isAuthenticated,
      }),
    }
  )
);