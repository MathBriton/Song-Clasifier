export interface Music {
  id: number;
  titulo: string;
  visualizacoes: number;
  youtube_id: string;
  thumb: string;
}

export interface ApiResponse {
  success: boolean;
  message?: string;
  data?: any;
}