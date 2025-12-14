<template>
  <div class="auth-container">
    <!-- Левая часть с приветствием -->
    <div class="auth-hero">
      <div class="hero-content">
        <div class="logo">
          <div class="logo-icon">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none">
              <path d="M19 3H5C3.9 3 3 3.9 3 5V19C3 20.1 3.9 21 5 21H19C20.1 21 21 20.1 21 19V5C21 3.9 20.1 3 19 3Z" stroke="currentColor" stroke-width="2"/>
              <path d="M9 17L15 12L9 7" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
          </div>
          <h1 class="logo-text">Mahjong Cars</h1>
        </div>
        <h2 class="hero-title">Собери все автомобили!</h2>
        <p class="hero-description">
          Увлекательная игра в маджонг с автомобильной тематикой.
          Собирай пары, открывай новые машины и соревнуйся с друзьями.
        </p>
        <div class="hero-features">
          <div class="feature">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
              <path d="M20 6L9 17L4 12" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span>Бесплатно играть</span>
          </div>
          <div class="feature">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
              <path d="M20 6L9 17L4 12" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span>Коллекция авто</span>
          </div>
          <div class="feature">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
              <path d="M20 6L9 17L4 12" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span>Ежедневные награды</span>
          </div>
        </div>
      </div>
      <div class="hero-background"></div>
    </div>

    <!-- Правая часть с формами -->
    <div class="auth-forms">
      <div class="forms-container">
        <!-- Переключатель форм -->
        <div class="forms-header">
          <button
            @click="activeForm = 'login'"
            :class="['form-tab', { active: activeForm === 'login' }]"
          >
            Вход
          </button>
          <button
            @click="activeForm = 'register'"
            :class="['form-tab', { active: activeForm === 'register' }]"
          >
            Регистрация
          </button>
        </div>

        <!-- Форма входа -->
        <transition name="fade" mode="out-in">
          <form
            v-if="activeForm === 'login'"
            @submit.prevent="handleLogin"
            class="auth-form login-form"
            key="login"
          >
            <div class="form-group">
              <label for="login-email" class="form-label">Email</label>
              <div class="input-group">
                <div class="input-icon">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                    <path d="M4 4H20C21.1 4 22 4.9 22 6V18C22 19.1 21.1 20 20 20H4C2.9 20 2 19.1 2 18V6C2 4.9 2.9 4 4 4Z" stroke="currentColor" stroke-width="2"/>
                    <path d="M22 6L12 13L2 6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                  </svg>
                </div>
                <input
                  id="login-email"
                  v-model="loginData.email"
                  type="email"
                  placeholder="your@email.com"
                  required
                  class="form-input"
                  :class="{ 'input-error': loginErrors.email }"
                />
              </div>
              <div v-if="loginErrors.email" class="error-message">
                {{ loginErrors.email }}
              </div>
            </div>

            <div class="form-group">
              <div class="label-row">
                <label for="login-password" class="form-label">Пароль</label>
                <button
                  type="button"
                  @click="showForgotPassword = true"
                  class="text-link"
                >
                  Забыли пароль?
                </button>
              </div>
              <div class="input-group">
                <div class="input-icon">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                    <path d="M19 11H5C3.9 11 3 11.9 3 13V20C3 21.1 3.9 22 5 22H19C20.1 22 21 21.1 21 20V13C21 11.9 20.1 11 19 11Z" stroke="currentColor" stroke-width="2"/>
                    <path d="M7 11V7C7 5.67392 7.52678 4.40215 8.46447 3.46447C9.40215 2.52678 10.6739 2 12 2C13.3261 2 14.5979 2.52678 15.5355 3.46447C16.4732 4.40215 17 5.67392 17 7V11" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                  </svg>
                </div>
                <input
                  id="login-password"
                  v-model="loginData.password"
                  :type="showLoginPassword ? 'text' : 'password'"
                  placeholder="Ваш пароль"
                  required
                  class="form-input"
                  :class="{ 'input-error': loginErrors.password }"
                />
                <button
                  type="button"
                  @click="showLoginPassword = !showLoginPassword"
                  class="password-toggle"
                >
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                    <path v-if="showLoginPassword" d="M2 12C2 12 5 5 12 5C19 5 22 12 22 12C22 12 19 19 12 19C5 19 2 12 2 12Z" stroke="currentColor" stroke-width="2"/>
                    <path v-if="showLoginPassword" d="M12 15C13.6569 15 15 13.6569 15 12C15 10.3431 13.6569 9 12 9C10.3431 9 9 10.3431 9 12C9 13.6569 10.3431 15 12 15Z" stroke="currentColor" stroke-width="2"/>
                    <path v-if="!showLoginPassword" d="M17.94 17.94C16.2306 19.243 14.1491 19.9649 12 20C5 20 2 12 2 12C3.228 9.232 5.182 7.062 7.5 5.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path v-if="!showLoginPassword" d="M9.9 4.24C10.5883 4.07888 11.2931 3.99834 12 4C19 4 22 12 22 12C21.393 13.135 20.669 14.204 19.84 15.19" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path v-if="!showLoginPassword" d="M15 12C15 13.6569 13.6569 15 12 15C10.3431 15 9 13.6569 9 12C9 10.3431 10.3431 9 12 9C13.6569 9 15 10.3431 15 12Z" stroke="currentColor" stroke-width="2"/>
                    <path v-if="!showLoginPassword" d="M2 2L22 22" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                  </svg>
                </button>
              </div>
              <div v-if="loginErrors.password" class="error-message">
                {{ loginErrors.password }}
              </div>
            </div>

            <div class="form-group remember-me">
              <label class="checkbox-label">
                <input
                  type="checkbox"
                  v-model="loginData.remember"
                  class="checkbox-input"
                />
                <span class="checkbox-custom"></span>
                <span class="checkbox-text">Запомнить меня</span>
              </label>
            </div>

            <button
              type="submit"
              :disabled="loginLoading"
              class="submit-btn"
              :class="{ loading: loginLoading }"
            >
              <span v-if="loginLoading" class="btn-loader"></span>
              <span v-else>Войти в аккаунт</span>
            </button>

            <div class="divider">
              <span>или</span>
            </div>

            <button type="button" class="social-btn google-btn">
              <svg width="20" height="20" viewBox="0 0 24 24">
                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
              </svg>
              <span>Войти через Google</span>
            </button>
          </form>

          <!-- Форма регистрации -->
          <form
            v-else
            @submit.prevent="handleRegister"
            class="auth-form register-form"
            key="register"
          >
            <div class="form-group">
              <label for="register-name" class="form-label">Имя</label>
              <div class="input-group">
                <div class="input-icon">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                    <path d="M20 21V19C20 17.9391 19.5786 16.9217 18.8284 16.1716C18.0783 15.4214 17.0609 15 16 15H8C6.93913 15 5.92172 15.4214 5.17157 16.1716C4.42143 16.9217 4 17.9391 4 19V21" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M12 11C14.2091 11 16 9.20914 16 7C16 4.79086 14.2091 3 12 3C9.79086 3 8 4.79086 8 7C8 9.20914 9.79086 11 12 11Z" stroke="currentColor" stroke-width="2"/>
                  </svg>
                </div>
                <input
                  id="register-name"
                  v-model="registerData.name"
                  type="text"
                  placeholder="Ваше имя"
                  required
                  class="form-input"
                  :class="{ 'input-error': registerErrors.name }"
                />
              </div>
              <div v-if="registerErrors.name" class="error-message">
                {{ registerErrors.name }}
              </div>
            </div>

            <div class="form-group">
              <label for="register-email" class="form-label">Email</label>
              <div class="input-group">
                <div class="input-icon">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                    <path d="M4 4H20C21.1 4 22 4.9 22 6V18C22 19.1 21.1 20 20 20H4C2.9 20 2 19.1 2 18V6C2 4.9 2.9 4 4 4Z" stroke="currentColor" stroke-width="2"/>
                    <path d="M22 6L12 13L2 6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                  </svg>
                </div>
                <input
                  id="register-email"
                  v-model="registerData.email"
                  type="email"
                  placeholder="your@email.com"
                  required
                  class="form-input"
                  :class="{ 'input-error': registerErrors.email }"
                />
              </div>
              <div v-if="registerErrors.email" class="error-message">
                {{ registerErrors.email }}
              </div>
            </div>

            <div class="form-group">
              <label for="register-password" class="form-label">Пароль</label>
              <div class="input-group">
                <div class="input-icon">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                    <path d="M19 11H5C3.9 11 3 11.9 3 13V20C3 21.1 3.9 22 5 22H19C20.1 22 21 21.1 21 20V13C21 11.9 20.1 11 19 11Z" stroke="currentColor" stroke-width="2"/>
                    <path d="M7 11V7C7 5.67392 7.52678 4.40215 8.46447 3.46447C9.40215 2.52678 10.6739 2 12 2C13.3261 2 14.5979 2.52678 15.5355 3.46447C16.4732 4.40215 17 5.67392 17 7V11" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                  </svg>
                </div>
                <input
                  id="register-password"
                  v-model="registerData.password"
                  :type="showRegisterPassword ? 'text' : 'password'"
                  placeholder="Создайте пароль"
                  required
                  class="form-input"
                  :class="{ 'input-error': registerErrors.password }"
                />
                <button
                  type="button"
                  @click="showRegisterPassword = !showRegisterPassword"
                  class="password-toggle"
                >
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                    <path v-if="showRegisterPassword" d="M2 12C2 12 5 5 12 5C19 5 22 12 22 12C22 12 19 19 12 19C5 19 2 12 2 12Z" stroke="currentColor" stroke-width="2"/>
                    <path v-if="showRegisterPassword" d="M12 15C13.6569 15 15 13.6569 15 12C15 10.3431 13.6569 9 12 9C10.3431 9 9 10.3431 9 12C9 13.6569 10.3431 15 12 15Z" stroke="currentColor" stroke-width="2"/>
                    <path v-if="!showRegisterPassword" d="M17.94 17.94C16.2306 19.243 14.1491 19.9649 12 20C5 20 2 12 2 12C3.228 9.232 5.182 7.062 7.5 5.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path v-if="!showRegisterPassword" d="M9.9 4.24C10.5883 4.07888 11.2931 3.99834 12 4C19 4 22 12 22 12C21.393 13.135 20.669 14.204 19.84 15.19" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path v-if="!showRegisterPassword" d="M15 12C15 13.6569 13.6569 15 12 15C10.3431 15 9 13.6569 9 12C9 10.3431 10.3431 9 12 9C13.6569 9 15 10.3431 15 12Z" stroke="currentColor" stroke-width="2"/>
                    <path v-if="!showRegisterPassword" d="M2 2L22 22" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                  </svg>
                </button>
              </div>
              <div v-if="registerErrors.password" class="error-message">
                {{ registerErrors.password }}
              </div>
            </div>

            <div class="form-group">
              <label for="register-password-confirm" class="form-label">Подтверждение пароля</label>
              <div class="input-group">
                <div class="input-icon">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                    <path d="M19 11H5C3.9 11 3 11.9 3 13V20C3 21.1 3.9 22 5 22H19C20.1 22 21 21.1 21 20V13C21 11.9 20.1 11 19 11Z" stroke="currentColor" stroke-width="2"/>
                    <path d="M7 11V7C7 5.67392 7.52678 4.40215 8.46447 3.46447C9.40215 2.52678 10.6739 2 12 2C13.3261 2 14.5979 2.52678 15.5355 3.46447C16.4732 4.40215 17 5.67392 17 7V11" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                  </svg>
                </div>
                <input
                  id="register-password-confirm"
                  v-model="registerData.password_confirmation"
                  :type="showRegisterConfirmPassword ? 'text' : 'password'"
                  placeholder="Повторите пароль"
                  required
                  class="form-input"
                  :class="{ 'input-error': registerErrors.password_confirmation }"
                />
                <button
                  type="button"
                  @click="showRegisterConfirmPassword = !showRegisterConfirmPassword"
                  class="password-toggle"
                >
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                    <path v-if="showRegisterConfirmPassword" d="M2 12C2 12 5 5 12 5C19 5 22 12 22 12C22 12 19 19 12 19C5 19 2 12 2 12Z" stroke="currentColor" stroke-width="2"/>
                    <path v-if="showRegisterConfirmPassword" d="M12 15C13.6569 15 15 13.6569 15 12C15 10.3431 13.6569 9 12 9C10.3431 9 9 10.3431 9 12C9 13.6569 10.3431 15 12 15Z" stroke="currentColor" stroke-width="2"/>
                    <path v-if="!showRegisterConfirmPassword" d="M17.94 17.94C16.2306 19.243 14.1491 19.9649 12 20C5 20 2 12 2 12C3.228 9.232 5.182 7.062 7.5 5.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path v-if="!showRegisterConfirmPassword" d="M9.9 4.24C10.5883 4.07888 11.2931 3.99834 12 4C19 4 22 12 22 12C21.393 13.135 20.669 14.204 19.84 15.19" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path v-if="!showRegisterConfirmPassword" d="M15 12C15 13.6569 13.6569 15 12 15C10.3431 15 9 13.6569 9 12C9 10.3431 10.3431 9 12 9C13.6569 9 15 10.3431 15 12Z" stroke="currentColor" stroke-width="2"/>
                    <path v-if="!showRegisterConfirmPassword" d="M2 2L22 22" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                  </svg>
                </button>
              </div>
              <div v-if="registerErrors.password_confirmation" class="error-message">
                {{ registerErrors.password_confirmation }}
              </div>
            </div>

            <div class="form-group">
              <label class="checkbox-label">
                <input
                  type="checkbox"
                  v-model="registerData.terms"
                  required
                  class="checkbox-input"
                />
                <span class="checkbox-custom"></span>
                <span class="checkbox-text">
                  Я соглашаюсь с <a href="#" class="link">Условиями использования</a> и
                  <a href="#" class="link">Политикой конфиденциальности</a>
                </span>
              </label>
            </div>

            <button
              type="submit"
              :disabled="registerLoading"
              class="submit-btn"
              :class="{ loading: registerLoading }"
            >
              <span v-if="registerLoading" class="btn-loader"></span>
              <span v-else>Создать аккаунт</span>
            </button>
          </form>
        </transition>

        <!-- Уведомления -->
        <transition name="slide-down">
          <div v-if="notification.show" class="notification" :class="notification.type">
            {{ notification.message }}
            <button @click="notification.show = false" class="notification-close">
              ✕
            </button>
          </div>
        </transition>
      </div>
    </div>

    <!-- Модалка восстановления пароля -->
    <transition name="modal">
      <div v-if="showForgotPassword" class="modal-overlay" @click.self="showForgotPassword = false">
        <div class="modal">
          <div class="modal-header">
            <h3>Восстановление пароля</h3>
            <button @click="showForgotPassword = false" class="modal-close">
              ✕
            </button>
          </div>
          <form @submit.prevent="handleForgotPassword" class="modal-form">
            <div class="form-group">
              <label for="reset-email" class="form-label">Введите ваш email</label>
              <input
                id="reset-email"
                v-model="forgotPasswordData.email"
                type="email"
                placeholder="your@email.com"
                required
                class="form-input"
              />
            </div>
            <button
              type="submit"
              :disabled="forgotPasswordLoading"
              class="submit-btn"
              :class="{ loading: forgotPasswordLoading }"
            >
              <span v-if="forgotPasswordLoading" class="btn-loader"></span>
              <span v-else>Отправить ссылку</span>
            </button>
          </form>
        </div>
      </div>
    </transition>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive } from 'vue'

