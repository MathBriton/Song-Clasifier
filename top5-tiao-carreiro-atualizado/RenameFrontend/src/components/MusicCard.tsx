import React from 'react';
import { Music } from '../types';

interface MusicCardProps {
  music: Music;
  rank: number;
}

const MusicCard: React.FC<MusicCardProps> = ({ music, rank }) => {
  const formatViews = (views: number): string => {
    if (views >= 1000000) {
      return `${(views / 1000000).toFixed(1)}M`;
    } else if (views >= 1000) {
      return `${(views / 1000).toFixed(1)}K`;
    }
    return views.toString();
  };

  return (
    <a 
      href={`https://www.youtube.com/watch?v=${music.youtube_id}`}
      target="_blank"
      rel="noopener noreferrer"
      className="block no-underline text-inherit transition-transform hover:scale-[1.02] active:scale-100"
    >
      <div className="bg-white rounded-lg p-4 md:p-6 mb-4 shadow-md flex items-center transition-all hover:shadow-lg">
        <div className="text-3xl md:text-4xl font-bold text-amber-900 mr-4 md:mr-6 min-w-[40px]">
          {rank}
        </div>
        
        <div className="flex-grow">
          <div className="text-lg md:text-xl font-bold mb-2 line-clamp-1">
            {music.titulo}
          </div>
          <div className="text-gray-600 text-sm md:text-base">
            {formatViews(music.visualizacoes)} visualizações
          </div>
        </div>
        
        <img 
          src={music.thumb} 
          alt={`Thumbnail ${music.titulo}`}
          className="w-24 h-14 md:w-28 md:h-16 ml-4 rounded object-cover" 
        />
      </div>
    </a>
  );
};

export default MusicCard;