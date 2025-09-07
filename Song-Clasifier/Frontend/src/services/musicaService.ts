import {
  Musica,
  FiltroMusica,
  SugerirMusicaRequest,
  CriarMusicaRequest,
  AtualizarMusicaRequest,
  PaginationInfo,
} from '../types';
import { httpClient } from './httpClient';

export class MusicaService {
  async getTop5(): Promise<Musica[]> {
    const response = await httpClient.get<Musica[]>('/musicas/top5');
    return response.data;
  }

  async listar(filtros: FiltroMusica = {}): Promise<{
    musicas: Musica[];
    pagination: PaginationInfo;
  }> {
    const response = await httpClient.get('/musicas', { params: filtros });
    return {
      musicas: response.data.data,
      pagination: response.data.pagination,
    };
  }

  async sugerir(dados: SugerirMusicaRequest): Promise<Musica> {
    const response = await httpClient.post<Musica>('/musicas/sugerir', dados);
    return response.data;
  }

  async buscarPorId(id: number): Promise<Musica> {
    const response = await httpClient.get<Musica>(`/admin/musicas/${id}`);
    return response.data;
  }

  async criar(dados: CriarMusicaRequest): Promise<Musica> {
    const response = await httpClient.post<Musica>('/admin/musicas', dados);
    return response.data;
  }

  async atualizar(dados: AtualizarMusicaRequest): Promise<Musica> {
    const response = await httpClient.put<Musica>(`/admin/musicas/${dados.id}`, dados);
    return response.data;
  }

  async excluir(id: number): Promise<void> {
    await httpClient.delete(`/admin/musicas/${id}`);
  }

  async aprovar(id: number): Promise<Musica> {
    const response = await httpClient.post<Musica>(`/admin/sugestoes/${id}/aprovar`);
    return response.data;
  }

  async reprovar(id: number): Promise<Musica> {
    const response = await httpClient.post<Musica>(`/admin/sugestoes/${id}/reprovar`);
    return response.data;
  }
}

export const musicaService = new MusicaService();