// Типы
interface LoginForm {
  email: string
  password: string
  remember: boolean
}

interface RegisterForm {
  name: string
  email: string
  password: string
  password_confirmation: string
  terms: boolean
}

interface ForgotPasswordForm {
  email: string
}

interface Notification {
  show: boolean
  type: 'success' | 'error' | 'info'
  message: string
}

// Состояние
const activeForm = ref<'login' | 'register'>('login')
const showForgotPassword = ref(false)

const showLoginPassword = ref(false)
const showRegisterPassword = ref(false)
const showRegisterConfirmPassword = ref(false)

const loginData = reactive<LoginForm>({
  email: '',
  password: '',
  remember: false
})

const registerData = reactive<RegisterForm>({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  terms: false
})

const forgotPasswordData = reactive<ForgotPasswordForm>({
  email: ''
})

const loginErrors = reactive<Partial<LoginForm>>({})
const registerErrors = reactive<Partial<RegisterForm>>({})

const loginLoading = ref(false)
const registerLoading = ref(false)
const forgotPasswordLoading = ref(false)

const notification = reactive<Notification>({
  show: false,
  type: 'info',
  message: ''
})

// Валидация
const validateLogin = (): boolean => {
  let isValid = true

  if (!loginData.email) {
    loginErrors.email = 'Email обязателен'
    isValid = false
  } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(loginData.email)) {
    loginErrors.email = 'Неверный формат email'
    isValid = false
  } else {
    loginErrors.email = undefined
  }

  if (!loginData.password) {
    loginErrors.password = 'Пароль обязателен'
    isValid = false
  } else if (loginData.password.length < 6) {
    loginErrors.password = 'Пароль должен содержать минимум 6 символов'
    isValid = false
  } else {
    loginErrors.password = undefined
  }

  return isValid
}

