export interface User {
  id: number;
  name: string;
  email: string;
  is_admin: boolean;
  is_active: boolean;
  email_verified_at: string | null;
  created_at: string;
  updated_at: string;
}

export interface Musica {
  id: number;
  titulo: string;
  artista: string;
  youtube_url: string;
  youtube_id: string;
  visualizacoes: number;
  visualizacoes_formatadas: string;
  thumbnail_url: string;
  duracao: number;
  duracao_formatada: string;
  status: MusicaStatus;
  status_label: string;
  user_id?: number;
  created_at: string;
  updated_at: string;
}

export type MusicaStatus = 'pendente' | 'aprovada' | 'reprovada';

export interface PaginationInfo {
  current_page: number;
  per_page: number;
  total: number;
  total_pages: number;
  has_next_page: boolean;
  has_previous_page: boolean;
}

export interface ApiResponse<T = any> {
  success: boolean;
  data: T;
  message: string;
  pagination?: PaginationInfo;
}

export interface LoginRequest {
  email: string;
  password: string;
  remember?: boolean;
}

export interface LoginResponse {
  user: User;
  token: string;
  refresh_token: string;
  token_type: string;
  expires_in: number;
}

export interface SugerirMusicaRequest {
  youtube_url: string;
}

export interface FiltroMusica {
  page?: number;
  per_page?: number;
  search?: string;
  status?: MusicaStatus;
  order_by?: string;
  order_direction?: 'asc' | 'desc';
}

export interface CriarMusicaRequest {
  titulo: string;
  artista: string;
  youtube_url: string;
  visualizacoes: number;
  thumbnail_url: string;
  duracao: number;
}

export interface AtualizarMusicaRequest extends CriarMusicaRequest {
  id: number;
}

export interface Estatisticas {
  total_musicas: number;
  musicas_aprovadas: number;
  musicas_pendentes: number;
  musicas_reprovadas: number;
  total_visualizacoes: number;
  media_visualizacoes: number;
  ultima_atualizacao: string;
}

export interface AuthState {
  user: User | null;
  token: string | null;
  refreshToken: string | null;
  isAuthenticated: boolean;
  isLoading: boolean;
}

export interface AuthActions {
  login: (credentials: LoginRequest) => Promise<void>;
  logout: () => void;
  refreshToken: () => Promise<void>;
  setUser: (user: User) => void;
  checkAuth: () => Promise<void>;
}