import { create } from 'zustand';
import { Musica, FiltroMusica } from '../types';
import { musicaService } from '../services/musicaService';
import toast from 'react-hot-toast';

interface MusicaState {
  top5: Musica[];
  musicas: Musica[];
  musicaSelecionada: Musica | null;
  isLoading: boolean;
  error: string | null;
}

interface MusicaActions {
  fetchTop5: () => Promise<void>;
  fetchMusicas: (filtros?: FiltroMusica) => Promise<void>;
  selecionarMusica: (musica: Musica | null) => void;
  sugerirMusica: (youtubeUrl: string) => Promise<void>;
  clearError: () => void;
}

interface MusicaStore extends MusicaState, MusicaActions {}

export const useMusicaStore = create<MusicaStore>()((set, get) => ({
  // Estado inicial
  top5: [],
  musicas: [],
  musicaSelecionada: null,
  isLoading: false,
  error: null,

  // Actions
  fetchTop5: async () => {
    set({ isLoading: true, error: null });
    try {
      const top5 = await musicaService.getTop5();
      set({ top5, isLoading: false });
    } catch (error: any) {
      set({ 
        error: error.message || 'Erro ao carregar top 5 músicas',
        isLoading: false 
      });
      toast.error('Erro ao carregar as músicas mais tocadas');
    }
  },

  fetchMusicas: async (filtros = {}) => {
    set({ isLoading: true, error: null });
    try {
      const response = await musicaService.listar(filtros);
      set({ musicas: response.musicas, isLoading: false });
    } catch (error: any) {
      set({ 
        error: error.message || 'Erro ao carregar músicas',
        isLoading: false 
      });
      toast.error('Erro ao carregar a lista de músicas');
    }
  },

  selecionarMusica: (musica) => {
    set({ musicaSelecionada: musica });
  },

  sugerirMusica: async (youtubeUrl: string) => {
    set({ isLoading: true, error: null });
    try {
      await musicaService.sugerir({ youtube_url: youtubeUrl });
      toast.success('Música sugerida com sucesso! Aguarde a aprovação.');
      set({ isLoading: false });
    } catch (error: any) {
      set({ 
        error: error.message || 'Erro ao sugerir música',
        isLoading: false 
      });
      toast.error(error.message || 'Erro ao sugerir música');
      throw error;
    }
  },

  clearError: () => {
    set({ error: null });
  },
}));