const validateRegister = (): boolean => {
  let isValid = true

  if (!registerData.name) {
    registerErrors.name = 'Имя обязательно'
    isValid = false
  } else if (registerData.name.length < 2) {
    registerErrors.name = 'Имя должно содержать минимум 2 символа'
    isValid = false
  } else {
    registerErrors.name = undefined
  }

  if (!registerData.email) {
    registerErrors.email = 'Email обязателен'
    isValid = false
  } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(registerData.email)) {
    registerErrors.email = 'Неверный формат email'
    isValid = false
  } else {
    registerErrors.email = undefined
  }

  if (!registerData.password) {
    registerErrors.password = 'Пароль обязателен'
    isValid = false
  } else if (registerData.password.length < 8) {
    registerErrors.password = 'Пароль должен содержать минимум 8 символов'
    isValid = false
  } else {
    registerErrors.password = undefined
  }

  if (!registerData.password_confirmation) {
    registerErrors.password_confirmation = 'Подтверждение пароля обязательно'
    isValid = false
  } else if (registerData.password !== registerData.password_confirmation) {
    registerErrors.password_confirmation = 'Пароли не совпадают'
    isValid = false
  } else {
    registerErrors.password_confirmation = undefined
  }

  if (!registerData.terms) {
    showNotification('error', 'Необходимо согласие с условиями')
    isValid = false
  }

  return isValid
}

