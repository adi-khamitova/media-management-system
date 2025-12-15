<template>
  <div id="game-container">
    <!-- Контейнер для машинок -->
    <div
      v-for="car in miniCars"
      :key="car.id"
      class="mini-car"
      :style="car.style"
    ></div>

    <div id="game-board">
      <MahjongTile
        v-for="tile in tiles"
        :key="tile.id"
        :car-id="tile.carId"
        :matched="tile.matched"
        :position="tile"
        @tile-clicked="handleTileClick(tile)"
        :class="{
          highlighted: tile === firstTile || tile === secondTile,
          selected: tile === firstTile || tile === secondTile,
          shake: secondTile && secondTile.id === tile.id && firstTile?.carId !== secondTile.carId,
        }"
        :style="tile.style"
      />
    </div>

    <CarInfo
      v-if="selectedCar"
      :car="selectedCar"
      :visible="carInfoVisible"
      @hide="carInfoVisible = false"
    />

    <AdModal
      :visible="adModalVisible"
      @hide="adModalVisible = false"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import MahjongTile from '@/components/MahjongTile.vue'
import CarInfo from '@/components/CarInfo.vue'
import AdModal from '@/components/AdModal.vue'
import carAtom from '@/assets/car_atom.png'
import tilesJson from '@/pages/tiles.json'

const props = defineProps<{
    layout: any;
}>()

// Типы
interface CarDetails {
  brand: string
  description: string
}

interface Tile {
  id: number
  carId: number
  matched: boolean
  x: number
  y: number
  z: number
  style: Record<string, string | number>
  ad?: boolean
}

interface MiniCar {
  id: number
  style: Record<string, string | number>
  moveX: number
  moveY: number
}

// Состояние
const tiles = ref<Tile[]>([])
const firstTile = ref<Tile | null>(null)
const secondTile = ref<Tile | null>(null)
const miniCars = ref<MiniCar[]>([])
const selectedCar = ref<CarDetails | null>(null)
const carInfoVisible = ref(false)
const adModalVisible = ref(false)

// Константы
const TILE_WIDTH = 60
const TILE_HEIGHT = 80
const Y_OFFSET_FIRST_LEVEL = 100
const MINI_CARS_COUNT = 10
const ANIMATION_DURATION = 1000
const MINI_CARS_VISIBLE_DURATION = 2500

// Данные об автомобилях
const carData: Record<number, CarDetails> = {
  1: { brand: "Audi", description: "Надежный автомобиль из Германии." },
  2: { brand: "BMW", description: "Элитный бренд из Германии." },
  3: { brand: "Porche", description: "Современный спорткар." },
  4: { brand: "VolksWagen", description: "Семейный автомобиль." },
}

// Методы
const initializeTiles = () => {
  const screenWidth = window.innerWidth

  // Преобразуем данные из JSON в Tile[]
  tiles.value = tilesJson.map(tile => ({
    ...tile,
    matched: false,
    style: {}
  }))

  // Найти дублирующиеся carId
  const tileGroups: Record<number, Tile[]> = {}

  tiles.value.forEach(tile => {
    if (!tileGroups[tile.carId]) {
      tileGroups[tile.carId] = []
    }
    tileGroups[tile.carId].push(tile)
  })

  // Получить carId, который встречается больше одного раза
  const duplicateCarId = Object.keys(tileGroups).find(
    carId => tileGroups[parseInt(carId)].length > 1
  )

  if (duplicateCarId) {
    const duplicateTiles = tileGroups[parseInt(duplicateCarId)].slice(0, 2)

    // Заменяем их на рекламные плитки
    duplicateTiles.forEach(tile => {
      tile.carId = -100
      tile.style = {
        backgroundColor: "#FFD700",
        backgroundImage: "url('https://via.placeholder.com/150?text=Ad')",
        backgroundSize: "cover",
        backgroundPosition: "center"
      }
      tile.ad = true
    })
  }

  // Устанавливаем позиции плиток
  tiles.value.forEach(tile => {
    tile.x = screenWidth / 2 - 3 * TILE_WIDTH + tile.x * TILE_WIDTH
    tile.y = Y_OFFSET_FIRST_LEVEL + tile.y * TILE_HEIGHT

    tile.style = {
      ...tile.style,
      left: `${tile.x}px`,
      top: `${tile.y}px`,
      transition: "all 0.4s ease-out",
      zIndex: "1"
    }
  })
}

