<template>
  <transition name="ad-modal">
    <div
      v-if="visible"
      class="ad-modal"
      @click.self="hideAd"
      @keydown.esc="handleEscape"
      tabindex="0"
      ref="modalRef"
    >
      <div class="ad-modal-content">
        <div class="ad-modal-header">
          <h2 class="ad-modal-title">🎯 Реклама</h2>
          <button
            class="ad-modal-close"
            @click="hideAd"
            aria-label="Закрыть рекламу"
          >
            <span class="close-icon">×</span>
          </button>
        </div>

        <div class="ad-modal-body">
          <p class="ad-modal-text">Здесь могла быть ваша реклама! 🚀</p>

          <div class="ad-image-container">
            <img
              :src="adImageUrl"
              alt="Рекламный баннер"
              class="ad-image"
              @load="handleImageLoad"
              @error="handleImageError"
            />
            <div v-if="!imageLoaded && !imageError" class="image-loading">
              <div class="loading-spinner"></div>
              <span>Загрузка рекламы...</span>
            </div>
            <div v-if="imageError" class="image-error">
              <span>⚠️ Рекламный баннер не загружен</span>
            </div>
          </div>

          <div class="ad-modal-footer">
            <p class="ad-modal-subtext">
              Реклама помогает поддерживать бесплатный контент
            </p>
          </div>
        </div>

        <div class="ad-modal-actions">
          <button class="ad-btn ad-btn-primary" @click="handleAdClick">
            Узнать больше
          </button>
          <button class="ad-btn ad-btn-secondary" @click="hideAd">
            Закрыть
          </button>
        </div>
      </div>
    </div>
  </transition>
</template>

<script setup lang="ts">
import { ref, watch, onMounted, onUnmounted, nextTick } from 'vue'
import adImage from '@/assets/kamaz.png'

// Props
interface Props {
  visible: boolean
}

const props = defineProps<Props>()

// Emits
const emit = defineEmits<{
  hide: []
  'ad-click': []
}>()

// Состояние
const modalRef = ref<HTMLElement | null>(null)
const imageLoaded = ref(false)
const imageError = ref(false)
const isVisible = ref(props.visible)

// Константы
const AD_IMAGE_URL = adImage
const AD_REDIRECT_URL = 'https://example.com/ad' // Замените на реальную ссылку

// Computed
const adImageUrl = ref(AD_IMAGE_URL)

// Методы
const hideAd = () => {
  emit('hide')
}

const handleEscape = (event: KeyboardEvent) => {
  if (event.key === 'Escape') {
    hideAd()
  }
}

const handleAdClick = () => {
  emit('ad-click')
  window.open(AD_REDIRECT_URL, '_blank', 'noopener,noreferrer')
  hideAd()
}

const handleImageLoad = () => {
  imageLoaded.value = true
  imageError.value = false
}

const handleImageError = () => {
  imageError.value = true
  imageLoaded.value = false
  console.error('Не удалось загрузить рекламное изображение')
}

const focusModal = () => {
  nextTick(() => {
    if (modalRef.value) {
      modalRef.value.focus()
    }
  })
}

const disableBodyScroll = () => {
  document.body.style.overflow = 'hidden'
}

const enableBodyScroll = () => {
  document.body.style.overflow = ''
}

// Наблюдатели
watch(() => props.visible, (newValue) => {
  isVisible.value = newValue

  if (newValue) {
    disableBodyScroll()
    focusModal()
    // Сброс состояния изображения при показе
    imageLoaded.value = false
    imageError.value = false
  } else {
    enableBodyScroll()
  }
})

// Хуки жизненного цикла
onMounted(() => {
  // Добавляем обработчик глобального Escape
  document.addEventListener('keydown', handleEscape)
})

onUnmounted(() => {
  // Убираем обработчик
  document.removeEventListener('keydown', handleEscape)
  enableBodyScroll()
})

// Сброс прокрутки при размонтировании
enableBodyScroll()
</script>

<style scoped>
.ad-modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.85);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 3000;
  backdrop-filter: blur(5px);
  outline: none;
}