// Отправка форм
const handleLogin = async () => {
  if (!validateLogin()) return

  loginLoading.value = true

  try {
    const response = await fetch('/login', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
      },
      body: JSON.stringify(loginData)
    })

    const data = await response.json()

    if (response.ok) {
      showNotification('success', 'Вход выполнен успешно!')
      // Редирект будет через Laravel
      window.location.href = '/dashboard'
    } else {
      if (data.errors) {
        // Обработка ошибок валидации Laravel
        if (data.errors.email) loginErrors.email = data.errors.email[0]
        if (data.errors.password) loginErrors.password = data.errors.password[0]
        showNotification('error', 'Ошибка входа. Проверьте данные.')
      } else {
        showNotification('error', data.message || 'Ошибка входа')
      }
    }
  } catch (error) {
    console.error('Login error:', error)
    showNotification('error', 'Ошибка сети. Попробуйте позже.')
  } finally {
    loginLoading.value = false
  }
}

const handleRegister = async () => {
  if (!validateRegister()) return

  registerLoading.value = true

  try {
    const response = await fetch('/register', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
      },
      body: JSON.stringify(registerData)
    })

    const data = await response.json()

    if (response.ok) {
      showNotification('success', 'Регистрация успешна! Проверьте вашу почту.')
      // Очищаем форму
      Object.assign(registerData, {
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        terms: false
      })
      // Переключаем на вкладку входа
      activeForm.value = 'login'
    } else {
      if (data.errors) {
        // Обработка ошибок валидации Laravel
        if (data.errors.name) registerErrors.name = data.errors.name[0]
        if (data.errors.email) registerErrors.email = data.errors.email[0]
        if (data.errors.password) registerErrors.password = data.errors.password[0]
        showNotification('error', 'Ошибка регистрации. Проверьте данные.')
      } else {
        showNotification('error', data.message || 'Ошибка регистрации')
      }
    }
  } catch (error) {
    console.error('Register error:', error)
    showNotification('error', 'Ошибка сети. Попробуйте позже.')
  } finally {
    registerLoading.value = false
  }
}