const handleTileClick = (tile: Tile) => {
  if (tile.matched) return

  if (!firstTile.value) {
    firstTile.value = tile
  } else if (firstTile.value.id === tile.id) {
    firstTile.value = null
  } else if (!secondTile.value) {
    secondTile.value = tile
    checkForMatch()
  }

  showCarInfo(tile.carId)
}

const showCarInfo = (carId: number) => {
  const carDetails = getCarDetails(carId)
  selectedCar.value = carDetails
  if (carId !== -100) {
    carInfoVisible.value = true
  }
}

const getCarDetails = (carId: number): CarDetails => {
  return carData[carId] || {
    brand: "Неизвестно",
    description: "Описание отсутствует."
  }
}

const checkForMatch = () => {
  if (!firstTile.value || !secondTile.value) return

  if (firstTile.value.carId === secondTile.value.carId) {
    setTimeout(() => {
      animateMerge()
    }, 300)

    if (firstTile.value.carId === -100) {
      adModalVisible.value = true
    }
  } else {
    setTimeout(() => {
      firstTile.value = null
      secondTile.value = null
    }, 1000)
  }
}

const animateMerge = () => {
  if (!firstTile.value || !secondTile.value) return

  const [tile1, tile2] = [firstTile.value, secondTile.value]

  tile1.style.zIndex = "10"
  tile2.style.zIndex = "10"

  tile1.style = {
    ...tile1.style,
    top: "50%",
    left: "50%",
    transform: "translate(-50%, -50%) scale(1.5)"
  }

  tile2.style = {
    ...tile2.style,
    top: "50%",
    left: "50%",
    transform: "translate(-50%, -50%) scale(1.5)"
  }

  setTimeout(() => {
    createMiniCars()

    tiles.value = tiles.value.filter(
      tile => tile.id !== tile1.id && tile.id !== tile2.id
    )

    firstTile.value = null
    secondTile.value = null
  }, ANIMATION_DURATION)
}

const createMiniCars = () => {
  const centerPosition = { top: 50, left: 50 }

  for (let i = 0; i < MINI_CARS_COUNT; i++) {
    const angle = (Math.PI * 2 * i) / MINI_CARS_COUNT
    const offsetX = Math.cos(angle) * 30
    const offsetY = Math.sin(angle) * 30

    // Увеличенный диапазон движения
    const moveX = Math.cos(angle) * 500
    const moveY = Math.sin(angle) * 500

    miniCars.value.push({
      id: i,
      style: {
        position: "absolute",
        width: "120px",
        height: "50px",
        backgroundImage: `url(${carAtom})`,
        backgroundSize: "cover",
        backgroundPosition: "center",
        top: `calc(${centerPosition.top}% + ${offsetY}px)`,
        left: `calc(${centerPosition.left}% + ${offsetX}px)`,
        transition: "transform 2s ease-out, opacity 2.5s ease-out",
        opacity: "1",
        zIndex: "1000"
      },
      moveX,
      moveY
    })
  }

  setTimeout(() => {
    miniCars.value.forEach(car => {
      car.style.transform = `translate(${car.moveX}px, ${car.moveY}px)`
      car.style.opacity = "0.8"
    })
  }, 100)

  setTimeout(() => {
    miniCars.value = []
  }, MINI_CARS_VISIBLE_DURATION)
}

// Инициализация
onMounted(() => {
  initializeTiles()
})
</script>

<style scoped>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

#game-container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  background-color: #121212;
  position: relative;
}

#game-board {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(60px, 1fr));
  gap: 10px;
  width: 80%;
  max-width: 1000px;
  margin: 0 auto;
}

.tile {
  transition: background-color 0.3s, transform 0.2s;
}

.tile.highlighted {
  background-color: #30FEFE;
  transform: scale(1.1);
  box-shadow: 5px 5px 0px #000000,
  5px 5px 10px rgba(0, 0, 0, 0.8);
}

.tile.shake {
  animation: shake 0.3s;
}

.mini-car {
  position: absolute;
  opacity: 1;
  transition: transform 0.s ease-out, opacity 0.6s ease-out;
  background-size: cover;
  background-position: center;
  border-radius: 4px;
}

@keyframes shake {
  0% { transform: translateX(0); }
  25% { transform: translateX(-5px); }
  50% { transform: translateX(5px); }
  75% { transform: translateX(-5px); }
  100% { transform: translateX(0); }
}
</style>
