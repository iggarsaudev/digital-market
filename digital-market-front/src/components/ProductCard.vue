<script setup>
import { computed } from "vue";
import { useCartStore } from "../stores/cart";

const props = defineProps({
  product: {
    type: Object,
    required: true,
  },
});

const cartStore = useCartStore();

// Evaluamos si este producto ya está en el carrito
const isInCart = computed(() => {
  return cartStore.items.some((item) => item.id === props.product.id);
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
          @click="cartStore.addToCart(product)"
          :disabled="isInCart"
          :class="[
            'px-4 py-2 rounded-md text-sm font-medium transition-colors',
            isInCart
              ? 'bg-emerald-100 text-emerald-700 cursor-not-allowed'
              : 'bg-indigo-600 hover:bg-indigo-700 text-white',
          ]"
        >
          {{ isInCart ? "En el carrito" : "Añadir" }}
        </button>
      </div>
    </div>
  </article>
</template>
