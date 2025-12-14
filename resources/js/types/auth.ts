export interface User {
  id: number
  name: string
  email: string
  email_verified_at: string | null
  created_at: string
  updated_at: string
}

export interface LoginResponse {
  user: User
  token?: string
  redirect?: string
}

export interface RegisterResponse {
  message: string
  user?: User
}

export interface ForgotPasswordResponse {
  message: string
  status: string
}

export interface ValidationErrors {
  [key: string]: string[]
}

export interface AuthState {
  user: User | null
  token: string | null
  isAuthenticated: boolean
  isLoading: boolean
}
