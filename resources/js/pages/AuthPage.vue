<template>
  <div class="auth-layout">
    <!-- Левый блок с информацией -->
    <div class="auth-info">
      <div class="auth-info-content">
        <div class="logo">
          <div class="logo-icon">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none">
              <path d="M19 3H5C3.9 3 3 3.9 3 5V19C3 20.1 3.9 21 5 21H19C20.1 21 21 20.1 21 19V5C21 3.9 20.1 3 19 3Z" stroke="currentColor" stroke-width="2"/>
              <path d="M9 17L15 12L9 7" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
          </div>
          <h1 class="logo-text">Mahjong Cars</h1>
        </div>

        <div class="hero-section">
          <h2>Собери все автомобили!</h2>
          <p>Увлекательная игра в маджонг с автомобильной тематикой.</p>

          <div class="features">
            <div class="feature">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                <path d="M20 6L9 17L4 12" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
              </svg>
              <span>Бесплатно играть</span>
            </div>
            <div class="feature">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                <path d="M20 6L9 17L4 12" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
              </svg>
              <span>Коллекция авто</span>
            </div>
            <div class="feature">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                <path d="M20 6L9 17L4 12" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
              </svg>
              <span>Ежедневные награды</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Правый блок с формами -->
    <div class="auth-forms">
      <div class="forms-container">
        <div class="tabs">
          <button
            @click="switchTab('login')"
            class="tab"
            :class="{ active: activeTab === 'login' }"
          >
            Вход
          </button>
          <button
            @click="switchTab('register')"
            class="tab"
            :class="{ active: activeTab === 'register' }"
          >
            Регистрация
          </button>
        </div>

        <!-- Формы -->
        <div class="form-wrapper">
          <!-- Форма входа -->
          <form
            v-if="activeTab === 'login'"
            @submit.prevent="submitLogin"
            class="auth-form"
          >
            <div v-if="loginForm.errors.email || loginForm.errors.password" class="alert alert-error mb-4">
              Проверьте введенные данные
            </div>

            <div class="form-group">
              <label for="email" class="form-label">Email</label>
              <input
                id="email"
                v-model="loginForm.email"
                type="email"
                class="form-input"
                :class="{ 'input-error': loginForm.errors.email }"
                required
                autofocus
                autocomplete="email"
              />
              <div v-if="loginForm.errors.email" class="error-message">
                {{ loginForm.errors.email }}
              </div>
            </div>

            <div class="form-group">
              <div class="label-row">
                <label for="password" class="form-label">Пароль</label>
                <button
                  type="button"
                  @click="activeTab = 'forgot-password'"
                  class="forgot-link"
                >
                  Забыли пароль?
                </button>
              </div>
              <div class="password-input-wrapper">
                <input
                  id="password"
                  v-model="loginForm.password"
                  :type="showPassword ? 'text' : 'password'"
                  class="form-input"
                  :class="{ 'input-error': loginForm.errors.password }"
                  required
                  autocomplete="current-password"
                />
                <button
                  type="button"
                  @click="showPassword = !showPassword"
                  class="password-toggle"
                >
                  <EyeIcon :open="showPassword" />
                </button>
              </div>
              <div v-if="loginForm.errors.password" class="error-message">
                {{ loginForm.errors.password }}
              </div>
            </div>

            <div class="form-group">
              <label class="checkbox">
                <input
                  type="checkbox"
                  v-model="loginForm.remember"
                  class="checkbox-input"
                />
                <span class="checkbox-label">Запомнить меня</span>
              </label>
            </div>

            <button
              type="submit"
              class="btn btn-primary"
              :disabled="loginForm.processing"
            >
              <span v-if="loginForm.processing">Вход...</span>
              <span v-else>Войти</span>
            </button>
          </form>

          <!-- Форма регистрации -->
          <form
            v-else-if="activeTab === 'register'"
            @submit.prevent="submitRegister"
            class="auth-form"
          >
            <div v-if="registerForm.errors.name || registerForm.errors.email || registerForm.errors.password" class="alert alert-error mb-4">
              Проверьте введенные данные
            </div>

            <div class="form-group">
              <label for="name" class="form-label">Имя</label>
              <input
                id="name"
                v-model="registerForm.name"
                type="text"
                class="form-input"
                :class="{ 'input-error': registerForm.errors.name }"
                required
                autofocus
                autocomplete="name"
              />
              <div v-if="registerForm.errors.name" class="error-message">
                {{ registerForm.errors.name }}
              </div>
            </div>

            <div class="form-group">
              <label for="register-email" class="form-label">Email</label>
              <input
                id="register-email"
                v-model="registerForm.email"
                type="email"
                class="form-input"
                :class="{ 'input-error': registerForm.errors.email }"
                required
                autocomplete="email"
              />
              <div v-if="registerForm.errors.email" class="error-message">
                {{ registerForm.errors.email }}
              </div>
            </div>

            <div class="form-group">
              <label for="register-password" class="form-label">Пароль</label>
              <div class="password-input-wrapper">
                <input
                  id="register-password"
                  v-model="registerForm.password"
                  :type="showRegisterPassword ? 'text' : 'password'"
                  class="form-input"
                  :class="{ 'input-error': registerForm.errors.password }"
                  required
                  autocomplete="new-password"
                />
                <button
                  type="button"
                  @click="showRegisterPassword = !showRegisterPassword"
                  class="password-toggle"
                >
                  <EyeIcon :open="showRegisterPassword" />
                </button>
              </div>
              <div v-if="registerForm.errors.password" class="error-message">
                {{ registerForm.errors.password }}
              </div>
            </div>

            <div class="form-group">
              <label for="password-confirmation" class="form-label">Подтверждение пароля</label>
              <div class="password-input-wrapper">
                <input
                  id="password-confirmation"
                  v-model="registerForm.password_confirmation"
                  :type="showConfirmPassword ? 'text' : 'password'"
                  class="form-input"
                  :class="{ 'input-error': registerForm.errors.password_confirmation }"
                  required
                  autocomplete="new-password"
                />
                <button
                  type="button"
                  @click="showConfirmPassword = !showConfirmPassword"
                  class="password-toggle"
                >
                  <EyeIcon :open="showConfirmPassword" />
                </button>
              </div>
              <div v-if="registerForm.errors.password_confirmation" class="error-message">
                {{ registerForm.errors.password_confirmation }}
              </div>
            </div>

            <div class="form-group">
              <label class="checkbox">
                <input
                  type="checkbox"
                  v-model="registerForm.terms"
                  required
                  class="checkbox-input"
                />
                <span class="checkbox-label">
                  Я соглашаюсь с <a href="/terms" class="link">Условиями использования</a>
                </span>
              </label>
            </div>

            <button
              type="submit"
              class="btn btn-primary"
              :disabled="registerForm.processing"
            >
              <span v-if="registerForm.processing">Регистрация...</span>
              <span v-else>Зарегистрироваться</span>
            </button>

            <p class="text-center text-sm text-gray-600 mt-4">
              Уже есть аккаунт?
              <button type="button" @click="switchTab('login')" class="text-blue-600 hover:underline font-medium">
                Войти
              </button>
            </p>
          </form>

          <!-- Форма восстановления пароля -->
          <form
            v-else-if="activeTab === 'forgot-password'"
            @submit.prevent="submitForgotPassword"
            class="auth-form"
          >
            <div class="form-group">
              <h3 class="text-xl font-bold mb-4">Восстановление пароля</h3>
              <p class="text-gray-600 mb-6">Введите ваш email для получения ссылки сброса пароля</p>

              <label for="forgot-email" class="form-label">Email</label>
              <input
                id="forgot-email"
                v-model="forgotPasswordForm.email"
                type="email"
                class="form-input"
                :class="{ 'input-error': forgotPasswordForm.errors.email }"
                required
                autofocus
                autocomplete="email"
              />
              <div v-if="forgotPasswordForm.errors.email" class="error-message">
                {{ forgotPasswordForm.errors.email }}
              </div>
            </div>

            <button
              type="submit"
              class="btn btn-primary mb-4"
              :disabled="forgotPasswordForm.processing"
            >
              <span v-if="forgotPasswordForm.processing">Отправка...</span>
              <span v-else>Отправить ссылку</span>
            </button>

            <button
              type="button"
              @click="switchTab('login')"
              class="btn btn-secondary"
            >
              Назад к входу
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useForm, router } from '@inertiajs/vue3'

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