const handleForgotPassword = async () => {
  forgotPasswordLoading.value = true

  try {
    const response = await fetch('/forgot-password', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
      },
      body: JSON.stringify(forgotPasswordData)
    })

    const data = await response.json()

    if (response.ok) {
      showNotification('success', 'Ссылка для сброса пароля отправлена на ваш email!')
      showForgotPassword.value = false
      forgotPasswordData.email = ''
    } else {
      showNotification('error', data.message || 'Ошибка отправки запроса')
    }
  } catch (error) {
    console.error('Forgot password error:', error)
    showNotification('error', 'Ошибка сети. Попробуйте позже.')
  } finally {
    forgotPasswordLoading.value = false
  }
}

// Уведомления
const showNotification = (type: Notification['type'], message: string) => {
  notification.type = type
  notification.message = message
  notification.show = true

  setTimeout(() => {
    notification.show = false
  }, 5000)
}
</script>

<style scoped>
.auth-container {
  display: flex;
  min-height: 100vh;
  background: #ffffff;
}

.auth-hero {
  flex: 1;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  padding: 60px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  position: relative;
  overflow: hidden;
}

.hero-background {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-image: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><rect width="100" height="100" fill="none"/><path d="M0,50 Q25,40 50,50 T100,50 L100,100 L0,100 Z" fill="rgba(255,255,255,0.1)"/><path d="M0,50 Q25,60 50,50 T100,50 L100,0 L0,0 Z" fill="rgba(255,255,255,0.05)"/></svg>');
  background-size: cover;
  opacity: 0.3;
}

