import { Musica, FiltroMusica, Estatisticas, User, PaginationInfo } from '../types';
import { httpClient } from './httpClient';

export class AdminService {
  async listarSugestoes(filtros: FiltroMusica = {}): Promise<{
    sugestoes: Musica[];
    pagination: PaginationInfo;
  }> {
    const response = await httpClient.get('/admin/sugestoes', { params: filtros });
    return {
      sugestoes: response.data.data,
      pagination: response.data.pagination,
    };
  }

  async listarMusicas(filtros: FiltroMusica = {}): Promise<{
    musicas: Musica[];
    pagination: PaginationInfo;
  }> {
    const response = await httpClient.get('/admin/musicas', { params: filtros });
    return {
      musicas: response.data.data,
      pagination: response.data.pagination,
    };
  }

  async obterEstatisticas(): Promise<Estatisticas> {
    const response = await httpClient.get<Estatisticas>('/admin/estatisticas');
    return response.data;
  }

  async listarUsuarios(filtros: { page?: number; per_page?: number } = {}): Promise<{
    usuarios: User[];
    pagination: PaginationInfo;
  }> {
    const response = await httpClient.get('/admin/usuarios', { params: filtros });
    return {
      usuarios: response.data.data,
      pagination: response.data.pagination,
    };
  }

  async ativarUsuario(id: number): Promise<User> {
    const response = await httpClient.post<User>(`/admin/usuarios/${id}/ativar`);
    return response.data;
  }

  async desativarUsuario(id: number): Promise<User> {
    const response = await httpClient.post<User>(`/admin/usuarios/${id}/desativar`);
    return response.data;
  }

  async excluirUsuario(id: number): Promise<void> {
    await httpClient.delete(`/admin/usuarios/${id}`);
  }
}

export const adminService = new AdminService();