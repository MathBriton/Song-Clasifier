import { Music, ApiResponse } from '../types';

const API_BASE = '/api'; // Adjust based on your backend

export const getTop5 = async (): Promise<Music[]> => {
  const response = await fetch(`${API_BASE}/top5.php`);
  if (!response.ok) {
    throw new Error('Failed to fetch top 5');
  }
  return response.json();
};

export const suggestMusic = async (url: string): Promise<ApiResponse> => {
  const formData = new FormData();
  formData.append('url', url);
  
  const response = await fetch(`${API_BASE}/sugerir.php`, {
    method: 'POST',
    body: formData,
  });
  
  return response.json();
};