.ad-modal-content {
  background: linear-gradient(135deg, #0063bf 0%, #000bab 100%);
  padding: 30px;
  border-radius: 20px;
  box-shadow:
    0 20px 60px rgba(0, 0, 0, 0.3),
    0 0 0 1px rgba(255, 255, 255, 0.1),
    0 0 30px rgba(226, 230, 89, 0.2);
  text-align: center;
  max-width: 500px;
  width: 90%;
  position: relative;
  border: 2px solid rgba(0, 0, 0, 0.1);
  transform: translateY(0);
  animation: modalAppear 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

@keyframes modalAppear {
  from {
    opacity: 0;
    transform: translateY(-30px) scale(0.9);
  }
  to {
    opacity: 1;
    transform: translateY(0) scale(1);
  }
}

.ad-modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
  padding-bottom: 15px;
  border-bottom: 2px solid rgba(0, 0, 0, 0.1);
}

.ad-modal-title {
  margin: 0;
  font-size: 28px;
  font-weight: 700;
  color: #333;
  text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
  letter-spacing: -0.5px;
}

.ad-modal-close {
  background: transparent;
  border: none;
  font-size: 32px;
  cursor: pointer;
  color: #666;
  width: 40px;
  height: 40px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  transition: all 0.2s ease;
  padding: 0;
  line-height: 1;
}

.ad-modal-close:hover {
  background: rgba(0, 0, 0, 0.1);
  color: #333;
  transform: rotate(90deg);
}

.ad-modal-close:active {
  transform: rotate(90deg) scale(0.9);
}

.close-icon {
  display: block;
  margin-top: -2px;
}

.ad-modal-body {
  margin-bottom: 25px;
}

.ad-modal-text {
  font-size: 18px;
  color: #444;
  margin-bottom: 25px;
  line-height: 1.5;
  font-weight: 500;
}

.ad-image-container {
  position: relative;
  margin: 20px 0;
  border-radius: 12px;
  overflow: hidden;
  border: 3px solid rgba(0, 0, 0, 0.1);
  background: rgba(255, 255, 255, 0.3);
  min-height: 200px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.ad-image {
  max-width: 100%;
  height: auto;
  display: block;
  border-radius: 8px;
  transition: transform 0.3s ease;
  opacity: 0;
  animation: imageFadeIn 0.5s ease forwards;
}

@keyframes imageFadeIn {
  to {
    opacity: 1;
  }
}

.ad-image:hover {
  transform: scale(1.02);
}

.image-loading,
.image-error {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  background: rgba(255, 255, 255, 0.9);
  color: #666;
  font-size: 16px;
  gap: 15px;
}

.loading-spinner {
  width: 40px;
  height: 40px;
  border: 4px solid rgba(226, 230, 89, 0.3);
  border-top-color: #0035e1;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

.ad-modal-footer {
  margin-top: 20px;
  padding-top: 15px;
  border-top: 1px dashed rgba(0, 0, 0, 0.2);
}

.ad-modal-subtext {
  font-size: 14px;
  color: #666;
  margin: 0;
  font-style: italic;
}

.ad-modal-actions {
  display: flex;
  gap: 15px;
  justify-content: center;
}

.ad-btn {
  padding: 12px 30px;
  border: none;
  border-radius: 50px;
  font-size: 16px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  min-width: 150px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.ad-btn-primary {
  background: linear-gradient(135deg, #4f46e5, #7c3aed);
  color: white;
  box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3);
}

.ad-btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(79, 70, 229, 0.4);
}

.ad-btn-primary:active {
  transform: translateY(0);
}

.ad-btn-secondary {
  background: transparent;
  color: #666;
  border: 2px solid #ddd;
}

.ad-btn-secondary:hover {
  border-color: #999;
  color: #333;
  background: rgba(0, 0, 0, 0.05);
}

/* Анимации перехода */
.ad-modal-enter-active,
.ad-modal-leave-active {
  transition: opacity 0.3s ease;
}

.ad-modal-enter-from,
.ad-modal-leave-to {
  opacity: 0;
}

.ad-modal-enter-active .ad-modal-content,
.ad-modal-leave-active .ad-modal-content {
  transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.ad-modal-enter-from .ad-modal-content,
.ad-modal-leave-to .ad-modal-content {
  transform: translateY(-30px) scale(0.9);
}

/* Адаптивность */
@media (max-width: 600px) {
  .ad-modal-content {
    padding: 20px;
    width: 95%;
  }

  .ad-modal-title {
    font-size: 24px;
  }

  .ad-modal-text {
    font-size: 16px;
  }

  .ad-modal-actions {
    flex-direction: column;
  }

  .ad-btn {
    width: 100%;
    min-width: auto;
  }

  .ad-modal-close {
    width: 36px;
    height: 36px;
    font-size: 28px;
  }
}

@media (max-width: 400px) {
  .ad-modal-content {
    padding: 15px;
  }

  .ad-modal-title {
    font-size: 20px;
  }

  .ad-modal-text {
    font-size: 15px;
  }
}
</style>