// Компонент иконки глаза
const EyeIcon = {
  props: {
    open: {
      type: Boolean,
      required: true
    }
  },
  template: `
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
      <template v-if="open">
        <path d="M2 12C2 12 5 5 12 5C19 5 22 12 22 12C22 12 19 19 12 19C5 19 2 12 2 12Z" stroke="currentColor" stroke-width="2"/>
        <path d="M12 15C13.6569 15 15 13.6569 15 12C15 10.3431 13.6569 9 12 9C10.3431 9 9 10.3431 9 12C9 13.6569 10.3431 15 12 15Z" stroke="currentColor" stroke-width="2"/>
      </template>
      <template v-else>
        <path d="M17.94 17.94C16.2306 19.243 14.1491 19.9649 12 20C5 20 2 12 2 12C3.228 9.232 5.182 7.062 7.5 5.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        <path d="M9.9 4.24C10.5883 4.07888 11.2931 3.99834 12 4C19 4 22 12 22 12C21.393 13.135 20.669 14.204 19.84 15.19" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        <path d="M15 12C15 13.6569 13.6569 15 12 15C10.3431 15 9 13.6569 9 12C9 10.3431 10.3431 9 12 9C13.6569 9 15 10.3431 15 12Z" stroke="currentColor" stroke-width="2"/>
        <path d="M2 2L22 22" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
      </template>
    </svg>
  `
}

