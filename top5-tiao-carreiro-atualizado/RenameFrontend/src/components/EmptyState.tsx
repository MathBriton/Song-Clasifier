import React from 'react';

const EmptyState: React.FC = () => {
  return (
    <div className="bg-white rounded-lg p-8 text-center shadow-md">
      <div className="text-5xl text-amber-900 mb-4">🎵</div>
      <div className="text-gray-600 text-lg mb-2">Nenhuma música cadastrada ainda</div>
      <div className="text-gray-500">
        Seja o primeiro a sugerir uma música usando o formulário acima!
      </div>
    </div>
  );
};

export default EmptyState;