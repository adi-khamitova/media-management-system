<template>
  <transition name="fade">
    <div
      v-if="visible"
      class="car-info"
      @click.self="hideInfo"
      :class="{ 'car-info-visible': visible }"
    >
      <div class="car-content">
        <h3>{{ car.brand }}</h3>
        <p>{{ car.description }}</p>
        <button class="close-btn" @click="hideInfo" aria-label="Close">
          ✕
        </button>
      </div>
    </div>
  </transition>
</template>

<script setup lang="ts">
// import { defineProps, defineEmits, computed } from 'vue'

// Интерфейсы
export interface CarInfo {
  brand: string
  description: string
}

// Props
interface Props {
  car: CarInfo
  visible: boolean
}

const props = withDefaults(defineProps<Props>(), {
  visible: false,
  car: () => ({
    brand: 'Unknown',
    description: 'No description available'
  })
})

// Emits
const emit = defineEmits<{
  hide: []
}>()

// Computed свойства
// const carDisplayInfo = computed(() => {
//   return {
//     brand: props.car.brand || 'Unknown Brand',
//     description: props.car.description || 'No description available'
//   }
// })

// Методы
const hideInfo = () => {
  emit('hide')
}

// Обработка нажатия клавиши Escape
const handleKeydown = (event: KeyboardEvent) => {
  if (event.key === 'Escape' && props.visible) {
    hideInfo()
  }
}

// Добавляем/убираем обработчик клавиатуры
import { onMounted, onUnmounted } from 'vue'

onMounted(() => {
  document.addEventListener('keydown', handleKeydown)
})

onUnmounted(() => {
  document.removeEventListener('keydown', handleKeydown)
})
</script>

<style scoped>
.car-info {
  position: fixed;
  bottom: 0;
  left: 0;
  width: 100%;
  background: rgba(0, 0, 0, 0.95);
  color: white;
  padding: 20px;
  box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.4);
  z-index: 2000;
  cursor: pointer;
  font-family: 'IBM Plex Mono', monospace;
  transform: translateY(100%);
  transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.3s ease;
  backdrop-filter: blur(10px);
  border-top: 2px solid rgba(48, 254, 254, 0.3);
}

.car-info.car-info-visible {
  transform: translateY(0);
}

.car-content {
  max-width: 800px;
  margin: 0 auto;
  text-align: center;
  position: relative;
  padding: 0 40px;
}

.car-content h3 {
  font-size: 28px;
  margin-bottom: 12px;
  color: #30fefe;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 1px;
  text-shadow: 0 0 10px rgba(48, 254, 254, 0.5);
}

.car-content p {
  font-size: 18px;
  line-height: 1.6;
  color: #e0e0e0;
  margin-bottom: 0;
  max-width: 600px;
  margin-left: auto;
  margin-right: auto;
}

.close-btn {
  position: absolute;
  top: 50%;
  right: 0;
  transform: translateY(-50%);
  background: transparent;
  border: 2px solid #30fefe;
  color: #30fefe;
  width: 36px;
  height: 36px;
  border-radius: 50%;
  font-size: 18px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.2s ease;
  padding: 0;
  outline: none;
}

.close-btn:hover {
  background: rgba(48, 254, 254, 0.2);
  transform: translateY(-50%) scale(1.1);
  box-shadow: 0 0 15px rgba(48, 254, 254, 0.4);
}

.close-btn:active {
  transform: translateY(-50%) scale(0.95);
}

.close-btn:focus-visible {
  outline: 2px solid #30fefe;
  outline-offset: 2px;
}

/* Анимации */
.fade-enter-active .car-info,
.fade-leave-active .car-info {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.fade-enter-from .car-info,
.fade-leave-to .car-info {
  opacity: 0;
  transform: translateY(100%);
}

/* Адаптивность */
@media (max-width: 768px) {
  .car-info {
    padding: 15px;
  }

  .car-content {
    padding: 0 50px 0 15px;
  }

  .car-content h3 {
    font-size: 22px;
    margin-bottom: 8px;
  }

  .car-content p {
    font-size: 16px;
    line-height: 1.5;
  }

  .close-btn {
    width: 32px;
    height: 32px;
    font-size: 16px;
  }
}

@media (max-width: 480px) {
  .car-content h3 {
    font-size: 20px;
  }

  .car-content p {
    font-size: 14px;
  }

  .close-btn {
    width: 28px;
    height: 28px;
    font-size: 14px;
    right: -5px;
  }
}
</style>
