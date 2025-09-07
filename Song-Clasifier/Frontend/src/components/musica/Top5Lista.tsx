import React, { useEffect } from 'react';
import { useMusicaStore } from '../../store/musicaStore';
import MusicaCard from './MusicaCard';
import LoadingSpinner from '../ui/LoadingSpinner';
import Card from '../ui/Card';

const Top5Lista: React.FC = () => {
  const { top5, isLoading, error, fetchTop5 } = useMusicaStore();

  useEffect(() => {
    fetchTop5();
  }, [fetchTop5]);

  if (isLoading) {
    return (
      <div className="flex justify-center items-center h-64">
        <LoadingSpinner size="lg" />
      </div>
    );
  }

  if (error) {
    return (
      <Card className="text-center py-8">
        <p className="text-red-600 mb-4">{error}</p>
        <Button onClick={fetchTop5}>Tentar Novamente</Button>
      </Card>
    );
  }

  if (top5.length === 0) {
    return (
      <Card className="text-center py-8">
        <p className="text-gray-500">Nenhuma música encontrada.</p>
      </Card>
    );
  }

  return (
    <div className="space-y-4">
      <div className="text-center mb-8">
        <h1 className="text-3xl font-bold text-white mb-2">
          Top 5 Músicas Mais Tocadas
        </h1>
        <p className="text-white/80">
          Tião Carreiro & Pardinho
        </p>
      </div>

      {top5.map((musica, index) => (
        <MusicaCard
          key={musica.id}
          musica={musica}
          rank={index + 1}
        />
      ))}
    </div>
  );
};

export default Top5Lista;