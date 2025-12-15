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
import { ref, onMounted, computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import MahjongTile from '@/components/MahjongTile.vue'
import CarInfo from '@/components/CarInfo.vue'
import AdModal from '@/components/AdModal.vue'
import carAtom from '@/assets/car_atom.png'

const props = defineProps<{
    layout: any;
}>()

console.log(props)

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
  clickable?: boolean
}

interface MiniCar {
  id: number
  style: Record<string, string | number>
  moveX: number
  moveY: number
}

interface LayoutItem {
  x: number
  y: number
  z: number
  type: number
  clickable: boolean
}

// Получаем данные из бэкенда
const page = usePage<any>()
console.log('Все данные с бэкенда:', page.props)

// Получаем данные из пропсов
const userMove = computed(() => {
  const data = page.props.userMove || {}
  console.log('UserMove данные:', data)
  return data
})

const gameId = computed(() => {
  const id = page.props.gameId
  console.log('GameId:', id)
  return id
})

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

// Данные об автомобилях (сопоставляем type из бэкенда с carId)
const carData: Record<number, CarDetails> = {
  0: { brand: "Audi", description: "Надежный автомобиль из Германии." },
  1: { brand: "BMW", description: "Элитный бренд из Германии." },
  2: { brand: "Porche", description: "Современный спорткар." },
  3: { brand: "VolksWagen", description: "Семейный автомобиль." },
  4: { brand: "Mercedes", description: "Премиальный автомобиль." },
  5: { brand: "Toyota", description: "Надежный японский автомобиль." },
  6: { brand: "Honda", description: "Качественный японский бренд." },
  7: { brand: "Ford", description: "Американский автомобиль." },
  8: { brand: "Chevrolet", description: "Классический американский бренд." },
  9: { brand: "Hyundai", description: "Современный корейский автомобиль." },
  10: { brand: "Kia", description: "Популярный корейский бренд." },
  11: { brand: "Nissan", description: "Японский автомобиль." },
  12: { brand: "Volvo", description: "Безопасный шведский автомобиль." },
  13: { brand: "Renault", description: "Французский автомобиль." },
  14: { brand: "Peugeot", description: "Еще один французский бренд." },
}

// Методы
const initializeTiles = () => {
//   const screenWidth = window.innerWidth

  // Получаем layout из бэкенда
  const layout: LayoutItem[] = userMove.value?.layout || []
  console.log('Layout из бэкенда:', layout)
  console.log('Количество плиток:', layout.length)

  // Если layout пустой, создаем тестовые плитки
  if (layout.length === 0) {
    console.log('Layout пустой, создаем тестовые плитки')
    // createTestTiles()
    return
  }

  // Преобразуем данные из бэкенда в Tile[]
  tiles.value = layout.map((item: LayoutItem, index: number) => {
    // type из бэкенда становится carId у нас
    const carId = item.type

    return {
      id: index + 1,
      x: item.x || 0,
      y: item.y || 0,
      z: item.z || 0,
      carId: carId,
      matched: false,
      style: {},
      ad: false, // по умолчанию не рекламная
      clickable: item.clickable || false
    }
  })

  console.log(`Создано ${tiles.value.length} плиток`)

  // Добавляем рекламные плитки (заменяем некоторые на рекламу)
  addAdTiles()

  // Устанавливаем позиции плиток
  updateTilePositions()
}

// const createTestTiles = () => {
//   // Создаем простые тестовые плитки если layout пустой
//   for (let i = 0; i < 20; i++) {
//     tiles.value.push({
//       id: i + 1,
//       x: Math.floor(i / 4),
//       y: i % 4,
//       z: 0,
//       carId: i % 4,
//       matched: false,
//       style: {},
//       ad: i < 2, // первые 2 плитки - рекламные
//       clickable: true
//     })
//   }

//   updateTilePositions()
// }

const addAdTiles = () => {
  // Находим плитки для замены на рекламу (например, первые 2 плитки с одинаковым carId)
  const tileGroups: Record<number, Tile[]> = {}

  tiles.value.forEach(tile => {
    if (!tileGroups[tile.carId]) {
      tileGroups[tile.carId] = []
    }
    tileGroups[tile.carId].push(tile)
  })

  // Ищем carId, который встречается хотя бы 2 раза
  const duplicateCarId = Object.keys(tileGroups).find(
    carId => tileGroups[parseInt(carId)].length >= 2
  )

  if (duplicateCarId) {
    const duplicateTiles = tileGroups[parseInt(duplicateCarId)].slice(0, 2)

    duplicateTiles.forEach(tile => {
      tile.carId = -100 // специальный ID для рекламы
      tile.ad = true
      tile.style = {
        ...tile.style,
        backgroundColor: "#FFD700",
        backgroundImage: "url('https://via.placeholder.com/150?text=Ad')",
        backgroundSize: "cover",
        backgroundPosition: "center"
      }
    })

    console.log('Добавлены рекламные плитки')
  }
}

