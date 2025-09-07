import React from 'react';
import { clsx } from 'clsx';
import { CardProps } from '../../types/components';

const Card: React.FC<CardProps> = ({
  children,
  className,
  hover = false,
  padding = 'md',
}) => {
  const paddingClasses = {
    sm: 'p-4',
    md: 'p-6',
    lg: 'p-8',
  };

  return (
    <div
      className={clsx(
        hover ? 'card-hover' : 'card',
        paddingClasses[padding],
        className
      )}
    >
      {children}
    </div>
  );
};

export default Card;