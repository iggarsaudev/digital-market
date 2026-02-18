<script setup>
// Definimos las props que recibe este componente
defineProps({
  product: {
    type: Object,
    required: true,
  },
});
</script>

<template>
  <article
    class="bg-white border border-gray-100 rounded-md shadow-sm hover:shadow-lg transition-shadow duration-300 flex flex-col h-full overflow-hidden"
  >
    <div class="h-48 overflow-hidden bg-gray-200">
      <img
        :src="product.image_url"
        :alt="product.name"
        class="w-full h-full object-cover hover:scale-105 transition-transform duration-500"
        loading="lazy"
      />
    </div>

    <div class="p-5 flex-1 flex flex-col">
      <h3 class="text-lg font-bold text-slate-800 mb-2 leading-tight">
        {{ product.name }}
      </h3>

      <p class="text-gray-500 text-sm mb-4 line-clamp-2 flex-1">
        {{ product.description }}
      </p>

      <div
        class="flex items-center justify-between mt-auto pt-4 border-t border-gray-100"
      >
        <span class="text-xl font-bold text-slate-900">
          {{ product.price_formatted }}
        </span>

        <button
          class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors"
        >
          Ver Detalles
        </button>
      </div>
    </div>
  </article>
</template>
