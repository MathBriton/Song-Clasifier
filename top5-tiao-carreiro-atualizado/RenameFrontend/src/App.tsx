import React, { useState } from 'react';
import Header from './components/Header';
import SubmitForm from './components/SubmitForm';
import MusicCard from './components/MusicCard';
import EmptyState from './components/EmptyState';
import { useTop5 } from './hooks/useTop5';

const App: React.FC = () => {
  const { top5, loading, error } = useTop5();
  const [notification, setNotification] = useState<{message: string, type: 'success' | 'error'} | null>(null);

  const showNotification = (message: string, type: 'success' | 'error') => {
    setNotification({ message, type });
    setTimeout(() => setNotification(null), 5000);
  };

  const handleSuggestionSuccess = (message: string) => {
    showNotification(message, 'success');
    // You might want to refetch the top5 here
  };

  const handleSuggestionError = (message: string) => {
    showNotification(message, 'error');
  };

  return (
    <div className="min-h-screen bg-gray-100">
      <Header />
      
      <main className="container mx-auto px-4 py-8 max-w-4xl">
        {notification && (
          <div className={`mb-6 p-4 rounded-lg ${
            notification.type === 'success' 
              ? 'bg-green-100 text-green-800 border border-green-200' 
              : 'bg-red-100 text-red-800 border border-red-200'
          }`}>
            {notification.message}
          </div>
        )}
        
        <SubmitForm 
          onSuggestionSuccess={handleSuggestionSuccess}
          onSuggestionError={handleSuggestionError}
        />
        
        <h3 className="text-xl font-bold text-amber-900 mb-4">Ranking Atual</h3>
        
        {loading && (
          <div className="text-center py-8">
            <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-amber-900 mx-auto"></div>
            <p className="mt-4 text-gray-600">Carregando músicas...</p>
          </div>
        )}
        
        {error && (
          <div className="bg-red-100 text-red-800 p-4 rounded-lg mb-6">
            Erro ao carregar músicas: {error}
          </div>
        )}
        
        {!loading && !error && top5.length === 0 && <EmptyState />}
        
        {!loading && top5.length > 0 && (
          <div className="space-y-4">
            {top5.map((music, index) => (
              <MusicCard key={music.id} music={music} rank={index + 1} />
            ))}
          </div>
        )}
      </main>
    </div>
  );
};

export default App;