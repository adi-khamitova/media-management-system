<template>
  <div
    class="tile"
    :style="tileStyle"
    @click="handleClick"
    :class="{ hidden: matched }"
  >
    <img
      v-if="!imageErrorFlag"
      :src="tileImage"
      alt="Mahjong Tile"
      @error="imageError"
      @load="imageLoaded"
    />
    <div v-else class="fallback-text">Mahjong Tile</div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed} from 'vue'

// Интерфейсы
interface Position {
  x: number
  y: number
  z?: number
}

interface Props {
  carId: number
  matched: boolean
  position: Position
}

interface Emits {
  (e: 'tile-clicked', carId: number): void
}

// Props с значениями по умолчанию
const props = withDefaults(defineProps<Props>(), {
  carId: 0,
  matched: false,
  position: () => ({ x: 0, y: 0, z: 1 })
})

// Emits
const emit = defineEmits<Emits>()

// Состояние
const imageErrorFlag = ref(false)
const imageLoadedFlag = ref(false)

// Computed свойства
const tileStyle = computed(() => {
  const baseStyle: Record<string, string | number> = {
    left: `${props.position.x}px`,
    top: `${props.position.y}px`,
    zIndex: props.position.z || 1,
    position: 'absolute',
    backgroundColor: props.carId === -100 ? '#e3e557' : '#FFFFFF',
    width: '60px',
    height: '80px',
    borderRadius: '10px',
    cursor: 'pointer',
    boxShadow: '5px 5px 0px #30FEFE, 5px 5px 10px rgba(0, 0, 0, 0.8)',
    display: 'flex',
    justifyContent: 'center',
    alignItems: 'center',
    transition: 'opacity 0.5s ease, background-color 0.3s, transform 0.2s'
  }

  return baseStyle
})

const tileImage = computed(() => {
  const imageMap: Record<number, string> = {
    1: 'https://i.postimg.cc/VNs23s58/audi.png',
    2: 'https://i.postimg.cc/pTGpqRcm/bmw.png',
    3: 'https://i.postimg.cc/9fc02rv4/porsche.png',
    4: 'https://i.postimg.cc/90wFbTqk/vw.png',
    [-100]: 'https://i.ibb.co/71jdd57/Untitled-Project-22.jpg'
  }

  return imageMap[props.carId] || ''
})

// Методы
const handleClick = () => {
  if (!props.matched) {
    emit('tile-clicked', props.carId)
  }
}

const imageError = (event: Event) => {
  imageErrorFlag.value = true
  console.error(`Image not found for tile with carId: ${props.carId}`)
  console.error('Image error event:', event)
}

const imageLoaded = () => {
  imageLoadedFlag.value = true
}
</script>

<style scoped>
.tile {
  width: 60px;
  height: 80px;
  border-radius: 10px;
  position: absolute;
  display: flex;
  justify-content: center;
  align-items: center;
  cursor: pointer;
  transition: opacity 0.5s ease, background-color 0.3s, transform 0.2s;
  box-shadow: 5px 5px 0px #30FEFE,
    5px 5px 10px rgba(0, 0, 0, 0.8);
  background-color: #FFFFFF;
}

.tile.hidden {
  opacity: 0;
  pointer-events: none;
  visibility: hidden;
}

.tile img {
  max-width: 100%;
  max-height: 100%;
  object-fit: contain;
  border-radius: 10px;
  transition: transform 0.3s ease;
}

.tile:hover img {
  transform: scale(1.05);
}

.fallback-text {
  font-size: 12px;
  color: #333;
  text-align: center;
  line-height: 1.2;
  font-weight: bold;
  padding: 8px;
  word-break: break-word;
}

.tile.selected {
  background-color: #30FEFE;
  transform: scale(1.2);
  box-shadow: 0 4px 15px rgba(0, 136, 255, 0.6);
  z-index: 10;
}

.tile.selected img {
  transform: scale(1.1);
}

/* Анимация для появления плитки */
.tile {
  animation: fadeIn 0.3s ease-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: scale(0.9);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}

/* Анимация для удаления плитки */
.tile.hidden {
  animation: fadeOut 0.3s ease-in forwards;
}

@keyframes fadeOut {
  from {
    opacity: 1;
    transform: scale(1);
  }
  to {
    opacity: 0;
    transform: scale(0.9);
    visibility: hidden;
  }
}
</style>
