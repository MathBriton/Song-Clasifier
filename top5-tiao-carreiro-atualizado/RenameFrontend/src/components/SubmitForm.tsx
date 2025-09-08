import React, { useState } from 'react';
import { suggestMusic } from '../services/api';

interface SubmitFormProps {
  onSuggestionSuccess: (message: string) => void;
  onSuggestionError: (message: string) => void;
}

const SubmitForm: React.FC<SubmitFormProps> = ({ onSuggestionSuccess, onSuggestionError }) => {
  const [url, setUrl] = useState('');
  const [isSubmitting, setIsSubmitting] = useState(false);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    
    if (!url) return;
    
    setIsSubmitting(true);
    try {
      const result = await suggestMusic(url);
      
      if (result.success) {
        onSuggestionSuccess(result.message || 'Vídeo cadastrado com sucesso!');
        setUrl('');
      } else {
        onSuggestionError(result.message || 'Erro ao cadastrar vídeo');
      }
    } catch (error) {
      onSuggestionError(error instanceof Error ? error.message : 'Erro ao processar solicitação');
    } finally {
      setIsSubmitting(false);
    }
  };

  return (
    <div className="bg-white rounded-lg p-6 md:p-8 mb-8 shadow-md">
      <h3 className="text-xl font-bold text-amber-900 mb-4">Sugerir Nova Música</h3>
      
      <form onSubmit={handleSubmit}>
        <div className="flex flex-col md:flex-row gap-4">
          <input
            type="url"
            value={url}
            onChange={(e) => setUrl(e.target.value)}
            placeholder="Cole aqui o link do YouTube"
            required
            className="flex-grow px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-amber-700 focus:outline-none transition-colors"
            disabled={isSubmitting}
          />
          
          <button
            type="submit"
            disabled={isSubmitting}
            className="bg-amber-900 text-white px-6 py-3 rounded-lg font-medium hover:bg-amber-800 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
          >
            {isSubmitting ? 'Enviando...' : 'Enviar Link'}
          </button>
        </div>
      </form>
    </div>
  );
};

export default SubmitForm;