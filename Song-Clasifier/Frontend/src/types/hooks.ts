export interface UseApiOptions {
  enabled?: boolean;
  refetchOnWindowFocus?: boolean;
  retry?: boolean | number;
  staleTime?: number;
  cacheTime?: number;
}

export interface UseMutationOptions<T = any> {
  onSuccess?: (data: T) => void;
  onError?: (error: ApiError) => void;
  onSettled?: () => void;
}

export interface UseFormOptions {
  defaultValues?: Record<string, any>;
  validationSchema?: any;
  onSubmit?: (data: any) => void | Promise<void>;
}