import React from 'react';
import { clsx } from 'clsx';
import { InputProps } from '../../types/components';

const Input: React.FC<InputProps> = ({
  label,
  error,
  required = false,
  className,
  type = 'text',
  ...props
}) => {
  return (
    <div className={className}>
      {label && (
        <label className="block text-sm font-medium text-gray-700 mb-1">
          {label}
          {required && <span className="text-red-500 ml-1">*</span>}
        </label>
      )}
      <input
        type={type}
        className={clsx(
          'input',
          error && 'input-error'
        )}
        {...props}
      />
      {error && (
        <p className="mt-1 text-sm text-red-600">{error}</p>
      )}
    </div>
  );
};

export default Input;