// Состояние
const activeTab = ref<'login' | 'register' | 'forgot-password'>('login')
const showPassword = ref(false)
const showRegisterPassword = ref(false)
const showConfirmPassword = ref(false)

// Определяем активную вкладку на основе URL
onMounted(() => {
  const path = window.location.pathname
  if (path.includes('register')) {
    activeTab.value = 'register'
  } else if (path.includes('password')) {
    activeTab.value = 'forgot-password'
  } else {
    activeTab.value = 'login'
  }
})

// Формы
const loginForm = useForm<LoginForm>({
  email: '',
  password: '',
  remember: false,
})

const registerForm = useForm<RegisterForm>({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  terms: false,
})

const forgotPasswordForm = useForm<ForgotPasswordForm>({
  email: '',
})

// Методы
const switchTab = (tab: 'login' | 'register' | 'forgot-password') => {
  activeTab.value = tab
  // Меняем URL без перезагрузки страницы
  const url = tab === 'login' ? '/login' :
              tab === 'register' ? '/register' :
              '/forgot-password'
  router.get(url, {}, { preserveState: true })
}

const submitLogin = () => {
  loginForm.post('/login', {
    onSuccess: () => {
      // Редирект на dashboard после успешного входа
      window.location.href = '/dashboard'
    },
    onError: () => {
      loginForm.reset('password')
    }
  })
}

const submitRegister = () => {
  registerForm.post('/register', {
    onSuccess: () => {
      // После регистрации Laravel сам сделает редирект
      // или можно показать сообщение об успехе
    },
    onError: () => {
      registerForm.reset('password', 'password_confirmation')
    }
  })
}

const submitForgotPassword = () => {
  forgotPasswordForm.post('/forgot-password', {
    onSuccess: () => {
      // Показываем сообщение об успехе и возвращаем к логину
      setTimeout(() => {
        switchTab('login')
        forgotPasswordForm.reset()
      }, 2000)
    }
  })
}
</script>

<style scoped>
.auth-layout {
  display: flex;
  min-height: 100vh;
  background: #fff;
}

.auth-info {
  flex: 1;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  padding: 60px;
  display: flex;
  align-items: center;
}

.auth-info-content {
  max-width: 600px;
}

