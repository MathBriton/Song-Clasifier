import React, { useEffect, useState } from 'react';
import { useMusicaStore } from '../../store/musicaStore';
import { FiltroMusica } from '../../types';
import MusicaCard from './MusicaCard';
import LoadingSpinner from '../ui/LoadingSpinner';
import Card from '../ui/Card';
import Input from '../ui/Input';
import Pagination from '../ui/Pagination';
import { MagnifyingGlassIcon } from '@heroicons/react/24/outline';

const MusicaLista: React.FC = () => {
  const { musicas, isLoading, error, fetchMusicas } = useMusicaStore();
  const [filtros, setFiltros] = useState<FiltroMusica>({
    page: 1,
    per_page: 12,
    search: '',
  });
  const [searchTerm, setSearchTerm] = useState('');
  const [currentPage, setCurrentPage] = useState(1);
  const [totalPages, setTotalPages] = useState(1);

  useEffect(() => {
    fetchMusicas(filtros);
  }, [filtros, fetchMusicas]);

  const handleSearch = (e: React.FormEvent) => {
    e.preventDefault();
    setFiltros(prev => ({
      ...prev,
      search: searchTerm,
      page: 1,
    }));
    setCurrentPage(1);
  };

  const handlePageChange = (page: number) => {
    setFiltros(prev => ({ ...prev, page }));
    setCurrentPage(page);
  };

  if (isLoading && musicas.length === 0) {
    return (
      <div className="flex justify-center items-center h-64">
        <LoadingSpinner size="lg" />
      </div>
    );
  }

  if (error && musicas.length === 0) {
    return (
      <Card className="text-center py-8">
        <p className="text-red-600 mb-4">{error}</p>
        <Button onClick={() => fetchMusicas(filtros)}>Tentar Novamente</Button>
      </Card>
    );
  }

  return (
    <div className="space-y-6">
      <Card>
        <form onSubmit={handleSearch} className="flex gap-4">
          <div className="flex-1">
            <Input
              placeholder="Buscar por título ou artista..."
              value={searchTerm}
              onChange={(e) => setSearchTerm(e.target.value)}
              className="w-full"
            />
          </div>
          <Button type="submit" className="flex items-center">
            <MagnifyingGlassIcon className="h-4 w-4 mr-2" />
            Buscar
          </Button>
        </form>
      </Card>

      {isLoading && (
        <div className="flex justify-center py-4">
          <LoadingSpinner />
        </div>
      )}

      {musicas.length === 0 && !isLoading ? (
        <Card className="text-center py-8">
          <p className="text-gray-500">
            {filtros.search 
              ? `Nenhuma música encontrada para "${filtros.search}"`
              : 'Nenhuma música encontrada.'
            }
          </p>
        </Card>
      ) : (
        <div className="grid gap-4 md:gap-6">
          {musicas.map((musica) => (
            <MusicaCard
              key={musica.id}
              musica={musica}
            />
          ))}
        </div>
      )}

      {totalPages > 1 && (
        <Pagination
          currentPage={currentPage}
          totalPages={totalPages}
          onPageChange={handlePageChange}
          className="mt-8"
        />
      )}
    </div>
  );
};