import { useState, useEffect } from 'react';
import { Music } from '../types';
import { getTop5 } from '../services/api';

export const useTop5 = () => {
  const [top5, setTop5] = useState<Music[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    const fetchTop5 = async () => {
      try {
        setLoading(true);
        const data = await getTop5();
        setTop5(data);
      } catch (err) {
        setError(err instanceof Error ? err.message : 'An error occurred');
      } finally {
        setLoading(false);
      }
    };

    fetchTop5();
  }, []);

  return { top5, loading, error };
};