const updateTilePositions = () => {
  const screenWidth = window.innerWidth

  tiles.value.forEach(tile => {
    // Преобразуем координаты из бэкенда в пиксели
    tile.x = screenWidth / 2 - 3 * TILE_WIDTH + tile.x * TILE_WIDTH
    tile.y = Y_OFFSET_FIRST_LEVEL + tile.y * TILE_HEIGHT + tile.z * 5 // небольшой сдвиг по Z

    tile.style = {
      ...tile.style,
      left: `${tile.x}px`,
      top: `${tile.y}px`,
      transition: "all 0.4s ease-out",
      zIndex: tile.z.toString(),
      opacity: tile.clickable === false ? 0.5 : 1
    }
  })
}

const handleTileClick = async (tile: Tile) => {
  // Проверяем, можно ли кликать по плитке
  if (tile.matched || tile.clickable === false) {
    console.log('Плитка не кликабельная или уже удалена')
    return
  }

  if (!firstTile.value) {
    firstTile.value = tile
    console.log('Выбрана первая плитка:', tile)
  } else if (firstTile.value.id === tile.id) {
    firstTile.value = null
    console.log('Снята первая плитка')
  } else if (!secondTile.value) {
    secondTile.value = tile
    console.log('Выбрана вторая плитка:', tile)
    await checkForMatch()
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
    brand: `Автомобиль #${carId}`,
    description: carId === -100 ? "Рекламная плитка" : "Описание отсутствует."
  }
}

const checkForMatch = async () => {
  if (!firstTile.value || !secondTile.value) return

  console.log('Проверка совпадения:', {
    firstTile: firstTile.value.carId,
    secondTile: secondTile.value.carId
  })

  if (firstTile.value.carId === secondTile.value.carId) {
    console.log('Плитки совпали!')

    // Отмечаем плитки как удаленные
    const tile1Index = tiles.value.findIndex(t => t.id === firstTile.value!.id)
    const tile2Index = tiles.value.findIndex(t => t.id === secondTile.value!.id)

    if (tile1Index !== -1 && tile2Index !== -1) {
      tiles.value[tile1Index].matched = true
      tiles.value[tile2Index].matched = true

      // Анимация
      animateMerge()

      // Отправляем на бэкенд
      await sendMoveToBackend()

      if (firstTile.value.carId === -100) {
        adModalVisible.value = true
      }
    }
  } else {
    console.log('Плитки НЕ совпали')
    setTimeout(() => {
      firstTile.value = null
      secondTile.value = null
    }, 1000)
  }
}

const sendMoveToBackend = async () => {
  if (!gameId.value) {
    console.error('Нет gameId для отправки')
    return
  }

  // Создаем новый layout без удаленных плиток
  const currentLayout = tiles.value
    .filter(tile => !tile.matched)
    .map(tile => ({
      x: tile.x,
      y: tile.y,
      z: tile.z,
      type: tile.carId === -100 ? 0 : tile.carId, // преобразуем обратно
      clickable: true // оставляем все кликабельными
    }))

  console.log('Отправляем на бэкенд layout:', currentLayout)
  console.log('GameId:', gameId.value)

  try {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''

    const response = await fetch(`/game/${gameId.value}/move`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: JSON.stringify({
        layout: currentLayout
      })
    })

    if (!response.ok) {
      throw new Error(`HTTP ошибка: ${response.status}`)
    }

    const data = await response.json()
    console.log('Ответ от бэкенда:', data)

    if (data.layout && data.layout.length === 0) {
      console.log('Игра окончена!')
      window.location.href = '/game/leaderboard'
    }

  } catch (error) {
    console.error('Ошибка отправки хода:', error)
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
    transform: "translate(-50%, -50%) scale(1.5)",
    opacity: "0.8"
  }

  tile2.style = {
    ...tile2.style,
    top: "50%",
    left: "50%",
    transform: "translate(-50%, -50%) scale(1.5)",
    opacity: "0.8"
  }

  setTimeout(() => {
    createMiniCars()

    // Фильтруем удаленные плитки
    tiles.value = tiles.value.filter(
      tile => !tile.matched
    )

    // Сбрасываем выбор
    firstTile.value = null
    secondTile.value = null

    // Проверяем конец игры
    if (tiles.value.length === 0) {
      setTimeout(() => {
        alert('Поздравляем! Игра завершена!')
      }, 1000)
    }
  }, ANIMATION_DURATION)
}

const createMiniCars = () => {
  const centerPosition = { top: 50, left: 50 }

  for (let i = 0; i < MINI_CARS_COUNT; i++) {
    const angle = (Math.PI * 2 * i) / MINI_CARS_COUNT
    const offsetX = Math.cos(angle) * 30
    const offsetY = Math.sin(angle) * 30

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

// Инициализация при загрузке
onMounted(() => {
  console.log('Страница загружена, инициализируем плитки...')
  initializeTiles()

  // Обработка изменения размера окна
  window.addEventListener('resize', updateTilePositions)
})
</script>

<style scoped>
/* Ваши существующие стили */
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

/* Стиль для некликабельных плиток */
.tile.unclickable {
  opacity: 0.5;
  cursor: not-allowed;
}
</style>
