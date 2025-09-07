import React from 'react';
import { PlayIcon, EyeIcon } from '@heroicons/react/24/outline';
import { MusicaCardProps } from '../../types/components';
import Card from '../ui/Card';
import StatusBadge from './StatusBadge';
import Button from '../ui/Button';

const MusicaCard: React.FC<MusicaCardProps> = ({
  musica,
  rank,
  showActions = false,
  onEdit,
  onDelete,
  onApprove,
  onReject,
}) => {
  const handlePlayClick = () => {
    window.open(musica.youtube_url, '_blank');
  };

  return (
    <Card hover className="flex items-center space-x-4">
      {rank && (
        <div className={`rank-number rank-${rank}`}>
          {rank}
        </div>
      )}
      
      <div className="flex-shrink-0">
        <img
          src={musica.thumbnail_url}
          alt={musica.titulo}
          className="h-16 w-24 object-cover rounded-md"
          onError={(e) => {
            const target = e.target as HTMLImageElement;
            target.src = 'https://via.placeholder.com/120x90?text=Sem+Imagem';
          }}
        />
      </div>

      <div className="flex-1 min-w-0">
        <h3 className="text-lg font-semibold text-gray-900 truncate">
          {musica.titulo}
        </h3>
        <p className="text-sm text-gray-600">{musica.artista}</p>
        <div className="flex items-center space-x-4 mt-2">
          <div className="flex items-center text-sm text-gray-500">
            <EyeIcon className="h-4 w-4 mr-1" />
            <span>{musica.visualizacoes_formatadas} visualizações</span>
          </div>
          <span className="text-sm text-gray-500">
            {musica.duracao_formatada}
          </span>
          {showActions && <StatusBadge status={musica.status} />}
        </div>
      </div>

      <div className="flex-shrink-0 flex items-center space-x-2">
        <Button
          variant="outline"
          size="sm"
          onClick={handlePlayClick}
          className="flex items-center"
        >
          <PlayIcon className="h-4 w-4 mr-1" />
          Assistir
        </Button>

        {showActions && (
          <div className="flex space-x-2">
            {musica.status === 'pendente' && onApprove && (
              <Button
                variant="primary"
                size="sm"
                onClick={() => onApprove(musica)}
              >
                Aprovar
              </Button>
            )}
            
            {musica.status === 'pendente' && onReject && (
              <Button
                variant="danger"
                size="sm"
                onClick={() => onReject(musica)}
              >
                Reprovar
              </Button>
            )}

            {onEdit && (
              <Button
                variant="secondary"
                size="sm"
                onClick={() => onEdit(musica)}
              >
                Editar
              </Button>
            )}

            {onDelete && (
              <Button
                variant="danger"
                size="sm"
                onClick={() => onDelete(musica)}
              >
                Excluir
              </Button>
            )}
          </div>
        )}
      </div>
    </Card>
  );
};

export default MusicaCard;