import React, { useState } from 'react';
import { useForm } from 'react-hook-form';
import { useMusicaStore } from '../../store/musicaStore';
import Input from '../ui/Input';
import Button from '../ui/Button';
import Card from '../ui/Card';
import toast from 'react-hot-toast';

interface FormData {
  youtube_url: string;
}

const SugerirMusicaForm: React.FC = () => {
  const [isSubmitting, setIsSubmitting] = useState(false);
  const { sugerirMusica } = useMusicaStore();

  const {
    register,
    handleSubmit,
    reset,
    formState: { errors },
  } = useForm<FormData>();

  const onSubmit = async (data: FormData) => {
    setIsSubmitting(true);
    try {
      await sugerirMusica(data.youtube_url);
      reset();
    } catch (error) {
      // Error já tratado no store
    } finally {
      setIsSubmitting(false);
    }
  };

  const validateYouTubeUrl = (url: string) => {
    const youtubeRegex = /^(https?:\/\/)?(www\.)?(youtube\.com\/(watch\?v=|embed\/)|youtu\.be\/)[\w-]+/;
    return youtubeRegex.test(url) || 'URL do YouTube inválida';
  };

  return (
    <Card className="mb-8">
      <div className="mb-4">
        <h2 className="text-xl font-semibold text-gray-900">Sugerir Nova Música</h2>
        <p className="text-sm text-gray-600 mt-1">
          Sugira uma música do Tião Carreiro & Pardinho informando o link do YouTube
        </p>
      </div>

      <form onSubmit={handleSubmit(onSubmit)} className="space-y-4">
        <Input
          label="Link do YouTube"
          placeholder="https://www.youtube.com/watch?v=..."
          {...register('youtube_url', {
            required: 'URL do YouTube é obrigatória',
            validate: validateYouTubeUrl,
          })}
          error={errors.youtube_url?.message}
          required
        />

        <div className="flex justify-end">
          <Button
            type="submit"
            loading={isSubmitting}
            disabled={isSubmitting}
          >
            Enviar Sugestão
          </Button>
        </div>
      </form>

      <div className="mt-4 p-4 bg-blue-50 rounded-lg">
        <h3 className="text-sm font-medium text-blue-900 mb-2">Como funciona:</h3>
        <ul className="text-xs text-blue-800 space-y-1">
          <li>• Cole o link completo de um vídeo do YouTube</li>
          <li>• Verifique se é uma música do Tião Carreiro & Pardinho</li>
          <li>• Sua sugestão será analisada por um administrador</li>
          <li>• Você receberá uma notificação sobre a aprovação</li>
        </ul>
      </div>
    </Card>
  );
};

export default SugerirMusicaForm;