.logo {
  display: flex;
  align-items: center;
  gap: 16px;
  margin-bottom: 40px;
}

.logo-icon {
  width: 60px;
  height: 60px;
  background: rgba(255, 255, 255, 0.1);
  border-radius: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.2);
}

.logo-icon svg {
  color: white;
}

.logo-text {
  font-size: 32px;
  font-weight: 700;
  margin: 0;
}

.hero-title {
  font-size: 48px;
  font-weight: 800;
  line-height: 1.2;
  margin-bottom: 24px;
  max-width: 600px;
}

.hero-description {
  font-size: 18px;
  line-height: 1.6;
  opacity: 0.9;
  margin-bottom: 40px;
  max-width: 500px;
}

.hero-features {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.feature {
  display: flex;
  align-items: center;
  gap: 12px;
  font-size: 16px;
}

.feature svg {
  flex-shrink: 0;
}

.auth-forms {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 40px;
  background: #f8fafc;
}

.forms-container {
  width: 100%;
  max-width: 440px;
}

.forms-header {
  display: flex;
  gap: 8px;
  margin-bottom: 40px;
  background: #ffffff;
  padding: 8px;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.form-tab {
  flex: 1;
  padding: 16px 24px;
  border: none;
  background: transparent;
  color: #64748b;
  font-size: 16px;
  font-weight: 600;
  cursor: pointer;
  border-radius: 8px;
  transition: all 0.3s ease;
}

.form-tab:hover {
  color: #475569;
}

.form-tab.active {
  background: #667eea;
  color: white;
  box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.auth-form {
  background: white;
  padding: 40px;
  border-radius: 20px;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
}

.form-group {
  margin-bottom: 24px;
}

.form-label {
  display: block;
  font-size: 14px;
  font-weight: 600;
  color: #374151;
  margin-bottom: 8px;
}

.label-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 8px;
}

.text-link {
  background: none;
  border: none;
  color: #667eea;
  font-size: 14px;
  cursor: pointer;
  text-decoration: underline;
  transition: color 0.2s ease;
}

.text-link:hover {
  color: #5a67d8;
}

.input-group {
  position: relative;
  display: flex;
  align-items: center;
}

.input-icon {
  position: absolute;
  left: 16px;
  color: #9ca3af;
  pointer-events: none;
}

.form-input {
  width: 100%;
  padding: 16px 16px 16px 48px;
  border: 2px solid #e5e7eb;
  border-radius: 12px;
  font-size: 16px;
  transition: all 0.3s ease;
  background: white;
}

.form-input:focus {
  outline: none;
  border-color: #667eea;
  box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
}

.form-input.input-error {
  border-color: #ef4444;
}

.form-input.input-error:focus {
  box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
}

.password-toggle {
  position: absolute;
  right: 16px;
  background: none;
  border: none;
  color: #9ca3af;
  cursor: pointer;
  padding: 4px;
  transition: color 0.2s ease;
}

.password-toggle:hover {
  color: #667eea;
}

.error-message {
  color: #ef4444;
  font-size: 13px;
  margin-top: 6px;
}

.remember-me {
  margin-top: -8px;
}

.checkbox-label {
  display: flex;
  align-items: center;
  cursor: pointer;
  user-select: none;
}

.checkbox-input {
  position: absolute;
  opacity: 0;
  cursor: pointer;
  height: 0;
  width: 0;
}

.checkbox-custom {
  width: 20px;
  height: 20px;
  border: 2px solid #d1d5db;
  border-radius: 6px;
  margin-right: 12px;
  position: relative;
  transition: all 0.3s ease;
}

.checkbox-input:checked ~ .checkbox-custom {
  background: #667eea;
  border-color: #667eea;
}

.checkbox-input:checked ~ .checkbox-custom::after {
  content: '';
  position: absolute;
  left: 6px;
  top: 2px;
  width: 5px;
  height: 10px;
  border: solid white;
  border-width: 0 2px 2px 0;
  transform: rotate(45deg);
}

.checkbox-text {
  font-size: 14px;
  color: #4b5563;
}

.checkbox-text .link {
  color: #667eea;
  text-decoration: none;
}

.checkbox-text .link:hover {
  text-decoration: underline;
}

.submit-btn {
  width: 100%;
  padding: 18px;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  border: none;
  border-radius: 12px;
  font-size: 16px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
}

.submit-btn:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
}

.submit-btn:active:not(:disabled) {
  transform: translateY(0);
}

.submit-btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.submit-btn.loading {
  color: transparent;
}

.btn-loader {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 20px;
  height: 20px;
  border: 3px solid rgba(255, 255, 255, 0.3);
  border-top-color: white;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  to {
    transform: translate(-50%, -50%) rotate(360deg);
  }
}

.divider {
  display: flex;
  align-items: center;
  margin: 24px 0;
  color: #9ca3af;
  font-size: 14px;
}

.divider::before,
.divider::after {
  content: '';
  flex: 1;
  height: 1px;
  background: #e5e7eb;
}

.divider span {
  padding: 0 16px;
}

.social-btn {
  width: 100%;
  padding: 16px;
  border: 2px solid #e5e7eb;
  background: white;
  border-radius: 12px;
  font-size: 15px;
  font-weight: 500;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 12px;
  transition: all 0.3s ease;
}

.social-btn:hover {
  border-color: #d1d5db;
  background: #f9fafb;
}

.google-btn {
  color: #374151;
}

.notification {
  position: fixed;
  top: 20px;
  right: 20px;
  padding: 16px 20px;
  border-radius: 12px;
  background: white;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
  max-width: 400px;
  z-index: 1000;
  animation: slideInRight 0.3s ease;
}

.notification.success {
  border-left: 4px solid #10b981;
}

.notification.error {
  border-left: 4px solid #ef4444;
}

.notification.info {
  border-left: 4px solid #3b82f6;
}

.notification-close {
  background: none;
  border: none;
  color: #9ca3af;
  cursor: pointer;
  font-size: 20px;
  line-height: 1;
  padding: 0;
  width: 24px;
  height: 24px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  transition: background 0.2s ease;
}

.notification-close:hover {
  background: #f3f4f6;
  color: #374151;
}

@keyframes slideInRight {
  from {
    transform: translateX(100%);
    opacity: 0;
  }
  to {
    transform: translateX(0);
    opacity: 1;
  }
}

.slide-down-enter-active,
.slide-down-leave-active {
  transition: all 0.3s ease;
}

.slide-down-enter-from,
.slide-down-leave-to {
  transform: translateY(-20px);
  opacity: 0;
}

.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  backdrop-filter: blur(4px);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 2000;
}

