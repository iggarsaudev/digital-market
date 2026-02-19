<script setup>
import { ref, onMounted } from "vue";
import ProductService from "../services/ProductService";
import ProductCard from "../components/ProductCard.vue";

const products = ref([]);
const isLoading = ref(true);

const fetchProducts = async () => {
  try {
    const response = await ProductService.getProducts();
    products.value = response.data.data;
  } catch (error) {
    console.error("error obteniendo productos:", error);
  } finally {
    isLoading.value = false;
  }
};

onMounted(() => {
  fetchProducts();
});
</script>

<template>
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
</template>
