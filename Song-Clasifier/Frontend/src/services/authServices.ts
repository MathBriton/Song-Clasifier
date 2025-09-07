
import { LoginRequest, LoginResponse, User } from '../types';
import { httpClient } from './httpClient';

export class AuthService {
  async login(credentials: LoginRequest): Promise<LoginResponse> {
    const response = await httpClient.post<LoginResponse>('/auth/login', credentials);
    return response.data;
  }

  async logout(): Promise<void> {
    await httpClient.post('/auth/logout');
  }

  async refresh(): Promise<LoginResponse> {
    const refreshToken = localStorage.getItem('refresh_token');
    const response = await httpClient.post<LoginResponse>('/auth/refresh', {
      refresh_token: refreshToken,
    });
    return response.data;
  }

  async me(): Promise<User> {
    const response = await httpClient.get<User>('/auth/me');
    return response.data;
  }

  setTokens(token: string, refreshToken: string): void {
    localStorage.setItem('auth_token', token);
    localStorage.setItem('refresh_token', refreshToken);
  }

  clearTokens(): void {
    localStorage.removeItem('auth_token');
    localStorage.removeItem('refresh_token');
  }

  getToken(): string | null {
    return localStorage.getItem('auth_token');
  }

  hasToken(): boolean {
    return !!this.getToken();
  }
}

export const authService = new AuthService();