.logo {
  display: flex;
  align-items: center;
  gap: 16px;
  margin-bottom: 60px;
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

.hero-section h2 {
  font-size: 48px;
  font-weight: 800;
  line-height: 1.2;
  margin-bottom: 20px;
}

.hero-section p {
  font-size: 18px;
  line-height: 1.6;
  opacity: 0.9;
  margin-bottom: 40px;
}

.features {
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

.auth-forms {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 40px;
}

.forms-container {
  width: 100%;
  max-width: 440px;
}

.tabs {
  display: flex;
  gap: 8px;
  margin-bottom: 40px;
  background: #f3f4f6;
  padding: 8px;
  border-radius: 12px;
}

.tab {
  flex: 1;
  padding: 16px 24px;
  text-align: center;
  border: none;
  background: transparent;
  color: #6b7280;
  font-weight: 600;
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.3s ease;
}

.tab:hover {
  color: #4b5563;
}

.tab.active {
  background: white;
  color: #667eea;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.form-wrapper {
  background: white;
  padding: 40px;
  border-radius: 20px;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
}

.alert {
  padding: 12px 16px;
  border-radius: 8px;
  font-size: 14px;
  margin-bottom: 20px;
}

.alert-error {
  background: #fef2f2;
  border: 1px solid #fecaca;
  color: #dc2626;
}

.auth-form {
  display: flex;
  flex-direction: column;
  gap: 24px;
}

.form-group {
  display: flex;
  flex-direction: column;
}

.form-label {
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

.forgot-link {
  font-size: 14px;
  color: #667eea;
  background: none;
  border: none;
  cursor: pointer;
  text-decoration: underline;
  padding: 0;
}

.forgot-link:hover {
  color: #5a67d8;
}

.password-input-wrapper {
  position: relative;
}

.form-input {
  width: 100%;
  padding: 16px;
  border: 2px solid #e5e7eb;
  border-radius: 12px;
  font-size: 16px;
  transition: all 0.3s ease;
}

.form-input:focus {
  outline: none;
  border-color: #667eea;
  box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
}

.form-input.input-error {
  border-color: #ef4444;
}

.password-toggle {
  position: absolute;
  right: 16px;
  top: 50%;
  transform: translateY(-50%);
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

.checkbox {
  display: flex;
  align-items: center;
  cursor: pointer;
}

.checkbox-input {
  margin-right: 12px;
  width: 18px;
  height: 18px;
}

.checkbox-label {
  font-size: 14px;
  color: #4b5563;
}

.checkbox-label .link {
  color: #667eea;
  text-decoration: none;
}

.checkbox-label .link:hover {
  text-decoration: underline;
}

.btn {
  width: 100%;
  padding: 16px;
  border: none;
  border-radius: 12px;
  font-size: 16px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
}

.btn-primary {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
}

.btn-primary:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
}

.btn-primary:disabled {
  opacity: 0.6;
  cursor: not-allowed;
  transform: none;
  box-shadow: none;
}

.btn-secondary {
  background: white;
  border: 2px solid #e5e7eb;
  color: #374151;
}

.btn-secondary:hover {
  border-color: #d1d5db;
  background: #f9fafb;
}

@media (max-width: 1024px) {
  .auth-layout {
    flex-direction: column;
  }

  .auth-info {
    padding: 40px;
  }

  .logo {
    margin-bottom: 40px;
  }

  .hero-section h2 {
    font-size: 36px;
  }
}

@media (max-width: 640px) {
  .auth-info {
    padding: 32px 24px;
  }

  .logo-text {
    font-size: 24px;
  }

  .logo-icon {
    width: 48px;
    height: 48px;
  }

  .hero-section h2 {
    font-size: 28px;
  }

  .auth-forms {
    padding: 32px 20px;
  }

  .form-wrapper {
    padding: 32px 24px;
  }

  .tabs {
    margin-bottom: 32px;
  }

  .tab {
    padding: 12px 20px;
    font-size: 15px;
  }
}

@media (max-width: 480px) {
  .auth-info {
    padding: 24px 20px;
  }

  .hero-section h2 {
    font-size: 24px;
  }

  .form-wrapper {
    padding: 24px 20px;
  }
}
</style>
