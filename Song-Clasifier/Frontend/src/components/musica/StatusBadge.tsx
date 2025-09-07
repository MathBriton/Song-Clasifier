import React from 'react';
import { CheckCircleIcon, ClockIcon, XCircleIcon } from '@heroicons/react/24/outline';
import { StatusBadgeProps } from '../../types/components';

const StatusBadge: React.FC<StatusBadgeProps> = ({ status, className = '' }) => {
  const statusConfig = {
    pendente: {
      label: 'Pendente',
      className: 'status-pendente',
      icon: ClockIcon,
    },
    aprovada: {
      label: 'Aprovada',
      className: 'status-aprovada',
      icon: CheckCircleIcon,
    },
    reprovada: {
      label: 'Reprovada',
      className: 'status-reprovada',
      icon: XCircleIcon,
    },
  };

  const config = statusConfig[status];
  const Icon = config.icon;

  return (
    <span className={`${config.className} ${className}`}>
      <Icon className="h-3 w-3 mr-1" />
      {config.label}
    </span>
  );
};

export default StatusBadge;