<script setup>
import { ref, onMounted } from "vue";
import ProductService from "./services/ProductService";
import ProductCard from "./components/ProductCard.vue";
import { ShoppingBag } from "lucide-vue-next"; // Icono del carrito

const products = ref([]);
const isLoading = ref(true);

// Función para cargar datos
const fetchProducts = async () => {
  try {
    const response = await ProductService.getProducts();
    products.value = response.data.data;
  } catch (error) {
    console.error("Error:", error);
  } finally {
    isLoading.value = false;
  }
};

onMounted(() => {
  fetchProducts();
});
</script>

<template>
  <div class="min-h-screen bg-slate-50 font-sans text-slate-800">
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-10">
      <div
        class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between"
      >
        <div class="flex items-center gap-2">
          <div
            class="w-8 h-8 bg-indigo-600 rounded-md flex items-center justify-center text-white font-bold"
          >
            D
          </div>
          <span class="font-bold text-xl tracking-tight">DigitalMarket</span>
        </div>

        <button
          class="relative p-2 text-slate-600 hover:text-indigo-600 transition-colors"
        >
          <ShoppingBag class="w-6 h-6" />
          <span
            class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/4 -translate-y-1/4 bg-red-500 rounded-full"
          >
            0
          </span>
        </button>
      </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
      <header class="mb-10 text-center">
        <h1 class="text-4xl font-extrabold text-slate-900 mb-4">
          Productos Digitales Premium
        </h1>
        <p class="text-lg text-slate-600 max-w-2xl mx-auto">
          Recursos de alta calidad para desarrolladores y creadores. Descarga
          inmediata tras tu compra segura.
        </p>
      </header>

      <div v-if="isLoading" class="flex justify-center py-20">
        <div
          class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"
        ></div>
      </div>

      <div
        v-else-if="products.length === 0"
        class="text-center py-20 text-gray-500"
      >
        No hay productos disponibles en este momento.
      </div>

      <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        <ProductCard
          v-for="product in products"
          :key="product.id"
          :product="product"
        />
      </div>
    </main>

    <footer class="bg-white border-t border-gray-200 mt-auto py-8">
      <div class="max-w-7xl mx-auto px-4 text-center text-slate-500 text-sm">
        &copy; {{ new Date().getFullYear() }} DigitalMarket. Proyecto Portfolio
        Laravel + Vue.
      </div>
    </footer>
  </div>
</template>