.modal {
  background: white;
  border-radius: 20px;
  padding: 32px;
  width: 90%;
  max-width: 400px;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
  animation: modalAppear 0.3s ease;
}

@keyframes modalAppear {
  from {
    opacity: 0;
    transform: scale(0.9);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
}

.modal-header h3 {
  margin: 0;
  font-size: 24px;
  color: #1f2937;
}

.modal-close {
  background: none;
  border: none;
  font-size: 24px;
  color: #9ca3af;
  cursor: pointer;
  width: 32px;
  height: 32px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  transition: background 0.2s ease;
}

.modal-close:hover {
  background: #f3f4f6;
  color: #374151;
}

.modal-form .form-group {
  margin-bottom: 32px;
}

.modal-form .form-input {
  padding: 12px 16px;
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease, transform 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
  transform: translateY(10px);
}

.modal-enter-active,
.modal-leave-active {
  transition: opacity 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
  opacity: 0;
}

/* Адаптивность */
@media (max-width: 1024px) {
  .auth-container {
    flex-direction: column;
  }

  .auth-hero {
    padding: 40px;
  }

  .hero-title {
    font-size: 36px;
  }

  .auth-forms {
    padding: 40px 20px;
  }
}

@media (max-width: 640px) {
  .auth-hero {
    padding: 32px 24px;
  }

  .logo {
    margin-bottom: 32px;
  }

  .logo-text {
    font-size: 24px;
  }

  .hero-title {
    font-size: 28px;
  }

  .hero-description {
    font-size: 16px;
  }

  .auth-form {
    padding: 32px 24px;
  }

  .forms-header {
    margin-bottom: 32px;
  }

  .form-tab {
    padding: 12px 20px;
    font-size: 15px;
  }
}

@media (max-width: 480px) {
  .auth-hero {
    padding: 24px 20px;
  }

  .logo {
    margin-bottom: 24px;
  }

  .logo-icon {
    width: 48px;
    height: 48px;
  }

  .hero-title {
    font-size: 24px;
    margin-bottom: 16px;
  }

  .auth-form {
    padding: 24px 20px;
  }

  .modal {
    padding: 24px 20px;
  }
}
</style>
